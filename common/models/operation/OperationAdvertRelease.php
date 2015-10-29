<?php

namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_advert_release".
 *
 * @property integer $id
 * @property integer $advert_content_id
 * @property integer $city_id
 * @property string $city_name
 * @property string $starttime
 * @property string $endtime
 * @property integer $status
 * @property integer $is_softdel
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationAdvertRelease extends \yii\db\ActiveRecord
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
            [['advert_content_id', 'advert_release_order', 'city_id', 'status', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['starttime', 'endtime'], 'safe'],
            [['city_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'advert_content_id' => '广告内容编号',
            'city_id' => '城市编号',
            'city_name' => '城市名称',
            'starttime' => '开始时间',
            'endtime' => '结束时间',
            'status' => '状态',
            'is_softdel' => '是否删除',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'advert_release_order' => '排序',
            'operation_advert_content_name' => '广告名称',
            'platform_name' => '所属平台',
            'platform_version_name' => '平台版本',
            'position_name' => '广告位置',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperationAdvertContent()
    {
        return $this->hasOne(OperationAdvertContent::className(), ['id' => 'advert_content_id'])->from(OperationAdvertContent::tableName().' AS operationAdvertContent');
    }
}
