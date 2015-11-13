<?php
/**
* 控制器  对账查询管理
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
use dbbase\models\finance\FinancePopOrderLog;

/**
 * FinancePopOrderLogSearch represents the model behind the search form about `dbbase\models\FinancePopOrderLog`.
 */
class FinancePopOrderLogSearch extends FinancePopOrderLog
{
    public function rules()
    {
        return [
            [['id', 'finance_pop_order_log_series_succeed_status', 'finance_pop_order_log_series_succeed_status_time', 'finance_pop_order_log_finance_status', 'finance_pop_order_log_finance_status_time', 'finance_pop_order_log_finance_audit', 'finance_pop_order_log_finance_audit_time', 'create_time', 'is_del'], 'integer'],
            [['finance_pay_order_num', 'finance_pop_order_number'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinancePopOrderLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_pop_order_log_series_succeed_status' => $this->finance_pop_order_log_series_succeed_status,
            'finance_pop_order_log_series_succeed_status_time' => $this->finance_pop_order_log_series_succeed_status_time,
            'finance_pop_order_log_finance_status' => $this->finance_pop_order_log_finance_status,
            'finance_pop_order_log_finance_status_time' => $this->finance_pop_order_log_finance_status_time,
            'finance_pop_order_log_finance_audit' => $this->finance_pop_order_log_finance_audit,
            'finance_pop_order_log_finance_audit_time' => $this->finance_pop_order_log_finance_audit_time,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_pay_order_num', $this->finance_pay_order_num])
            ->andFilterWhere(['like', 'finance_pop_order_number', $this->finance_pop_order_number]);

        return $dataProvider;
    }
}
