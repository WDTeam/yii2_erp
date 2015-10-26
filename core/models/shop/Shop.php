<?php
namespace core\models\shop;
use yii;
use yii\behaviors\TimestampBehavior;
use boss\models\Operation\OperationCity;
use boss\models\Operation\OperationArea;
use yii\web\HttpException;
use yii\base\ErrorException;
use yii\web\BadRequestHttpException;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use core\models\worker\Worker;
use core\behaviors\ShopStatusBehavior;
use yii\helpers\ArrayHelper;
class Shop extends \common\models\shop\Shop
{
    public static $audit_statuses = [
        0=>'待审核',
        1=>'通过',
        2=>'不通过'
    ];
    public static $is_blacklists = ['否', '是'];
    /**
     * 自动处理创建时间和修改时间
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            [
                'class'=>ShopStatusBehavior::className(),
                'model_name'=>ShopStatus::MODEL_SHOP
            ]
        ];
    }
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['name','city_id', 'street', 'principal', 'tel', 'shop_manager_id'], 'required'],
            [['shop_manager_id', 'province_id', 'city_id', 'county_id', 'is_blacklist', 
                 'audit_status', 'worker_count', 
                'complain_coutn', 'tel', 'bankcard_number'], 'integer'],
            [['name', 'account_person'], 'string', 'max' => 100],
            [['street', 'opening_address'], 'string', 'max' => 255],
            [['principal', 'tel', 'bankcard_number', 'level'], 'string', 'max' => 50],
            [['other_contact', 'opening_bank', 'sub_branch'], 'string', 'max' => 200],
        ]);
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
            'is_blacklist'=>Yii::t('app', '是否黑名单'),
            'shop_manager_id'=>Yii::t('app', '归属家政'),
        ]);
    }

    /**
     * 获取家政名称
     */
    public function getManagerName()
    {
        $model = ShopManager::find()->where(['id'=>$this->shop_manager_id])->one();
        return isset($model)?$model->name:'';
    }
    /**
     * 获取城市名称
     */
    public function getCityName()
    {
        $model = OperationArea::find()->where(['id'=>$this->city_id])->one();
        return isset($model)?$model->area_name:'';
    }
    
    /**
     * 获取审核各状态数据
     * @param int $number
     */
    public static function getAuditStatusCountByNumber($number)
    {
        return (int)self::find()->select('COUNT(1)')->where(['audit_status'=>$number])->scalar();
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
     * 获取记录总数
     */
    public static function getTotal()
    {
        return (int)self::find()->select('COUNT(1)')->where('isdel is null or isdel=0')->scalar();
    }
    /**
     * 获取黑名单数
     */
    public static function getIsBlacklistCount()
    {
        return (int)self::find()->select('COUNT(1)')->where(['is_blacklist'=>1])->scalar();
    }
    /**
     * 加入黑名单
     * @param string $cause 原因
     */
    public function joinBlacklist($cause='')
    {
        $this->is_blacklist = 1;
        if($this->save()){
            //门店阿姨全拉黑
            $workers = $this->getWorkers();
            foreach ($workers as $worker){
                $worker->joinBlacklist($cause);
            }
        }
        return false;
    }
    /**
     * 移出黑名单
     * @param string $cause 原因
     */
    public function removeBlacklist($cause='')
    {
        $sm = ShopManager::find()->where(['id'=>$this->shop_manager_id])->one();
        if($sm->is_blacklist==1){
            throw new BadRequestHttpException('所在的小家政未移出黑名单');
        }
        $this->is_blacklist = 0;
        return $this->save();
    }
    /**
     * 改变审核状态
     * @param int $number 状态码
     * @param string $cause 原因
     */
    public function changeAuditStatus($number, $cause='')
    {
        $this->audit_status = $number;
        $this->cause = $cause;
        if($this->save()){
            return true;
        }
        return false;
    }
    /**
     * 软删除
     */
    public function softDelete()
    {
        $this->isdel = 1;
        return $this->save();
    }
    /**
     * 门店下阿姨
     */
    public function getWorkers() {
        $models = Worker::find()->where(['shop_id'=>$this->id])->all();
        return (array)$models;
    }
    public static function findById($id) {
        return self::findOne(['id'=>$id]);
    }
    /**
     * 获取所有有效shop_id并带manager_id
     */
    public static function getShopIds()
    {
        return self::find()->select(['id as shop_id','shop_manager_id'])
        ->where('isdel is null or isdel=0')->asArray()->all();
    }
    /**
     * 通过省ID查询SHOP列表
     */
    public static function getCitesByProvinceId($province_id)
    {
        return self::findAll(['province_id'=>$province_id]);
    }
    /**
     * 通过市ID查询SHOP列表
     */
    public static function getCitesByCityId($city_id)
    {
        return self::findAll(['city_id'=>$city_id]);
    }
    /**
     * 通过县ID查询SHOP列表
     */
    public static function getCitesByCountyId($county_id)
    {
        return self::findAll(['county_id'=>$county_id]);
    }
}