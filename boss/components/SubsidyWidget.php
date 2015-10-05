<?php
namespace boss\components;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use boss\models\FinanceWorkerNonOrderIncomeSearch;

/**
 * Description of SubsidyWidget
 *
 * @author weibeinan
 */
class SubsidyWidget extends \yii\widgets\InputWidget{
    //put your code here
    
    public $html;//最后输出的html
    
    public $model;
    
    public $attribute = 'finance_settle_apply_id';
    
    public function init(){
        parent::init();
        $this->getSubsidyDetail($this->model->id);
    }
    
    private function getSubsidyDetail($settleApplyId){
        $nonIncomeArr = FinanceWorkerNonOrderIncomeSearch::find()->where(['finance_settle_apply_id'=>$settleApplyId])->all();
        foreach($nonIncomeArr as $nonIncome){
            $this->html.=$nonIncome->finance_worker_non_order_income_type_des.':'.$nonIncome->finance_worker_non_order_income.'|';
        }
    }
    public function run(){
        return $this->render('SubsidyWidget', ['html'=>$this->html]);
    }
}
