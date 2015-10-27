<?php

namespace boss\models\operation;

use Yii;
use core\models\operation\OperationTag as CoreOperationTag;

/**
 * This is the model class for table "{{%operation_tag}}".
 *
 * @property integer $id
 * @property string $operation_tag_name
 * @property integer $operation_applicable_scope_id
 * @property string $operation_applicable_scope_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationTag extends CoreOperationTag
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_applicable_scope_id', 'created_at', 'updated_at'], 'integer'],
            [['operation_tag_name', 'operation_applicable_scope_name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_tag_name' => Yii::t('app', '标签名称'),
            'operation_applicable_scope_id' => Yii::t('app', '适用范围编号'),
            'operation_applicable_scope_name' => Yii::t('app', '适用范围名称'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }
}
