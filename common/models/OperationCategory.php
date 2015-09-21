<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%operation_category}}".
 *
 * @property integer $id
 * @property string $operation_category_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['operation_category_name'], 'string', 'max' => 60],
//            [['operation_category_name'], 'integer', 'min' => '1', 'max' => '5'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_category_name' => Yii::t('app', '服务品类名称'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }

    /**
     * @inheritdoc
     * @return OperationCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OperationCategoryQuery(get_called_class());
    }
}
