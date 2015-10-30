<?php

namespace core\models\worker;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%worker_rule_config}}".
 *
 * @property integer $id
 * @property string $worker_rule_name
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 * @property double $isdel
 */
class WorkerRuleConfig extends \dbbase\models\worker\WorkerRuleConfig
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_rule_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_ad', 'updated_ad', 'admin_id'], 'integer'],
            [['isdel'], 'number'],
            [['worker_rule_name'], 'string', 'max' => 20]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨身份配置表自增id'),
            'worker_rule_name' => Yii::t('app', '主表角色名称'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'admin_id' => Yii::t('app', '操作管理员id'),
            'isdel' => Yii::t('app', '是否删除 0正常1删除'),
        ];
    }

    /*
     * 获取阿姨所有身份
     * @return array ['id'=>'worker_rule_name',..]
     */
    public static function getWorkerRuleList(){
        $workerRuleList = self::find()->select('id,worker_rule_name')->where(['isdel'=>0])->asArray()->all();
        return \yii\helpers\ArrayHelper::map($workerRuleList,'id','worker_rule_name');
    }

    /*
     * 获取阿姨身份名称
     * @param int worker_rule
     * @return String worker_rule_name
     */
    public static function getWorkerRuleShow($workerRuleId){

        $workerRuleInfo = self::find()->where(['id'=>$workerRuleId,'isdel'=>0])->asArray()->one();

        return $workerRuleInfo['worker_rule_name'];
    }

}
