<?php
namespace api\controllers;

use Yii;
use core\models\Operation\CoreOperationShopDistrictGoods;
use core\models\Operation\CoreOperationCategory;
use core\models\Operation\CoreOperationCity;
use \core\models\customer\CustomerAccessToken;

class ConfigureController extends \api\components\Controller
{
    /**
     * @api {POST} /configure/all-services 城市服务初始化 （已完成）
     * @apiName actionAllServices
     * @apiGroup configure
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
     * @api {POST} v1/configure/user-init 用户端首页初始化 （赵顺利20% 假数据）
     * @apiName actionUserInit
     * @apiGroup configure
     * @apiDescription 获得开通城市列表，广告轮播图 等初始化数据
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "code": "ok",
     *          "msg": "操作成功",
     *          "ret": {
     *              "city_list": [
     *              {
     *                  "id": 1,
     *                  "province_id": 120000,
     *                  "province_name": "天津",
     *                  "city_id": 120100,
     *                  "city_name": "天津市",
     *                  "operation_city_is_online": 1,
     *                  "created_at": 1444283773,
     *                  "updated_at": 1444283773
     *              },
     *              {
     *                  "id": 2,
     *                  "province_id": 110000,
     *                  "province_name": "北京",
     *                  "city_id": 110100,
     *                  "city_name": "北京市",
     *                  "operation_city_is_online": 1,
     *                  "created_at": 1444368462,
     *                  "updated_at": 1444368462
     *              },
     *              {
     *                  "id": 3,
     *                  "province_id": 140000,
     *                  "province_name": "山西省",
     *                  "city_id": 140300,
     *                  "city_name": "阳泉市",
     *                  "operation_city_is_online": 1,
     *                  "created_at": 1444413962,
     *                  "updated_at": 1444413962
     *              },
     *              {
     *                  "id": 4,
     *                  "province_id": 140000,
     *                  "province_name": "山西省",
     *                  "city_id": 140100,
     *                  "city_name": "太原市",
     *                  "operation_city_is_online": 1,
     *                  "created_at": 1444635891,
     *                  "updated_at": 1444635891
     *              }
     *              ],
     *              "pic_list": [
     *              {
     *                  "img_path": "http://webapi2.1jiajie.com/app/images/ios_banner_1.png",
     *                  "link": "http://wap.1jiajie.com/trainAuntie1.html",
     *                  "url_title": "标准服务"
     *              },
     *              {
     *                  "img_path": "http://webapi2.1jiajie.com/app/images/20150603ad_top_v4_1.png",
     *                  "link": "http://wap.1jiajie.com/pledge.html",
     *                  "url_title": "服务承诺"
     *              },
     *              {
     *                  "img_path": "http://webapi2.1jiajie.com/app/images/20150311ad_top_v4_3.png",
     *                  "link": "",
     *                  "url_title": ""
     *              }
     *              ],
     *              "server_list": [
     *              {
     *                  "goods_id": "1",
     *                  "goods_no": "",
     *                  "goods_name": "管道疏通",
     *                  "goods_introduction": "含：专业设备+专业技师+上门服务",
     *                  "goods_english_name": "",
     *                  "goods_img": "",
     *                  "goods_app_ico": "",
     *                  "goods_pc_ico": "",
     *                  "goods_price": "160.00",
     *                  "goods_price_unit": "眼",
     *                  "goods_price_description": ""
     *              },
     *              {
     *                  "goods_id": "2",
     *                  "goods_no": "",
     *                  "goods_name": "家电维修",
     *                  "goods_introduction": "含：专业设备+专业技师+上门服务",
     *                  "goods_english_name": "",
     *                  "goods_img": "",
     *                  "goods_app_ico": "",
     *                  "goods_pc_ico": "",
     *                  "goods_price": "160.00",
     *                  "goods_price_unit": "次",
     *                  "goods_price_description": ""
     *              },
     *              {
     *                  "goods_id": "3",
     *                  "goods_no": "",
     *                  "goods_name": "家具组装",
     *                  "goods_introduction": "含：专业设备+专业技师+上门服务",
     *                  "goods_english_name": "",
     *                  "goods_img": "",
     *                  "goods_app_ico": "",
     *                  "goods_pc_ico": "",
     *                  "goods_price": "160.00",
     *                  "goods_price_unit": "次",
     *                  "goods_price_description": ""
     *              }
     *          ]
     *      }
     * }
     *
     * @apiError CityNotFound 城市尚未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code":"error",
     *       "msg": "城市尚未开通"
     *     }
     */
    public function actionUserInit()
    {
        $param = Yii::$app->request->post() or
        $param = json_decode(Yii::$app->request->getRawBody(), true);

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", "error", "403");
        }
        //获取城市列表
        $city_list = CoreOperationCity::getOnlineCitys();
        //获取首页轮播图
        $pic_list = [
            [
                "img_path" => "http://webapi2.1jiajie.com/app/images/ios_banner_1.png",
                "link" => "http://wap.1jiajie.com/trainAuntie1.html",
                "url_title" => "标准服务"
            ],
            [
                "img_path" => "http://webapi2.1jiajie.com/app/images/20150603ad_top_v4_1.png",
                "link" => "http://wap.1jiajie.com/pledge.html",
                "url_title" => "服务承诺"
            ],
            [
                "img_path" => "http://webapi2.1jiajie.com/app/images/20150311ad_top_v4_3.png",
                "link" => "",
                "url_title" => ""
            ]
        ];
        //获取该城市的首页服务类型
        $server_list = [
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

        $ret = [
            'city_list' => $city_list,
            'pic_list' => $pic_list,
            'server_list' => $server_list,
        ];

        return $this->send($ret, '操作成功', 'ok');
    }

