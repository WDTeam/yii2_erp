<?php

namespace common\models\Operation;

use Yii;

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
class CommonOperationAdvertRelease extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_advert_release}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_name'], 'string'],
            [['created_at', 'updated_at', 'city_id'], 'integer'],
            [['operation_release_contents'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'city_name' => '城市名称',
            'city_id' => '城市编号',
            'operation_release_contents' => '发布详情',
            'created_at' => '首次发布时间',
            'updated_at' => '最后发布时间',
        ];
    }
}
