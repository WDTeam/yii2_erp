<?php
namespace api\controllers;

use Yii;
use \core\models\worker\Worker;
use \core\models\worker\WorkerAccessToken;
class WorkerController extends \api\components\Controller
{

    /**
     *
     * @api {GET} /worker/worker-info 查看阿姨信息 (田玉星 80%)
     * 
     * @apiDescription 【备注：阿姨身份、星级、个人技能等待model底层】
     * 
     * @apiName WorkerInfo
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token 阿姨登录token
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/worker-info
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *      "msg": "阿姨信息查询成功",
     *      "ret": {
     *          "worker_name": "李刘珍",
     *          "worker_phone": "13121999270",
     *          "head_url": "",
     *          "worker_identity": "兼职",
     *          "worker_role": "保姆",
     *          "worker_start": 4.5,
     *          "personal_skill": [
     *              "煮饭",
     *              "开荒",
     *              "护老",
     *              "擦玻璃",
     *              "带孩子"
     *          ]
     *        }
     *     }
     * 
     * @apiError UserNotFound 用户认证已经过期.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *     }
     */
    public function actionWorkerInfo(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
          return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
            $workerInfo = Worker::getWorkerDetailInfo($worker->id);
            //数据整理
            $ret =[
                "worker_name" => $workerInfo['worker_name'],
                "worker_phone" => $workerInfo['worker_phone'],
                "head_url" => $workerInfo['worker_photo'],
                "worker_identity"  =>$workerInfo['worker_rule_description'],//身份
                "worker_role" => "保姆",
                'worker_start'=> 4.5,
                "personal_skill" =>['煮饭','开荒','护老','擦玻璃','带孩子'],
            ];
              return $this->send($ret, "阿姨信息查询成功", "ok");
        } else {
            return $this->send(null, "阿姨不存在.", "error", 403);
        }
    }
     
    /**
     * @api {POST} /worker/handle-worker-leave  阿姨请假（田玉星 80%）
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionHandleWorkerLeave
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [platform_version] 平台版本号.
     * @apiParam {String} date 请假时间.
     * @apiParam {String} type 请假类型
     * .
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/handle-worker-leave
     * 
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "result": "1",
     *          "msg": "您的请假已提交，请耐心等待审批。"
     *      }
     *  }
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "阿姨不存在"
     *  }
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
     * @api {GET} /worker/handle-worker-leave-history  查看阿姨请假历史（田玉星 80%）
     * 
     * @apiDescription 【备注：等待model底层支持】
     * @apiName actionHandleWorkerLeaveHistory
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [page_num]   页码数，默认1 
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/handle-worker-leave-history
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *       "code": "ok",
     *        "msg": "操作成功",
     *       "ret": [
     *          {
     *              "leave_type": "休假",
     *              "leave_date": "2015-10-01",
     *              "leave_status": "待审批"
     *          }
     *     ]
     *   }
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
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
           return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
            //判断页码
            if(!isset($param['page_num'])||!intval($param['page_num'])){
                $param['page_num'] = 1;
            }
            $page_num = intval($param['page_num']);
            
            //调取阿姨请假历史情况
            $ret = [
                [
                    'leave_type' => '休假',
                    'leave_date' => '2015-10-01',
                    'leave_status'=>'待审批'
                ],
                [
                    'leave_type' => '事假',
                    'leave_date' => '2015-10-11',
                    'leave_status'=>'成功'
                ],
                [
                    'leave_type' => '事假',
                    'leave_date' => '2015-10-12',
                    'leave_status'=>'失败'
                ],
            ];
            return $this->send($ret,"操作成功","ok");
        }else{
            return $this->send(null, "阿姨不存在.", "error", 403);
        }
          
     }

   /**
     * @api {GET} /worker/get-worker-place-by-id  获取阿姨住址(田玉星 100% )
     * 
     * @apiName actionGetWorkerPlaceById
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token.
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-place-by-id
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
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "阿姨不存在"
     *  }
     *
     */
    public function actionGetWorkerPlaceById(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
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
     * @api {GET} /worker/get-worker-comment 获取阿姨对应的评论 (田玉星 80%)
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionGetWorkerComment
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} comment_type 评论类型 【1：满意 2：一般 3差评】
     * @apiParam {String} [page_num]   页码数，默认1 
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-comment
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg": "操作成功.",
     *      "ret": [
     *         {
     *             "comment_id": "1",
     *             "comment": "这是第一条评论类型为2评论",
     *             "comment_date": "2015-10-22"
     *         },
     *         {
     *             "comment_id": "1",
     *             "comment": "这是第二条评论类型为2评论",
     *            "comment_date": "2015-10-22"
     *         }
     *      ]
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function  actionGetWorkerComment(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
           return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        
        if(!isset($param['comment_type'])|| !intval($param['comment_type'])||!in_array($param['comment_type'],array(1,2,3))){
            return $this->send(null, "评论类型不正确", "error", 403);
        }
        //判断页码
        if(!isset($param['page_num'])||!intval($param['page_num'])){
            $param['page_num'] = 1;
        }
        $page_num = intval($param['page_num']);
        
        //判断用户是否存在
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!$worker||!$worker->id){
             return $this->send(null, "阿姨不存在.", "error", 404);
        }
        //数据返回
        $ret = [
            [
                "comment_id"=>'1',
                "comment"=>"这是第一条评论类型为".$param['comment_type']."评论",
                'comment_date'=>date('Y-m-d')
            ],
            [
                "comment_id"=>'1',
                "comment"=>"这是第二条评论类型为".$param['comment_type']."评论",
                'comment_date'=>date('Y-m-d')
            ],
            [
                "comment_id"=>'1',
                "comment"=>"这是第三条评论类型为".$param['comment_type']."评论",
                'comment_date'=>date('Y-m-d')
            ],
        ];
        return $this->send($ret, "操作成功.", "ok");
    }
     /**
     * @api {GET} /worker/get-worker-complain 获取阿姨对应的投诉 (田玉星 80%)
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionGetWorkerComplain
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} [page_num]   页码数，默认1 
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-complain
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg": "操作成功.",
     *      "ret": [
     *         {
     *             "complain_id": "1",
     *             "complain": "这是第一条投诉",
     *             "complain_date": "2015-10-22"
     *         },
     *         {
     *             "complain_id": "1",
     *             "complain": "这是第二条投诉",
     *             "complain_date": "2015-10-22"
     *         }
     *      ]
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function  actionGetWorkerComplain(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
           return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
  
        //判断页码
        if(!isset($param['page_num'])||!intval($param['page_num'])){
            $param['page_num'] = 1;
        }
        $page_num = intval($param['page_num']);
        
        //判断用户是否存在
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!$worker||!$worker->id){
             return $this->send(null, "阿姨不存在.", "error", 404);
        }
        //数据返回
        $ret = [
            [
                "comment_id"=>'1',
                "comment"=>"这是第一条投诉",
                'comment_date'=>date('Y-m-d')
            ],
            [
                "comment_id"=>'1',
                "comment"=>"这是第二条投诉",
                'comment_date'=>date('Y-m-d')
            ],
            [
                "comment_id"=>'1',
                "comment"=>"这是第三条投诉",
                'comment_date'=>date('Y-m-d')
            ],
        ];
        return $this->send($ret, "操作成功.", "ok");
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
     * @api {post} /worker/single-worker-time.php  单次服务排班表(李勇80%缺少core/model支持)
     * @apiName SingleWorkerTime
     * @apiGroup Worker
     * @apiDescription 帮客户下单，单次服务获取服务时间
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} [district_id]   商圈id
     * @apiParam {String} [plan_time] 计划服务时长
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *  {
     *       "code": "ok",
     *       "msg": "获取单次服务排班表成功"
     *       "ret":{
     *          "appointment": [
     *              {
     *                  "date_format": "10月10日",
     *                  "date_stamp": 1444406400,
     *                  "week": "明天",
     *                  "have_worker": 1,
     *                  "hour": [
     *                      {
     *                          "time": "08:00-10:00",
     *                          "status": "0"
     *                      },
     *                      {
     *                          "time": "18:00-20:00",
     *                          "status": "1"
     *                      }
     *                  ]
     *              }
     *          ]
     *       }
     *     }
     *
     *  @apiError UserNotFound 用户认证已经过期.
     *
     *  @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    function actionSingleWorkerTime(){
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        if(!isset($param['district_id'])||!$param['district_id']||!isset($param['plan_time'])||!$param['plan_time']){
                    return $this->send(null, "请填写服务地址或服务时长", "error", 403);
        }
        $district_id = $param['district_id'];
        $plan_time=$param['plan_time'];
        $appointment = array();
        for ($i = 1; $i <= 7; $i++) {
            $item = [
                'date_format' => date('m月d日', strtotime('+' . $i . ' day')),
                'date_stamp' => time(date('m月d日', strtotime('+' . $i . ' day'))),
                'week' => $i == 1 ? '明天' : '',
                'have_worker' => '1',
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
            $appointment[] = $item;
        }

        $ret = ["appointment" => $appointment];
        return $this->send($ret, "获取单次服务排班表成功", "ok");
    }
    
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
     * @api {get} /worker/recursive-worker-time.php  周期服务时间表(李勇80%缺少model)
     * @apiName actionRecursiveWorkerTime
     * @apiGroup Worker
     * @apiDescription 帮客户下单，周期服务
     * @apiParam {String} access_token    用户认证.
     * @apiParam {String} [district_id]   商圈id
     * @apiParam {String} [worker_id]   阿姨id
     * @apiParam {String} [plan_time] 计划服务时长
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
     *  @apiError UserNotFound 用户认证已经过期.
     *
     *  @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *
     *     }
     *
     */
    function actionRecursiveWorkerTime(){
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        if(!isset($param['access_token'])||!$param['access_token']||!WorkerAccessToken::checkAccessToken($param['access_token'])){
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        if(!isset($param['worker_id'])||!$param['worker_id']||!isset($param['district_id'])||!$param['district_id']||!isset($param['plan_time'])||!$param['plan_time']){
                    return $this->send(null, "请填写服务地址或服务时长或阿姨", "error", 403);
        }
        $district_id = $param['district_id'];
        $plan_time=$param['plan_time'];
        $worker_id=$param['worker_id'];
        $workerTime = array();
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
                'date_name_tag'=>date('m月d日', strtotime('+' . $i . ' day')).'（今天）'
            ];
            $workerTime[] = $item;
        }
        $ret = array(
                    "selectTimeArea" =>'6',
                    "maxPlanTime" => '3',
                    "minPlanTime" => '2',
                    "msgStyle" => 'msgStyle',
                    "alertMsg" => 'alertMsg'
               );
        $ret = ["workerTime" => $workerTime];
        return $this->send($ret, "获取周期服务时间表成功", "ok");
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
    
    
   
    }


/**
 * 获得所有投诉
 */
/**
 * 获取对阿姨的评论
 */
/**
 * 获取该阿姨的消息列表
 */
/**
 * 删除阿姨消息
 */
    
    

?>
