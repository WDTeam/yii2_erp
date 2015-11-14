<?php
namespace core\models\shop;
use yii;
use yii\behaviors\TimestampBehavior;
use core\models\operation\OperationCity;
use core\models\operation\OperationArea;
use yii\web\BadRequestHttpException;
use core\models\worker\Worker;
use core\behaviors\ShopStatusBehavior;
use yii\helpers\ArrayHelper;
use core\models\operation\OperationShopDistrict;
use dbbase\components\BankHelper;

class Shop extends \dbbase\models\shop\Shop
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
            ['name', 'unique'],
            [['name','city_id', 'street', 'principal', 'tel', 
                'operation_shop_district_id',
            'shop_manager_id'], 'required'],
            [['street'], 'string', 'max'=>30],
            [['principal'], 'match', 'pattern' => '/^[\w|\x{4e00}-\x{9fa5}]+$/u'],
            [['shop_manager_id', 'province_id', 'city_id', 'county_id', 'is_blacklist', 
                 'audit_status', 'worker_count', 
                'complain_coutn', 'tel', 'bankcard_number'], 'integer'],
            [['audit_status', 'is_blacklist', 'operation_shop_district_id'], 'default', 'value'=>0],
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
     * 默认自营门店审核通过
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert)
    {
        if($this->getIsNewRecord() && $this->shop_manager_id==1){
            $this->audit_status = 1;
        }
        return parent::beforeSave($insert);
    }

    /**
     * 获取家政名称
     */
    public function getManagerName()
    {
        $name = ShopManager::find()
        ->select(['name'])
        ->where(['id'=>$this->shop_manager_id])->scalar();
        return $name;
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
        return (int)self::find()->where(['audit_status'=>$number])->count();
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
        return (int)self::find()->where('isdel is null or isdel=0')->count();
    }
    /**
     * 获取黑名单数
     */
    public static function getIsBlacklistCount()
    {
        return (int)self::find()->where(['is_blacklist'=>1])->count();
    }
    /**
     * 加入黑名单
     * @param string $cause 原因
     */
    public function joinBlacklist($cause='')
    {
        $this->is_blacklist = 1;
        $this->cause = $cause;
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
        $this->cause = $cause;
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
     * 获取商圈数组
     */
    public static function getShopDistrictList($city_id=null)
    {
        $models = OperationShopDistrict::getCityShopDistrictList($city_id);
        return ArrayHelper::map($models, 'id', 'operation_shop_district_name');
    }
    /**
     * 商圈名称
     */
    public function getOperation_shop_district_name()
    {
        return OperationShopDistrict::getShopDistrictName($this->operation_shop_district_id);
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
    /**
     * 获取已开通城市列表
     * @return array [city_id=>city_name,...]
     */
    public static function getOnlineCityList(){
        $onlineCityList = OperationCity::getCityOnlineInfoList();
        return $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
    }
    /**
     * 获取银行列表
     */
    public static function getBankNames()
    {
        return BankHelper::getBankNames();
    }
    /**
     * 处理阿姨数量
     * @param int $shop_id
     */
    public static function runCalculateWorkerCount($shop_id)
    {
        $model = self::findById($shop_id);
        $model->worker_count = (int)Worker::countShopWorkerNums($shop_id);
        if($model->save()){
            $shop_manager = ShopManager::findOne($model->shop_manager_id);
            $count = self::find()->select(['SUM(worker_count)'])
            ->where(['shop_manager_id'=>$shop_manager->id])->scalar();
            $shop_manager->worker_count = $count;
            return $shop_manager->save();
        }
        return false;
    }
    
    
    
    /**
     * 通过用户id返回门店名称
     * @date: 2015-11-2
     * @author: peak pan
     * @return:
     **/
    
    public static function get_id_name($id)
    {
    	$usernamelist=self::find()->select('name')->where(['id'=>$id])->asArray()->one();
    	if($usernamelist){
    		return $usernamelist['name'];
    	}else{
    		return '未知';
    	}
    	 
    	 
    }

    
    /**
     * 使用修改通过搜索关键字获取门店信息
     * @date: 2015-11-2
     * @author: peak pan
     * @return:
     **/
    public static function ShowShop()
    {
    
    	$shopResult = Shop::find()->select('id, name')->asArray()->all();
    	if($shopResult){
    		$namelist=['0'=>'请选择门店'];
    		$namelist=array_merge($namelist,ArrayHelper::map($shopResult, 'id', 'name'));
    		return $namelist;
    	}else{
    		return array();
    	}
    	
    }
    
    
    
    
}