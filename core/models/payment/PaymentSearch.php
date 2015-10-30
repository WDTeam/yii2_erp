<?php

namespace core\models\payment;

use core\models\order\OrderSearch;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\payment\Payment;

/**
 * PaymentSearch represents the model behind the search form about `\core\models\Payment\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'order_id', 'payment_source', 'payment_mode', 'payment_status', 'payment_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at'], 'integer'],
            [['payment_money', 'payment_actual_money'], 'number'],
            [['payment_source_name', 'payment_transaction_id', 'payment_eo_order_id', 'payment_memo', 'payment_admin_name', 'payment_handle_admin_name', 'payment_verify'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * 获取订单金额总和
     * @param $order_id intger | array
     */
    public static function getOrderSumMoney($order_id)
    {
        //查询支付表数据
        $data = OrderSearch::getOrderExtPayData($order_id);
        //计算需要支付的金额
        $order_pay_money = 0;
        foreach($data as $val)
        {
            $order_pay_money += $val['order_pay_money'];
        }
        return $order_pay_money;
    }

    /**
     * 查询支付状态
     * @param $order_id 订单ID
     * @param int $status 支付状态 1成功,0失败
     * @return array
     */
    public static function searchPayStatus($order_id, $status=1){
        $where = ['order_id'=>$order_id,'payment_status'=>$status];
        return Payment::find()->where($where)->asArray()->one();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Payment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'order_id' => $this->order_id,
            'payment_money' => $this->payment_money,
            'payment_actual_money' => $this->payment_actual_money,
            'payment_source' => $this->payment_source,
            'payment_mode' => $this->payment_mode,
            'payment_status' => $this->payment_status,
            'payment_is_coupon' => $this->payment_is_coupon,
            'admin_id' => $this->admin_id,
            'worker_id' => $this->worker_id,
            'handle_admin_id' => $this->handle_admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]);

        $query->andFilterWhere(['like', 'payment_source_name', $this->payment_source_name])
            ->andFilterWhere(['like', 'payment_transaction_id', $this->payment_transaction_id])
            ->andFilterWhere(['like', 'payment_eo_order_id', $this->payment_eo_order_id])
            ->andFilterWhere(['like', 'payment_memo', $this->payment_memo])
            ->andFilterWhere(['like', 'payment_admin_name', $this->payment_admin_name])
            ->andFilterWhere(['like', 'payment_handle_admin_name', $this->payment_handle_admin_name])
            ->andFilterWhere(['like', 'payment_verify', $this->payment_verify]);

        return $dataProvider;
    }
    
    
    
    public  $finance_record_log_statime; 
    public  $finance_record_log_endtime;
    
    public function searchpaylist()
    {
    	$query = Payment::find();
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			]);
    	$query->andFilterWhere([
    			'id' => $this->id,
    			'customer_id' => $this->customer_id,
    			'order_id' => $this->order_id,
    			'payment_money' => $this->payment_money,
    			'payment_actual_money' => $this->payment_actual_money,
    			'payment_source' => $this->payment_source,
    			'payment_mode' => $this->payment_mode,
    			'payment_status' => $this->payment_status,
    			'payment_is_coupon' => $this->payment_is_coupon,
    			'admin_id' => $this->admin_id,
    			'worker_id' => $this->worker_id,
    			'handle_admin_id' => $this->handle_admin_id,
    			'updated_at' => $this->updated_at
    			]);
    
    	$query->andFilterWhere(['like', 'payment_source_name', $this->payment_source_name])
    	->andFilterWhere(['like', 'payment_transaction_id', $this->payment_transaction_id])
    	->andFilterWhere(['between', 'created_at', $this->finance_record_log_statime,$this->finance_record_log_endtime])
    	->andFilterWhere(['not in', 'payment_transaction_id', $this->payment_transaction_id])
    	->andFilterWhere(['like', 'payment_eo_order_id', $this->payment_eo_order_id])
    	->andFilterWhere(['like', 'payment_memo', $this->payment_memo])
    	->andFilterWhere(['like', 'payment_admin_name', $this->payment_admin_name])
    	->andFilterWhere(['like', 'payment_handle_admin_name', $this->payment_handle_admin_name])
    	->andFilterWhere(['like', 'payment_verify', $this->payment_verify]);
    
    	return $dataProvider;
    } 
    
    
}
