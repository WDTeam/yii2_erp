<?php
namespace api\controllers;

use Yii;
use core\models\Operation\CoreOperationShopDistrictGoods;
use core\models\Operation\CoreOperationCategory;
use core\models\Operation\CoreOperationShopDistrictCoordinate;


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
     * @api {GET} v1/service/all-services 依据城市 获取所有服务列表 （已完成）
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
     * @api {GET} v1/service/goods-service-time 依据地址 获取某服务从今天开始7天商圈的阿姨可服务时间表(赵顺利0%)
     * @apiName actionGoodsServiceTime
     * @apiGroup service
     * @apiDescription 依据地址 获取某项服务在某个地址从今天开始7天商圈的阿姨可服务时间表
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} goods_id 服务类型id
     * @apiParam {String} address_id 地址id
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

}

?>