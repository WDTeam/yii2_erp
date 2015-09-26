<?php
namespace boss\models;
use yii;
use yii\behaviors\TimestampBehavior;
use boss\models\Operation\OperationCity;
use boss\models\Operation\OperationArea;
class Shop extends \common\models\Shop
{
    public static $audit_statuses = [
        0=>'待审核',
        1=>'通过',
        2=>'不通过'
    ];
    /**
     * 自动处理创建时间和修改时间
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'province_id' => Yii::t('app', '省份'),
            'city_id' => Yii::t('app', '城市'),
            'county_id' => Yii::t('app', '区县'),
            'audit_status' => Yii::t('app', '审核状态'),
        ]);
    }
    /**
     * 获取家政名称
     */
    public function getMenagerName()
    {
        $model = ShopManager::find()->where(['id'=>$this->shop_manager_id])->one();
        return $model->name;
    }
    /**
     * 获取城市名称
     */
    public function getCityName()
    {
        $model = OperationArea::find()->where(['id'=>$this->city_id])->one();
        return $model->area_name;
    }
    /**
     * 获取审核各状态数据
     * @param int $number
     */
    public static function getAuditStatusCountByNumber($number)
    {
        return (int)self::find()->where(['audit_status'=>$number])->scalar();
    }
    /**
     * 获取黑名单数
     */
    public static function getIsBlacklistCount()
    {
        return (int)self::find()->where(['is_blacklist'=>1])->scalar();
    }
    /**
     * 获取地址全称,直辖市不需要显示省字段
     */
    public function getAllAddress()
    {
    
        $arg = [110000, 120000, 310000, 500000];
        if(in_array($this->province_id, $arg)){
            $province = '';
        }else{
            $province = OperationArea::find()->select('area_name')
            ->where(['id'=>$this->province_id])->scalar();
        }
    
        $city = OperationArea::find()->select('area_name')
        ->where(['id'=>$this->city_id])->scalar();
        $county = OperationArea::find()->select('area_name')
        ->where(['id'=>$this->county_id])->scalar();
        return $province.$city.$county.$this->street;
    }
    /**
     * 加入黑名单
     * @param string $cause 原因
     */
    public function joinBlacklist($cause='')
    {
        $this->is_blacklist = 1;
        if($this->save()){
            $status = new ShopStatus();
            $status->status_number = 1;
            $status->model_name = ShopStatus::MODEL_SHOP;
            $status->status_type = 2;
            $status->created_at = time();
            $status->cause = $cause;
            return $status->save();
        }
        return false;
    }
    /**
     * 移出黑名单
     * @param string $cause 原因
     */
    public function removeBlacklist($cause='')
    {
        $this->is_blacklist = 0;
        if($this->save()){
            $status = new ShopStatus();
            $status->status_number = 0;
            $status->model_name = ShopStatus::MODEL_SHOP;
            $status->status_type = 2;
            $status->created_at = time();
            $status->cause = $cause;
            return $status->save();
        }
        return false;
    }
    /**
     * 改变审核状态
     * @param int $number 状态码
     * @param string $cause 原因
     */
    public function changeAuditStatus($number, $cause='')
    {
        $this->audit_status = $number;
        if($this->save()){
            $status = new ShopStatus();
            $status->status_number = $this->audit_status;
            $status->model_name = ShopStatus::MODEL_SHOP;
            $status->status_type = 1;
            $status->created_at = time();
            $status->cause = $cause;
            return $status->save();
        }
        return false;
    }
}