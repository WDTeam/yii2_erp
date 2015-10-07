<?php

namespace boss\models\Operation;

use Yii;
use core\models\Operation\CoreOperationSpec;

/**
 * This is the model class for table "{{%operation_spec}}".
 *
 * @property integer $id
 * @property string $operation_spec_name
 * @property string $operation_spec_description
 * @property string $operation_spec_values
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationSpec extends CoreOperationSpec
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_spec_description', 'operation_spec_values'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['operation_spec_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_spec_name' => Yii::t('app', '规格名称'),
            'operation_spec_description' => Yii::t('app', '规格备注'),
            'operation_spec_values' => Yii::t('app', '规格值(序列化属性)'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }
}
