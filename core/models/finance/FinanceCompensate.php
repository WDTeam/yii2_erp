<?php

namespace core\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\finance\FinanceCompensate as FinanceCompensateModel;

/**
 * FinanceCompensate represents the model behind the search form about `dbbase\models\finance\FinanceCompensate`.
 */
class FinanceCompensate extends FinanceCompensateModel
{
    const FINANCE_COMPENSATE_REVIEW_INIT = 0;//提出申请
    
    const FINANCE_COMPENSATE_REVIEW_PASSED = 1;//确认打款
    
    const FINANCE_COMPENSATE_REVIEW_FAILED = -1;//不通过
    
    private $financeCompensateStatusArr = [self::FINANCE_COMPENSATE_REVIEW_INIT=>'提出申请，待财务打款确认',
        self::FINANCE_COMPENSATE_REVIEW_PASSED=>'财务已打款确认',
        self::FINANCE_COMPENSATE_REVIEW_FAILED=>'财务不通过',
        ];
    
    const FINANCE_COMPENSATE_DELETE = 1;//已被逻辑删除
    
    const FINANCE_COMPENSATE_NO_DELETE = 0;//未被逻辑删除
    
    public $finance_compensate_starttime;//赔偿申请开始时间
    
    public $finance_compensate_endtime;//赔偿申请结束时间
    
    public function rules()
    {
        return [
            [['finance_complaint_id', 'worker_id', 'customer_id', 'updated_at', 'created_at', 'is_softdel','finance_compensate_status'], 'integer'],
            [['finance_compensate_money','finance_compensate_total_money','finance_compensate_insurance_money','finance_compensate_company_money','finance_compensate_worker_money'], 'number'],
            [['finance_compensate_reason', 'comment','worker_tel','worker_name','customer_name'], 'string'],
            [['finance_compensate_oa_code'], 'string', 'max' => 40],
            [['finance_compensate_coupon','finance_compensate_coupon_money'], 'string', 'max' => 150],
            [['finance_compensate_proposer', 'finance_compensate_auditor'], 'string', 'max' => 20]
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FinanceCompensate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_complaint_id' => $this->finance_complaint_id,
            'worker_id' => $this->worker_id,
            'customer_id' => $this->customer_id,
            'finance_compensate_money' => $this->finance_compensate_money,
            'finance_compensate_status' => $this->finance_compensate_status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'is_softdel' => $this->is_softdel,
        ]);

        $query->andFilterWhere(['like', 'finance_compensate_oa_code', $this->finance_compensate_oa_code])
                ->andFilterWhere(['like', 'worker_tel1', $this->worker_tel])
            ->andFilterWhere(['like', 'finance_compensate_coupon', $this->finance_compensate_coupon])
            ->andFilterWhere(['like', 'finance_compensate_reason', $this->finance_compensate_reason])
            ->andFilterWhere(['like', 'finance_compensate_proposer', $this->finance_compensate_proposer])
            ->andFilterWhere(['like', 'finance_compensate_auditor', $this->finance_compensate_auditor])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
    
    public function  getFinanceCompensateStatusDes($status){
        return $this->financeCompensateStatusArr[$status];
    }
    
    public static function getFinanceCompensateListByWorkerId($worker_id,$start_time,$end_time){
        return self::find()->where(['worker_id'=>$worker_id,'finance_compensate_status'=>self::FINANCE_COMPENSATE_REVIEW_PASSED])
                ->andFilterWhere(['between','updated_at',$start_time,$end_time])
                ->all();
    }
    
    public function attributeLabels()
    {
        $parentAttributeLabels = parent::attributeLabels();
        $addAttributeLabels = [
            'finance_compensate_starttime' => Yii::t('app', '赔偿申请开始时间'),
            'finance_compensate_endtime' => Yii::t('app', '赔偿申请结束时间'),
        ];
        return array_merge($addAttributeLabels,$parentAttributeLabels);
    }
}
