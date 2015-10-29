<?php

namespace dbbase\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_skill_config}}".
 *
 * @property integer $id
 * @property integer $worker_skill_name
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 * @property double $isdel
 */
class WorkerSkillConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_skill_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_skill_name', 'created_ad', 'updated_ad', 'admin_id'], 'integer'],
            [['isdel'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨技能配置表自增id'),
            'worker_skill_name' => Yii::t('app', '阿姨技能名称'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '最后更新时间'),
            'admin_id' => Yii::t('app', '操作管理员id'),
            'isdel' => Yii::t('app', '是否删除 0正常1删除'),
        ];
    }
}
