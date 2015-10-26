<?php

namespace common\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_access_token}}".
 *
 * @property integer $id
 * @property string $worker_access_token
 * @property integer $worker_access_token_expiration
 * @property integer $worker_code_id
 * @property string $worker_code
 * @property string $worker_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerAccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_access_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_access_token', 'worker_access_token_expiration', 'created_at', 'updated_at'], 'required'],
            [['worker_access_token_expiration', 'worker_code_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['worker_access_token'], 'string', 'max' => 64],
            [['worker_code'], 'string', 'max' => 8],
            [['worker_phone'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'worker_access_token' => 'Worker Access Token',
            'worker_access_token_expiration' => 'Worker Access Token Expiration',
            'worker_code_id' => 'Worker Code ID',
            'worker_code' => 'Worker Code',
            'worker_phone' => 'Worker Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }
}
