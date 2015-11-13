<?php

namespace restapi\controllers;

use Yii;
use \core\models\operation\OperationShopDistrictGoods;
use \core\models\operation\OperationCategory;
use \core\models\operation\OperationGoods;
use \core\models\operation\OperationShopDistrictCoordinate;
use \core\models\worker\Worker;
use core\models\customer\CustomerWorker;
use \core\models\customer\CustomerAccessToken;
use \core\models\operation\OperationSelectedService;
use \core\models\customer\CustomerAddress;
use \restapi\models\alertMsgEnum;
use \restapi\models\EjjEncryption;

class ServiceController extends \restapi\components\Controller
{

    /**
     * @api {GET} /service/service-goods [GET] /service/service-goods（80%）
     * @apiName actionServiceGoods
     * @apiGroup service
     * @apiDescription 获得某城市下某服务的所有子服务列表，返回子服务数组[服务名,服务描述,服务图标，服务id，url](赵顺利--url不能获取 )
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} category_id 服务品类id
     * @apiParam {String} order_channel_name      订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "",
     *      "ret":
     *      [
     *          {
     *              "goods_id": "2", 服务类型id
     *              "goods_no": null,  服务类型编号
     *              "goods_name": "空调清洗",  服务类型名
     *              "goods_introduction": "", 服务类型简介
     *              "goods_english_name": "", 服务类型英文名称
     *              "goods_img": "", 服务类型图片
     *              "goods_app_ico": null,  APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)
     *              "goods_pc_ico": null,  PC端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)
     *              "goods_price": "0.0000", 价格
     *              "goods_price_unit": "件",  单位
     *              "goods_price_description": "1232131"
     *              "goods_page_url": ""
     *          },
     *       ],
     *  }
     *
     * @apiError CityNotSupportFound 该城市暂未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"0",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionServiceGoods()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403, null, alertMsgEnum::getGoodsesFailed);
        }

        $goodses = OperationShopDistrictGoods::getGoodsByCityCategory($param['city_name'], $param['category_id']);

        if (empty($goodses)) {
            return $this->send(null, "该城市暂未开通该类型的服务", 0, 403, null, alertMsgEnum::getGoodsesFailed);
        }
        $gDate = [];
        foreach ($goodses as $gItem) {
            $gobject = [
                'goods_id' => $gItem['goods_id'],
                'goods_no' => $gItem['operation_goods_no'],
                'goods_name' => $gItem['operation_goods_name'],
                'goods_introduction' => $gItem['operation_goods_introduction'],
                'goods_english_name' => $gItem['operation_goods_english_name'],
                'goods_img' => $gItem['operation_goods_img'],
                'goods_app_ico' => $gItem['operation_goods_app_ico'],
                'goods_pc_ico' => $gItem['operation_goods_pc_ico'],
                'goods_price' => $gItem['operation_goods_price'],
                'goods_price_unit' => $gItem['operation_spec_strategy_unit'],
                'goods_price_description' => $gItem['operation_goods_price_description'],
                'goods_page_url' => '',
            ];
            $gDate[] = $gobject;
        }
        return $this->send($gDate, "数据获取成功", 1, 200, null, alertMsgEnum::getGoodsesSuccess);
    }

    /**
     * @api {GET} /service/service-items [GET] /service/service-items ( for pop )
     * @apiName actionServiceItems
     * @apiGroup service
     * @apiDescription 获得所有服务项目[服务id, 服务编号,服务名,服务描述,服务英文名称]
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "数据获取成功",
     *      "ret":
     *      [
     *          {
     *              "goods_id": "2", 服务id
     *              "goods_no": null,  服务编号
     *              "goods_name": "空调清洗",  服务名
     *              "goods_introduction": "", 服务简介
     *              "goods_english_name": "", 服务英文名称
     *          },
     *       ],
     *  }
     *
     */
    public function actionServiceItems()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //pop访问时可以不用输入access_token（用本身的验证加密方法）
        $apiPopKey = Yii::$app->params["apiPopKey"];
        $apiSecretKey = Yii::$app->params["apiSecretKey"];
        $sign = isset($param["sign"]) ? $param["sign"] : "";
        $nonce = isset($param["nonce"]) ? $param["nonce"] : "";
        $arrParams = array();
        $arrParams["sign"] = $sign;
        $arrParams["nonce"] = $nonce;
        $arrParams["api_key"] = $apiPopKey;
        try {
            $objSign = new EjjEncryption($apiPopKey, $apiSecretKey);
            $bolCheck = $objSign->checkSignature($arrParams);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if (!$bolCheck) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::customerLoginFailed);
        }
        try {
            $categories = OperationCategory::getAllCategory();
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        $gDate = [];
        if (!empty($categories)) {
            foreach ($categories as $category) {
                try {
                    $goodses = OperationGoods::getCategoryGoods($category['id']);
                } catch (\Exception $e) {
                    return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
                }
                if (!empty($goodses)) {
                    foreach ($goodses as $gItem) {
                        $gobject = [
                            'goods_id' => $gItem['id'],
                            'goods_no' => $gItem['operation_goods_no'],
                            'goods_name' => $gItem['operation_goods_name'],
                            'goods_introduction' => $gItem['operation_goods_introduction'],
                            'goods_english_name' => $gItem['operation_goods_english_name'],
                        ];
                        $gDate[] = $gobject;
                    }
                }
            }
        }

        return $this->send($gDate, "数据获取成功", 1, 200, null, alertMsgEnum::getGoodsesSuccess);
    }

    /**
     * @api {GET} /service/home-services [GET] /service/home-services（20% ）
     * @apiName actionHomeServices
     * @apiGroup service
     * @apiDescription 获取城市首页服务项目信息简介(赵顺利--假数据，未与boss关联)
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} order_channel_name      订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": 1,
     *      "msg": "信息获取成功",
     *      "ret":
     *      [
     *      {
     *          "goods_id": "1",
     *          "goods_no": "",
     *          "goods_name": "管道疏通",
     *          "goods_introduction": "含：专业设备+专业技师+上门服务",
     *          "goods_english_name": "",
     *          "goods_img": "",
     *          "goods_app_ico": "",
     *          "goods_pc_ico": "",
     *          "goods_price": "160.00",
     *          "goods_price_unit": "眼",
     *          "goods_price_description": ""
     *      },
     *      {
     *          "goods_id": "2",
     *          "goods_no": "",
     *          "goods_name": "家电维修",
     *          "goods_introduction": "含：专业设备+专业技师+上门服务",
     *          "goods_english_name": "",
     *          "goods_img": "",
     *          "goods_app_ico": "",
     *          "goods_pc_ico": "",
     *          "goods_price": "160.00",
     *          "goods_price_unit": "次",
     *          "goods_price_description": ""
     *      },
     *      {
     *          "goods_id": "3",
     *          "goods_no": "",
     *          "goods_name": "家具组装",
     *          "goods_introduction": "含：专业设备+专业技师+上门服务",
     *          "goods_english_name": "",
     *          "goods_img": "",
     *          "goods_app_ico": "",
     *          "goods_pc_ico": "",
     *          "goods_price": "160.00",
     *          "goods_price_unit": "次",
     *          "goods_price_description": ""
     *      }
     *      ]
     *  }
     *
     * @apiError CityNotSupportFound 该城市暂未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":0,
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionHomeServices()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403, null, alertMsgEnum::homeGoodsesFailed);
        }

        $ret = [
            [
                'goods_id' => '1',
                'goods_no' => '',
                'goods_name' => '管道疏通',
                'goods_introduction' => '含：专业设备+专业技师+上门服务',
                'goods_english_name' => '',
                'goods_img' => '',
                'goods_app_ico' => '',
                'goods_pc_ico' => '',
                'goods_price' => '160.00',
                'goods_price_unit' => '眼',
                'goods_price_description' => ''
            ],
            [
                'goods_id' => '2',
                'goods_no' => '',
                'goods_name' => '家电维修',
                'goods_introduction' => '含：专业设备+专业技师+上门服务',
                'goods_english_name' => '',
                'goods_img' => '',
                'goods_app_ico' => '',
                'goods_pc_ico' => '',
                'goods_price' => '160.00',
                'goods_price_unit' => '次',
                'goods_price_description' => ''
            ],
            [
                'goods_id' => '3',
                'goods_no' => '',
                'goods_name' => '家具组装',
                'goods_introduction' => '含：专业设备+专业技师+上门服务',
                'goods_english_name' => '',
                'goods_img' => '',
                'goods_app_ico' => '',
                'goods_pc_ico' => '',
                'goods_price' => '160.00',
                'goods_price_unit' => '次',
                'goods_price_description' => ''
            ],
        ];

        return $this->send($ret, "信息获取成功", 0, 200, null, alertMsgEnum::homeGoodsesSuccess);
    }

    /**
     * @api {GET} /service/all-services   [GET] /service/all-services (100%)
     * @apiName actionAllServices 
     * @apiGroup service
     * @apiDescription 获取城市所以服务类型列表 （赵顺利）
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} order_channel_name      订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "",
     *      "ret":
     *      [
     *      {
     *          "category_id":"", 服务品类id
     *          "category_name":"专业保洁",  服务品类名
     *          "goodses":
     *          [
     *          {
     *              "goods_id": "2", 服务类型id
     *              "goods_no": null,  服务类型编号
     *              "goods_name": "空调清洗",  服务类型名
     *              "goods_introduction": "", 服务类型简介
     *              "goods_english_name": "", 服务类型英文名称
     *              "goods_img": "", 服务类型图片
     *              "goods_app_ico": null,  APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)
     *              "goods_pc_ico": null,  PC端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)
     *              "goods_price": "0.0000", 价格
     *              "goods_price_unit": "件",  单位
     *              "goods_price_description": "1232131"
     *          },
     *          ]
     *       }
     *       ],
     *  }
     *
     * @apiError CityNotSupportFound 该城市暂未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"0",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionAllServices()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403, null, alertMsgEnum::allGoodsesFailed);
        }

        $categoryes = OperationCategory::getAllCategory();
        $goodses = OperationShopDistrictGoods::getGoodsByCity($param['city_name']);

        if (empty($categoryes) || empty($goodses)) {
            return $this->send(null, "该城市暂未开通", 0, 403, null, alertMsgEnum::allGoodsesFailed);
        }
        $cDate = [];
        foreach ($categoryes as $cItem) {
            $gDate = [];
            foreach ($goodses as $gItem) {
                if ($cItem['id'] == $gItem['operation_category_id']) {
                    $gobject = [
                        'goods_id' => $gItem['goods_id'],
                        'goods_no' => $gItem['operation_goods_no'],
                        'goods_name' => $gItem['operation_goods_name'],
                        'goods_introduction' => $gItem['operation_goods_introduction'],
                        'goods_english_name' => $gItem['operation_goods_english_name'],
                        'goods_img' => $gItem['operation_goods_img'],
                        'goods_app_ico' => $gItem['operation_goods_app_ico'],
                        'goods_pc_ico' => $gItem['operation_goods_pc_ico'],
                        'goods_price' => $gItem['operation_goods_price'],
                        'goods_price_unit' => $gItem['operation_spec_strategy_unit'],
                        'goods_price_description' => $gItem['operation_goods_price_description'],
                    ];
                    $gDate[] = $gobject;
                }
            }
            $cObject = [
                'category_id' => $cItem['id'],
                'category_name' => $cItem['operation_category_name'],
                'goodses' => $gDate
            ];
            $cDate[] = $cObject;
        }

        return $this->send($cDate, "数据获取成功", 1, 200, null, alertMsgEnum::allGoodsesSuccess);
    }

    /**
     * @api {GET} /service/goods-price [GET] /service/goods-price（100%）
     * @apiName actionGoodsPrice
     * @apiGroup service 
     * @apiDescription 获取某城市某商品的价格及备注信息 （赵顺利）
     *
     * @apiParam {String} city_id 城市id
     * @apiParam {String} longitude 经度
     * @apiParam {String} latitude 纬度
     * @apiParam {String} goods_id 服务品类id
     * @apiParam {String} order_channel_name      订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": 1,
     *      "msg": "",
     *      "ret":
     *      [
     *          "category_name": "", 服务品类名称
     *          "goods_category_name": "", 服务商品名称
     *          "goods_price": "0.0000", 价格
     *      ],
     *  }
     *
     * @apiError CityNotSupportFound 错误的城市信息.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":0,
     *       "msg": "错误的城市信息"
     *     }
     */
    public function actionGoodsPrice()
    {
        $params = Yii::$app->request->get();

        if (empty($params['longitude']) || empty($params['latitude'])) {
            return $this->send(null, "经纬度信息不存在", 0, 403, null, alertMsgEnum::goodsInfoFailed);
        }
        $shopDistrict = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($params['longitude'], $params['latitude']);
        if (empty($shopDistrict)) {
            return $this->send(null, "没有上线商圈", 0, 403, null, alertMsgEnum::goodsInfoFailed);
        }
        $goods = OperationShopDistrictGoods::getShopDistrictGoodsInfo($params['city_id'], $shopDistrict['operation_shop_district_id'], $params['goods_id']);

        if (empty($goods)) {
            return $this->send(null, "该商圈没有上线当前服务品类", 0, 403, null, alertMsgEnum::goodsInfoFailed);
        }

        $ret = [
            'category_name' => $goods['operation_category_name'],
            'goods_name' => $goods['operation_shop_district_goods_name'],
            'goods_price' => $goods['operation_shop_district_goods_price'],
        ];

        return $this->send($ret, "数据获取成功", 1, 200, null, alertMsgEnum::goodsInfoSuccess);
    }

    /**
     * @api {GET} /service/cleaning-task [GET] /service/cleaning-task（100%）
     * @apiGroup service
     * @apiName actionCleaningTask
     * @apiDescription 获取城市所有保洁任务(赵顺利)
     *
     * @apiParam {String} city_id 城市
     * @apiParam {String} address_id 地址id
     * @apiParam {String} build_area 建筑面积 传面积类型 1\2; 1是小于100平米的，2是大于100平米的
     * @apiParam {String} order_channel_name      订单渠道名称
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "",
     *      "ret":[
     *          {
     *              "id": "1",
     *              "selected_service_scene": "",
     *              "selected_service_area": "1",
     *              "selected_service_sub_area": "1",
     *              "selected_service_standard": "",
     *              "selected_service_area_standard": "1",
     *              "selected_service_unit": "1",
     *              "selected_service_photo": "1",
     *              "created_at": "1"
     *          },
     *          ]
     *  }
     *
     * @apiError CityNotSupportFound 该城市暂未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"0",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionCleaningTask()
    {
        $params = Yii::$app->request->get();
        if (empty($params) || empty($params['city_id']) || empty($params['build_area']))
            return $this->send(null, "参数信息不完整", '0', 403, null, alertMsgEnum::allCleaningTaskFailed);

        //获取地址信息
        $address = CustomerAddress::getAddress($params['city_id']);
        if (empty($address))
            return $this->send(null, "获取地址信息失败", '0', 403, null, alertMsgEnum::allCleaningTaskFailed);

        //获取商圈
        $shopDistrict = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($address['customer_address_longitude'], $address['customer_address_latitude']);
        if (empty($shopDistrict))
            return $this->send(null, "未找到相应商圈", '0', 403, null, alertMsgEnum::allCleaningTaskFailed);

        //获取商圈品类上线
        $goodses = OperationShopDistrictGoods::getGoodsCategoryInfo($params['city_id'], $shopDistrict['id'], '精品保洁');
        if (empty($goodses))
            return $this->send(null, "该商圈未上线精品保洁", '0', 403, null, alertMsgEnum::allCleaningTaskFailed);

        $date = OperationSelectedService::getSelectedServiceList($params['build_area']);

        if (empty($date))
            return $this->send(null, "获取精品保洁商品信息失败", "0", "403", null, alertMsgEnum::allCleaningTaskFailed);

        return $this->send($date, "获取精品保洁商品信息成功", 1, 200, null, alertMsgEnum::allCleaningTaskFailed);
    }

    /**
     * @api {GET} /service/single-service-time [GET] /service/single-service-time(100%)
     * 
     * @apiDescription 单次服务获取服务时间（李勇）
     * @apiName actionSingleServiceTime
     * @apiGroup service
     * 
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} service_type  服务类型
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     * @apiParam {String} plan_time 计划服务时长
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "获取单次服务排班表成功",
     * "ret": [
     *       {
     *           "date": "2015-10-29",
     *           "timeline": [
     *               {
     *                   "time": "8:00-10:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "8:30-10:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "9:00-11:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "9:30-11:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "10:00-12:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "10:30-12:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "11:00-13:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "11:30-13:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "12:00-14:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "12:30-14:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "13:00-15:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "13:30-16:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "14:00-17:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "14:30-17:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "15:00-18:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "16:30-18:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "17:00-19:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "17:30-19:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "18:00-20:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "18:30-20:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "19:00-21:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "19:30-21:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "20:00-22:00",
     *                   "enable": false
     *               }
     *           ]
     *       }
     *    ],
     * "alertMsg": "获取周期服务时间表成功"
     * }
     * 
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *       {
     *          "code": 0,
     *          "msg": "用户认证已经过期,请重新登录",
     *          "ret": {},
     *          "alertMsg": "用户认证已经过期,请重新登录"
     *        }
     *
     */
    function actionSingleServiceTime()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['longitude']) || !$param['longitude'] || !isset($param['latitude']) || !$param['latitude'] || !isset($param['plan_time']) || !$param['plan_time']) {
            return $this->send(null, "请填写服务地址或服务时长", 0, 403, null, alertMsgEnum::singleServiceTimeDataDefect);
        }
        $longitude = $param['longitude'];
        $latitude = $param['latitude'];
        $plan_time = $param['plan_time'];
        $service_type = isset($param['service_type']) ? $param['service_type'] : "";
        //pop访问时可以不用输入access_token（用本身的验证加密方法）
        $apiPopKey = Yii::$app->params["apiPopKey"];
        $apiSecretKey = Yii::$app->params["apiSecretKey"];
        $sign = isset($param["sign"]) ? $param["sign"] : "";
        $nonce = isset($param["nonce"]) ? $param["nonce"] : "";
        $arrParams = array();
        $arrParams["sign"] = $sign;
        $arrParams["nonce"] = $nonce;
        $arrParams["api_key"] = $apiPopKey;
        $arrParams["longitude"] = $longitude;
        $arrParams["latitude"] = $latitude;
        $arrParams["plan_time"] = $plan_time;
        $arrParams["service_type"] = $service_type;
        try {
            $objSign = new EjjEncryption($apiPopKey, $apiSecretKey);
            $bolCheck = $objSign->checkSignature($arrParams);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
//        var_dump($bolCheck);die;
        //生成加密
//        $objSign = new EjjEncryption($apiPopKey, $apiSecretKey);
//        $arrParams = $objSign->signature($arrParams);
//        var_dump($arrParams);die;

        if (!isset($param['access_token']) || !$param['access_token'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            if (!$bolCheck) {
                return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::customerLoginFailed);
            }
        }

        //根据经纬度获取商圈id
        try {
            $ShopDistrictInfo = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if (empty($ShopDistrictInfo)) {
            return $this->send(null, "商圈不存在", 0, 403, null, alertMsgEnum::singleServiceTimeDistrictNotExist);
        } else {
            $district_id = $ShopDistrictInfo['operation_shop_district_id'];
        }
        //获取单次服务排班表
        try {
            $single_worker_time = Worker::getWorkerTimeLine($district_id, $plan_time, time(), 7);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::bossError);
        }
        return $this->send($single_worker_time, "获取单次服务排班表成功", 1, 200, null, alertMsgEnum::singleServiceTimeSuccess);
    }

    /**
     * @api {GET} /service/recursive-service-time  [GET] /service/recursive-service-time(100%)
     * 
     * @apiDescription 周期服务时间表（李勇）
     * @apiName actionRecursiveServiceTime
     * @apiGroup service
     * 
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} service_type  服务类型
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     * @apiParam {String} worker_id   阿姨id.
     * @apiParam {String} plan_time 计划服务时长.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "获取周期服务时间表成功",
     *   "ret": [
     *       {
     *           "date": "2015-10-29",
     *           "timeline": [
     *               {
     *                   "time": "8:00-10:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "8:30-10:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "9:00-11:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "9:30-11:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "10:00-12:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "10:30-12:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "11:00-13:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "11:30-13:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "12:00-14:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "12:30-14:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "13:00-15:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "13:30-16:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "14:00-17:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "14:30-17:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "15:00-18:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "16:30-18:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "17:00-19:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "17:30-19:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "18:00-20:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "18:30-20:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "19:00-21:00",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "19:30-21:30",
     *                   "enable": false
     *               },
     *               {
     *                   "time": "20:00-22:00",
     *                   "enable": false
     *               }
     *           ]
     *       }
     *    ],
     *  "alertMsg": "获取周期服务时间表成功"
     * }
     * 
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 Not Found
     *       {
     *          "code": 0,
     *          "msg": "用户认证已经过期,请重新登录",
     *          "ret": {},
     *          "alertMsg": "用户认证已经过期,请重新登录"
     *        }
     *
     */
    function actionRecursiveServiceTime()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::customerLoginFailed);
        }
        if (!isset($param['longitude']) || !$param['longitude'] || !isset($param['latitude']) || !$param['latitude'] || !isset($param['plan_time']) || !$param['plan_time'] || !isset($param['worker_id']) || !$param['worker_id']) {
            return $this->send(null, "请填写服务地址或服务时长或选择阿姨", 0, 403, null, alertMsgEnum::recursiveServiceTimeDataDefect);
        }
        $longitude = $param['longitude'];
        $latitude = $param['latitude'];
        $worker_id = $param['worker_id'];
        $plan_time = $param['plan_time'];
        //根据经纬度获取商圈id
        try {
            $ShopDistrictInfo = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if (empty($ShopDistrictInfo)) {
            return $this->send(null, "商圈不存在", 0, 403, null, alertMsgEnum::recursiveServiceTimeDistrictNotExist);
        } else {
            $district_id = $ShopDistrictInfo['operation_shop_district_id'];
        }
        //获取周期服务时间表
        try {
            $recursive_worker_time = Worker::getWorkerCycleTimeLine($district_id, $plan_time, $worker_id);
        } catch (\Exception $e) {
            return $this->send(null, "获取周期服务时间表系统错误", 1024, 403, null, alertMsgEnum::bossError);
        }
        return $this->send($recursive_worker_time, "获取周期服务时间表成功", 1, 200, null, alertMsgEnum::recursiveServiceTimeSuccess);
    }

    /**
     * @api {GET} /service/server-worker-list [GET] /service/server-worker-list（100%）
     * 
     * @apiDescription 获取周期服务可用阿姨列表（李勇）
     * @apiGroup service
     * @apiName actionServerWorkerList
     *
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     * @apiParam {String} per_page  每页显示多少条.
     * @apiParam {String} page  第几页.
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "获取周期服务可用阿姨列表成功",
     *       "ret": {
     *           "page": "第几页",
     *           "pageNum": "每页显示多少条",
     *           "data": [
     *               {
     *                   "id": "阿姨表自增id",
     *                   "worker_name": "阿姨姓名",
     *                   "worker_photo": "阿姨手机",
     *                   "worker_star": "阿姨星级",
     *                   "updated_at": "最后更新时间",
     *                   "worker_server_num": "阿姨服务次数",
     *                   "worker_comment_score": "阿姨评论评分"
     *               }
     *           ]
     *       },
     *       "alertMsg": "获取周期服务可用阿姨列表成功"
     *   }
     *
     * @apiError queryNotSupportFound 没有可用阿姨
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *       {
     *           "code": 0,
     *           "msg": "没有可用阿姨",
     *           "ret": {},
     *           "alertMsg": "没有可用阿姨"
     *       }
     */
    public function actionServerWorkerList()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::customerLoginFailed);
        }
        $customer = CustomerAccessToken::getCustomer($param['access_token']);

        if (empty($customer)) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 200, null, alertMsgEnum::customerLoginFailed);
        }
        if (!isset($param['longitude']) || !$param['longitude'] || !isset($param['latitude']) || !$param['latitude']) {
            return $this->send(null, "请填写服务地址", 0, 403, null, alertMsgEnum::serverWorkerListNoAddress);
        }
        if (!isset($param['page']) || !$param['page'] || !isset($param['per_page']) || !$param['per_page']) {
            return $this->send(null, "请输入每页条数和第几页", 0, 403, null, alertMsgEnum::serverWorkerListNoPage);
        }
        $page = $param['page'];
        $per_page = $param['per_page'];
        $longitude = $param['longitude'];
        $latitude = $param['latitude'];

        $customer_id = $customer->id;
        //根据经纬度获取商圈id
        try {
            $ShopDistrictInfo = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::bossError);
        }
        if (empty($ShopDistrictInfo)) {
            return $this->send(null, "商圈不存在", 0, 403, null, alertMsgEnum::serverWorkerListDistrictNotExist);
        } else {
            $district_id = $ShopDistrictInfo['operation_shop_district_id'];
        }
        //获取周期订单可用阿姨的列表
        try {
            $worker_list = CustomerWorker::getCustomerDistrictNearbyWorker($customer_id, $district_id, $page, $per_page);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403, null, alertMsgEnum::bossError);
        }
        if (empty($worker_list['data'])) {
            return $this->send(null, "没有可用阿姨", 0, 403, null, alertMsgEnum::serverWorkerListFail);
        } else {
            return $this->send($worker_list, "获取周期服务可用阿姨列表成功", 1, 200, null, alertMsgEnum::serverWorkerListSuccess);
        }
    }

    /**
     * @api {GET} /service/baidu-map [GET]/service/baidu-map（100%）
     * @apiGroup service
     * @apiName actionBaiduMap
     * @apiDescription 根据地址获取百度地图数据(赵顺利 )
     *
     * @apiParam {String} query 查询关键字
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} location 经纬度
     * @apiParam {Number} radius 半径
     * @apiParam {String} output 输出方式
     * @apiParam {String} ak
     * @apiParam {Number} page_size 每页条数
     * @apiParam {Number} page_num 页数  允许为0
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/service/baidu-map
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "1",
     *      "msg": "",
     *      "ret":
     *  }
     *
     * @apiError queryNotSupportFound 关键字不能为空.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"0",
     *       "msg": "关键字不能为空"
     *     }
     */
    public function actionBaiduMap()
    {
        $params = Yii::$app->request->get();

        $path = "http://api.map.baidu.com/place/v2/search";
        if (empty($params) || empty($params['query']) || empty($params['location']) || empty($params['radius']) || empty($params['output']) || empty($params['ak']) || empty($params['page_size']) || is_null($params['page_num'])) {
            return $this->send(null, '参数不完成', '0', '403', null, alertMsgEnum::baiduMapFailed);
        }
        $url = "http://api.map.baidu.com/place/v2/search?query=" . $params['query'] . '&location=' . $params['location'] .
                '&radius=' . $params['radius'] . '&output=' . $params['output'] . '&ak=' . $params['ak']
                . '&page_size=' . $params['page_size'] . '&page_num=' . $params['page_num'];

        $date = file_get_contents($url);

        return $this->send(json_decode($date), '操作成功', '0', '200', null, alertMsgEnum::baiduMapSuccess);
    }

    /**
     * @api {GET} /service/get-shop-district-info [GET] /service/get-shop-district-info（100%）
     * 
     * @apiDescription 根据经纬度获取商圈信息（李勇）
     * @apiGroup service
     * @apiName actionGetShopDistrictInfo
     *
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} order_channel_name      订单渠道名称
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "获取商圈信息成功",
     *       "ret": {
     *           "id": "1",
     *           "operation_shop_district_id": "商圈id",
     *           "operation_shop_district_name": "商圈名称",
     *           "operation_city_id": "城市编号",
     *           "operation_city_name": "城市名称",
     *           "operation_area_id": "区域id",
     *           "operation_area_name": "区域名称"
     *       },
     *       "alertMsg": "获取商圈信息成功"
     *   }
     *
     * @apiError queryNotSupportFound 获取商圈信息失败
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *   {
     *       "code": 0,
     *       "msg": "商圈不存在",
     *       "ret": {},
     *       "alertMsg": "商圈不存在"
     *   }
     */
    public function actionGetShopDistrictInfo()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 401, 403, null, alertMsgEnum::customerLoginFailed);
        }
        if (!isset($param['longitude']) || !$param['longitude'] || !isset($param['latitude']) || !$param['latitude']) {
            return $this->send(null, "请填写服务地址", 0, 403, null, alertMsgEnum::serverWorkerListNoAddress);
        }
        $longitude = $param['longitude'];
        $latitude = $param['latitude'];
        $customer = CustomerAccessToken::getCustomer($param['access_token']);
        $customer_id = $customer->id;
        //根据经纬度获取商圈id
        try {
            $ShopDistrictInfo = OperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::bossError);
        }
        if (empty($ShopDistrictInfo)) {
            return $this->send(null, "商圈不存在", 0, 403, null, alertMsgEnum::getShopDistrictInfoFail);
        } else {
            return $this->send($ShopDistrictInfo, "获取商圈信息成功", 1, 200, null, alertMsgEnum::getShopDistrictInfoSuccess);
        }
    }

}

?>