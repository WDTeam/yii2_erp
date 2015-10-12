<?php

class ConfigureController
{
    /**
     *
     * @api {GET} /configure/services 城市服务初始化
     * @apiName GetServicesInfoByCity
     * @apiGroup configure
     * 
     * @apiParam {String} city 城市
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "",
     *       "ret":{
     *       "webViewUrl": "http://wap.1jiajie.com/serverinfo/ejiajie.html?cn=city_name&type=service_type",
     *       "city_price": [
     *          {
     *           "city_name": "北京",
     *           "price": {
     *               "1": 25
     *          },
     *          "top_text": {
     *              "baojie_money": "单次25元/小时（2小时起）",
     *              "xinju_money": "50平米以下,300元;50平米以上,6元/平米",
     *              "chufanggaowen_money": "450元/套",
     *              "weishengjian_money": "240元/间",
     *              "caboli_money": "12元/平米，10平米起",
     *              "dimianjinghua_money": "12元/平米",
     *              "360danchen_money": "5元/平米，300元起",
     *              "chuangsha_money": "平推40元-80元/扇,隐形150元-280元/套",
     *              "youyanji_money": "中式160元/台，欧式200元/台",
     *              "kongtiao_money": "壁挂式190元/台，柜机200元/台",
     *              "weibolu_money": "100元/台",
     *              "dianbingxiang_money": "120元/台起，最高200元/台",
     *              "yinshuiji_money": "100元/台",
     *              "kaoxiang_money": "100元/台",
     *              "xiyiji_money": "120元/台",
     *              "pizhishafa_money": "300元起，单体120元/座",
     *              "muzhijiaju_money": "200元/套，5套以上100元/套",
     *              "ditan_money": "20元/平米+300元出车费",
     *              "dibandala_money": "20元/平米，200元起",
     *              "chuangpinchuman_money": "成人180/床，婴儿150/床",
     *              "buyishafa_money": "1-1-3规格600元/套，单体120元/座",
     *              "chuanglian_money": "10元/平米起",
     *              "shicaijiejing_money": "50元/平米",
     *              "xiyi_money": "洗衣单件9元起，99元/袋",
     *              "pixiebaoyang_money": "低帮鞋15元，低腰鞋20元，高腰鞋25元",
     *              "aibaoqingxi_money": "普通包20元起，奢侈品包200元起",
     *              "xixie_money": "洗鞋15元起",
     *              "baomu_money": "13000元-5000元",
     *              "yuesao_money": "5000元-18000元",
     *              "yuersao_money": "3000元-8000元",
     *              "baoyue_money": "1000元-8000元",
     *              "shachong_money": "100元/50平米+300元出车费",
     *              "matong_money": "200元/眼",
     *              "guandao_money": "160元/眼"
     *          },
     *          "main_menu": [
     *              {
     *                  "专业保洁": [
     *                       "擦玻璃"
     *                   ]
     *               },
     *               {
     *                   "家电清洗": [
     *                       "空调清洗"
     *                   ]
     *               },
     *               {
     *                   "家电清洗": [
     *                       "油烟机清洗"
     *                   ]
     *               }
     *           ],
     *           "second_menu": [
     *               {
     *                   "专业保洁": [
     *                       "家庭保洁",
     *                       "新居开荒",
     *                       "厨房高温保洁",
     *                       "卫生间保洁",
     *                       "擦玻璃",
     *                       "地面净化除菌",
     *                       "360°掸尘",
     *                       "窗纱更换"
     *                   ]
     *               },
     *               {
     *                   "家电清洗": [
     *                       "油烟机清洗",
     *                       "空调清洗",
     *                       "微波炉清洗",
     *                       "电冰箱清洗",
     *                       "饮水机清洗",
     *                       "烤箱清洗",
     *                       "洗衣机清洗"
     *                   ]
     *               },
     *               {
     *                   "家居养护": [
     *                       "皮质沙发保养",
     *                       "木质家居保养",
     *                       "地毯保养",
     *                       "地板抛光打蜡",
     *                       "床品除螨",
     *                       "布艺沙发除螨",
     *                       "窗帘清洗",
     *                       "石材结晶保养"
     *                   ]
     *               },
     *               {
     *                   "洗护服务": [
     *                       "洗衣",
     *                       "洗鞋",
     *                       "皮鞋保养",
     *                       "爱包清洗"
     *                   ]
     *               },
     *               {
     *                   "生活急救箱": [
     *                      "管道疏通",
     *                       "马桶疏通",
     *                       "杀虫"
     *                   ]
     *               }
     *           ]
     *       },
     *       
     *   ],
     *   "items": {
     *       "专业保洁": {
     *           "家庭保洁": {
     *               "name": "家庭保洁",
     *               "top": "baojie_money",
     *               "thum": "http://webapi2.1jiajie.com/app/images/jiatingbaojie_them.png",
     *               "pic": "http://webapi2.1jiajie.com/app/images/jiatingbaojie_pic.png",
     *               "pic_v4": "http://webapi2.1jiajie.com/app/images/jiatingbaojie_pic_v4.png",
     *               "type_id": 1
     *           },
     *           "clean": {
     *               "name": "clean",
     *               "top": "baojie_money",
     *               "thum": "http://webapi2.1jiajie.com/app/images/jiatingbaojie_them.png",
     *               "pic": "http://webapi2.1jiajie.com/app/images/jiatingbaojie_pic.png",
     *               "pic_v4": "http://webapi2.1jiajie.com/app/images/jiatingbaojie_pic_v4.png",
     *               "type_id": 1
     *           },
     *       "暂无服务": {
     *           "暂无服务": {
     *              "top": "zanwufuwu_money",
     *               "name": "暂无服务",
     *               "thum": "http://webapi2.1jiajie.com/app/images/xiyi_them.png",
     *               "pic": "http://webapi2.1jiajie.com/app/images/xiyi_pic.png",
     *               "pic_v4": "http://webapi2.1jiajie.com/app/images/xiyi_pic_v4.png",
     *               "type_id": 99
     *           }
     *       }
     *   }
     *  
     *
     *
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
    public function actionGetServicesInfoByCity()
    {
    
    }
    
    /**
     *
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
    public function actionInitConfigureByCity(){}
    
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