<?php
namespace restapi\controllers;
use Yii;
use \core\models\operation\OperationShopDistrictGoods;
use \core\models\operation\OperationCategory;
use \core\models\operation\OperationCity;
use \core\models\operation\OperationAdvertContent;
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
     * @apiParam {String} city_id 城市ID
     * @apiParam {String} [access_token] 用户认证
     * @apiParam {String} [app_version] 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *     "code": 1,
     *     "msg": "操作成功",
     *     "alertMsg": "成功"
     *     "ret": {
     *         "city_list": [
     *             {
     *                 "city_id": "110100",
     *                 "city_name": "北京市"
     *             }
     *         ],
     *         "header_link": {
     *             "comment_link": {
     *                 "title": "意见反馈",
     *                 "url": "http://dev.m2.1jiajie.com/statics/images/MyView_FeedBack.png",
     *                 "img": "http://dev.m2.1jiajie.com/statics/images/MyView_FeedBack.png"
     *             },
     *             "phone_link": {
     *                 "title": "18210922324",
     *                 "url": "",
     *                 "img": "http://dev.m2.1jiajie.com/statics/images/MyView_Tel.png"
     *             }
     *         },
     *         "pic_list": [
     *             {
     *                 "img_path": "http://webapi2.1jiajie.com/app/images/ios_banner_1.png",
     *                 "link": "http://wap.1jiajie.com/trainAuntie1.html",
     *                 "url_title": "标准服务"
     *             }
     *         ],
     *         "home_order_server": [
     *             {
     *                 "title": "单次保洁",
     *                 "introduction": "新用户第1小时免费",
     *                 "icon": "http://dev.m2.1jiajie.com/statics/images/dancibaojie.png",
     *                 "url": "http://dev.m2.1jiajie.com/#/order/createOnceOrder/1",
     *                 "bg_colour": "ffb518",
     *                 "font_colour": "ffffff"
     *             },
     *             {
     *                 "title": "周期保洁",
     *                 "introduction": "一次下单 清洁无忧",
     *                 "icon": "http://dev.m2.1jiajie.com/statics/images/zhouqibaojie.png",
     *                 "url": "http://dev.m2.1jiajie.com/#/order/createOnceOrder/2",
     *                 "bg_colour": "ff8a44",
     *                 "font_colour": "ffffff"
     *             }
     *         ],
     *         "server_list": [
     *             {
     *                 "category_id": "2",
     *                 "category_name": "保洁任务",
     *                 "category_icon": "http://7b1f97.com1.z0.glb.clouddn.com/14468862302219563dbb56de6d3",
     *                 "category_introduction": "地板深度保护",
     *                 "category_url": "http://www.baidu.com",
     *                 "colour": "颜色",
     *                 "category_price_description": "价格描述"
     *             }
     *         ],
     *         "isBlock": "用户是否为黑名单【1表示黑名单，0表示正常】",
     *         "isEffect": "用户token是否有效【0表示正常，1表示失效】"
     *     }
     * }
 
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code":0,
     *       "alertMsg": "城市尚未开通",
     *       "msg": "城市尚未开通"
     *     }
     */
    public function actionUserInit()
    {
        $param = Yii::$app->request->get();
        if(!isset($param['city_id'])||!intval($param['city_id'])){
            $param['city_id'] = "110100";
        }
        //判断token是否有效
        $isEffect="0";
        if(isset($param['access_token'])&&!$param['access_token']&&!CustomerAccessToken::checkAccessToken($param['access_token'])){
            $isEffect="1";
        }
        //获取城市列表
        try{
            $onlineCitys = OperationCity::getOnlineCitys();
            $cityCategoryList = OperationShopDistrictGoods::getCityCategory($param['city_id']);
        } catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 200, null, alertMsgEnum::getWorkerInitFailed);
        }
        //整理开通的城市
        $onlineCityList = array();
        foreach($onlineCitys as $key=>$val){
            $onlineCityList[$key]['city_id'] = $val['city_id'];
            $onlineCityList[$key]['city_name'] = $val['city_name'];
        }
        //整理开通的服务类型
        $serviceCategoryList = array();
        foreach($cityCategoryList as $key=>$val){
            $serviceCategoryList[$key]['category_id'] = $val['id'];
            $serviceCategoryList[$key]['category_name'] = $val['operation_category_name'];
            $serviceCategoryList[$key]['category_icon'] = $val['operation_category_icon'];
            $serviceCategoryList[$key]['category_introduction'] = "暂无";
            $serviceCategoryList[$key]['category_url'] = 'http://www.baidu.com';
            $serviceCategoryList[$key]['colour'] = '颜色';
            $serviceCategoryList[$key]['category_price_description'] = '价格描述';
            
        }
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
        //服务分类
        $home_order_server = [
            [
                'title' => '单次保洁',
                'introduction' => '新用户第1小时免费',
                'icon' => 'http://dev.m2.1jiajie.com/statics/images/dancibaojie.png',
                'url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/1',
                'bg_colour' => 'ffb518',
                'font_colour' => 'ffffff',
            ],
            [
                'title' => '周期保洁',
                'introduction' => '一次下单 清洁无忧',
                'icon' => 'http://dev.m2.1jiajie.com/statics/images/zhouqibaojie.png',
                'url' => 'http://dev.m2.1jiajie.com/#/order/createOnceOrder/2',
                'bg_colour' => 'ff8a44',
                'font_colour' => 'ffffff',
            ]
        ];
        $isBlock="0";
        $ret = [
            'city_list' => $onlineCityList,
            'header_link' => $header_link,
            'pic_list' => $pic_list,
            'home_order_server' => $home_order_server,
            'server_list' => $serviceCategoryList,
            'isBlock' => $isBlock,
            'isEffect' => $isEffect,
        ];
        return $this->send($ret, '操作成功',1,200,null,alertMsgEnum::getUserInitSuccess);
    }

    /**
     * @api {GET} /configure/worker-check-update [GET] /configure/worker-check-update （0%）
     * @apiDescription 检查阿姨端版本更新 (赵顺利)
     * @apiName actionWorkerCheckUpdate
     * @apiGroup configure
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
            return $this->send(null, "用户无效,请先登录", 401,403,null,alertMsgEnum::getWorkerInitFailed);
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