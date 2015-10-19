<?php

class ConfigureController
{
    /**
     * @api {GET} /configure/all-services 城市服务初始化 （赵顺利）
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
     *          "type_id":"",
     *          "name":"专业保洁",
     *          "icon":"",
     *          "pic":"",
     *          "goods":
     *          [
     *          {
     *              "server_id":"",
     *              "name":"家庭保洁",
     *              "icon":"",
     *              "pic":"",
     *              "url":"",
     *          },
     *          {
     *              "type_id":"",
     *              "name":"家庭保洁",
     *              "icon":"",
     *              "pic":"",
     *              "url":"",
     *          },
     *          ]
     *       }
     *       ],
     *  }
     *  
     * @apiError CityNotSupportFound 该城市未开通.
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
    }

    /**
     * @api {GET} /configure/ 轮播图等初始化
     * @apiName InitConfigure
     * @apiGroup configure
     * @apiDescription 获得开通城市列表，广告轮播图 等初始化数据
     * @apiParam {String} city 城市
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg":"获取成功"
     *       "ret":{
     *         "default_city": "北京",
     *         "is_black_user": "0",
     *         "city_list": [
     *          "北京",
     *          "上海",
     *          "广州",
     *          "深圳",
     *          "成都",
     *          "南京"
     *         ],
     *         "callcenter": "4006767636",
     *         "ad_top_v4_city": {
     *           "北京": [
     *            {
     *              "img_path": "http://webapi2.1jiajie.com/app/images/ios_banner_1.png",
     *              "link": "http://wap.1jiajie.com/trainAuntie1.html",
     *              "url_title": "标准服务"
     *            },
     *            {
     *              "img_path": "http://webapi2.1jiajie.com/app/images/20150603ad_top_v4_1.png",
     *              "link": "http://wap.1jiajie.com/pledge.html",
     *              "url_title": "服务承诺"
     *            },
     *            {
     *              "img_path": "http://webapi2.1jiajie.com/app/images/20150311ad_top_v4_3.png",
     *              "link": "",
     *              "url_title": ""
     *            }
     *          ]
     *       
     *        },
     *        "isUpdate": 0,
     *        "updateContent": "",
     *        "updateUrl": "https://itunes.apple.com/cn/app/e-jia-jie/id718617336?ls=1&mt=8",
     *        "servicePromiseUrl": "http://wap.1jiajie.com/pledge.html",
     *        "alertMsg": "",
     *        "maxReserveDay": "7",
     *        "ShowWorkerBusyTime": "0",
     *        "isShareExp": "0",
     *        "isShowGiveStar": "1",
     *        "is_show_pay_way": "0"
     *}
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

    /**
     * @api {GET} /v2/worker/check_update.php 检查阿姨端版本更新
     * @apiName actionCheckUpdate
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
     * @api {get} /v2/worker/home_page.php 阿姨app初始化
     * @apiName actionIndex
     * @apiGroup configure
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
}

?>