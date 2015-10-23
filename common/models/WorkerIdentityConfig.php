<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%worker_identity_config}}".
 *
 * @property integer $id
 * @property string $worker_identity_name
 * @property integer $created_ad
 * @property integer $updated_ad
 * @property integer $admin_id
 * @property integer $isdel
 */
class WorkerIdentityConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_identity_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'created_ad', 'updated_ad', 'admin_id', 'isdel'], 'integer'],
            [['worker_identity_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'worker_identity_name' => Yii::t('app', '阿姨身份名称'),
            'created_ad' => Yii::t('app', '创建时间'),
            'updated_ad' => Yii::t('app', '创建时间'),
            'admin_id' => Yii::t('app', '操作管理员id'),
            'isdel' => Yii::t('app', '是否删除0正常1删除'),
        ];
    }
}
