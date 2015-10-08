<?php

namespace core\models\worker;

use Yii;

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
class WorkerRuleConfig extends \common\models\WorkerRuleConfig
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
            'worker_rule_name' => Yii::t('app', '主表阿姨id'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'admin_id' => Yii::t('app', '操作管理员id'),
            'isdel' => Yii::t('app', '是否删除 0正常1删除'),
        ];
    }
}
