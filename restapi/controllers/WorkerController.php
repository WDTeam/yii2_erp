<?php
namespace restapi\controllers;

use core\models\customer\CustomerAccessToken;
use core\models\customer\CustomerComment;
use core\models\worker\Worker;
use core\models\worker\WorkerSkill;
use core\models\worker\WorkerVacationApplication;
use core\models\worker\WorkerTaskLog;
use core\models\finance\FinanceSettleApplySearch;
use core\models\order\OrderSearch;
use core\models\order\OrderComplaint;

use restapi\models\Worker as ApiWorker;
use restapi\models\alertMsgEnum;
use Yii;
class WorkerController extends \restapi\components\Controller
{
    /**
     * @api {GET} /worker/worker-info 查看阿姨信息 (田玉星 100%)
     *
     * @apiName WorkerInfo
     * @apiGroup Worker
     *
     * @apiParam {String} access_token 用户登录token
     * @apiParam {String} [worker_id]  阿姨id
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/worker-info
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "1",
     *      "msg": "阿姨信息查询成功",
     *      "alertMsg": "获取阿姨信息成功"，
     *      "ret": {
      *          "worker_name": "阿姨姓名",
     *          "worker_phone": "阿姨手机号",
     *          "worker_photo": "头像地址",
     *          "worker_identity_description": "阿姨身份说明",
     *          "worker_identity_id":"阿姨身份标识【1全职 2兼职 3高峰 4时段】",
     *          "worker_type_description": "角色",
     *          "worker_star": "星级",
     *          "personal_skill": [
     *              "阿姨技能1",
     *              "阿姨技能2",
     *              "阿姨技能3"
     *          ]
     *        }
     *     } 
     *
     * @apiError UserNotFound 用户认证已经过期.
     * @apiErrorExample Error-Response:
     * HTTP/1.1 403 Not Found
     * {
     *   "code": "0",
     *   "msg": "用户认证已经过期,请重新登录，",
     *   "ret": null
     * }
     */
    public function actionWorkerInfo()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token']|| !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, alertMsgEnum::userLoginFailed, 0, 403,null,alertMsgEnum::userLoginFailed);
        }
        if(!isset($param['worker_id']) ||!$param['worker_id']||!intval($param['worker_id'])){
            return $this->send(null, '阿姨ID传输错误', 0, 403,null,alertMsgEnum::workerInfoFailed);
        }
        //数据调取
        try{
            $workerId = intval($param['worker_id']);
            $workerInfo = Worker::getWorkerDetailInfo($workerId);
            $ret = array();
            if(!empty($workerInfo)){
                $ret = [
                    "worker_name" => $workerInfo['worker_name'],
                    "worker_phone" => $workerInfo['worker_phone'],
                    "worker_photo" => $workerInfo['worker_photo'],
                    "worker_identity_description" => $workerInfo['worker_identity_description'],//身份
                    "worker_identity_id" => $workerInfo['worker_identity_id'],//身份类型
                    "worker_type" => $workerInfo["worker_type"],
                    "worker_type_description" => $workerInfo["worker_type_description"],
                    'worker_star' => number_format($workerInfo["worker_star"],1),
                    "personal_skill" =>WorkerSkill::getWorkerSkill($workerId) ,
                ];
            }
            return $this->send($ret, '阿姨信息查询成功', 1, 200,null,alertMsgEnum::workerInfoSuccess);
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerInfoFailed);
        }
    }

    /**
     * @api {POST} /worker/handle-worker-leave  阿姨请假（田玉星 100%）
     *
     * @apiName actionHandleWorkerLeave
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [platform_version] 平台版本号.
     * @apiParam {String} leave_time 请假时间，如果请假时间是两天则格式为:【2015-09-10_2015-09-20】
     * @apiParam {String} leave_type 请假类型  1.休假 2事假
     * .
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/handle-worker-leave
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "您的请假已提交，请耐心等待审批",
     *    "alertMsg": "您的请假已提交，请耐心等待审批"，
     *   "ret": {}
     *  }
     * 
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "阿姨不存在",
     *  }
     */
    public function actionHandleWorkerLeave()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        $worker_identity_id = $checkResult['workerInfo']['worker_identity_id'];
        //判断数据完整
        if(!isset($param['leave_type']) || !$param['leave_type']|| !in_array($param['leave_type'], array(1, 2))){
            return $this->send(null, "请假类型不正确", 0, 403,null,alertMsgEnum::workerApplyLeaveTypeFailed);
        }
        $vacationType = intval($param['leave_type']);
        //请假时间
        if (!isset($param['leave_time']) || !$param['leave_time']) {
            return $this->send(null, "数据不完整,请选择请假时间", 0, 403,null, alertMsgEnum::workerApplyLeaveTimeFailed);
        }
        try{
            if($worker_identity_id!=1) return $this->send(null, "只有全职阿姨才可以申请请假", 0, 403,null,alertMsgEnum::workerApplyLeaveFailed);
            $vacationTimeLine = WorkerVacationApplication::getApplicationTimeLine($workerID,$vacationType);
         }catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::workerApplyLeaveFailed);
        }
        $vacationTimeArr = explode("_",$param['leave_time']);
        $vacationTime = array_keys($vacationTimeLine);
        //根据请假类型判断请假时间合法性
        $firstDay = $vacationTimeArr[0];
        $secondDay = isset($vacationTimeArr[1])?$vacationTimeArr[1]:"";
        if(!in_array($firstDay, $vacationTime)||!$vacationTimeLine[$firstDay]){
            return $this->send(null, "请假时间不在请假时间范围内", 0, 403,null,alertMsgEnum::workerApplyLeaveTimeFailed);
        }
        if($vacationType==1&&count($vacationTimeArr)>2){
            return $this->send(null, "休假最多只能选择两天", 0, 403,null,alertMsgEnum::workerApplyLeaveTimeFailed);
        }
        if($vacationType==2&&count($vacationTimeArr)>1){
            return $this->send(null, "事假最多只能选择一天", 0, 403,null,alertMsgEnum::workerApplyLeaveTimeFailed);
        }
        //休假或者事假申请（一天）
        if(!WorkerVacationApplication::createVacationApplication($workerID,$firstDay,$vacationType)){
            return $this->send(null, "请假申请失败", 0, 403,null,alertMsgEnum::workerApplyLeaveFailed);
        }
        //如果选择休假且选择了两天
        if($vacationType==1&&$secondDay){//休假
            if(!in_array($secondDay, $vacationTime)||!$vacationTimeLine[$secondDay]){
                return $this->send(null, "请假时间不在请假时间范围内", 0, 403,null,alertMsgEnum::workerApplyLeaveTimeFailed);
            }
            if(!WorkerVacationApplication::createVacationApplication($workerID,$secondDay,$vacationType)){
                return $this->send(null, "请假申请失败", 0, 403,null,alertMsgEnum::workerApplyLeaveFailed);
            }
        }
        return $this->send(null, "您的请假已提交，请耐心等待审批",1,200,null,alertMsgEnum::workerApplyLeaveSuccess);
        
    }

    /**
     * @api {GET} /worker/get-worker-leave-history  查看阿姨请假历史（田玉星 100%）
     *
     * @apiName actionGetWorkerLeaveHistory
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} per_page   页码数
     * @apiParam {String} page_num   每页显示数
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-leave-history
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功",
     *   "alertMsg": "获取阿姨请假历史记录成功",
     *   "ret": {
     *       "per_page": 1,
     *       "page_num": 1,
     *       "data": [
     *           {
     *               "leave_type": "请假类型【1休假 2事假】",
     *               "leave_time": "请假时间",
     *               "leave_status": "请假状态"
     *           }
     *       ]
     *      }
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
    public function actionGetWorkerLeaveHistory()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
             return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        //判断页码
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        //调取阿姨请假历史情况
        try{
            $data = WorkerVacationApplication::getApplicationList($workerID,$per_page,$page_num);
        }catch (\Exception $e) {
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::workerLeaveHistoryFailed);
        }
        $pageData = array();
        if($data['data']){
            foreach($data['data'] as $key => $val){
                $pageData[$key]['leave_type'] = $val['worker_vacation_application_type']==1?"休假":"事假";
                $pageData[$key]['leave_time'] =  date('Y-m-d',$val['worker_vacation_application_start_time']);
                switch($val['worker_vacation_application_approve_status']){
                    case "0":
                        $pageData[$key]['leave_status'] = "待审核";
                        break;
                    case "1":
                        $pageData[$key]['leave_status'] = "审核通过";
                        break;
                    case "2":
                        $pageData[$key]['leave_status'] = "审核不通过";
                        break;
                    default :
                        $pageData[$key]['leave_status'] = "未知";
                }
            }
        } 
        $ret = [
            'per_page'=> $data['page'],
            'page_num'=> $data['pageNum'],
            'data'    => $pageData
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerLeaveHistorySuccess);
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
     *      "code": "1",
     *      "msg":"查询地址成功",
     *      "alertMsg": "获取阿姨住址成功",
     *      "ret":
     *      {
     *          "live_place": "阿姨常住地址"
     *      }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "阿姨不存在"
     *  }
     *
     */
    public function actionGetWorkerPlaceById()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        try{
            $workerInfo = Worker::getWorkerDetailInfo($workerID);
        }catch(\Exception $e){
            return $this->send(null, $e->getMessage(), 1024, 403,null,alertMsgEnum::workerLivePlaceFailed);
        }
        $ret = array(
            "live_place" => $workerInfo['worker_live_place']
        );
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerLivePlaceSuccess);
    }

    /**
     * @api {GET} /worker/get-worker-comment 获取阿姨对应的评论 (田玉星 100%)
     *
     * @apiName actionGetWorkerComment
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} comment_level   评论类型 【1：满意 2：一般 3：差评】
     * @apiParam {String} per_page   页码数
     * @apiParam {String} page_num   每页显示数
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-comment
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "操作成功",
     *       "alertMsg": "获取评论成功"，
     *       "ret": {
     *           "per_page": 1,
     *           "page_num": 10,
     *           "data": [
     *               {
     *                   "comment_id": "评论ID",
     *                   "comment_content": "评论内容",
     *                   "comment_time": "评论日期"
     *               }
     *           ]
     *       }
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function  actionGetWorkerComment()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        //判断评论类型
        if (!isset($param['comment_level']) || !intval($param['comment_level']) || !in_array($param['comment_level'], array(1, 2, 3))) {
            return $this->send(null, "评论类型不正确", 0, 403,null,alertMsgEnum::workerCommentTypeFailed);
        }
        //分页数据
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        //获取数据
        $retData = array();
        try{
            $commentList = CustomerComment::getCustomerCommentworkerlist($workerID,$param['comment_level'],$per_page,$page_num);
            if($commentList){
                foreach($commentList as $key=>$val){
                    $retData[$key]['comment_id'] = $val['id'];
                    $retData[$key]['comment_content'] = $val['customer_comment_content'];
                    $retData[$key]['comment_time'] = date('Y-m-d',$val['created_at']);
                }
            }
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerCommentFailed);
        }
        $ret = [
            'per_page'=>$per_page,
            'page_num'=>$page_num,
            'data'=>$retData
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerCommentSuccess);
    }

    /**
     * @api {GET} /worker/get-worker-complain 获取阿姨对应的投诉 (田玉星 100%)
     *
     * @apiName actionGetWorkerComplain
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} per_page    第几页
     * @apiParam {String} page_num   每页显示的数据数量
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-complain
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功",
     *   "alertMsg": "获取投诉成功"，
     *   "ret": {
     *       "per_page": 1,
     *       "page_num": 10,
     *       "worker_is_block":"阿姨账号状态【0正常 1封号】",
     *       "data": [
     *           {
     *               "complaint_content": "投诉内容",
     *               "complaint_time": "投诉时间"
     *           }
     *       ]
     *   }
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerComplain()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        //分页数据
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        //调取数据
        try{
            $conplainList = OrderComplaint::getWorkerComplain($workerID);
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerComplainFailed);
        }
        if($conplainList){
            foreach($conplainList as $key=>$val){
                $conplainList[$key]['complaint_time'] = date('Y-m-d H:i:s',$val['complaint_time']);
            }
        }
        //数据返回
        $ret = [
            'per_page'=>$per_page,
            'page_num'=>$page_num,
            'worker_is_block'=> $checkResult['workerInfo']['worker_is_block'],
            'data'  => $conplainList
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerComplainSuccess);
    }

    /**
     * @api {GET} /worker/get-worker-service-info 获取账单阿姨服务信息 (田玉星 100%)
     * 
     * @apiName actionGetWorkerServiceInfo
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-service-info
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "1",
     *      "msg": "操作成功.",
     *       "alertMsg": "获取服务信息成功",
     *      "ret": [
     *             "worker_name": "阿姨姓名",
     *             "order_count": "服务订单数",
     *             "service_family_count": "服务家庭总数",
     *             "worker_income":"阿姨收入"
     *      ]
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerServiceInfo()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        //获取数据
        try{
            $service = FinanceSettleApplySearch::getWorkerIncomeSummaryInfoByWorkerId($workerID);
            $workerInfo = Worker::getWorkerStatInfo($workerID);
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerServiceInfoFailed);
        }
        //数据整理返回
        $ret = [
            "worker_name" => $service['worker_name'],
            "order_count" => intval($service['all_order_count']),
            "worker_income" => $service['all_worker_money'],
            "service_family_count" => intval($workerInfo['worker_stat_server_customer'])
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerServiceInfoSuccess);

    }

    /**
     * @api {GET} /worker/get-worker-bill-list 获取阿姨对账单列表 (田玉星 100%)
     * 
     * @apiName actionGetWorkerBillList 
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} per_page  第几页
     * @apiParam {String} page_num  每页显示多少条
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-bill-list
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     * 
     *  "code": 1,
     *   "msg": "操作成功",
     *   "alertMsg": "获取账单列表成功",
     *   "ret": {
     *       "per_page": 1,
     *       "page_num": 10,
     *       "explain_url": "账单说明跳转URL",
     *       "data": [
     *           {
     *               "settle_id": "账单唯一标识",
     *               "settle_year": "账单归属年限",
     *               "order_count": "账单内完成的订单总数",
     *               "worker_income": "该账单阿姨的总收入",
     *               "settle_cycle": "账单类型【1周期账单 2月结账单】",
     *               "settle_cycle_des": "账单文字说明",
     *               "settle_task_money": "任务奖励金额",
     *               "base_salary_subsidy": "底薪补贴",
     *               "money_deduction": "处罚金额",
     *               "order_money_except_cash": "工时服务费",
     *               "settle_status":"账单状态【0未结算 1已结算】",
     *               "settle_starttime": "账单开始日期【如果是月结则settle_endtime无效】",
     *                "settle_endtime": "账单结束日期",
     *               "worker_is_confirmed": "阿姨是否确认账单【0未确认 1已确认】"
     *           }
     *       ]
     *    }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerBillList()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        $workerID = $checkResult['workerInfo']['worker_id'];
        //分页数据
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        try{
            $billList = FinanceSettleApplySearch::getSettledWorkerIncomeListByWorkerId($workerID,$per_page,$page_num);
         }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerBillListFailed);
        }
        foreach($billList as $key=>$val){
            if($val['settle_cycle']==2){//周结账单
                $billList[$key]['settle_starttime'] = date('Y-m',strtotime($val['settle_starttime']));
                $billList[$key]['settle_endtime'] = date('Y-m',strtotime($val['settle_starttime']));
            }
            $billList[$key]['worker_is_confirmed'] = $val['isWorkerConfirmed'];
            unset($billList[$key]['isWorkerConfirmed']);
        }
        $ret = [
            'per_page' => $per_page,
            'page_num' => $page_num,
            'explain_url'=>'http://www.baidu.com',
            'data'  => $billList
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerBillListSuccess);
    }

    /**
     * @api {GET} /worker/get-worker-tasktime-list 获取阿姨工时列表 (田玉星 100%)
     * 
     * @apiName actionGetWorkerTasktimeList
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} settle_id  账单唯一标识.
     * @apiParam {String} per_page  第几页
     * @apiParam {String} page_num  每页显示多少条.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-tasktime-list
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功",
     *   "alertMsg": "获取工时列表成功",
     *   "ret": {
     *       "per_page": 1,
     *       "page_num": 10,
     *       "data": [
     *           {
     *               "order_id": "订单ID",
     *               "order_money": "订单金额",
     *               "order_code": "订单号",
     *               "service_date": "服务日期",
     *               "service_time": "服务时间段"
     *           }
     *       ]
     *   }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */ 
    public function actionGetWorkerTasktimeList(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        } 
        //数据整理
        if(!isset($param['settle_id'])||!intval($param['settle_id'])){
            return $this->send(null,'账单唯一标识错误.', 0, 403,null,alertMsgEnum::workerSettleIdFailed);
        }
         //分页数据
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        try{
            $billList = FinanceSettleApplySearch::getOrderArrayBySettleId(intval($param['settle_id']),$per_page,$page_num);
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerTasktimeListFailed);
        }
        //数据整理
        if($billList){
            foreach($billList as $key=>$val){
                $beginTime = strtotime($val['order_begin_time']);
                $billList[$key]['service_date'] = date('Y-m-d',$beginTime);
                $billList[$key]['service_time'] = date('H:i',$beginTime).'-'.date('H:i',strtotime($val['order_end_time']));
                unset($billList[$key]['order_begin_time']);
                unset($billList[$key]['order_end_time']);
            }
        }
        $ret = [
            'per_page' => $per_page,
            'page_num' => $page_num,
            'data'  => $billList
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerTasktimeListSuccess);
    }
    
  
    /**
     * @api {GET} /worker/get-worker-taskreward-list 获取阿姨奖励列表 (田玉星 100%)
     * 
     * @apiName actionGetWorkerTaskrewardList
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} settle_id  账单唯一标识.
     * @apiParam {String} per_page  第几页
     * @apiParam {String} page_num  每页显示多少条
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-taskreward-list
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功",
     *   "alertMsg": "获取任务奖励成功",
     *   "ret":{
     *       "per_page": 1,
     *       "page_num": 10,
     *       "data": [
     *           {
     *               "task_money": "奖励金额",
     *               "task_des": "任务说明"
     *           }
     *       ]    
     *  }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerTaskrewardList(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        //分页数据
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        //数据整理
        if(!isset($param['settle_id'])||!intval($param['settle_id'])){
            return $this->send(null,'账单唯一标识错误', 0, 403,null,alertMsgEnum::workerTasktimeListFailed);
        }
        try{
            //获取任务奖励列表
            $taskRewardret = FinanceSettleApplySearch::getTaskArrayBySettleId(intval($param['settle_id']));
         }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerTaskRewardListFailed);
        }
        $ret = [
            'per_page' => $per_page,
            'page_num' => $page_num,
            'data'  => $taskRewardret
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerTaskRewardListSuccess);
    }
    
    /**
     * @api {GET} /worker/get-worker-punish-list 获取阿姨受处罚列表 (田玉星 100%)
     * 
     * @apiName actionGetWorkerPunishList
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} settle_id  账单唯一标识
     * @apiParam {String} per_page  第几页
     * @apiParam {String} page_num  每页显示多少条
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-punish-list
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *    "code": 1,
     *     "msg": "操作成功",
     *     "alertMsg": "获取处罚列表成功",
     *     "ret":{
     *              "per_page": 1,
     *              "page_num": 10,
     *              "data":[
     *                 {
     *                  "deduction_money": "处罚金额",
     *                  "deduction_des": "处罚描述",
     *                  "deduction_type": "处罚类型",
     *                  "deduction_time": "处罚时间",
     *                  "deduction_type_des": "处罚类型描述"
     *                }
     *              ]  
     *      }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerPunishList(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        //数据整理
        if(!isset($param['settle_id'])||!intval($param['settle_id'])){
            return $this->send(null,'账单唯一标识错误', 0, 403,null,alertMsgEnum::workerTasktimeListFailed);
        }
        //分页数据
        (isset($param['per_page'])&&intval($param['per_page']))?$per_page = intval($param['per_page']):$per_page = 1;
        (isset($param['page_num'])&&intval($param['page_num']))?$page_num = intval($param['page_num']):$page_num = 10;
        
        try{
            $punishList = FinanceSettleApplySearch::getDeductionArrayBySettleId(intval($param['settle_id']));//获取任务奖励列表
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerPunishListFailed);
        }
        if($punishList){
            foreach($punishList as $key=>$val){
                switch($val['deduction_type']){
                    case "2":
                        $punishList[$key]['deduction_type_des'] = "投诉";
                        break;
                    case "3":
                        $punishList[$key]['deduction_type_des'] = "赔偿";
                        break;
                    default:
                        $punishList[$key]['deduction_type_des'] = "未知";
                }
            }
        }
        //获取受处罚列表
        $ret = [
            'per_page' => $per_page,
            'page_num' => $page_num,
            'data'  => $punishList
        ];
        return $this->send($ret, "操作成功",1,200,null,alertMsgEnum::workerPunishListSuccess);
    }
    
    /**
     * @api {PUT} /worker/worker-bill-confirm 账单确认 (田玉星 100%)
     * 
     * @apiName actionWorkerBillConfirm
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} settle_id  账单唯一标识.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/worker-bill-confirm
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *    "code": 1,
     *    "msg": "账单确定成功",
     *    "alertMsg": "账单确认成功",
     *    "ret": {}
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"0",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionWorkerBillConfirm(){
        $param = Yii::$app->request->post() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        //数据整理
        if(!isset($param['settle_id'])||!intval($param['settle_id'])){
            return $this->send(null,'账单唯一标识错误', 0, 403,null,alertMsgEnum::workerTasktimeListFailed);
        }
        try{
            if(FinanceSettleApplySearch::workerConfirmSettlement(intval($param['settle_id']))){
                return $this->send(null,'账单确定成功', 1, 200,null,alertMsgEnum::workerBillConfirmSuccess);
            }
         }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerBillConfirmFailed);
        }
        return $this->send(null,'账单确定失败', 0, 403,null,alertMsgEnum::workerBillConfirmFailed);
    }
    
    /**
     * @api {GET} /worker/get-worker-center 个人中心首页 (田玉星 100%)
     *
     * @apiName getWorkerCenter
     * @apiGroup Worker
     *
     * @apiParam {String} access_token 阿姨登录token
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-center
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "code": "ok",
     *      "msg": "阿姨信息查询成功",
     *      "alertMsg": "获取阿姨数据成功",
     *      "ret": {
     *          "worker_name": "阿姨姓名",
     *          "worker_phone": "阿姨手机号",
     *          "worker_photo": "头像地址",
     *          "worker_identity_description": "阿姨身份说明",
     *          "worker_identity_id":"阿姨身份标识【1全职 2兼职 3高峰 4时段】",
     *          "worker_type": "阿姨类型【 1自有 2非自有】",
     *          "worker_star": "星级",
     *          "personal_skill": [
     *              "阿姨技能1",
     *              "阿姨技能2",
     *              "阿姨技能3"
     *          ]
     *        }
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     {
     *       "code": "error",
     *       "msg": "用户认证已经过期,请重新登录，"
     *     }
     */
    public function actionGetWorkerCenter(){
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], $checkResult['code'], 403,null,$checkResult['msg']);
        }
        //数据整理
        try{
            $workerID = $checkResult['workerInfo']['worker_id'];
             $workerInfo = Worker::getWorkerDetailInfo($workerID);
        }catch (\Exception $e) {
            return $this->send(null,$e->getMessage(), 1024, 403,null,alertMsgEnum::workerCenterFailed);
        }
        $ret = [
            "worker_name" => $workerInfo['worker_name'],
            "worker_phone" => $workerInfo['worker_phone'],
            "worker_photo" => $workerInfo['worker_photo'],
            "worker_identity_description" => $workerInfo['worker_identity_description'],//身份
            "worker_identity_id" => $workerInfo['worker_identity_id'],//身份类型
            "worker_type" => $workerInfo["worker_type"],
            "worker_star" => number_format($workerInfo["worker_star"],1),
            "personal_skill" => WorkerSkill::getWorkerSkill($workerID),
        ];
        return $this->send($ret,'阿姨信息查询成功', 1, 200,null,alertMsgEnum::workerCenterSuccess);
    }
    
    
    /** 
     * @api {GET} /worker/system-news  消息通知中心(田玉星0%)
     * 
     * @apiDescription 未定
     * 
     * @apiName actionSystemNews
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录token.
     * @apiParam {String} [platform_version] 平台版本号.
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
     * @api {get} /worker/worker-leave  查看请假情况 (李勇100%)
     * @apiName actionWorkerLeave
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} type 请假类型
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *{
     *   "code": 1,
     *   "msg": "操作成功",
     *   "ret": {
     *       "2015-10-28": true,
     *       "2015-10-29": true,
     *       "2015-10-30": false,
     *       "2015-10-31": false,
     *       "2015-11-01": false,
     *       "2015-11-02": true,
     *       "2015-11-03": true,
     *       "2015-11-04": true,
     *       "2015-11-05": true,
     *       "2015-11-06": false,
     *       "2015-11-07": false,
     *       "2015-11-08": false,
     *       "2015-11-09": true,
     *   }
     *}
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "阿姨不存在"
     *     }
     */
    public function actionWorkerLeave()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::workerLoginFailed);
        } 
        if (!isset($param['type']) || !$param['type'] || !in_array($param['type'], array(1, 2))) {
            return $this->send(null, "请选择请假类型", 0, 403,null,alertMsgEnum::workerLeaveNoType);
        }
        $worker_id = $checkResult['workerInfo']['worker_id'];
        $type = $param['type'];
        try{
            $ret= WorkerVacationApplication::getApplicationTimeLine($worker_id,$type);
        }catch (\Exception $e) {
            return $this->send($e, "获取阿姨请假表系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        return $this->send($ret, "获取阿姨请假表成功", 1, 200,null,alertMsgEnum::workerLeaveSuccess);
    }

    /**
     * 获取该阿姨的消息列表
     */

    /**
     * 删除阿姨消息
     */

    /**
     * 查看该阿姨所有未交罚款记录
     */

    /**
     * 获得该阿姨所有未领取任务奖励记录
     */
    
    
    
     /**
     * @api {get} /worker/task-doing  获得进行中的任务列表 (李勇100%)
     * @apiName actionTaskDoing
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "操作成功",
     *       "ret": [
     *           {
     *               "id": 2,
     *               "worker_id": 1,
     *               "worker_task_id": 2,
     *               "worker_task_cycle_number": "1",
     *               "worker_task_name": "任务名称2",
     *               "worker_task_log_start": 1446096240,
     *               "worker_task_log_end": 1446297240,
     *               "worker_task_is_done": 0,
     *               "worker_task_done_time": 0,
     *               "worker_task_reward_type": 0,
     *               "worker_task_reward_value": 0,
     *               "created_at": 1446097240,
     *               "updated_at": 0,
     *               "is_del": 0
     *           }
     *       ]
     *   }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "您没有任务哦"
     *     }
     */
    public function actionTaskDoing()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::workerLoginFailed);
        } 
        $worker_id = $checkResult['workerInfo']['worker_id'];
        try{
            $ret= WorkerTaskLog::getCurListByWorkerId($worker_id);
        }catch (\Exception $e) {
            return $this->send($e, "获取当前阿姨任务列表系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if(empty($ret)){
              return $this->send(null, "您没有任务哦", 0, 403,null,alertMsgEnum::taskDoingFail);
        }
        return $this->send($ret, "操作成功", 1, 200,null,alertMsgEnum::taskDoingSuccess);
    }
    
    
   
     /**
     * @api {get} /worker/task-done  获得已完成的任务列表 (李勇100%)
     * @apiName actionTaskDone
     * @apiGroup Worker
     * @apiParam {String} per_page  第几页
     * @apiParam {String} page_num  每页显示多少条
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "操作成功",
     *       "ret": [
     *           {
     *               "id": 2,
     *               "worker_id": 1,
     *               "worker_task_id": 2,
     *               "worker_task_cycle_number": "1",
     *               "worker_task_name": "任务名称2",
     *               "worker_task_log_start": 1446096240,
     *               "worker_task_log_end": 1446297240,
     *               "worker_task_is_done": 0,
     *               "worker_task_done_time": 0,
     *               "worker_task_reward_type": 0,
     *               "worker_task_reward_value": 0,
     *               "created_at": 1446097240,
     *               "updated_at": 0,
     *               "is_del": 0
     *           }
     *       ]
     *   }
     * 
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "您没有已完成任务哦"
     *     }
     */
    public function actionTaskDone()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::workerLoginFailed);
        }
        if(!isset($param['page']) || !$param['page']||!isset($param['per_page']) || !$param['per_page']){
            return $this->send(null, "数据不完整,请输入每页条数和第几页", 0, 403,null,alertMsgEnum::taskDoneNoPage);
        }
        $worker_id = $checkResult['workerInfo']['worker_id'];
        $page = $param['page'];
        $per_page = $param['per_page'];
        try{
            $ret= WorkerTaskLog::getDonedTasks($worker_id,1,$page,$per_page);
        }catch (\Exception $e) {
            return $this->send($e, "获取已完成的任务的系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if(empty($ret)){
              return $this->send(null, "您没有已完成任务哦", 0, 403,null,alertMsgEnum::taskDoneFail);
        }
        return $this->send($ret, "操作成功", 1,200,null,alertMsgEnum::taskDoneSuccess);
    }
    
     /**
     * @api {get} /worker/task-fail  获得已失败的任务列表 (李勇100%)
     * @apiName actionTaskFail
     * @apiGroup Worker
     * @apiParam {String} per_page  第几页
     * @apiParam {String} page_num  每页显示多少条
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     *   {
     *       "code": 1,
     *       "msg": "操作成功",
     *       "ret": [
     *           {
     *               "id": 3,
     *               "worker_id": 1,
     *               "worker_task_id": 1,
     *               "worker_task_cycle_number": "0",
     *               "worker_task_name": "任务名称3",
     *               "worker_task_log_start": 1446096240,
     *               "worker_task_log_end": 1446097240,
     *               "worker_task_is_done": -1,
     *               "worker_task_done_time": 0,
     *               "worker_task_reward_type": 0,
     *               "worker_task_reward_value": 0, 
     *               "created_at": 1446097240,
     *               "updated_at": 0,
     *               "is_del": 0,
     *               "values": [],
     *               "worker_task_description": ""
     *           }
     *       ]
     *   }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found
     *     { 
     *       "code":"0",
     *       "msg": "您没有失败的任务哦"
     *     }
     */
    public function actionTaskFail()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::workerLoginFailed);
        } 
        if(!isset($param['page']) || !$param['page']||!isset($param['per_page']) || !$param['per_page']){
            return $this->send(null, "数据不完整,请输入每页条数和第几页", 0, 403,null,alertMsgEnum::taskFailNoPage);
        }
        $worker_id = $checkResult['workerInfo']['worker_id'];
        $page = $param['page'];
        $per_page = $param['per_page'];
        try{
            $ret= WorkerTaskLog::getDonedTasks($worker_id,-1,$page,$per_page);
        }catch (\Exception $e) {
            return $this->send($e, "boss系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        if(empty($ret)){
              return $this->send(null, "您没有任务哦", 0,403,null,alertMsgEnum::taskFailFail);
        }
        return $this->send($ret, "操作成功", 1,200,null,alertMsgEnum::taskFailSuccess);
    }

    /**
     * @api {get} /worker/check-task  查看任务的详情 (李勇100%)
     * @apiName actionCheckTask
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} id    任务id
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg":"操作成功",
     *      "ret":
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_description": "任务描述",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "settled":[
     *               {
     *                  "order_id": "订单id",
     *                  "order_time": "订单时间",
     *                  "work_hours": "工时"
     *                },
     *                {
     *                  "order_id": "订单id",
     *                  "order_time": "订单时间",
     *                  "work_hours": "工时"
     *                }  
     *           ]
     *      }
     * }
     *
     * @apiError SessionIdNotFound 未找到会话ID.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Not Found 
     *     { 
     *       "code":"0",
     *       "msg": "查看任务失败"
     *     }
     */
    public function actionCheckTask()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = ApiWorker::checkWorkerLogin($param);
        if($checkResult['code']!=1){
            return $this->send(null, $checkResult['msg'], 0, 403,null,alertMsgEnum::workerLoginFailed);
        } 
        if (!isset($param['id']) || !$param['id']){
            return $this->send(null, "请填写任务id", 0, 403,null,alertMsgEnum::checkTaskNoId);
        }
        $worker_id = $checkResult['workerInfo']['worker_id'];
        $id = $param['id'];
        //获取任务的详情
        try{
            $task_log=WorkerTaskLog::findOne(['id'=>$id])->getDetail();
        }catch (\Exception $e) {
            return $this->send($e, "获取任务的详情系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        $worker_task_log_start=$task_log['worker_task_log_start'];
        $worker_task_log_end=$task_log['worker_task_log_end'];
        //获取任务的订单列表
        try{
            $order_list=OrderSearch::getWorkerAndOrderAndDoneTime($worker_id ,$worker_task_log_start,$worker_task_log_end);
        }catch (\Exception $e) {
            return $this->send($e, "获取任务的订单列表系统错误", 1024, 403,null,alertMsgEnum::bossError);
        }
        $task_log['order_list']=$order_list;
        if(empty($task_log)){
              return $this->send(null, "查看任务失败", 0,403,null,alertMsgEnum::checkTaskFail);
        }
        return $this->send($task_log, "操作成功", 1,200,null,alertMsgEnum::checkTaskSuccess);
    }
}


?>
