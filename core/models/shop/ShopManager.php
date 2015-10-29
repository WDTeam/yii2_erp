<?php
namespace core\models\shop;
use yii;
use yii\behaviors\TimestampBehavior;
use core\models\Operation\OperationArea;
use yii\base\Object;
use core\models\shop\ShopStatus;
use crazyfd\qiniu\Qiniu;
use core\behaviors\ShopStatusBehavior;
use core\models\operation\OperationCity;
use yii\helpers\ArrayHelper;
class ShopManager extends \common\models\shop\ShopManager
{
    /**
     * 营业执照注册类型
     */
    public static $bl_types = [
        1=>'个体户',
        2=>'商户'
    ];
    /**
     * 审核状态
     */
    public static $audit_statuses = [
        0=>'待审核',
        1=>'通过',
        2=>'不通过'
    ];
    public static $is_blacklists = ['否', '是'];
    /**
     * 黑名单原因
     */
    public $blacklist_cause='';
    /**
     * 黑名单时间
     */
    public $blacklist_time;
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
                'model_name'=>ShopStatus::MODEL_SHOPMANAGER
            ]
        ];
    }
    /**
     * 验证规则
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['name', 'street', 'principal', 'tel', 
            ], 'required'],
            [['password'], 'string'],
            [['audit_status', 'is_blacklist'], 'default', 'value'=>0],
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
            'bl_type' => Yii::t('app', '注册类型'),
//             'bl_expiry_start' => Yii::t('app', '有效期起始时间'),
//             'bl_expiry_end' => Yii::t('app', '有效期结束时间'),
            'is_blacklist' => Yii::t('app', '是否是黑名单'),
            'audit_status' => Yii::t('app', '审核状态'),
        ]);
    }
    /**
     * 获取城市名称
     */
    public function getCityName()
    {
        if(empty($this->city_id)){
            return '';
        }
        $model = OperationArea::find()->where(['id'=>$this->city_id])->one();
        return $model->area_name;
    }
    /**
     * 获取审核各状态的记录总数
     * @param int $number
     */
    public static function getAuditStatusCountByNumber($number)
    {
        return (int)self::find()->where(['audit_status'=>$number])->count();
    }
    /**
     * 获取黑名单数
     */
    public static function getIsBlacklistCount()
    {
        return (int)self::find()->where(['is_blacklist'=>1])->count();
    }
    /**
     * 获取记录总数
     */
    public static function getTotal()
    {
        return (int)self::find()->where('isdel is null or isdel=0')->count();
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
        $this->cause = $cause;
        if($this->save()){
            $this->affShopJoinBlacklist($cause);
            return true;
        }
        return false;
    }
    /**
     * 家政下所有门店加入黑名单
     */
    public function affShopJoinBlacklist($cause)
    {
        $models = Shop::find()->where(['shop_manager_id'=>$this->id])->all();
        foreach ($models as $model){
            $model->joinBlacklist($cause);
        }
        return count($models);
    }
    /**
     * 移出黑名单
     * @param string $cause 原因
     */
    public function removeBlacklist($cause='')
    {
        $this->is_blacklist = 0;
        $this->cause = $cause;
        if($this->save()){
            return true;
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
     * 获取执照URL
     */
    public function getBlPhotoUrlByQiniu()
    {
        $qn = new Qiniu();
        return $qn->getLink().$this->bl_photo_url;
    }
    /**
     * 密码处理
     */
    public function getPassword()
    {
        return '';
    }
    public function setPassword($password)
    {
        if(!empty($password)){
            $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
        }
    }
    
    public static function findById($id) {
        return self::findOne(['id'=>$id]);
    }
    /**
     * 获取已开通城市列表
     * @return array [city_id=>city_name,...]
     */
    public static function getOnlineCityList(){
        $onlineCityList = OperationCity::getCityOnlineInfoList();
        return $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
    }
}