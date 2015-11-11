<?php

namespace core\models\system;

use yii\helpers\ArrayHelper;
use core\models\shop\ShopCustomeRelation;
use core\models\shop\Shop;
use core\models\shop\ShopManager;
use core\models\auth\AuthItem;
class SystemUser extends \dbbase\models\system\SystemUser
{
    public $repassword;
    private $_statusLabel;
    private $_roleLabel;
    
//     const CLASSIFY_SYSTEM = 0;
//     const CLASSIFY_BOSS = 1;
//     const CLASSIFY_MINIBOSS = 2;
//     public static function getClassifes()
//     {
//         return [
//             self::CLASSIFY_SYSTEM=>'系统保留',
//             self::CLASSIFY_BOSS=>'BOSS 用户',
//             self::CLASSIFY_MINIBOSS=>'MINI BOSS 用户'
//         ];
//     }
    
    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = empty($statuses[$this->status])?'无效':$statuses[$this->status];
        }
        return $this->_statusLabel;
    }
    /**
     * 显示分类名
     */
    public function getClassifyLabel()
    {
        $classifys = self::getClassifes();
        if(isset($classifys[$this->classify])){
            return $classifys[$this->classify];
        }else{
            return '';
        }
    }
    
    
    /**
    * 获取用户列表
    * @date: 2015-10-31
    * @author: peak pan
    * @return:
    **/
    public static function getuserlist()
    {
    	
    	$usernamelist=self::find()->select('id,username')->asArray()->all();
    	if($usernamelist){
    		return ArrayHelper::map($usernamelist, 'id', 'username');
    	}else{
    		return array();
    	}
    	
    	
    }
    
    
    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_ACTIVE => \Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => \Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED => \Yii::t('app', 'STATUS_DELETED'),
        ];
    }
    /**
     * 获取角色列表
     */
    public static function getArrayRole()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
    public function getRoleLabel()
    {
        if ($this->_roleLabel === null) {
            $roles = self::getArrayRole();
            $this->_roleLabel = isset($roles[$this->role])?$roles[$this->role]:'';
        }
        return $this->_roleLabel;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password', 'repassword'], 'required', 'on' => ['admin-create']],
            [['username', 'email', 'password', 'repassword'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['username', 'email'], 'unique'],
            // Username
        //             ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            // E-mail
            ['email', 'string', 'max' => 100],
            ['email', 'email'],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            //['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
    
            // Status
            ['roles', 'validateRole'],
        ];
    }
    /**
     * role 验证规则
     * @see \dbbase\models\SystemUser::validateRole()
     */
    public function validateRole($attribute, $params)
    {
        //         array_keys(self::getArrayRole());
        if (!$this->hasErrors()) {
    
        }
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
        //             'admin-create' => ['username', 'email', 'password', 'repassword', 'status', 'role'],
        //             'admin-update' => ['username', 'email', 'password', 'repassword', 'status', 'role']
        ]);
    }
    /**
     * 获取用户对应的家政公司ID
     */
    public function getShopManagerIds()
    {
        return (array)ShopCustomeRelation::find()
        ->select(['shop_manager_id'])
        ->where([
            'system_user_id'=>$this->id,
            'stype'=>ShopCustomeRelation::TYPE_STYPE_SHOPMANAGER,
            'is_del'=>0
        ])->column();
    }
    /**
     * 获取用户对应的家政公司列表
     */
    public function getShopManagerList()
    {
        $ids = $this->getShopManagerIds();
        if(empty($ids)){
            return [];
        }
        $res = (array)ShopManager::find()
        ->andFilterWhere(['in','id', $ids])
        ->all();
        return $res;
    }
    /**
     * 获取用户对应的门店ID
     */
    public function getShopIds()
    {
        return (array)ShopCustomeRelation::find()
        ->select(['shopid'])
        ->where([
            'system_user_id'=>$this->id,
            'stype'=>ShopCustomeRelation::TYPE_STYPE_SHOP,
            'is_del'=>0
        ])->column();
    }
    /**
     * 获取用户对应的门店列表
     */
    public function getShopList()
    {
        $ids = $this->getShopIds();
        if(empty($ids)){
            return [];
        }
        $res = (array)Shop::find()
        ->andFilterWhere(['in','id', $ids])
        ->all();
        return $res;
    }
    /**
     * 获取用户所有门店的商圈IDS
     */
    public function getShopDistrictIds()
    {
        $ids = $this->getShopIds();
        if(empty($ids)){
            return [];
        }
        $res = (array)Shop::find()
        ->select('operation_shop_district_id')
        ->andFilterWhere(['in','id', $ids])
        ->column();
        return $res;
    }
    /**
     * 非管理员
     */
    public function isNotAdmin()
    {
        return !\Yii::$app->user->can(AuthItem::SYSTEM_ROLE_ADMIN);
    }
    
    /**
    * 通过用户id返回用名称
    * @date: 2015-11-2
    * @author: peak pan
    * @return:
    **/
    
    public static function get_id_name($id)
    {
    	$usernamelist=self::find()->select('username')->where(['id'=>$id])->asArray()->one();
    	if($usernamelist){
    		return $usernamelist['username'];
    	}else{
    		return '未知';
    	}
    	
    	
    }
    
    
    
    
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
    
        return array_merge(
            $labels,
            [
                'classify'=>'用户类别',
                'mobile'=>'手机号',
                'password' => \Yii::t('app', '密码'),
                'repassword' => \Yii::t('app', '确认密码')
            ]
        );
    }
}