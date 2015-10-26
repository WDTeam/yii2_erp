<?php
namespace api\controllers;

use Yii;
use core\models\Operation\CoreOperationShopDistrictGoods;
use core\models\Operation\CoreOperationCategory;
use core\models\Operation\CoreOperationShopDistrictCoordinate;
use \core\models\worker\Worker;
use \core\models\customer\CustomerAccessToken;


class ServiceController extends \api\components\Controller
{
    /**
     * @api {GET} v1/service/service-goods 依据城市和服务品类 获取服务类型列表 （赵顺利 80%url不能获取）
     * @apiName actionServiceGoods
     * @apiGroup service
     * @apiDescription 获得某城市下某服务的所有子服务列表，返回子服务数组[服务名,服务描述,服务图标，服务id，url]
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} category_id 服务品类id
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "ok",
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
     *       "code":"error",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionServiceGoods()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403);
        }

        $goodses = CoreOperationShopDistrictGoods::getGoodsByCityCategory($param['city_name'], $param['category_id']);

        if (empty($goodses)) {
            return $this->send(null, "该城市暂未开通该类型的服务", 0, 403);
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
        return $this->send($gDate, "数据获取成功");
    }

    /**
     * @api {GET} v1/service/home-services 依据城市 获取首页服务列表 （赵顺利 20% 假数据，未与boss关联）
     * @apiName actionHomeServices
     * @apiGroup service
     * @apiDescription 获取城市首页服务项目信息简介
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
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
            return $this->send(null, "未取得城市信息", 0, 403);
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

        return $this->send($ret, "信息获取成功");
    }

    /**
     * @api {GET} v1/service/all-services 依据城市 获取所有服务列表 (赵顺利100%)
     * @apiName actionAllServices
     * @apiGroup service
     * @apiDescription 获取城市所以服务类型列表
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "ok",
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
     *       "code":"error",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionAllServices()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403);
        }

        $categoryes = CoreOperationCategory::getAllCategory();
        $goodses = CoreOperationShopDistrictGoods::getGoodsByCity($param['city_name']);

        if (empty($categoryes) || empty($goodses)) {
            return $this->send(null, "该城市暂未开通", 0, 403);
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

        return $this->send($cDate, "数据获取成功");
    }

    /**
     * @api {GET} v1/service/goods-price 获取某城市某商品的价格及备注信息（赵顺利 100%）
     * @apiName actionGoodsPrice
     * @apiGroup service
     * @apiDescription 获取某城市某商品的价格及备注信息
     *
     * @apiParam {String} city_id 城市id
     * @apiParam {String} longitude 经度
     * @apiParam {String} latitude 纬度
     * @apiParam {String} goods_id 服务品类id
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": 1,
     *      "msg": "",
     *      "ret":
     *      [
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
            return $this->send(null, "经纬度信息不存在", 0, 403);
        }
        $shopDistrict = CoreOperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($params['longitude'], $params['latitude']);
        if (empty($shopDistrict)) {
            return $this->send(null, "没有上线商圈", 0, 403);
        }
        $goods = CoreOperationShopDistrictGoods::getShopDistrictGoodsInfo($params['city_id'], $shopDistrict['operation_shop_district_id'], $params['goods_id']);

        if (empty($goods)) {
            return $this->send(null, "该商圈没有上线当前服务品类", 0, 403);
        }

        $ret = [
            'goods_price' => $goods['operation_shop_district_goods_price'],
        ];

        return $this->send($ret, "数据获取成功");
    }

    /**
     * @api {GET} v1/service/boutique-cleaning 获得所有精品保洁项目（赵顺利0%）
     * @apiGroup service
     * @apiName actionBoutiqueCleaning
     * @apiDescription 获取城市所有精品保洁
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "ok",
     *      "msg": "",
     *      "ret":
     *  }
     *
     * @apiError CityNotSupportFound 该城市暂未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"error",
     *       "msg": "该城市暂未开通"
     *     }
     */

