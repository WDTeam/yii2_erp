<?php
namespace restapi\controllers;

use Yii;
use \core\models\operation\OperationShopDistrictGoods;
use \core\models\operation\OperationCategory;
use \core\models\operation\OperationCity;
use \core\models\customer\CustomerAccessToken;
use \core\models\order\OrderSearch;
use \core\models\order\OrderStatusDict;
use \core\models\worker\WorkerAccessToken;
use \restapi\models\alertMsgEnum;

class ConfigureController extends \restapi\components\Controller
{
    /**
     * @api {GET} /configure/all-services  [GET] /configure/all-services（100%）
     * @apiDescription 获取城市全部上线服务 (赵顺利)
     * @apiName actionAllServices 
     * @apiGroup configure
     * 
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
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
     * @apiDescription 获取城市服务配置项价格介绍页面以及分类的全部服务项目
     */
    public function actionAllServices()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403,null,alertMsgEnum::allServicesFailed);
        }

        $categoryes = OperationCategory::getAllCategory();
        $goodses = OperationShopDistrictGoods::getGoodsByCity($param['city_name']);

        if (empty($categoryes) || empty($goodses)) {
            return $this->send(null, "该城市暂未开通", 0, 403,null,alertMsgEnum::allServicesFailed);
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

        return $this->send($cDate, "数据获取成功", 1,200,null,alertMsgEnum::allServicesSuccess);
    }

    /**
     * @api {GET} /configure/user-init  [GET] /configure/user-init（20% ）
     * @apiName actionUserInit
     * @apiGroup configure
     * @apiDescription 用户端首页初始化,获得开通城市列表，广告轮播图 等初始化数据(赵顺利--假数据 )
     *
     * @apiParam {String} city_name 城市
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "code": "1",
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
     *              "home_order_server": [
     *              {
     *                  "title"=>"单次保洁",
     *                  "introduction"=>"新用户第1小时免费",
     *                  "icon"=>"",
     *                  "url"=>"",
     *                  "sort"=>"1",  排序
     *                  "bg_colour"=>"",  背景颜色
     *                  "font_colour"=>"",  字体颜色
     *                  "category_id" => "1",
     *                  "category_name" => "专业保洁",
     *                  "category_icon" => "",
     *                  "category_introduction" => "44项定制清洁服务",
     *                  "category_price" => "25.00",
     *                  "category_price_unit" => "小时",
     *                  "category_price_description" => "￥25/小时",
     *              },
     *              ],
     *              "server_list": [
     *              {
     *                  "category_id": "6",   服务品类id
     *                  "category_name": "精品保洁",  服务品类名
     *                  "category_icon": "",   小图片
     *                  "category_url": "",    调转地址url
     *                  "category_introduction": "",  简介
     *                  "category_price": "",  价格
     *                  "category_price_unit": "",  价格单位
     *                  "category_price_description": "",  价格备注
     *                  "colour"=>"",
     *                  "sort": "1"   排序
     *              },
     *              {
     *                  "category_id": "1",
     *                  "category_name": "专业保洁",
     *                  "category_icon": "",
     *                  "category_url": "",
     *                  "category_introduction": "44项定制清洁服务",
     *                  "category_price": "25.00",
     *                  "category_price_unit": "小时",
     *                  "category_price_description": "￥25/小时",
     *                  "colour"=>"",
     *                  "sort": "2"
     *              },
     *              ],
     *              "footer_link":[
     *              {
     *                  "link_id"=>"1",
     *                  "title"=>"首页",
     *                  "url"=>"",   跳转链接
     *                  "link_icon_check" => "http://dev.m2.1jiajie.com/statics/images/nav_01.png", 选中图片
     *                  "link_icon_uncheck" => "http://dev.m2.1jiajie.com/statics/images/nav_d_01.png", 未选中图片
     *                  "colour_check" => "#f7b136", 选中颜色
     *                  "colour_uncheck" => "#555",  未选中颜色
     *                  "sort"=>"1"  排序
     *              },
     *              {
     *                  "link_id"=>"2",
     *                  "title"=>"订单",
     *                  "url"=>"",
     *                  "link_icon"=>"",
     *                  "colour"=>"",
     *                  "sort"=>"2"
     *              },
     *          ]
     *      }
     * }
     *
     * @apiError CityNotFound 城市尚未开通.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code":"0",
     *       "msg": "城市尚未开通"
     *     }
     */
    public function actionUserInit()
    {
        $param = Yii::$app->request->get();

        if (empty(@$param['city_name'])) {
            return $this->send(null, "未取得城市信息", 0, 403,null,alertMsgEnum::getUserInitFailed);
        }
        //获取城市列表
        $city_list = OperationCity::getOnlineCitys();
        //页首链接
        $header_link = [
            'comment_link' => [
                'title' => '意见反馈',
                'url' => 'http://dev.m2.1jiajie.com/statics/images/MyView_FeedBack.png',
                'img' => 'http://dev.m2.1jiajie.com/statics/images/MyView_FeedBack.png',
            ],
            'phone_link' => [
                'title' => '18210922324',
                'url' => '',
                'img' => 'http://dev.m2.1jiajie.com/statics/images/MyView_Tel.png',
            ],
        ];
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
        $home_order_server = [
            [
                'title' => '单次保洁',
                'introduction' => '新用户第1小时免费',
                'icon' => 'http://dev.m2.1jiajie.com/statics/images/dancibaojie.png',
                'url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/1',
                'sort' => '1',
                'bg_colour' => 'ffb518',
                'font_colour' => 'ffffff',
                'category_id' => '1',
                'category_name' => '专业保洁',
                'category_icon' => '',
                'category_introduction' => '44项定制清洁服务',
                'category_price' => '25.00',
                'category_price_unit' => '小时',
                'category_price_description' => '￥25/小时',
            ],
            [
                'title' => '周期保洁',
                'introduction' => '一次下单 清洁无忧',
                'icon' => 'http://dev.m2.1jiajie.com/statics/images/zhouqibaojie.png',
                'url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/2',
                'sort' => '2',
                'bg_colour' => 'ff8a44',
                'font_colour' => 'ffffff',
                'category_id' => '1',
                'category_name' => '专业保洁',
                'category_icon' => '',
                'category_introduction' => '44项定制清洁服务',
                'category_price' => '25.00',
                'category_price_unit' => '小时',
                'category_price_description' => '￥25/小时',
            ]

        ];
        //获取该城市的首页服务类型
        $server_list = [
            [
                'category_id' => '6',
                'category_name' => '保洁任务',
                'category_icon' => 'http://dev.m2.1jiajie.com/statics/images/baojierenwu.png',
                'category_url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/3',
                'category_introduction' => '37项定制化精品保洁',
                'category_price' => '30',
                'category_price_unit' => '小时',
                'category_price_description' => '￥30/小时',
                'colour' => 'ff701a',
                'sort' => '1',

            ],
            [
                'category_id' => '1',
                'category_name' => '专业保洁',
                'category_icon' => 'http://dev.m2.1jiajie.com/statics/images/zhuanyebaojie.png',
                'category_url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/4',
                'category_introduction' => '44项定制清洁服务',
                'category_price' => '25.00',
                'category_price_unit' => '小时',
                'category_price_description' => '￥25/小时',
                'colour' => 'ffb518',
                'sort' => '2',

            ],
            [
                'category_id' => '2',
                'category_name' => '洗护服务',
                'category_icon' => 'http://dev.m2.1jiajie.com/statics/images/xihufuwu.png',
                'category_url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/4',
                'category_introduction' => '衣服、皮鞋、美包',
                'category_price' => '9.00',
                'category_price_unit' => '件',
                'category_price_description' => '￥9/件起',
                'colour' => '7fce0f',
                'sort' => '3',
            ],
            [
                'category_id' => '3',
                'category_name' => '家电维修',
                'category_icon' => 'http://dev.m2.1jiajie.com/statics/images/jiadianweixiu.png',
                'category_url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/4',
                'category_introduction' => '油烟机、空调等深度清洁',
                'category_price' => '100.00',
                'category_price_unit' => '台',
                'category_price_description' => '￥100/台起',
                'colour' => '2cc2f9',
                'sort' => '4',
            ],
            [
                'category_id' => '4',
                'category_name' => '家具养护',
                'category_icon' => 'http://dev.m2.1jiajie.com/statics/images/jiajuyanghu.png',
                'category_url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/4',
                'category_introduction' => '地板家具深度养护、除螨',
                'category_price' => '',
                'category_price_unit' => '',
                'category_price_description' => '￥250起',
                'colour' => 'e6001f',
                'sort' => '5',
            ],
            [
                'category_id' => '5',
                'category_name' => '生活急救箱',
                'category_icon' => 'http://dev.m2.1jiajie.com/statics/images/shenghuojijiu.png',
                'category_url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/4',
                'category_introduction' => '管道维修疏通、除虫',
                'category_price' => '',
                'category_price_unit' => '',
                'category_price_description' => '￥160起',
                'colour' => 'e544a3',
                'sort' => '6',
            ],
        ];

        $footer_link = [
            [
                'link_id' => '1',
                'title' => '首页',
                'url' => '#',
                'link_icon_check' => 'http://dev.m2.1jiajie.com/statics/images/nav_01.png',
                'link_icon_uncheck' => 'http://dev.m2.1jiajie.com/statics/images/nav_d_01.png',
                'colour_check' => 'f7b136',
                'colour_uncheck' => '555555',
                'sort' => '1',
            ],
            [
                'link_id' => '2',
                'title' => '订单',
                'url' => 'http://dev.m2.1jiajie.com/index.html#/order/index',
                'link_icon_check' => 'http://dev.m2.1jiajie.com/statics/images/nav_02.png',
                'link_icon_uncheck' => 'http://dev.m2.1jiajie.com/statics/images/nav_d_02.png',
                'colour_check' => 'f7b136',
                'colour_uncheck' => '555555',
                'sort' => '2',
            ],
            [
                'link_id' => '3',
                'title' => '优惠券',
                'url' => 'http://dev.m2.1jiajie.com/index.html#/promoCode/index',
                'link_icon_check' => 'http://dev.m2.1jiajie.com/statics/images/nav_03.png',
                'link_icon_uncheck' => 'http://dev.m2.1jiajie.com/statics/images/nav_d_03.png',
                'colour_check' => 'f7b136',
                'colour_uncheck' => '555555',
                'sort' => '3',
            ],
            [
                'link_id' => '4',
                'title' => '我的',
                'url' => 'http://dev.m2.1jiajie.com/index.html#/personalCenter/index',
                'link_icon_check' => 'http://dev.m2.1jiajie.com/statics/images/nav_04.png',
                'link_icon_uncheck' => 'http://dev.m2.1jiajie.com/statics/images/nav_d_04.png',
                'colour_check' => 'f7b136',
                'colour_uncheck' => '555555',
                'sort' => '4',
            ],
        ];

        $ret = [
            'city_list' => $city_list,
            'header_link' => $header_link,
            'pic_list' => $pic_list,
            'home_order_server' => $home_order_server,
            'server_list' => $server_list,
            'footer_link' => $footer_link,
        ];

        return $this->send($ret, '操作成功',1,200,null,alertMsgEnum::getUserInitSuccess);
    }

    /**
     * @api {GET} /configure/worker-check-update [GET] /configure/worker-check-update （0%）
     * @apiDescription 检查阿姨端版本更新 (赵顺利)
     * @apiName actionWorkerCheckUpdate
     * @apiGroup configure
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "1",
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
     *      "code":"0",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */

    /**
     * @api {GET} /configure/worker-init  [GET] /configure/worker-init（100%）
     * @apiDescription 阿姨app初始化 （赵顺利）
     * @apiName actionWorkerInit
     * @apiGroup configure
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "code": "1",
     *          "msg": "操作成功",
     *          "ret": {
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
     *              "order_num":
     *              {
     *                  "server_count"=>"", 待服务订单
     *                  "worker_count"=>"", 指定阿姨订单
     *                  "order_count"=>"",  待抢单订单
     *
     *              },
     *              "footer_link":[
     *              {
     *                  "link_id"=>"1",
     *                  "title"=>"首页",
     *                  "url"=>"",   跳转链接
     *                  "link_icon"=>"",
     *                  "colour"=>"",
     *                  "sort"=>"1"  排序
     *              },
     *              {
     *                  "link_id"=>"2",
     *                  "title"=>"任务",
     *                  "url"=>"",
     *                  "link_icon"=>"",
     *                  "colour"=>"",
     *                  "sort"=>"2"
     *              },
     *          ]
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
    public function actionWorkerInit()
    {
        $params = Yii::$app->request->get();
        @$token = $params['access_token'];
        $worker = WorkerAccessToken::getWorker($token);
        if (empty($worker)) {
            return $this->send(null, "用户无效,请先登录", 0,403,null,alertMsgEnum::getWorkerInitFailed);
        }
        //获取阿姨待服务订单
        $args["owr.worker_id"] = $worker->id;
        $arr = array();
        $arr[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
        $arr[] = OrderStatusDict::ORDER_WORKER_BIND_ORDER;

        $serverCount = OrderSearch::searchWorkerOrdersWithStatusCount($args, $arr);
        //获取待服务订单
        //"$workerCount": "指定阿姨订单"
        //"$orderCount": "待抢单订单"
        $workerCount = OrderSearch::getPushWorkerOrdersCount($worker->id, 0);
        $orderCount = OrderSearch::getPushWorkerOrdersCount($worker->id, 1);

        $order_num = [
            "server_count" => $serverCount,
            "worker_count" => $workerCount,
            "order_count" => $orderCount,
        ];

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
        $footer_link = [
            [
                'link_id' => '1',
                'title' => '首页',
                'url' => '',
                'link_icon' => '',
                'colour' => '',
                'sort' => '1',
            ],
            [
                'link_id' => '2',
                'title' => '订单',
                'url' => '',
                'link_icon' => '',
                'colour' => '',
                'sort' => '2',
            ],
            [
                'link_id' => '3',
                'title' => '优惠券',
                'url' => '',
                'link_icon' => '',
                'colour' => '',
                'sort' => '3',
            ],
            [
                'link_id' => '4',
                'title' => '我的',
                'url' => '',
                'link_icon' => '',
                'colour' => '',
                'sort' => '4',
            ],
        ];

        $ret = [
            'pic_list' => $pic_list,
            'order_num' => $order_num,
            'footer_link' => $footer_link,
        ];

        return $this->send($ret, "查询成功",1,200,null,alertMsgEnum::getWorkerInitSuccess);

    }

    /**
     * @api {GET} /configure/start-page  [GET] /configure/start-page  （20%）
     * @apiDescription 阿姨端启动页（赵顺利）
     * @apiName actionStartPage
     * @apiGroup configure
     *
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "code": "1",
     *          "msg": "操作成功",
     *          "ret": {
     *              "pages": [
     *              {
     *                  "id": "1", 编号
     *                  "img": "", 图片地址
     *                  "title": "", 文字
     *                  "remark": "",  备注
     *                  "sort": "1" 排序
     *                  "time": "5"  停留时间，默认5秒
     *                  "next_url": "" 下一页url
     *              },
     *              {
     *                  "id": "2", 编号
     *                  "img": "", 图片地址
     *                  "title": "", 文字
     *                  "remark": "",  备注
     *                  "sort": "2" 排序
     *                  "time": "5"  停留时间，默认5秒
     *                  "next_url": "" 下一页url
     *              },
     *              ]
     *      }
     * }
     *
     * @apiError ExceptionService 服务异常.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "服务异常"
     *  }
     *
     */
    public function actionStartPage()
    {
        $params = Yii::$app->request->get();
        $app_version = $params['app_version'];

        if (empty($app_version)) {
            return $this->send(null, "访问源信息不存在，请确认信息完整",0,403,alertMsgEnum::getWorkerStartPageFailed);
        }
        $pages = [
            [
                "id" => "1",
                "img" => "",
                "title" => "",
                "remark" => "",
                "sort" => "1",
                "time" => "5",
                "next_url" => "",
            ],
            [
                "id" => "2",
                "img" => "",
                "title" => "",
                "remark" => "",
                "sort" => "2",
                "time" => "5",
                "next_url" => "",
            ],
        ];
        $ret=[
            'pages'=>$pages
        ];
        return $this->send($ret, "查询成功",1,200,alertMsgEnum::getWorkerStartPageSuccess);
    }

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