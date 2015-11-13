<?php
/**
* 控制器   对账日志查询models
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace boss\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use dbbase\models\finance\FinanceRefund;

/**
 * FinanceRefundSearch represents the model behind the search form about `dbbase\models\FinanceRefund`.
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
        
        
        if($this->create_time){ $statime=strtotime($this->create_time);}else { $statime= null;}
        if($this->create_time_end){$endtime=strtotime($this->create_time_end);}else{$endtime= null; }
        
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
            ->andFilterWhere(['between', 'create_time',$statime,$endtime])
            
            ->andFilterWhere([$infoname, 'isstatus', $this->isstatus])
            ->andFilterWhere(['like', 'finance_refund_check_name', $this->finance_refund_check_name])
            ->andFilterWhere(['like', 'finance_refund_reason', $this->finance_refund_reason])
            ->andFilterWhere(['like', 'finance_pay_channel_title', $this->finance_pay_channel_title])
            ->andFilterWhere(['like', 'finance_refund_pay_flow_num', $this->finance_refund_pay_flow_num])
            ->andFilterWhere(['like', 'finance_refund_worker_tel', $this->finance_refund_worker_tel]);

        return $dataProvider;
    }
}
