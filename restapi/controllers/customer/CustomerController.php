<?php

namespace api\controllers\customer;

use Yii;
use core\models\customer\CustomerAccessToken;
use core\models\customer\CustomerComment;
use core\models\comment\CustomerCommentLevel;
use core\models\comment\CustomerCommentTag;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends \api\components\Controller
{

    /**
     * 根据评价的order_id获取评价详情
     * @date: 2015-10-27
     * @author: peak pan
     * @return:
     * */
    public function actionPostControllerIssetinfo()
    {
        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $level = CustomerComment::issetes($param['order_id']);
        if (!empty($level)) {
            $ret = ['data' => $level, 'commentTag' => true];
            return $this->send($ret, '此订单已经评价');
        } else {
            return $this->send($ret, '此订单可以评价', 1, 200);
        }
    }

    /**
     * 根据阿姨的id获取记录列表
     * @date: 2015-10-27
     * @author: peak pan
     * @return:
     * */
    public function actionPostControllerworkerlist()
    {

        $param = Yii::$app->request->post();
        if (empty($param)) {
            $param = json_decode(Yii::$app->request->getRawBody(), true);
        }
        $level = CustomerComment::getCustomerCommentworkerlist($param['worker_id'], $param['customer_comment_level'], $param['newpage'], $param['countpage']);
        if (!empty($level)) {
            $ret = ['comment' => $level];
            return $this->send($ret, '阿姨评价列表');
        } else {
            return $this->send($ret, '暂无数据', 1, 200);
        }
    }

}
