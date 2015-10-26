<?php
namespace api\controllers;

use Yii;
use \core\models\customer\CustomerAccessToken;
use \core\models\worker\Worker;
use \core\models\worker\WorkerSkill;
use \core\models\worker\WorkerAccessToken;
use \core\models\Operation\CoreOperationShopDistrictCoordinate;

class WorkerController extends \api\components\Controller
{

    /**
     * @api {GET} /worker/worker-info 查看阿姨信息 (田玉星 80%)
     *
     * @apiDescription 【备注：阿姨身份、星级、个人技能等待model底层】
     *
     * @apiName WorkerInfo
     * @apiGroup Worker
     *
     * @apiParam {String} access_token 阿姨登录token/用户登录token
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

        $workerId = 0;
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            if (!isset($param['access_token']) || !$param['access_token'] || !isset($param['worker_id']) || !$param['worker_id'] || !CustomerAccessToken::checkAccessToken($param['access_token'])) {
                return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
            } else {
                // 用户登录，按阿姨id获取阿姨信息
                $workerId = $param['worker_id'];
            }
        } else {
            // 阿姨登陆，获取阿姨信息
            $worker = WorkerAccessToken::getWorker($param['access_token']);
            if (!empty($worker)) {
                $workerId = $worker->id;
            }
        }

        if ($workerId > 0) {
            $workerInfo = Worker::getWorkerDetailInfo($workerId);
            //数据整理
            $ret = [
                "worker_name" => $workerInfo['worker_name'],
                "worker_phone" => $workerInfo['worker_phone'],
                "head_url" => $workerInfo['worker_photo'],
                "worker_identity" => $workerInfo['worker_identity_description'],//身份
                "worker_role" => $workerInfo["worker_type_description"],
                'worker_start' => 4.5,
                'total_money' => 1000,
                "personal_skill" => WorkerSkill::getWorkerSkill($workerId),
            ];
            return $this->send($ret, "阿姨信息查询成功");
        } else {
            return $this->send(null, "阿姨不存在.", 0, 403);
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
    public function actionHandleWorkerLeave()
    {
        $param = Yii::$app->request->post() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
            $attributes = [];
            $attributes['worker_id'] = $worker->id;
            if (!isset($param['type']) || !$param['type'] || !in_array($param['type'], array(1, 2))) {
                return $this->send(null, "数据不完整,请选择请假类型", 0, 403);
            }
            $attributes['worker_vacation_type'] = $param['type'];

            //请假时间范围判断
            if (!isset($param['date']) || !$param['date']) {
                return $this->send(null, "数据不完整,请选择请假时间", 0, 403);
            }
            $vacation_start_time = time();
            $vacation_end_time = strtotime(date('Y-m-d', strtotime("+14 days")));
            $current_vacation_time = strtotime($param['date']);
            if ($current_vacation_time <= $vacation_start_time || $current_vacation_time > $vacation_end_time) {
                return $this->send(null, "请假时间不在请假时间范围内,请选择未来14天的日期", 0, 403);
            }
            $attributes['worker_vacation_start_time'] = $attributes['worker_vacation_finish_time'] = $current_vacation_time;
            //请假入库
            $workerVacation = new \core\models\order\WorkerVacation();
            $is_success = $workerVacation->createNew($attributes);
            if ($is_success) {
                $result = array(
                    'result' => 1,
                    "msg" => "您的请假已提交，请耐心等待审批。"
                );
                return $this->send($result, "操作成功");
            } else {
                return $this->send(null, $workerVacation->errors, 0, 403);
            }
        } else {
            return $this->send(null, "阿姨不存在.", 0, 403);
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
     * @apiParam {String} per_page   页码数
     * @apiParam {String} page_num   每页显示数
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
    public function actionHandleWorkerLeaveHistory()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
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
            $ret = [
                [
                    'leave_type' => '休假',
                    'leave_date' => '2015-10-01',
                    'leave_status' => '待审批'
                ],
                [
                    'leave_type' => '事假',
                    'leave_date' => '2015-10-11',
                    'leave_status' => '成功'
                ],
                [
                    'leave_type' => '事假',
                    'leave_date' => '2015-10-12',
                    'leave_status' => '失败'
                ],
            ];
            return $this->send($ret, "操作成功");
        } else {
            return $this->send(null, "阿姨不存在.", 0, 403);
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
    public function actionGetWorkerPlaceById()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", "error", 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
            $workerInfo = Worker::getWorkerDetailInfo($worker->id);
            $ret = array(
                "result" => '1',
                "live_place" => $workerInfo['worker_live_place']
            );
            return $this->send($ret, "操作成功.");
        } else {
            return $this->send(null, "阿姨不存在.", 0, 404);
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
     * @apiParam {String} comment_type 评论类型 【1：满意 2：一般 3：差评】
     * @apiParam {String} per_page   页码数
     * @apiParam {String} page_num   每页显示数
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
    public function  actionGetWorkerComment()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

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

        //判断用户是否存在
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!$worker || !$worker->id) {
            return $this->send(null, "阿姨不存在.", 0, 404);
        }
        //数据返回
        $ret = [
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
            ],
        ];
        return $this->send($ret, "操作成功.");
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
     * @apiParam {String} per_page    第几页
     * @apiParam {String} page_num   每页显示的数据数量
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
    public function  actionGetWorkerComplain()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

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

        //判断用户是否存在
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!$worker || !$worker->id) {
            return $this->send(null, "阿姨不存在.", 0, 404);
        }
        //数据返回
        $ret = [
            [
                "comment_id" => '1',
                "comment" => "这是第一条投诉",
                'comment_date' => date('Y-m-d')
            ],
            [
                "comment_id" => '1',
                "comment" => "这是第二条投诉",
                'comment_date' => date('Y-m-d')
            ],
            [
                "comment_id" => '1',
                "comment" => "这是第三条投诉",
                'comment_date' => date('Y-m-d')
            ],
        ];
        return $this->send($ret, "操作成功.");
    }

    /**
     * @api {GET} /worker/get-worker-service-info 获取阿姨服务信息 (田玉星 80%)
     *
     * @apiDescription 【备注：等待model底层支持】
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
     *             "service_count": "60",
     *             "service_family_count": "60",
     *              "total_income"=>"23888.00"
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
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!$worker || !$worker->id) {
            return $this->send(null, "阿姨不存在", 0, 403);
        }

        //TODO:通过MODEL获取阿姨服务信息d378a0c76007a68888ac300e8a821f29
        $ret = [
            "worker_name" => "张",
            "service_count" => "60",
            "service_family_count" => "60",
            "salary" => "23888.00"
        ];
        return $this->send($ret, "操作成功.");

    }

    /**
     * @api {GET} /worker/get-worker-bill-list 获取阿姨服务信息 (田玉星 60%)
     *
     * @apiDescription 【备注：等待model底层支持】
     *
     * @apiName actionGetWorkerBillList
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
     *             "worker_name": "张",
     *             "service_count": "60",
     *             "service_family_count": "60",
     *              "total_income"=>"23888.00"
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
    public function actionGetWorkerBillList()
    {
        $param = Yii::$app->request->get() or $param = json_decode(Yii::$app->request->getRawBody(), true);
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }

        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!$worker || !$worker->id) {
            return $this->send(null, "阿姨不存在", 0, 403);
        }
        //获取阿姨身份:兼职/全职
        $workerInfo = Worker::getWorkerInfo($worker->id);
        $identify = $workerInfo['worker_identity_id'];

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
        $ret = [
            [
                'bill_type' => "1",
                'bill_explain' => "这是一个周期账单说明",
                'bill_date' => '09年07月-09月13日',
                'order_count' => '10',
                'salary' => '320.00',
                'balance_status' => "1"
            ],
            [
                'bill_type' => "2",
                'bill_explain' => "这是一个月结账单说明",
                'bill_date' => '8月',
                'order_count' => '10',
                'salary' => '320.00',
                'balance_status' => "2"
            ]
        ];
        return $this->send($ret, "操作成功.");
    }

    /**
     * @api {get} /mobileapidriver2/get_driver_info 个人中心首页 (田玉星0%)
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
        if (!isset($param['access_token']) || !$param['access_token'] || !WorkerAccessToken::checkAccessToken($param['access_token'])) {
            return $this->send(null, "用户认证已经过期,请重新登录", 0, 403);
        }
        $worker = WorkerAccessToken::getWorker($param['access_token']);
        if (!empty($worker) && !empty($worker->id)) {
            if (!isset($param['type']) || !$param['type'] || !in_array($param['type'], array(1, 2))) {
                return $this->send(null, "请选择请假类型", 0, 403);
            }
            $worker_id = $worker->id;
            $type = $param['type'];
            //$ret= Worker::getWorkerLeave($worker_id,$type);
            $ret = [
                "result" => 1,
                "msg" => "ok",
                "titleMsg" => "您本月已请假0天，本月剩余请假2天",
                "orderTimeList" => ["2015-09-14", "2015-09-15"],
                "workerLeaveList" => ["2015-09-14", "2015-09-15"]

            ];
            return $this->send($ret, "操作成功", 1);

        } else {
            return $this->send(null, "阿姨不存在.", 0, 403);
        }
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
     * 获得所有该阿姨已经完成未对账订单
     */


}


?>
