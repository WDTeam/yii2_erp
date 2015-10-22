<?php
namespace api\controllers;

use Yii;
use \core\models\worker\Worker;
use \core\models\worker\WorkerAccessToken;
class WorkerController extends \api\components\Controller
{

    /**
     *
     * @api {GET} /worker/work-info 查看阿姨信息 (田玉星 95%)
     *
     *
     * @apiName WorkerInfo
     * 
     * @apiGroup Worker
     *
     * @apiParam {String} access_token 阿姨登录token
     *
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *       "msg": "阿姨信息查询成功",
     *       ret:{
     *           "worker_name": "刘红霞",
     *            "user_phone": "15010208249",
     *            "head_url": "",
     *            "worker_rule": "兼职",
     *            "livePlace": "北京市通州区土桥",
     *            "personal_skill": [
     *                  []
     *              ]
     *           }
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
     * @apiError WorkerNotFound 阿姨不存在.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "阿姨不存在，"
     *
     *     }
     *
     *
     */
    public function actionWorkerInfo(){
          $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
          if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
          }
          $worker = WorkerAccessToken::getWorker($param['access_token']);
          if (!empty($worker) && !empty($worker->id)) {
               $workerInfo = Worker::getWorkerDetailInfo($worker->id);
               //数据整理
               $ret = array(
                    "worker_name" => $workerInfo['worker_name'],
                    "user_phone" => $workerInfo['worker_phone'],
                    "head_url" => $workerInfo['worker_photo'],
                    "worker_rule" => $workerInfo['worker_rule_description'],
                    "livePlace" => $workerInfo['worker_live_place'],
                    "personal_skill" =>array(array())
               );
  
               return $this->send($ret, "阿姨信息查询成功", "ok");
         } else {
             return $this->send(null, "阿姨不存在.", "error", 403);
         }
    }
    
    /**
     * @api {get} /mobileapidriver2/driver_get_now_order_list_hide 阿姨去不了
     * @apiName actionDriverGetNowOrderListHide
     * @apiGroup Worker
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id  订单id.
     * @apiParam {String} list_type  订单类型.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
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
     */
    public function actionWorkerCancelService(){

    }
        
    /**
     * @api {get} /mobileapidriver2/get_driver_info 个人中心首页
     * @apiName actionGetDriverInfo
     * @apiGroup Worker
     *
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号
     * @apiParam {String} Sign  传了123.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"",
     *      "ret":
     *      {
     *          "orderCount": 0,
     *          "cancelCount": "",
     *          "driverTotalScore": "",
     *          "myRewards": [],
     *          "userPhone": "13401096964",
     *          "driverName": "陈琴昭测试",
     *          "driverAge": "32",
     *          "qualityScoreClauseUrl": "http://wap.1jiajie.com/serverinfo/complainManage.html",
     *          "livePlace": "北京市房山区长阳",
     *          "homeTown": "河北省",
     *          "identityCard": "********",
     *          "goodRate": [],
     *          "badRate": [],
     *          "totalRate": [],
     *          "myMoney": "0.0元",
     *          "rankList": [],
     *          "myRank":
     *          [
     *          {
     *              "driver_name": "",
     *              "rank": "100+",
     *              "money": "0"
     *          }
     *          ],
     *          "starCountList": [],
     *          "meStarList":
     *          [
     *          {
     *              "driver_name": "冷桂艳",
     *              "rank": "100+",
     *              "money": "0"
     *          }
     *          ],
     *          "personal_skill":
     *          [
     *          {
     *              "title": "洗衣",
     *              "type": 1,
     *              "value": null
     *          }
     *          ],
     *          "druingTime": "2015.9.1  一  2015.9.13",
     *          "restScore": "分",
     *          "complainNum": "1个",
     *          "unPayMoney": "0.0元",
     *          "isPayMoney": "0.0元",
     *          "unPayList": [],
     *          "isPayList": [],
     *          "myMoneyList": [],
     *          "restDay": "剩余周期135天",
     *          "scoreList": [],
     *          "fineMoney": "0.00",
     *          "unComplainList": [],
     *          "restDayStr": "您本次的积分周期自2014-01-02至2014-05-06止",
     *          "complainStr": "",
     *          "complainClauseUrl": "http://wap.1jiajie.com/serverinfo/punishManage.html",
     *          "todayFinishOrder": 0,
     *          "todayFinishMoney": 0,
     *          "monthFinishOrder": 0,
     *          "monthFinishMoney": 0,
     *          "succRate": 100,
     *          "driverLevel": "",
     *          "alertType": 0,
     *          "accountRestMoney": 0,
     *          "payMoney": 0,
     *          "chargeMoney": 0,
     *          "driverCompany": "家政公司",
     *          "curCarId": null,
     *          "curCarBrand": null,
     *          "curCarNumber": null,
     *          "curCarColor": null,
     *          "curColor": null,
     *          "curCarType": 1,
     *          "isOpenStart": true,
     *          "result": "1",
     *          "headUrl": "http://static.1jiajie.com/picture_default.jpg",
     *          "driverDegree": "高中",
     *          "driverWorkAge": "2年",
     *          "driverLanguage": "普通话",
     *          "healthCard": "无",
     *          "department": "北京大悦城店",
     *          "serverRange": "5公里",
     *          "tranSportation": "无",
     *          "activity_url": "http://wap.1jiajie.com/wap_theme_activity/ayiduan/index.html"
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
     * @api {get} /v2/worker_busy_list.php  工作时间
     * @apiName actionWorkerBusyList
     * @apiGroup Worker
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} worker_id      阿姨id.
     * @apiParam {String} is_assign_info  1.
     * @apiParam {String} start_time  查询那一天的.
     * @apiParam {String} select_day  查询几天 1.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "orderCount": 0,
     *          "cancelCount": null,
     *          "driverTotalScore": null,
     *          "myRewards": [],
     *          "userPhone": "13401096964",
     *          "driverName": "陈测试1",
     *          "driverAge": "32",
     *          "qualityScoreClauseUrl": "http://wap.1jiajie.com/serverinfo/complainManage.html",
     *          "livePlace": "北京市密云县密云",
     *          "homeTown": "河北省",
     *          "identityCard": "********",
     *          "goodRate": [],
     *          "badRate": [],
     *          "totalRate": [],
     *          "myMoney": "0.0元",
     *          "rankList": [],
     *          "myRank":
     *          [
     *          {
     *              "driver_name": "",
     *              "rank": "100+",
     *              "money": "0"
     *          }
     *          ],
     *          "starCountList": [],
     *          "meStarList":
     *          [
     *          {
     *              "driver_name": "",
     *              "rank": "100+",
     *              "money": "0"
     *          }
     *          ],
     *          "personal_skill":
     *          [
     *          {
     *              "title": "洗衣",
     *              "type": 1,
     *              "value": null
     *          }
     *          ],
     *          "druingTime": "2015.9.1  一  2015.9.13",
     *          "restScore": "分",
     *          "complainNum": "0个",
     *          "unPayMoney": "0.0元",
     *          "isPayMoney": "0.0元",
     *          "unPayList": [],
     *          "isPayList": [],
     *          "myMoneyList": [],
     *          "restDay": "剩余周期135天",
     *          "scoreList": [],
     *          "fineMoney": "0.00",
     *          "unComplainList": [],
     *          "restDayStr": "您本次的积分周期自2014-01-02至2014-05-06止",
     *          "complainStr": "",
     *          "complainClauseUrl": "http://wap.1jiajie.com/serverinfo/punishManage.html",
     *          "todayFinishOrder": 0,
     *          "todayFinishMoney": 0,
     *          "monthFinishOrder": 0,
     *          "monthFinishMoney": 0,
     *          "succRate": 100,
     *          "driverLevel": "",
     *          "alertType": 0,
     *          "accountRestMoney": 0,
     *          "payMoney": 0,
     *          "chargeMoney": 0,
     *          "driverCompany": "家政公司",
     *          "curCarId": null,
     *          "curCarBrand": null,
     *          "curCarNumber": null,
     *          "curCarColor": null,
     *          "curColor": null,
     *          "curCarType": 1,
     *          "isOpenStart": true,
     *          "result": "1",
     *          "headUrl": "http://static.1jiajie.com/picture_default.jpg",
     *          "driverDegree": "大学",
     *          "driverWorkAge": "1年",
     *          "driverLanguage": "普通话",
     *          "healthCard": "无",
     *          "department": "测试用门店",
     *          "serverRange": "5公里",
     *          "tranSportation": "无",
     *          "activity_url": "http://wap.1jiajie.com/wap_theme_activity/ayiduan/index.html"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError WorkerIdNotFound 未找到阿姨ID.
     * @apiError StartTimeNotFound 未找到开始时间.
     * @apiError TimesNotFound 未找到时间段信息.
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
     * @api {get} /v2/worker_busy.php  提交工作时间
     * @apiName actionWorkerBusy
     * @apiGroup Worker
     * 
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} busy_time  忙碌的时间段.
     * @apiParam {String} report_type   1.
     * @apiParam {String} busy_type    1，忙碌类型.
     * @apiParam {String} admin_id    702.
     * @apiParam {String} city_code    城市code.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"",
     *      "ret":
     *      {
     *          "workerId": 12507,
     *          "alertMsg": "更新成功"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError CityCodeNotFound 未找到城市编码.
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
     * @api {get} /mobileapidriver2/helpUserCreateOrder  查询帮用户下的订单
     * @apiName actionHelpUserCreateOrder
     * @apiGroup Worker
     * @apiDescription 查询帮助用户创建的订单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} tel  查询的电话号码.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "result": 1,
     *          "msg": "",
     *          "orderList":
     *          [
     *          {
     *              "orderId": "6924842",
     *              "orderType": "家庭保洁",
     *              "orderPlace": "北京 定慧福里(北门) 25号楼",
     *              "longitude": "0",
     *              "latitude": "0",
     *              "orderDate": "2015-08-31",
     *              "orderStartTime": "08:00",
     *              "orderEndTime": "10:00",
     *              "orderAllTime": "2.0",
     *              "userPhone": "13521516291",
     *              "userName": "",
     *              "cityName": "北京",
     *              "extendInfo": "重点打扫厨房,重点打扫卫生间##重点打扫厨房,重点打扫卫生间,",
     *              "timestamp": 1440979200,
     *              "userType": "1"
     *          }
     *          ]
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError PhoneNotFound 未找到电话信息.
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
     * @api {get} /v2/worker_time_table.php  单次服务排班表
     * @apiName actionWorkerTimeTable
     * @apiGroup Worker
     * @apiDescription 帮客户下单，单次服务获取服务时间
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} service_name  服务名称，家庭保洁不用传.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "msgStyle": "toast",
     *          "alertMsg": "",
     *          "workerTimeTable":
     *          [
     *          {
     *              "date_name": "09月13日",
     *              "date_week": "今天",
     *              "date_time": ["13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00"]
     *          }
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
    
    
    /**
     * @api {get} /worker/help_user_create_one_order.php  
     * @apiName actionHelpUserCreateOneOrder
     * @apiGroup Worker
     * 
     * @apiDescription 帮客户下单，单次服务确认下单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} order_id   订单id.
     * @apiParam {String} order_type 订单类型，非家庭保洁传空.
     * @apiParam {String} work_time  服务时长.
     * @apiParam {String} reserve_time  服务时间.
     * @apiParam {String} no_auto_assign  1.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "code": "-1",
     *          "msg":
     *          {
     *              "msgStyle": "toast",
     *              "alertMsg": "下单失败，下单时段您有其他服务。"
     *          }
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
     * @api {get} /worker/worker_time_table.php  周期服务时间表(李勇80%缺少model)
     * @apiName actionWorkerTimeTable
     * @apiGroup Worker
     * @apiDescription 帮客户下单，周期服务
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
     *          "selectTimeArea": "6",
     *          "maxPlanTime": "6",
     *          "minPlanTime": "2",
     *          "msgStyle": "",
     *          "alertMsg": "",
     *          "workerTime":
     *          [
     *          {
     *              "date_name": "09月13日",
     *              "date_week": "周日",
     *              "date_week_every": "每周日",
     *              "date_time":
     *     ["14:00-16:00","14:30-16:30","15:00-17:00","15:30-17:30","16:00-18:00","16:30-18:30","17:00-19:00","17:30-19:30","18:00-20:00"],
     *              "date_name_tag": "09月13日(今天)"
     *          }
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
    public function actionWorkerTimeTable(){
          $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
          $worker = WorkerAccessToken::getWorker($param['access_token']);
          print_r($worker);die;
          if (!empty($worker) && !empty($worker->id)) {
               //$filed = array('worker_live_province','worker_live_city','worker_live_area','worker_live_street');
               //$workerInfo = Worker::getWorkerListByIds($worker->id,implode(',',$filed));
               $ret = array(
                        "select_time_area"=> "6",
                        "max_plan_time"=> "6",
                        "min_plan_time"=> "2",
                        "msg_style"=> "",
                        "alert_msg"=> "",
                        "worker_time"=>""
               );
                 return $this->send($ret, "操作成功.", "ok");
          }else{
               return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
          }
    }
    
    /**
     * @api {get} /v2/worker/help_user_create_period_order.php  提交周期订单
     * @apiName actionHelpUserCreatePeriodOrder
     * @apiGroup Worker 
     * @apiDescription 帮客户下单，周期服务提交订单
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} begin_time   开始时间.
     * @apiParam {String} place_detail  详细地址.
     * @apiParam {String} reserve_time  周期服务时间.
     * @apiParam {String} order_id   订单id.
     * @apiParam {String} no_auto_assign 1.
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "code": "-1",
     *          "msg":
     *          {
     *              "orderId": 0,
     *              "popMsg": "",
     *              "pageMsg": "",
     *              "dupOrderInfo": [],
     *              "total_score": 0,
     *              "PeriodOrderId": 0,
     *              "alertMsg": "已选择过此阿姨进行周期服务，不可重复提交",
     *              "msgStyle": ""
     *          }
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError StartTimeNotFound 未找到开始时间.
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
     * @api {get} /mobileapidriver2/system_news  通知中心
     * @apiName actionSystemNews
     * @apiGroup Worker
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
     *          "result": "1",
     *          "initInfo": []
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
     * @api {get} /mobileapidriver2/workerLeave  查看请假情况
     * @apiName actionWorkerLeave
     * @apiGroup Worker
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
     *          "result": "1",
     *          "msg": "ok",
     *          "titleMsg": "您本月已请假0天，本月剩余请假2天",
     *          "orderTimeList": ["2015-09-14","2015-09-15",],
     *          "workerLeaveList": []
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
     * @api {get} /worker/handle-worker-leave  阿姨请假（田玉星 98%）
     * @apiName actionHandleWorkerLeave
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} date 请假时间.
     * @apiParam {String} type 请假类型.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "result": "1",
     *          "msg": "您的请假已提交，请耐心等待审批。"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError TimesNotFound 未找到时间段信息.
     * @apiError TypeNotFound 未找到类型信息.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"Failed",
     *      "msg": "SessionIdNotFound"
     *  }
     *
     */
     public function actionHandleWorkerLeave(){
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
          $worker = WorkerAccessToken::getWorker($param['access_token']);
          if (!empty($worker) && !empty($worker->id)) {
               $attributes = [];
               $attributes['worker_id'] = $worker->id;
               if(!isset($param['type'])||!$param['type']||!in_array($param['type'], array(1,2))){
                    return $this->send(null, "数据不完整,请选择请假类型", "error", 403);
               }
               $attributes['worker_vacation_type'] = $param['type'];
               
               //请假时间范围判断
               if(!isset($param['date'])||!$param['date']){
                    return $this->send(null, "数据不完整,请选择请假时间", "error", 403);
               }
               $vacation_start_time = time();
               $vacation_end_time = strtotime(date('Y-m-d',strtotime("+14 days")));
               $current_vacation_time = strtotime($param['date']);
               if($current_vacation_time<=$vacation_start_time||$current_vacation_time>$vacation_end_time){
                    return $this->send(null, "请假时间不在请假时间范围内,请选择未来14天的日期", "error", 403);
               }
               $attributes['worker_vacation_start_time'] = $attributes['worker_vacation_finish_time'] = $current_vacation_time;
               
               //请假入库
               $workerVacation = new \core\models\order\WorkerVacation();
               $is_success = $workerVacation -> createNew($attributes);    
               if ($is_success) {
                    $result = array(
                         'result' => 1,
                         "msg"    => "您的请假已提交，请耐心等待审批。"
                    );
                 return $this->send($result,"操作成功","ok");
               } else {

                 return $this->send(null,$workerVacation->errors,  "error",403);
               }
          }else{
               return $this->send(null, "阿姨不存在.", "error", 403);
          }
          
     }
    
    /**
     * @api {get} /worker/handle-worker-leave-history  阿姨请假历史（田玉星 70%）
     * @apiName actionHandleWorkerLeaveHistory
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} platform_version 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *         
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     * @apiError TimesNotFound 未找到时间段信息.
     * @apiError TypeNotFound 未找到类型信息.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "阿姨不存在"
     *  }
     *
     */
     public function actionHandleWorkerLeaveHistory(){
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {

             //调取阿姨请假历史情况
             return $this->send(null,"操作成功","ok");
        }else{

             return $this->send(null, "阿姨不存在.", "error", 403);
        }
          
     }

    
    /**
     * @api {get} /worker/get-worker-place-by-id  获取阿姨住址(田玉星 100% )
     * @apiName actionGetWorkerPlaceById
     * @apiGroup Worker
     * @apiDescription 获取阿姨住址 用来查看路线
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"查询地址成功",
     *      "ret":
     *      {
     *          "result": 1,
     *          "live_place": "北京市密云县密云"
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "阿姨不存在"
     *  }
     *
     */
    public function actionGetWorkerPlaceById(){
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
          return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
             $workerInfo = Worker::getWorkerDetailInfo($worker->id);
             $ret = array(
                  "result"=>'1',
                  "live_place"=>$workerInfo['worker_live_place']
             );
             return $this->send($ret, "操作成功.", "ok");
        }else{
             return $this->send(null, "阿姨不存在.", "error", 404);
        }
    }
    /**
     * @api {get} /v2/FixedUserPeriod.php  固定客户列表
     * @apiName actionFixedUserPeriod
     * @apiGroup Worker
     * @apiDescription 对账固定客户（点击查看全部）
     * @apiParam {String} session_id    会话id.
     * @apiParam {String} platform_version 平台版本号.
     * @apiParam {String} per_page  每页显示多少条.
     * @apiParam {String} page  第几页.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "alertMsg": "",
     *          "fixedUserPeriod":
     *          [
     *          {
     *              "period_id": "15",
     *              "place_detail": "6换10",
     *              "street": "海淀区定慧北里",
     *              "telephone": "136****3636",
     *              "order_num": 2,
     *              "order_time": "5.5小时"
     *          }
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
    
    /**
     *
     * @api {GET} /worker/nearby 获取附近服务人员列表
     * @apiName WorkerNearby
     * @apiGroup Worker
     *
     * @apiParam {String} platform_version 平台版本
     * @apiParam {float} lat 纬度
     * @apiParam {float} lng 经度
     *
     * @apiSuccess {String} code 返回码 ok.
     * @apiSuccess {array} msg 返回附近阿姨信息
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *			code: "ok",
     *			msg: {
     *				alertMsg: "",
     *				workers_list: [
     *					{
     *						card_ability: "0",
     *						shop_id: "107",
     *						driver_phone: "18518676006",
     *						is_fulltime: "中介全职",
     *						order_count: "服务:85次",
     *						id: "1111118",
     *						name: "中介测试",
     *						age: "40岁",
     *						star_rate: "0",
     *						home_town: "河南",
     *						is_face: "1",
     *						head_url: "http://static.1jiajie.com/worker/face/1111118.jpg",
     *						kilometer: "1.8km"
     *					}
     *				]
     *			}
     *		}
     *
     *
     * @apiSampleRequest http://test.web.1jiajie.com/v2/near_by_worker.php?lng=116.459003&lat=39.918737&platform_version=test
     */
    public function actionNearby()
    {
        echo 'v2/near_by_worker.php';
    }
    
   
    }
    
    

?>
