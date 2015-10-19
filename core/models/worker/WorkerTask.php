<?php
namespace core\models\worker;

class WorkerTask extends \common\models\WorkerTask
{
    /**
     * 条件名
     */
    const CONDITION_NAME = [
        1=>'主动接单',
        2=>'增值服务',
        3=>'复购率',
        4=>'用户投诉',
        5=>'用户好评',
        6=>'完成工时',
        7=>'取消订单',
        8=>'拒绝订单',
        9=>'迟到订单',
    ];
    /**
     * 条件判断符
     */
    const CONDITION_JUDGE = [
        '<'=>'小于',
        '<='=>'小于等于',
        '='=>'等于',
        '>='=>'大于等于',
        '>'=>'大于',
        '<>'=>'不等于'
    ];
    /**
     * 条件处理周期
     */
    const TASK_CYCLE = [
        '1'=>'月',
        '2'=>'周',
        '3'=>'天'
    ];
    
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['worker_task_name', 'worker_task_start', 'worker_task_end', 'worker_task_cycle'], 'required'],
            [['conditions'], 'validateConditions'],
        ]);
    }
    
    public function validateConditions($attribute, $params)
    {
//         if (!$this->hasErrors()) {
//             $this->addError($attribute, \Yii::t('app', '条件错误.'));
//         }
    }
    
    public function getConditions()
    {
        $names = self::CONDITION_NAME;
        $data = json_decode($this->worker_task_conditions, true);
        foreach ($data as $id=>$item){
            $data[$id]['name'] = $names[$id];
        }
        return $data;
    }
    public function setConditions($data)
    {
        $this->worker_task_conditions = json_encode($data);
    }
    /**
     * 完整条件，包含所有已设置和未设置的
     */
    public function getFullConditions()
    {
        $res = [];
        $cons = $this->getConditions();
        $names = self::CONDITION_NAME;
        foreach ($names as $id=>$name){
            $res[$id] = [
                'id'=>$id,
                'name'=>$name,
                'judge'=>'',
                'value'=>'',
            ];
            if(isset($cons[$id])){
                $res[$id] = array_merge($res[$id],[
                    'judge'=>$cons[$id]['judge'],
                    'value'=>$cons[$id]['value'],
                ]);
            }
        }
        return $res;
    }
}