    /**
     * @api {POST} /configure/worker-check-update 检查阿姨端版本更新 （赵顺利0%）
     * @apiName actionWorkerCheckUpdate
     * @apiGroup configure
     *
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "curAndroidVersion": 23,
     *          "androidVersionUrl": "http://webapi2.1jiajie.com/app/aunt_2.5.apk",
     *          "androidVersionAlertMsg": "1、兼职阿姨也可登录阿姨端。2、兼职阿姨可修改自己的工作时间。3、新增待接活订单推送通知。",
     *          "isAndroidUpdateForce": false,
     *          "msgStyle": "",
     *          "alertMsg": ""
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */

    /**
     * @api {POST} /configure/worker-init 阿姨app初始化 （赵顺利0%）
     * @apiName actionWorkerInit
     * @apiGroup configure
     *
     * @apiParam {String} session_id 会话id.
     * @apiParam {String} platform_version 平台版本号.
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "OK",
     *      "msg": "首页信息获取成功",
     *      "ret":
     *      {
     *          "msgStyle": "",
     *          "alertMsg": "",
     *          "worker":
     *          {
     *              "id": "1111116",
     *              "name": "陈测试1",
     *              "head": "http://static.1jiajie.com/worker/face/1111116.jpg",
     *              "notify": "傍晚好，祝您晚餐愉快！"
     *          },
     *          "forEntry":
     *          {
     *              "title": "",
     *              "num": 0,
     *              "info": []
     *           },
     *          "forService":
     *          {
     *              "title": "您的下一个订单",
     *              "num": 7,
     *              "info":
     *              [
     *              {
     *                  "auot_pay_status": 0,
     *                  "order_id": "188",
     *                  "is_member": 1,
     *                  "member_value": "4600.00",
     *                  "reserve_time": "2015-09-10 12:00",
     *                  "date_name_tag": "09月10日",
     *                  "lat": "39.929669",
     *                  "lng": "116.523996",
     *                  "city_name": "北京",
     *                  "place": "朝阳区大悦城 测试测试",
     *                  "telephone": "15652146926",
     *                  "list": [],
     *                  "order_status": "2",
     *                  "server_type_name": "家庭保洁",
     *                  "suggest_worked_time": 2,
     *                  "worker_time": "2015-09-10 12:00-14:00",
     *                  "extend_info": "",
     *                  "pay_amount": "0",
     *                  "coupon": "",
     *                  "coupon_money": 0,
     *                  "is_cancel": "0",
     *                  "order_price": 25
     *              }
     *              ]
     *          },
     *          "sysMsg":
     *          {
     *              "num": 0,
     *              "info": ""
     *          },
     *          "picVersion": 1,
     *          "bigPic": "http://webapi2.1jiajie.com/ayiduan/images/aunt_ad_big.png",
     *          "smallPic": "http://webapi2.1jiajie.com/ayiduan/images/aunt_ad_small.png",
     *          "isShow": 1
     *      }
     *  }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */

    /**
     * 支付方式展示api 获得各个支付方式的配置 折叠显示列表，默认支付方式，非折叠显示列表
     */

    /**
     * 通过广告位置 获取广告
     */

    /**
     * 是否强制更新
     */
}


?>