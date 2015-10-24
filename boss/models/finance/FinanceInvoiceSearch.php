<?php

namespace boss\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\FinanceInvoice;

/**
 * FinanceInvoiceSearch represents the model behind the search form about `common\models\FinanceInvoice`.
 */
class FinanceInvoiceSearch extends FinanceInvoice
{
    public function rules()
    {
        return [
            [['id', 'finance_invoice_serial_number', 'pay_channel_pay_id', 'finance_invoice_pay_status', 'admin_confirm_uid', 'finance_invoice_enrolment_time', 'finance_invoice_status', 'finance_invoice_check_id', 'finance_invoice_number', 'finance_invoice_district_id', 'classify_id', 'classify_title', 'is_del'], 'integer'],
            [['finance_invoice_customer_tel', 'finance_invoice_worker_tel', 'pay_channel_pay_title', 'finance_invoice_title', 'finance_invoice_address', 'finance_invoice_corp_email', 'finance_invoice_corp_address', 'finance_invoice_corp_name', 'create_time'], 'safe'],
            [['finance_invoice_money', 'finance_invoice_service_money'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceInvoice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_invoice_serial_number' => $this->finance_invoice_serial_number,
            'pay_channel_pay_id' => $this->pay_channel_pay_id,
            'finance_invoice_pay_status' => $this->finance_invoice_pay_status,
            'admin_confirm_uid' => $this->admin_confirm_uid,
            'finance_invoice_enrolment_time' => $this->finance_invoice_enrolment_time,
            'finance_invoice_money' => $this->finance_invoice_money,
            'finance_invoice_status' => $this->finance_invoice_status,
            'finance_invoice_check_id' => $this->finance_invoice_check_id,
            'finance_invoice_number' => $this->finance_invoice_number,
            'finance_invoice_service_money' => $this->finance_invoice_service_money,
            'finance_invoice_district_id' => $this->finance_invoice_district_id,
            'classify_id' => $this->classify_id,
            'classify_title' => $this->classify_title,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_invoice_customer_tel', $this->finance_invoice_customer_tel])
            ->andFilterWhere(['like', 'finance_invoice_worker_tel', $this->finance_invoice_worker_tel])
            ->andFilterWhere(['like', 'pay_channel_pay_title', $this->pay_channel_pay_title])
            ->andFilterWhere(['like', 'finance_invoice_title', $this->finance_invoice_title])
            ->andFilterWhere(['like', 'finance_invoice_address', $this->finance_invoice_address])
            ->andFilterWhere(['like', 'finance_invoice_corp_email', $this->finance_invoice_corp_email])
            ->andFilterWhere(['like', 'finance_invoice_corp_address', $this->finance_invoice_corp_address])
            ->andFilterWhere(['like', 'finance_invoice_corp_name', $this->finance_invoice_corp_name])
            ->andFilterWhere(['like', 'create_time', $this->create_time]);

        return $dataProvider;
    }
}
