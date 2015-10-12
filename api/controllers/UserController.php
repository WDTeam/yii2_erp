<?php

class UserController
{
    /**
     *
     * @api {GET} /user/addaddress 添加常用地址
     *
     * @apiName AddAddress
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} lng 经度
     * @apiParam {String} lat 纬度
     * @apiParam {String} city_name 城市名
     * @apiParam {String} street 城街道信息小区信息
     * @apiParam {String} place_detail 详细门牌信息
     * 
     * @apiSuccess {Object[]} addresses 用户常用地址数组.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "地址添加成功"
     *       "ret":{
     *       "address": [
     *          {
     *           "city": "北京",
     *           "latitude": "39.770908",
     *           "longitude": "116.223751",
     *           "street": "上海路",
     *           "place_detail": "某某小区8栋3单元502",
     *           "address": "长阳镇",
     *           "address_id": "1612679"
     *          }
     *         ]
     *        }
     *
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     * @apiError AddressNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 200 address Not Found
     *     {
     *       "code": "error",
     *       "msg": "地址不存在"
     *
     *     }
     */
    public function actionAddAddress()
    {
    
    }
    
    
    /**
     *
     * @api {GET} /user/addresses 常用地址列表
     * 
     * @apiName Addresses
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccess {Object[]} addresses 用户常用地址数组.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "查询成功"
     *       "ret":{
     *       "addresses": [
     *          {
     *           "city": "北京",
     *           "latitude": "39.770908",
     *           "longitude": "116.223751",
     *           "street": "上海路",
     *           "place_detail": "某某小区8栋3单元502",
     *           "address": "长阳镇",
     *           "address_id": "1612679"
     *          }
     *         ]
     *        }
     *
     *     }
     *
     * @apiError UserNotFound 用户认证失败.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *       
     *     }
     */
    public function actionAddresses()
    {
    
    }
    
    /**
     *
     * @api {DELETE} /user/addresses 删除用户常用地址
     * 
     *
     * @apiName DeleteAddresses
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} address_id 常用地址id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "删除成功"
     *
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     */
    public function actionDeleteAddress()
    {
    
    }
    
    /**
     *
     * @api {GET} /user/setdefaultcity 设置默认地址
     *
     * @apiName SetDefaultCity
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city 城市
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccess {Object[]} services 该城市提供的服务.
     * @apiSuccess {Object[]} appInfoWithCity 该城市相关初始化配置.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "设置成功"
     *       "ret":{
     *          "services":{}
     *          "appInfoWithCity":{}
     *        }
     *
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     */
    public function actionSetDefaultCity(){
        
    }
    
    /**
     *
     * @api {GET} /user/setdefaultcity 获取用户个性化配置和优惠劵
     *
     * @apiName GetCouponsAndPersonality
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city 城市
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccess {Object[]} myCoupons 用户拥有优惠劵.
     * @apiSuccess {Object[]} personality 用户可以选择的个性化需求.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "设置成功"
     *       "ret":{
     *          "myCoupons": [
     *           {
     *               "is_expires": "0",
     *               "expires_time": "2015.04.21-2016.04.20",
     *               "freeStr": "30元擦玻璃充值券",
     *               "service_second": "擦玻璃",
     *               "service_main": "专业保洁",
     *               "is_selected": "0",
     *               "free_money": "30",
     *               "free_code": "rr_27794"
     *           }],
     *          "personality": [
     *              "重点打扫厨房",
     *              "重点打扫卫生间",
     *              "家有爱宠",
     *              "上门前打个电话",
     *              "很久没打扫了",
     *              "阿姨不要很多话"
     *           ]
     *
     *        }
     *
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     */
    public function actionGetCouponsAndPersonality(){
    
    }
    
    /**
     *
     * @api {GET} /user/exchangecoupon 兑换优惠劵
     *
     * @apiName ExchangeCoupon
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} city 城市
     * @apiParam {String} coupon_code 优惠码
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "兑换成功"
     *       
     *
     *     }
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
     * @apiError CouponNotFound 优惠码不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "优惠码不存在，"
     *
     *     }
     */
    public function actionExchangeCoupon(){
    
    }
    
    /**
     *
     * @api {GET} /user/getsharetext 获取分享优惠文本
     *
     * @apiName GetShareText
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": {
     *               "wxCnt": "送你e家洁的10元免费体验邀请码：1011685，关注下e家洁的微信账号： ejiajie，十几分钟保洁阿姨就到了，关键是还便宜！只需50元就可以将家里彻底打扫一遍，快告诉你好友吧！",
     *               "wbCnt": "最近用了【e家洁】App找保洁小时工，阿姨准时登门，干活麻利，门后墙角都干干净净的，2小时才50元，必须推荐给你们！http://t.cn/8siFiZZ 下载后输入体验邀请码：1011685，你们还可以获得10元优惠券哦！",
     *                "wxGroupCnt": "最近用了【e家洁】App找保洁小时工，阿姨准时登门，干活麻利，门后墙角都干干净净的，2小时才50元，必须推荐给你们！http://t.cn/8siFiZZ 下载后输入体验邀请码：1011685，你们还可以获得10元优惠券哦！",
     *                "wxFriendGroupShare": "品质生活  从e家洁开始",
     *                "wbShare": "最近使用的保洁打扫利器，新居开荒家电清洗洗衣洗护样样齐全，优质服务省事贴心！快来体验更多~ http://t.cn/8siFiZZ",
     *                "sms": "最近用了【e家洁】App找保洁小时工，阿姨准时登门，干活麻利，门后墙角都干干净净的，2小时才50元，必须推荐给你们！http://t.cn/8siFiZZ 下载后输入体验邀请码：1011685，你们还可以获得10元优惠券哦！"
     *               }
     *
     *
     *     }
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
    public function actionGetShareText(){
    
    }
    
    /**
     *
     * @api {GET} /user/deleteUsedWorker 删除常用阿姨
     *
     *
     * @apiName deleteUsedWorker
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} worker_id  阿姨id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "删除成功"
     *
     *     }
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
     * @apiError WorkerNotFound 该阿姨不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "不存在要删除的阿姨"
     *
     *     }
     *
     */
    public function deleteUsedWorker(){
    
    }
    
    
    /**
     *
     * @api {GET} /user/blacklistworkers 黑名单阿姨列表
     * @apiDescription 获得该用户添加进黑名单的阿姨
     *
     * @apiName blacklistworkers
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "删除成功"
     *       "ret":{
     *         "blockWorkers": [
     *         {
     *           "worker_id": "12409",
     *           "face": "http://static.1jiajie.com/worker/face/12409.jpg",
     *           "name": "夏测试",
     *           "order_num": "服务:168次",
     *           "kilometer": "",
     *           "star_rate": "0",
     *           "last_serve_time": "最后服务时间:2015-04-22 16:00:34",
     *           "shop_id": "68",
     *           "is_fulltime": "全职全日",
     *           "telephone": "18610932174"
     *         }
     *        ]
     *       }
     *     }
     *
     *
     *     
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
     *
     */
    public function blackListWorkers(){
    
    }
    
