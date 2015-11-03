<?php
namespace core\models\order;

use Yii;
use yii\data\ActiveDataProvider;

class OrderResponse extends \dbbase\models\order\OrderResponse
{
	
    /**
     * 响应次数
     * @return multitype:string
     */
    public static function ResponseTimes()
    {
    	return array(
    			'1'=>'1次',
    			'2'=>'2次',
    			'3'=>'3次',
    	);
    }

    /**
     * 接听结果
     * @return multitype:string
     */
    public static function ReplyResult()
    {
    	return array(
    			'1' => '已接听',
    			'2' => '未接听',
    			'3' => '通话中',
    			'4' => '关机',
    	);
    }

    /**
     * 是否响应
     * @return multitype:string
     */
    public static function ResponseOrNot()
    {
    	return array(
    			'1' => '仍需响应',
    			'0' => '无需响应',
    	);
    }

    /**
     * 响应结果
     * @return multitype:string
     */
    public static function ResponseResult()
    {
    	return array(
    			'1' => '改约订单',
    			'2' => '取消订单',
    			'3' => '地址已核实',
    			'4' => '联系三次无法接通取消',
    	);
    }

    /**
     * 保存响应数据
     *
     * @param array  $data  前端选择的数据
     * @return 
     */ 
    public static function saveOrderResponse($data)
    {
        $model = new OrderResponse();

        $model->order_id = $data['order_id'];
        $model->order_operation_user = $data['order_operation_user'];
        $model->order_response_times = $data['order_reply_result'];
        $model->order_reply_result = $data['order_reply_result'];
        $model->order_response_or_not = $data['order_response_or_not'];
        $model->order_response_result = $data['order_response_result'];
        $model->created_at = time();

        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据订单ID获取对应的响应记录
     *
     * @param inter  $order_id  订单记录id
     * @return array
     */ 
    public static function getResponseRecord($order_id = 0)
    {
        $records = OrderResponse::find()
            ->where(['order_id' => $order_id])
            ->asArray()
            ->all();
        $times = OrderResponse::ResponseTimes();
        $reply = OrderResponse::ReplyResult();
        $ornot = OrderResponse::ResponseOrNot();
        $response = OrderResponse::ResponseResult();

        foreach ($records as $key => $val) {
            $val['order_response_times'] = $times[$val['order_response_times']];
            $val['order_reply_result'] = $reply[$val['order_reply_result']];

            if ($val['order_response_or_not'] == 0) {
                $val['order_response_result'] = $response[$val['order_response_result']];
            } elseif ($val['order_response_or_not'] == 1) {
                $val['order_response_result'] = '无结果';
            }

            $val['order_response_or_not'] = $ornot[$val['order_response_or_not']];
            $val['created_at'] = date('Y-m-d H:i:s', $val['created_at']);
            $records[$key] = $val;
        }

        return $records;
    }
}
