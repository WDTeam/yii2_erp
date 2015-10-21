<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceRefund;

/**
 * FinanceRefundSearch represents the model behind the search form about `common\models\FinanceRefund`.
 */
class FinanceRefundSearch extends FinanceRefund
{
	
	public $statusstype;
	
    public function rules()
    {
    	//finance_refund_pay_create_time
        return [
            [['id', 'finance_refund_stype', 'finance_pay_channel_id', 'finance_refund_pay_status','finance_refund_county_id','finance_refund_city_id','finance_refund_province_id','finance_refund_shop_id','finance_refund_pop_nub','finance_order_channel_id','finance_refund_worker_id',  'is_del'], 'integer'],
            [['finance_refund_tel','isstatus','statusstype','create_time','finance_refund_check_name','finance_refund_reason', 'finance_pay_channel_title', 'finance_refund_pay_flow_num', 'finance_refund_worker_tel'], 'safe'],
            [['finance_refund_money', 'finance_refund_discount'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search()
    {
        $query = FinanceRefund::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /*  if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }  */
         if($this->statusstype=='index'){
         $infoname='!=';	
        }else {
         $infoname='like';	
        } 
        $query->andFilterWhere([
            'id' => $this->id,
        	'finance_refund_pop_nub' => $this->finance_refund_pop_nub,
        	'finance_order_channel_id' => $this->finance_order_channel_id,
            'finance_refund_money' => $this->finance_refund_money,
            'finance_refund_stype' => $this->finance_refund_stype,
            'finance_refund_discount' => $this->finance_refund_discount,
            'finance_refund_pay_create_time' => $this->finance_refund_pay_create_time,
            'finance_pay_channel_id' => $this->finance_pay_channel_id,
            'finance_refund_pay_status' => $this->finance_refund_pay_status,
            'finance_refund_worker_id' => $this->finance_refund_worker_id,	
        	'finance_refund_shop_id' => $this->finance_refund_shop_id,
        	'finance_refund_province_id' => $this->finance_refund_province_id,
        	'finance_refund_city_id' => $this->finance_refund_city_id,
        	'finance_refund_county_id' => $this->finance_refund_county_id,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_refund_tel', $this->finance_refund_tel])
            ->andFilterWhere(['>=', 'create_time', strtotime($this->create_time)])
            ->andFilterWhere([$infoname, 'isstatus', $this->isstatus])
            ->andFilterWhere(['like', 'finance_refund_check_name', $this->finance_refund_check_name])
            ->andFilterWhere(['like', 'finance_refund_reason', $this->finance_refund_reason])
            ->andFilterWhere(['like', 'finance_pay_channel_title', $this->finance_pay_channel_title])
            ->andFilterWhere(['like', 'finance_refund_pay_flow_num', $this->finance_refund_pay_flow_num])
            ->andFilterWhere(['like', 'finance_refund_worker_tel', $this->finance_refund_worker_tel]);

        return $dataProvider;
    }
}