    /**
     *
     * @api {GET} /user/removeblacklistworker 移除黑名单中的阿姨
     *
     *
     * @apiName RemoveBlackListWorker
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} worker_id 阿姨id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "移除成功"
     *       
     *     }
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
     *
     */
    public function removeBlackListWorker(){
    
    }
    
    /**
     *
     * @api {GET} /user/chooseusedworker 选择常用阿姨
     * @apiDescription 获得曾经使用过的所有阿姨和用户打扫时间空闲的阿姨
     *
     *
     * @apiName ChooseUsedWorker
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} lng 经度
     * @apiParam {String} lat 纬度
     * @apiParam {String} date_format 预约日期
     * @apiParam {String} time 预约时间段
     * @apiParam {String} city 城市
     *
     * @apiSuccess {Object[]} usedWork 曾经服务过的阿姨.
     * @apiSuccess {Object[]} now_free 在该时间段空闲阿姨.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": ""
     *       "ret":{
     *          "usedWorker": [
     *           {
     *              "worker_id": "12507",
     *              "face": "http://static.1jiajie.com/worker/face/12507.jpg",
     *              "name": "陈琴昭测试",
     *              "order_num": "服务:129次",
     *              "kilometer": ">15km",
     *              "star_rate": "0",
     *              "last_serve_time": "最后服务时间:2015-08-24 18:06:30",
     *              "shop_id": "1",
     *              "is_fulltime": "兼职",
     *              "telephone": "13401096964",
     *              "isFull": "0"
     *           }
     *          ],
     *          "now_free": []
     *
     *          }
     *      }
     *
     *
     *     }
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
    public function actionChooseUsedWorker(){
    
    }
    
    /**
     *
     * @api {GET} /user/usermoney 用户余额和消费记录
     *
     *
     * @apiName UserMoney
     * 
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccess {Object} UserMoney 用户当前余额和消费记录对象
     *      
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "查询成功"
     *       "ret": {
     *          "msgStyle": "toast",
     *          "alertMsg": "",
     *          "totalMoney": "9863.00元",
     *          "cardName": "您好！铂金卡会员",
     *          "isMember": "1",
     *          "payRecord": [
     *              {
     *                  "desc": "家庭保洁",
     *                  "balanceMoney": "余额支付:¥0",
     *                  "time": "2015-09-14",
     *                  "payDetails": "总额:¥25"
     *              }
     *          ]
     *        }
     *     }
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
    public function actionUserMoney(){
    
    }
    
    /**
     *
     * @api {GET} /user/userscore 用户积分明细
     * 
     * @apiDescription 获取用户当前积分，积分兑换奖品信息，怎样获取积分信息
     * @apiName Userscore
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "提交成功"
     *       "ret":{
     *          "scoreCategory": [
     *              {
     *                  "desc": "在线支付后评价订单",
     *                  "score": "¥*5"
     *              },
     *              {
     *                  "desc": "在线支付",
     *                  "score": "¥*5"
     *              },
     *              {
     *                  "desc": "分享给朋友",
     *                  "score": "10"
     *              }
     *          ],
     *          "scoreDetail": [],
     *          "score": "0分",
     *          "userPrize": [
     *              {
     *                  "prizeId": "3",
     *                  "prizeName": "e家洁厨房高温保养",
     *                  "prizeCost": "1500",
     *                  "prizeRule": [
     *                      "如需咨询请拨打客服电话：400-6767-636"
     *                  ],
     *                  "prizeThumb": "http://webapi2.1jiajie.com/app/images/gaowenbaojie_small3.png",
     *                  "prizePic": "http://static.1jiajie.com/prizePhoto/gaowenbaojie_big.png"
     *              }
     *          ],
     *        }
     *
     *     }
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
    public function actionUserScore(){
    
    }
    
    /**
     *
     * @api {POST} /user/usersuggest 用户提交意见反馈
     *
     * @apiName UserSuggest
     * @apiGroup User
     *
     * @apiParam {String} access_token 用户认证
     * @apiParam {String} app_version 访问源(android_4.2.2)
     * @apiParam {String} suggest 用户意见
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "提交成功"
     *       
     *     }
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
    public function actionUserSuggest(){
    
    }
    
    
    
}

?>