<?php

namespace boss\models\operation;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ejj_operation_selected_service".
 *
 * @property integer $id
 * @property integer $selected_service_goods_id
 * @property string $selected_service_scene
 * @property string $selected_service_area
 * @property string $selected_service_sub_area
 * @property string $selected_service_standard
 * @property integer $selected_service_area_standard
 * @property string $selected_service_price
 * @property integer $selected_service_unit
 * @property string $selected_service_photo
 * @property integer $is_softdel
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $remark
 */
class OperationSelectedService extends \core\models\operation\OperationSelectedService
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['selected_service_scene', 'selected_service_area', 'selected_service_sub_area', 'selected_service_standard', 'selected_service_unit', 'selected_service_area_standard'], 'required'],
            [['selected_service_goods_id', 'selected_service_area_standard', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['selected_service_unit'], 'integer', 'max' => 9999],
            [['selected_service_price'], 'number'],
            [['selected_service_scene', 'selected_service_area'], 'string', 'max' => 32],
            [['selected_service_sub_area'], 'string', 'max' => 64],
            [['selected_service_standard'], 'string', 'max' => 128],
            ['selected_service_photo', 'image', 'extensions' => ['png', 'jpg', 'gif'], 'maxHeight' => 1024, 'maxWidth' => 1024, 'maxSize' => 1024*1024],
            ['selected_service_photo', 'required', 'on' => ['create']],
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
            'selected_service_standard' => Yii::t('app', '清洁标准'),
            'selected_service_area_standard' => Yii::t('app', '面积标准'),
            'selected_service_price' => Yii::t('app', '价格'),
            'selected_service_unit' => Yii::t('app', '时长'),
            'selected_service_photo' => Yii::t('app', '图片'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
            'remark' => Yii::t('app', '备注'),
        ];
    }

    /**
     * 获取精品保洁按钮css样式class
     *
     * @param  int $btnCate 按钮所属类型 1-2
     * @return string 按钮css样式class   btn-success-selected(按钮被选中) or btn-success(按钮未选中)
     */
    public static function setBtnCss($btnCate){
        $params = Yii::$app->request->getQueryParams();
        $selectedParams = isset($params['OperationSelectedService'])?$params['OperationSelectedService']:[];

        if($btnCate==1 && isset($selectedParams['selected_service_area_standard']) && $selectedParams['selected_service_area_standard'] == 1){
            return 'btn-success-selected';
        }elseif($btnCate==2 && isset($selectedParams['selected_service_area_standard']) && $selectedParams['selected_service_area_standard'] == 2){
            return 'btn-success-selected';
        } else{
            return 'btn-success';
        }
    }

    /**
     * 渲染view层的表格
     */
    public function search($params)
    {
        $query = OperationSelectedService::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params))) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'selected_service_area_standard' => $this->selected_service_area_standard,
        ]);

        $query->orderBy(['selected_service_scene' => SORT_DESC]);

        return $dataProvider;
    }
}