    /**
     * @api {get} v1/service/single-service-time  单次服务排班表(李勇90%缺少model支持)
     * @apiName SingleServiceTime
     * @apiGroup service
     * @apiDescription 单次服务获取服务时间
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} service_type  服务类型
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     * @apiParam {String} plan_time 计划服务时长
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *       "code": "ok",
     *       "msg": "获取单次服务排班表成功"
     *       "ret":{
     *          "single_worker_time": [
     *               {
     *                   "date_format": "10月25日",
     *                   "date_stamp": 1445669758,
     *                   "week": "明天",
     *                   "hour": [
     *                       {
     *                           "time": "08:00-10:00",
     *                           "status": "0"
     *                       },
     *                       {
     *                           "time": "18:00-20:00",
     *                           "status": "1"
     *                       }
     *                   ]
     *               },
     *               {
     *                   "date_format": "10月26日",
     *                   "date_stamp": 1445669758,
     *                   "week": "",
     *                   "hour": [
     *                       {
     *                           "time": "08:00-10:00",
     *                           "status": "0"
     *                       },
     *                       {
     *                           "time": "18:00-20:00",
     *                           "status": "1"
     *                       }
     *                   ]
     *               },
     *          ]
     *       }
     *   }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    function actionSingleServiceTime()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        if (!isset($param['longitude']) || !$param['longitude'] || !isset($param['latitude']) || !$param['latitude'] || !isset($param['plan_time']) || !$param['plan_time']) {
            return $this->send(null, "请填写服务地址或服务时长", 0, 403);
        }
        $longitude = $param['longitude'];
        $latitude = $param['latitude'];
        $plan_time = $param['plan_time'];
        //根据经纬度获取商圈id
        $ShopDistrictInfo = CoreOperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        if (empty($ShopDistrictInfo)) {
            return $this->send(null, "商圈不存在", 0, 403);
        } else {
            $district_id = $ShopDistrictInfo['id'];
        }
        //获取单次服务排班表
        //$single_worker_time=Worker::getSingleWorkerTable($district_id,$plan_time);
        $single_worker_time = array();
        for ($i = 1; $i <= 7; $i++) {
            $item = [
                'date_format' => date('m月d日', strtotime('+' . $i . ' day')),
                'date_stamp' => time(date('m月d日', strtotime('+' . $i . ' day'))),
                'week' => $i == 1 ? '明天' : '',
                'hour' =>
                    [
                        ['time' => '08:00-10:00',
                            'status' => '0']

                        ,
                        [
                            "time" => "18:00-20:00",
                            "status" => "1"
                        ]
                    ]
            ];
            $single_worker_time[] = $item;
        }

        $ret = ["single_worker_time" => $single_worker_time];
        return $this->send($ret, "获取单次服务排班表成功");
    }

    /**
     * @api {get} /worker/recursive-service-time  周期服务时间表(李勇90%缺少model)
     * @apiName actionRecursiveServiceTime
     * @apiGroup service
     * @apiDescription 周期服务时间表
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} service_type  服务类型
     * @apiParam {String} longitude     当前经度.
     * @apiParam {String} latitude      当前纬度.
     * @apiParam {String} is_recommend  是否使用推荐阿姨（0：不是，1：是）
     * @apiParam {String} worker_id   阿姨id.
     * @apiParam {String} plan_time 计划服务时长.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *       "code": "ok",
     *       "msg": "获取周期服务时间表成功"
     *       "ret":{
     *          "recursive_worker_time": [
     *               {
     *                   "date_format": "10月25日",
     *                   "date_stamp": 1445669758,
     *                   "week": "明天",
     *                   "hour": [
     *                       {
     *                           "time": "08:00-10:00",
     *                           "status": "0"
     *                       },
     *                       {
     *                           "time": "18:00-20:00",
     *                           "status": "1"
     *                       }
     *                   ]
     *               },
     *               {
     *                   "date_format": "10月26日",
     *                   "date_stamp": 1445669758,
     *                   "week": "",
     *                   "hour": [
     *                       {
     *                           "time": "08:00-10:00",
     *                           "status": "0"
     *                       },
     *                       {
     *                           "time": "18:00-20:00",
     *                           "status": "1"
     *                       }
     *                   ]
     *               },
     *          ]
     *       }
     *   }
     *
     * @apiError UserNotFound 用户认证已经过期.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    function actionRecursiveServiceTime()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        if (!isset($param['longitude']) || !$param['longitude'] || !isset($param['latitude']) || !$param['latitude'] || !isset($param['is_recommend']) || !isset($param['plan_time']) || !$param['plan_time']) {
            return $this->send(null, "请填写服务地址或服务时长", 0, 403);
        }
        $longitude = $param['longitude'];
        $latitude = $param['latitude'];
        $is_recommend = $param['is_recommend'];
        $plan_time = $param['plan_time'];
        if ($is_recommend == 1) {
            if (!isset($param['worker_id']) || !$param['worker_id']) {
                return $this->send(null, "请选择服务阿姨", 0, 403);
            } else {
                $worker_id = $param['worker_id'];
            }
        }
        //根据经纬度获取商圈id
        $ShopDistrictInfo = CoreOperationShopDistrictCoordinate::getCoordinateShopDistrictInfo($longitude, $latitude);
        if (empty($ShopDistrictInfo)) {
            return $this->send(null, "商圈不存在", 0, 403);
        } else {
            $district_id = $ShopDistrictInfo['id'];
        }
        //获取周期服务时间表
        //$recursive_worker_time=Worker::getRecursiveWorkerTable($district_id,$plan_time);
        $recursive_worker_time = array();
        for ($i = 7; $i <= 36; $i++) {
            $item = [
                'date_name' => date('m月d日', strtotime('+' . $i . ' day')),
                'date_week' => date('w', strtotime('+' . $i . ' day')),
                'date_week_every' => '每周日',
                'date_time' =>
                    [
                        ['time' => '08:00-10:00',
                            'status' => '0']

                        ,
                        [
                            "time" => "18:00-20:00",
                            "status" => "1"
                        ]
                    ],
                'date_name_tag' => date('m月d日', strtotime('+' . $i . ' day')) . '（今天）'
            ];
            $recursive_worker_time[] = $item;
        }
        $ret = ["recursive_worker_time" => $recursive_worker_time];
        return $this->send($ret, "获取周期服务时间表成功");
    }

    /**
     * @api {GET} v1/service/baidu-map 根据地址获取百度地图数据（赵顺利0%）
     * @apiGroup service
     * @apiName actionBaiduMap
     * @apiDescription 获取城市所有精品保洁
     *
     * @apiParam {String} query 查询关键字
     * @apiParam {String} location 经纬度
     * @apiParam {String} radius 半径
     * @apiParam {String} output 输出方式
     * @apiParam {String} ak
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "ok",
     *      "msg": "",
     *      "ret":
     *  }
     *
     * @apiError CityNotSupportFound 该城市暂未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "code":"error",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionBaiduMap()
    {
        $params=Yii::$app->request->get();

        $url="http://api.map.baidu.com/place/v2/search";
        if(empty($params)||empty($params['query'])||empty($params['location'])||empty($params['radius'])||empty($params['output'])||empty($params['ak']))
        {
            return $this->send(null,'参数不完成','error','403');
        }
        $url="http://api.map.baidu.com/place/v2/search?query=".$params['query'].'&location='.$params['location'].
            '&radius='.$params['radius'].'&output='.$params['output'].'$ak='.$params['ak'];

        $date=\Yii::$app->response->redirect($url, 301);

        return $this->send($date,'操作成功','ok');

    }
}

?>