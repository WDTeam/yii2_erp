<?php

namespace common\models\worker;

use Yii;

/**
 * This is the model class for table "{{%worker_code}}".
 *
 * @property integer $id
 * @property string $worker_code
 * @property integer $worker_code_expiration
 * @property string $worker_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%worker_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_code', 'worker_code_expiration', 'created_at', 'updated_at'], 'required'],
            [['worker_code_expiration', 'created_at', 'updated_at', 'is_del'], 'integer'],
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
            'worker_code' => 'Worker Code',
            'worker_code_expiration' => 'Worker Code Expiration',
            'worker_phone' => 'Worker Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }
}
