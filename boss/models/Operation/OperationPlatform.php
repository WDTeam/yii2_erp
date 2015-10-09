<?php

namespace boss\models\Operation;

use Yii;

use core\models\Operation\CoreOperationPlatform;
/**
 * This is the model class for table "{{%operation_platform}}".
 *
 * @property integer $id
 * @property string $operation_platform_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationPlatform extends CoreOperationPlatform
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['operation_platform_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_platform_name' => '平台名称',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
