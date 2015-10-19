<?php
namespace core\models\worker;

use yii\base\InvalidParamException;
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
    /**
     * 任务奖励类型
     */
    const REWARD_TYPES = [
        1=>'金额',
        2=>'当月流量',
        3=>'次月流量',
    ];
    
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['worker_task_name', 'worker_task_start', 'worker_task_end', 'worker_task_reward_type'], 'required'],
            [['worker_types', 'worker_rules', 'worker_cites'], 'string'],
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
        $data = (array)json_decode($this->worker_task_conditions, true);
        foreach ($data as $item){
            $data[$item['id']]['name'] = $names[$item['id']];
        }
        return $data;
    }
    public function setConditions($data)
    {
        $this->worker_task_conditions = json_encode($data);
    }
    /**
     * 阿姨类型字段
     */
    public function getWorker_types()
    {
        return explode(',', $this->worker_type);
    }
    public function setWorker_types($value)
    {
        $this->worker_type = implode(',', $this->worker_type);
    }
    /**
     * 阿姨角色字段
     */
    public function getWorker_rules()
    {
        return explode(',', $this->worker_rule_id);
    }
    public function setWorker_rules($value)
    {
        $this->worker_rule_id = implode(',', $this->worker_rule_id);
    }
    /**
     * 城市字段
     */
    public function getWorker_cites()
    {
        return explode(',', $this->worker_task_city_id);
    }
    public function setWorker_cites($value)
    {
        $this->worker_task_city_id = implode(',', $this->worker_task_city_id);
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
    /**
     * 计算阿姨任务列表
     */
    public static function getTaskListByWorkerId($worker_id)
    {
        $cur_time = time();
        $worker = Worker::findOne(['id'=>$worker_id]);
        if(empty($worker)){
            throw new InvalidParamException('阿姨不存在');
        }
        $tasks = self::find()
        ->where("FIND_IN_SET({$worker->worker_type}, worker_type) AND FIND_IN_SET({$worker->worker_rule_id}, worker_rule_id)")
        ->andFilterWhere(['<','worker_task_start', $cur_time])
        ->andFilterWhere(['>','worker_task_end', $cur_time])
        ->all();
        return $tasks;
    }
}