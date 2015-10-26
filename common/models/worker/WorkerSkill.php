<?php

namespace common\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_skill}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_skill_id
 * @property integer $created_ad
 */
class WorkerSkill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_skill}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_skill_id', 'created_ad'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '阿姨技能关联表自增id'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'worker_skill_id' => Yii::t('app', '阿姨技能配置表id'),
            'created_ad' => Yii::t('app', '创建时间'),
        ];
    }
}
