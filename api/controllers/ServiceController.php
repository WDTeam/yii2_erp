<?php
namespace api\controllers;

use Yii;
use core\models\Operation\CoreOperationShopDistrictGoods;
use core\models\Operation\CoreOperationCategory;

class ServiceController extends \api\components\Controller
{
    /**
     *获得某城市下某服务的所有子服务列表，返回子服务数组[服务名,服务描述,服务图标，服务id，url]
     */

    /**
     * @api {POST} v1/service/home-services 依据城市 获取首页服务列表 （赵顺利 20% 假数据，未与boss关联）
     * @apiName actionHomeServices
     * @apiGroup service
     * @apiDescription 获取城市首页服务项目信息简介
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "ok",
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
     *       "code":"error",
     *       "msg": "该城市暂未开通"
     *     }
     */
    public function actionHomeServices()
    {
        $param = Yii::$app->request->post() or
        $param = json_decode(Yii::$app->request->getRawBody(), true);

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", "error", "403");
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

        return $this->send($ret, "信息获取成功", "ok");
    }

    /**
     * @api {POST} v1/service/all-services 依据城市 获取所有服务列表 （已完成）
     * @apiName actionAllServices
     * @apiGroup service
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
     * @apiDescription 获取城市服务配置项价格介绍页面以及分类的全部服务项目
     */
    public function actionAllServices()
    {
        $param = Yii::$app->request->post() or
        $param = json_decode(Yii::$app->request->getRawBody(), true);

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", "error", "403");
        }

        $categoryes = CoreOperationCategory::getAllCategory();
        $goodses = CoreOperationShopDistrictGoods::getGoodsByCity($param['city_name']);

        if (empty($categoryes) || empty($goodses)) {
            return $this->send(null, "该城市暂未开通", "error", "403");
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

        return $this->send($cDate, "数据获取成功", "ok");
    }

    /**
     * 依据地址 获取某项服务在某个地址从今天开始7天商圈的阿姨可服务时间表
     */

    /**
     * 获取某城市某商品的价格备注信息
     */

    /**
     * 获得所有精品保洁项目
     */

}

?>