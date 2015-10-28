<?php
namespace api\controllers;

use Yii;
use \core\models\customer\CustomerAccessToken;
use \core\models\worker\Worker;
use \core\models\worker\WorkerSkill;
use \core\models\worker\WorkerVacationApplication;
use \core\models\finance\FinanceSettleApplySearch;
use \core\models\order\OrderComplaint;
use \core\models\worker\WorkerAccessToken;
use \core\models\operation\OperationShopDistrictCoordinate;

class WorkerController extends \api\components\Controller
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
    public function actionWorkerInfo()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !isset($param['worker_id']) || !$param['worker_id'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        // 按阿姨id获取阿姨信息
        $workerId = intval($param['worker_id']);
        if ($workerId ) {
            $workerInfo = Worker::getWorkerDetailInfo($workerId);
            //数据整理
            if(!empty($workerInfo)){
                $ret = [
                    "worker_name" => $workerInfo['worker_name'],
                    "worker_phone" => $workerInfo['worker_phone'],
                    "head_url" => $workerInfo['worker_photo'],
                    "worker_identity" => $workerInfo['worker_identity_description'],//身份
                    "worker_role" => $workerInfo["worker_type_description"],
                    'worker_start' => $workerInfo["worker_star"],
                    'total_money' => $workerInfo['worker_stat_order_money'],
                    "personal_skill" => WorkerSkill::getWorkerSkill($workerId),
                ];
                return $this->send($ret, "阿姨信息查询成功");
            }
        }
        return $this->send(null, "阿姨不存在.", 0, 403);
    }

    /**
     * 公用检测阿姨登录情况
     * @param type $param 
     */
    private function checkWorkerLogin($param=array()){
        $msg = array('code'=>0,'msg'=>'','worker_id'=>0);
        if(!isset($param['access_token'])||!$param['access_token']){
           $msg['msg'] = '请登录';
           return $msg;
        }
        try{
            $isright_token = WorkerAccessToken::checkAccessToken($param['access_token']);
            $worker = WorkerAccessToken::getWorker($param['access_token']);
        }catch (\Exception $e) {
            $msg['code'] = '1024';
            $msg['msg'] = 'boss系统错3误';
            return $msg;
        }
        if(!$isright_token){
            $msg['msg'] = '用户认证已经过期,请重新登录';
            return $msg;
        }
        if (!$worker|| !$worker->id) {
            $msg['msg'] = '阿姨不存在';
            return $msg;
        }
        //验证通过
        $msg['code'] = 1;
        $msg['msg'] = '验证通过';
        $msg['worker_id'] = $worker->id;
        return $msg;
    }
    /**
     * @api {POST} /worker/handle-worker-leave  阿姨请假（田玉星 80%）
     *
     * @apiName actionHandleWorkerLeave
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} [platform_version] 平台版本号.
     * @apiParam {String} leave_date 请假时间.
     * @apiParam {String} leave_type 请假类型
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
    public function actionHandleWorkerLeave()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        //判断数据完整
        if(!isset($param['leave_type']) || !$param['leave_type']){
            return $this->send(null, "数据不完整,请选择请假类型", 0, 403);
        }
        if (!in_array($param['leave_type'], array(1, 2))) {
            return $this->send(null, "请假类型不正确", 0, 403);
        }
        $vacationType = intval($param['leave_type']);

        //请假时间范围判断
        if (!isset($param['leave_date']) || !$param['leave_date']) {
            return $this->send(null, "数据不完整,请选择请假时间", 0, 403);
        }
       
        $vacation_start_time = time();
        $vacation_end_time = strtotime(date('Y-m-d', strtotime("+14 days")));
        $current_vacation_time = strtotime($param['leave_date']);
        if ($current_vacation_time <= $vacation_start_time || $current_vacation_time > $vacation_end_time) {
            return $this->send(null, "请假时间不在请假时间范围内,请选择未来14天的日期", 0, 403);
        }
        $vacationDate = date("Y-m-d",$current_vacation_time);
        //请假入库
        $is_success = WorkerVacationApplication::createVacationApplication($checkResult['worker_id'],$vacationDate,$vacationType);
        if ($is_success) {
            $result = array(
                'result' => 1,
                "msg" => "您的请假已提交，请耐心等待审批。"
            );
            return $this->send($result, "操作成功");
        } else {
            return $this->send(null,"插入失败", 0, 403);
        }
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
     *   "ret": {
     *       "per_page": 1,
     *       "page_num": 1,
     *       "data": [
     *           {
     *               "leave_type": "休假",
     *               "leave_date": "2015-10-30",
     *               "leave_status": "待审核"
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
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        //判断页码
        if (!isset($param['per_page']) || !intval($param['per_page'])) {
            $param['per_page'] = 1;
        }
        $per_page = intval($param['per_page']);
        //每页显示数据数
        if (!isset($param['page_num']) || !intval($param['page_num'])) {
            $param['page_num'] = 10;
        }
        $page_num = intval($param['page_num']);

        //调取阿姨请假历史情况
        $data = WorkerVacationApplication::getApplicationList($checkResult['worker_id'],$per_page,$page_num);
        $pageData = array();
        if($data['data']){
            foreach($data['data'] as $key => $val){
                $pageData[$key]['leave_type'] = $val['worker_vacation_application_type']==1?"休假":"事假";
                $pageData[$key]['leave_date'] =  date('Y-m-d',$val['worker_vacation_application_start_time']);
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
        return $this->send($ret, "操作成功");
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
    public function actionGetWorkerPlaceById()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }
        $workerInfo = Worker::getWorkerDetailInfo($checkResult['worker_id']);
        $ret = array(
            "live_place" => $workerInfo['worker_live_place']
        );
        return $this->send($ret, "操作成功.");
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
     * @apiParam {String} comment_type 评论类型 【1：满意 2：一般 3：差评】
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
     *       "msg": "操作成功.",
     *       "ret": {
     *           "per_page": 1,
     *           "page_num": 10,
     *           "data": [
     *               {
     *                   "comment_id": "1",
     *                   "comment": "这是第一条评论类型为评论",
     *                   "comment_date": "2015-10-27"
     *               }
     *           ]
     *       }
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function  actionGetWorkerComment()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        //判断评论类型
        if (!isset($param['comment_type']) || !intval($param['comment_type']) || !in_array($param['comment_type'], array(1, 2, 3))) {
            return $this->send(null, "评论类型不正确", 0, 403);
        }
        //判断页码
        if (!isset($param['per_page']) || !intval($param['per_page'])) {
            $param['per_page'] = 1;
        }
        $per_page = intval($param['per_page']);
        //每页显示数
        if (!isset($param['page_num']) || !intval($param['page_num'])) {
            $param['page_num'] = 10;
        }
        $page_num = intval($param['page_num']);
        
        //获取数据
        try{
            $commentList = CustomerComment::getCustomerCommentworkerlist($checkResult['worker_id'],$param['comment_type'],$per_page,$page_num);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        $ret = [
            'per_page'=>$per_page,
            'page_num'=>$page_num,
            'data'=>[
                [
                "comment_id" => '1',
                "comment" => "这是第一条评论类型为" . $param['comment_type'] . "评论",
                'comment_date' => date('Y-m-d')
                ],
                [
                    "comment_id" => '1',
                    "comment" => "这是第二条评论类型为" . $param['comment_type'] . "评论",
                    'comment_date' => date('Y-m-d')
                ],
                [
                    "comment_id" => '1',
                    "comment" => "这是第三条评论类型为" . $param['comment_type'] . "评论",
                    'comment_date' => date('Y-m-d')
                ]
            ]
        ];
        return $this->send($ret, "操作成功.");
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
     *   "msg": "操作成功.",
     *   "ret": {
     *       "per_page": 1,
     *       "page_num": 10,
     *       "data": [
     *           {
     *               "complaint_content": null,
     *               "complaint_time": "1970-01-01 08:00:00"
     *           }
     *       ]
     *   }
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function  actionGetWorkerComplain()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        $checkResult['worker_id'] = 123;
        //判断页码
        if (!isset($param['per_page']) || !intval($param['per_page'])) {
            $param['per_page'] = 1;
        }
        $per_page = intval($param['per_page']);
        //每页显示数据量
        if (!isset($param['page_num']) || !intval($param['page_num'])) {
            $param['page_num'] = 10;
        }
        $page_num = intval($param['page_num']);
        try{
            $conplainList = OrderComplaint::getWorkerComplain($checkResult['worker_id']);
            if($conplainList){
                foreach($conplainList as $key=>$val){
                    $conplainList[$key]['complaint_time'] = date('Y-m-d H:i:s',$val['complaint_time']);
                }
            }
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        //数据返回
        $ret = [
            'per_page'=>$per_page,
            'page_num'=>$page_num,
            'data'  => $conplainList
        ];
        return $this->send($ret, "操作成功.");
    }

    /**
     * @api {GET} /worker/get-worker-service-info 获取账单阿姨服务信息 (田玉星 95%)
     *
     * @apiDescription 【备注：缺少worker提供阿姨服的家庭数量】
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
     *      "code": "ok",
     *      "msg": "操作成功.",
     *      "ret": [
     *             "worker_name": "张",
     *             "order_count": "60",
     *             "service_family_count": "60",
     *             "worker_income"=>"23888.00"
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
    public function actionGetWorkerServiceInfo()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
         //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }
        //获取数据
        try{
            $service = FinanceSettleApplySearch::getWorkerIncomeSummaryInfoByWorkerId($checkResult['worker_id']);
            $workerInfo = Worker::getWorkerStatInfo($checkResult['worker_id']);
        }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        //数据整理返回
        $ret = [
            "worker_name" => $service['worker_name'],
            "order_count" => $service['all_order_count'],
            "worker_income" => $service['all_worker_money'],
            "service_family_count" => $workerInfo[''],//todo:等待model返回字段
        ];
        return $this->send($ret, "操作成功.");

    }

    /**
     * @api {GET} /worker/get-worker-settle-list 获取阿姨对账单列表 (田玉星 100%)
     * 
     * @apiName actionGetWorkerSettleList 
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} per_page  每页显示多少条.
     * @apiParam {String} page  第几页.
     * @apiParam {String} [platform_version] 平台版本号.
     *
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-bill-list
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *      "code": "ok",
     *      "msg": "操作成功.",
     *      "ret": [
     *      {
     *         "settle_id"=>"32"
     *         'settle_type' =>"1",
     *         'settle_year' =>"2014"
     *         'settle_date'=>'09年07月-09月13日',
     *         'settle_type' =>1,
     *         'order_count'=>'10',
     *         'worker_income'=>'320.00',
     *         'settle_status'=>"1"
     *       }
     *      ]
     *       
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerSettleList()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        $checkResult['worker_id'] = 18475;
        //判断页码
        if (!isset($param['per_page']) || !intval($param['per_page'])) {
            $param['per_page'] = 1;
        }
        $per_page = intval($param['per_page']);
        //每页显示数据量
        if (!isset($param['page_num']) || !intval($param['page_num'])) {
            $param['page_num'] = 10;
        }
        $page_num = intval($param['page_num']);
        //调取model层
        try{
            $settleList = FinanceSettleApplySearch::getSettledWorkerIncomeListByWorkerId($checkResult['worker_id'],$per_page,$page_num);
         }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        
        $settleArr = array();
        foreach($settleList as $key=>$val){
            $settleArr[$key]['settle_id'] = $val['settle_id'];
            $settleArr[$key]['settle_year'] = $val['settle_year'];
            if($val['settle_cycle_type']==1){//周结账单
                $settleArr[$key]['settle_date'] = $val['settle_starttime'].'-'.$val['settle_endtime'];
            }else{
                $settleArr[$key]['settle_date'] = date('m',strtotime($val['settle_starttime']));
            }
            $settleArr[$key]['settle_type'] = $val['settle_cycle'];
            $settleArr[$key]['order_count'] = $val['order_count'];
            $settleArr[$key]['worker_income'] = $val['worker_income'];
            $settleArr[$key]['settle_status'] = $val['settle_status'];
        }
        $ret = [
            'per_page' => $per_page,
            'page_num' => $page_num,
            'data'  => $settleArr
        ];
        return $this->send($ret, "操作成功.");
    }

    /**
     * @api {GET} /worker/get-worker-settle-detail 获取阿姨对账单列表详情 (田玉星 70%)
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionGetWorkerBillDetail
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} bill_id  账单唯一标识.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-bill-detail
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功.",
     *    "ret": {
     *       "title_msg": {
     *           "salary": '6000.00',
     *           "salary_constitute": '3000元(底薪)+2000元(工时服务)+1100元(奖励)-100元(处罚)'
     *       },
     *       "order_list": [
     *           {
     *               "service_time": "9.10 14:00-16:00",
     *               "order_num": "32341334352",
      *              "order_price":"25.00",
     *               "service_addr": "北京市朝阳区光华路SOHO"
     *           }
     *       ]
     *   }
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerBillDetail(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }  
        //数据整理
        $bill_id = intval($param['bill_id']);//账单ID
        
        //TODO:获取账单
        $ret = [
            "title_info"=>[
                'salary'=>'6000.00',
                'salary_constitute'=>"3000元(底薪)+2000元(工时服务)+1100元(奖励)-100元(处罚)"
            ],
            'order_list'=>[
                [ 
                    'service_time'=>'9.10 14:00-16:00',
                    'order_num' =>'32341334352',
                    "order_price"=>"25.00",
                    'service_addr'=>'北京市朝阳区光华路SOHO'
                ],
                [ 
                    'service_time'=>'9.11 14:00-16:00',
                    'order_num' =>'32341334352',
                    "order_price"=>"25.00",
                    'service_addr'=>'北京市朝阳区建外SOHO东区'
                ],
            ]
        ];
        return $this->send($ret, "操作成功.");
    }
    /**
     * @api {GET} /worker/get-worker-tasktime-list 获取阿姨工时列表 (田玉星 70%)
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionGetWorkerTasktimeList
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} bill_id  账单唯一标识.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-tasktime-list
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功.",
     *   "ret": [
     *      {
     *         "service_time": "9.10 14:00-16:00",
     *         "order_price": "25.00",
     *         "order_num": "32341334352",
     *         "service_addr": "北京市朝阳区光华路SOHO"
     *        }
     *      ]
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerTasktimeList(){
         $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }  
        //数据整理
        $bill_id = intval($param['bill_id']);//账单ID
        
        //获取工时列表
        $ret = [
            [
            "service_time" => "9.10 14:00-16:00",
            "order_price" => "25.00",
             'order_num' =>'32341334352',
            "service_addr" => "北京市朝阳区光华路SOHO"
            ]
        ];
        return $this->send($ret, "操作成功.");
    }
    
    /**
     * @api {GET} /worker/get-worker-taskreward-list 获取阿姨奖励列表 (田玉星 100%)
     * 
     * @apiName actionGetWorkerTaskrewardList
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} settle_id  账单唯一标识.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-taskreward-list
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功.",
     *   "ret": [
     *       {
     *           "reward_money": "50.00",
     *           "reward_des": "每个月请假不超过4天"
     *       }
     *   ]
     * }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerTaskrewardList(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        //数据整理
        $settle_id = intval($param['settle_id']);//账单ID
        if(!$settle_id){
            return $this->send(null, "账单唯一标识错误", 0, 403);
        }
        try{
            //获取任务奖励列表
            $rewardList = FinanceSettleApplySearch::getTaskArrayBySettleId($settle_id);
            $ret = array();
            if($rewardList){
                foreach($rewardList as $key=>$val){
                    $ret[$k]['reward_money'] = $val['task_money'];
                    $ret[$k]['reward_des'] = $val['task_des'];
                }
            }
         }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        return $this->send($ret, "操作成功.");
    }
    
    /**
     * @api {GET} /worker/get-worker-punish-list 获取阿姨受处罚列表 (田玉星 90%)
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionGetWorkerPunishList
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} bill_id  账单唯一标识.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/get-worker-punish-list
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功.",
     *   "ret": [
     *      {
     *         "punish_date": "2015.09.08",
     *         "punish_money": "25.00",
     *        }
     *      ]
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionGetWorkerPunishList(){
        $param = Yii::$app->request->get() or $param =  json_decode(Yii::$app->request->getRawBody(),true);
//        //检测阿姨是否登录
//        $checkResult = $this->checkWorkerLogin($param);
//        if(!$checkResult['code']){
//            return $this->send(null, $checkResult['msg'], 0, 403);
//        }
        //数据整理
        $settle_id = 1;//;//账单ID
        if(!$settle_id){
            return $this->send(null, "账单唯一标识错误", 0, 403);
        }
        try{
            //获取任务奖励列表
            $punishList = FinanceSettleApplySearch::getDeductionArrayBySettleId($settle_id);
            $ret = array();
            if($punishList){
                foreach($punishList as $key=>$val){
                    $ret[$k]['punish_money'] = $val['task_money'];
                    $ret[$k]['punish_des'] = $val['task_des'];
                }
            }
         }catch (\Exception $e) {
            return $this->send(null, "boss系统错误", 1024, 403);
        }
        //获取受处罚列表
        $ret = [
            [
            "punish_date" => "2015.09.08",
            "punish_money" => "25.00",
            "punish_reason" =>"打扫不干净"
            ]
        ];
        return $this->send($ret, "操作成功.");
    }
    
    /**
     * @api {PUT} /worker/worker-bill-confirm 确定账单无误 (田玉星 70%)
     * 
     * @apiDescription 【备注：等待model底层支持】
     * 
     * @apiName actionWorkerBillConfirm
     * @apiGroup Worker
     * 
     * @apiParam {String} access_token    阿姨登录token
     * @apiParam {String} bill_id  账单唯一标识.
     * @apiParam {String} [platform_version] 平台版本号.
     * 
     * @apiSampleRequest http://dev.api.1jiajie.com/v1/worker/worker-bill-confirm
     * 
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *   "code": 1,
     *   "msg": "操作成功.",
     *   "ret": {
     *         "result": "1",
     *         "msg": "账单已确认",
     *        }
     *   }
     *
     * @apiErrorExample Error-Response:
     *  HTTP/1.1 404 Not Found
     *  {
     *      "code":"error",
     *      "msg": "用户认证已经过期,请重新登录"
     *  }
     */
    public function actionWorkerBillConfirm(){
        $param =  json_decode(Yii::$app->request->getRawBody(),true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }
        //数据整理
        $bill_id = intval($param['bill_id']);//账单ID
        
        //账单
        $ret = [
            "result" => "1",
            "msg" => "账单已确认"
        ];
        return $this->send($ret, "操作成功.");
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
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        }  
        //数据整理
        $workerInfo = Worker::getWorkerDetailInfo($checkResult['worker_id']);
        $ret = [
            "worker_name" => $workerInfo['worker_name'],
            "worker_phone" => $workerInfo['worker_phone'],
            "head_url" => $workerInfo['worker_photo'],
            "worker_identity" => $workerInfo['worker_identity_description'],//身份
            "worker_role" => $workerInfo["worker_type_description"],
            'worker_start' => $workerInfo["worker_star"],
            'total_money' => $workerInfo['worker_stat_order_money'],
            "personal_skill" => WorkerSkill::getWorkerSkill($checkResult['worker_id']),
        ];
        return $this->send($ret, "阿姨信息查询成功");
    }
    
    
    /** 
     * @api {get} /mobileapidriver2/system_news  通知中心(田玉星0%)
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
     * @api {get} /worker/worker-leave  查看请假情况 (李勇80%)
     * @apiName actionWorkerLeave
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} type 请假类型
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
     *          "workerLeaveList": ["2015-09-13","2015-09-16",],
     *      }
     * }
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
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        //检测阿姨是否登录
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        if (!isset($param['type']) || !$param['type'] || !in_array($param['type'], array(1, 2))) {
            return $this->send(null, "请选择请假类型", 0, 403);
        }
        $worker_id = $checkResult['worker_id'];
        $type = $param['type'];
//        try{
//            $ret= WorkerVacationApplication::getApplicationTimeLine($worker_id,$type);
//        }catch (\Exception $e) {
//            return $this->send(null, "boss系统错误", 1024, 403);
//        }
        $ret = [
            "result" => 1,
            "msg" => "ok",
            "titleMsg" => "您本月已请假0天，本月剩余请假2天",
            "orderTimeList" => ["2015-09-14", "2015-09-15"],
            "workerLeaveList" => ["2015-09-14", "2015-09-15"]

        ];
        return $this->send($ret, "操作成功", 1);
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
     * @api {get} /worker/task-doing  获得进行中的任务列表 (李勇70%)
     * @apiName actionTaskDoing
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
     *      [
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "worker_task_already": "任务已经完成次数"
     *      },
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "worker_task_already": "任务已经完成次数"
     *      }
     *      ]
     *      }
     * }
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
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        $worker_id = $checkResult['worker_id'];
//        try{
//            $ret= WorkerVacationApplication::getApplicationTimeLine($worker_id);
//        }catch (\Exception $e) {
//            return $this->send(null, "boss系统错误", 1024, 403);
//        }
        $ret = [
                [
                    "id"=> "任务id",
                    "worker_task_name"=> "任务名称",
                    "worker_task_start"=> "任务开始时间",
                    "worker_task_end"=> "任务结束时间",
                    "worker_task_reward_value"=> "任务奖励值",
                    "worker_task_conditions"=> "任务需要完成次数",
                    "worker_task_already"=> "任务已经完成次数"

                ],
                [
                    "id"=> "任务id2",
                    "worker_task_name"=> "任务名称2",
                    "worker_task_start"=> "任务开始时间2",
                    "worker_task_end"=> "任务结束时间2",
                    "worker_task_reward_value"=> "任务奖励值2",
                    "worker_task_conditions"=> "任务需要完成次数2",
                    "worker_task_already"=> "任务已经完成次数2"

                ]
           ];
        if(empty($ret)){
              return $this->send(null, "您没有任务哦", 0);
        }
        return $this->send($ret, "操作成功", 1);
    }
    
    
   
     /**
     * @api {get} /worker/task-done  获得已完成的任务列表 (李勇70%)
     * @apiName actionTaskDone
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
     *      [
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "worker_task_already": "任务已经完成次数"
     *      },
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "worker_task_already": "任务已经完成次数"
     *      }
     *      ]
     *      }
     * }
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
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        $worker_id = $checkResult['worker_id'];
//        try{
//            $ret= WorkerVacationApplication::getApplicationTimeLine($worker_id);
//        }catch (\Exception $e) {
//            return $this->send(null, "boss系统错误", 1024, 403);
//        }
        $ret = [
                [
                    "id"=> "任务id",
                    "worker_task_name"=> "任务名称",
                    "worker_task_start"=> "任务开始时间",
                    "worker_task_end"=> "任务结束时间",
                    "worker_task_reward_value"=> "任务奖励值",
                    "worker_task_conditions"=> "任务需要完成次数",
                    "worker_task_already"=> "任务已经完成次数"

                ],
                [
                    "id"=> "任务id2",
                    "worker_task_name"=> "任务名称2",
                    "worker_task_start"=> "任务开始时间2",
                    "worker_task_end"=> "任务结束时间2",
                    "worker_task_reward_value"=> "任务奖励值2",
                    "worker_task_conditions"=> "任务需要完成次数2",
                    "worker_task_already"=> "任务已经完成次数2"

                ]
           ];
        if(empty($ret)){
              return $this->send(null, "您没有任务哦", 0);
        }
        return $this->send($ret, "操作成功", 1);
    }
    
     /**
     * @api {get} /worker/task-fail  获得已失败的任务列表 (李勇70%)
     * @apiName actionTaskFail
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
     *      [
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "worker_task_already": "任务已经完成次数"
     *      },
     *      {
     *          "id": "任务id",
     *          "worker_task_name": "任务名称",
     *          "worker_task_start": "任务开始时间",
     *          "worker_task_end": "任务结束时间",
     *          "worker_task_reward_value": "任务奖励值",
     *          "worker_task_conditions": "任务需要完成次数",
     *          "worker_task_already": "任务已经完成次数"
     *      }
     *      ]
     *      }
     * }
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
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        $worker_id = $checkResult['worker_id'];
//        try{
//            $ret= WorkerVacationApplication::getApplicationTimeLine($worker_id);
//        }catch (\Exception $e) {
//            return $this->send(null, "boss系统错误", 1024, 403);
//        }
        $ret = [
                [
                    "id"=> "任务id",
                    "worker_task_name"=> "任务名称",
                    "worker_task_start"=> "任务开始时间",
                    "worker_task_end"=> "任务结束时间",
                    "worker_task_reward_value"=> "任务奖励值",
                    "worker_task_conditions"=> "任务需要完成次数",
                    "worker_task_already"=> "任务已经完成次数"

                ],
                [
                    "id"=> "任务id2",
                    "worker_task_name"=> "任务名称2",
                    "worker_task_start"=> "任务开始时间2",
                    "worker_task_end"=> "任务结束时间2",
                    "worker_task_reward_value"=> "任务奖励值2",
                    "worker_task_conditions"=> "任务需要完成次数2",
                    "worker_task_already"=> "任务已经完成次数2"

                ]
           ];
        if(empty($ret)){
              return $this->send(null, "您没有任务哦", 0);
        }
        return $this->send($ret, "操作成功", 1);
    }

    /**
     * @api {get} /worker/check-task  查看任务的详情 (李勇70%)
     * @apiName actionCheckTask
     * @apiGroup Worker
     *
     * @apiParam {String} access_token    阿姨登录 token.
     * @apiParam {String} task_id    任务id
     * @apiParam {String} platform_version 平台版本号.
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
        $checkResult = $this->checkWorkerLogin($param);
        if(!$checkResult['code']){
            return $this->send(null, $checkResult['msg'], 0, 403);
        } 
        $worker_id = $checkResult['worker_id'];
        $task_id = $param['task_id'];
//        try{
//            $ret= WorkerVacationApplication::getApplicationTimeLine($worker_id,$task_id);
//        }catch (\Exception $e) {
//            return $this->send(null, "boss系统错误", 1024, 403);
//        }
        $ret = [
                [
                    "id"=> "任务id",
                    "worker_task_name"=> "任务名称",
                    "worker_task_description"=> "任务描述",
                    "worker_task_start"=> "任务开始时间",
                    "worker_task_end"=> "任务结束时间",
                    "worker_task_reward_value"=> "任务奖励值",
                    "worker_task_conditions"=> "任务需要完成次数",
                    "worker_task_already"=> "任务已经完成次数",
                    "settled"=>[
                        [
                            "order_id"=> "订单id",
                            "order_time"=> "订单时间",
                            "work_hours"=> "工时"
                        ],
                         [
                            "order_id"=> "订单id2",
                            "order_time"=> "订单时间2",
                            "work_hours"=> "工时2"
                        ]
                    ]

                ]
           ];
        if(empty($ret)){
              return $this->send(null, "查看任务失败", 0);
        }
        return $this->send($ret, "操作成功", 1);
    }
}


?>
