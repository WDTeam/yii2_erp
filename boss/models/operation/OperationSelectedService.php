<?php
namespace boss\models\operation;

use Yii;
use core\models\operation\CoreOperationSelectedService;

/**
 * This is the model class for table "ejj_operation_selected_service".
 *
 * @property integer $id
 * @property integer $selected_service_goods_id
 * @property string $selected_service_scene
 * @property string $selected_service_area
 * @property string $selected_service_sub_area
 * @property string $selected_service_standard
 * @property string $selected_service_price
 * @property integer $selected_service_unit
 * @property string $created_at
 * @property string $remark
 */
class OperationSelectedService extends CoreOperationSelectedService
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_operation_selected_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['selected_service_scene', 'selected_service_area', 'selected_service_sub_area', 'selected_service_standard', 'selected_service_price', 'selected_service_unit'], 'required'],
            [['selected_service_goods_id', 'selected_service_unit'], 'integer'],
            [['selected_service_price'], 'number'],
            [['created_at'], 'safe'],
            [['selected_service_scene', 'selected_service_area'], 'string', 'max' => 32],
            [['selected_service_sub_area'], 'string', 'max' => 64],
            [['selected_service_standard'], 'string', 'max' => 128],
            [['remark'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'selected_service_goods_id' => Yii::t('app', '商品编号'),
            'selected_service_scene' => Yii::t('app', '场景'),
            'selected_service_area' => Yii::t('app', '区域'),
            'selected_service_sub_area' => Yii::t('app', '子区域'),
            'selected_service_standard' => Yii::t('app', '标准'),
            'selected_service_price' => Yii::t('app', '价格'),
            'selected_service_unit' => Yii::t('app', '时间'),
            'selected_service_photo' => Yii::t('app', '图片'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
            'remark' => Yii::t('app', '备注'),
        ];
    }
}
