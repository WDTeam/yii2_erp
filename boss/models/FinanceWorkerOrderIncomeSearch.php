<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinanceWorkerOrderIncome;

/**
 * FinanceWorkerOrderIncomeSearch represents the model behind the search form about `common\models\FinanceWorkerOrderIncome`.
 */
class FinanceWorkerOrderIncomeSearch extends FinanceWorkerOrderIncome
{
    public $worker_name;
    
    public $worker_phone;
    
    public $worker_type;
    
    public $order_count;
    
    public $manage_fee;
    
    public $worker_idcard;
    
    //订单收入类型表，包括纯订单收入和订单补贴
    public $orderIncomeType = array('order_money'=>'0','cash_money'=>'1',
            'far_subsidy'=>'2','night_subsidy'=>'3',
            'empty_handed_subsidy'=>'4','channel_bonus'=>'5',
            'small_maintain'=>'6',
        );
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'worder_id', 'order_id', 'finance_worker_order_income_type', 'order_booked_count', 'isSettled', 'finance_settle_apply_id', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_worker_order_income'], 'number'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FinanceWorkerOrderIncome::find();

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
            'worder_id' => $this->worder_id,
            'order_id' => $this->order_id,
            'finance_worker_order_income_type' => $this->finance_worker_order_income_type,
            'finance_worker_order_income' => $this->finance_worker_order_income,
            'order_booked_count' => $this->order_booked_count,
            'isSettled' => $this->isSettled,
            'finance_settle_apply_id' => $this->finance_settle_apply_id,
            'isdel' => $this->isdel,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
    
     public function attributeLabels()
    {
        $parentAttributeLabels = parent::attributeLabels();
        $addAttributeLabels = [
            'worker_idcard' => Yii::t('app', '阿姨身份证号'),
            'worker_name' => Yii::t('app', '阿姨姓名'),
            'worker_phone' => Yii::t('app', '阿姨电话'),
            'worker_type' => Yii::t('app', '阿姨类型'),
            'order_count' => Yii::t('app', '完成单量'),
            'manage_fee' => Yii::t('app', '服务费'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
    
}
