<?php

namespace boss\models\Operation;

use Yii;

use core\models\Operation\CoreOperationAdvertRelease;
/**
 * This is the model class for table "{{%operation_advert_release}}".
 *
 * @property integer $id
 * @property string $operation_advert_position_id
 * @property string $operation_advert_position_name
 * @property integer $operation_advert_content_id
 * @property integer $operation_advert_content_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertRelease extends CoreOperationAdvertRelease
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_advert_position_name'], 'string'],
            [['operation_advert_content_id', 'operation_advert_content_name', 'created_at', 'updated_at'], 'integer'],
            [['operation_advert_position_id'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_advert_position_id' => '广告位置编号',
            'operation_advert_position_name' => '广告位置名称',
            'operation_advert_content_id' => '广告内容编号',
            'operation_advert_content_name' => '广告内容名称',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
