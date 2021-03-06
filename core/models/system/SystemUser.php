<?php

namespace core\models\system;

use yii\helpers\ArrayHelper;
use core\models\shop\ShopCustomeRelation;
use core\models\shop\Shop;
use core\models\shop\ShopManager;
use core\models\auth\AuthItem;
use yii\base\Object;
use boss\components\RbacHelper;
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


    private $_roles = [];
    public function setRoles($roles)
    {
        if(is_array($roles)){
            $this->_roles = $roles;
        }else{
            $this->_roles = [$roles];
        }
    }
    /**
     * 权限保存
     * 
     * @return multitype:\yii\rbac\Role |multitype:
     */
    public function saveRoles()
    {
        $_has_roles = array_keys(self::getArrayRole());
        $roles= array_intersect($this->_roles, $_has_roles);
        $auth = \Yii::$app->authManager;
        if(!empty($roles)){
            $auth->revokeAll($this->id);
            foreach ($roles as $role_name){
                $role = $auth->getRole($role_name);
                if(!empty($role)){
                    $auth->assign($role, $this->id);
                }
            }
            RbacHelper::updateConfigVersion();
            return $auth->getRolesByUser($this->id);
        }else{
            return [];
        }
    }
    public function getRoles()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRolesByUser($this->id), 'name', 'name');
    }
    public function getRolesLabel()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRolesByUser($this->id), 'name', 'description');
    }
    /**
     * 保存SHOP IDS
     */
    public function saveShopIds()
    {
        ShopCustomeRelation::deleteAll([
            'stype'=>ShopCustomeRelation::TYPE_STYPE_SHOP,
            'system_user_id'=>$this->id,
        ]);
        $shops = Shop::find()->select(['id', 'shop_manager_id'])
        ->andFilterWhere(['in', 'id', $this->_shop_ids])
        ->asArray()->all();
        $shop_manager_ids = ArrayHelper::map($shops, 'id', 'shop_manager_id');
        $shop_ids = (array)$this->_shop_ids;
        foreach ($shop_ids as $shop_id){
            $model = new ShopCustomeRelation();
            $model->system_user_id = $this->id;
            $model->shopid = $shop_id;
            if(isset($shop_manager_ids[$shop_id])){
                $model->shop_manager_id = $shop_manager_ids[$shop_id];
            }
            $model->stype = ShopCustomeRelation::TYPE_STYPE_SHOP;
            $model->save();
            if($model->getErrors()){
                var_dump($model->errors);exit;
            }
        }
        return true;
    }
    /**
     * 保存ShopManager IDS
     */
    public function saveShopManagerIds()
    {
        ShopCustomeRelation::deleteAll([
            'stype'=>ShopCustomeRelation::TYPE_STYPE_SHOPMANAGER,
            'system_user_id'=>$this->id,
        ]);
        $shop_manager_ids = (array)$this->_shop_manager_ids;
        foreach ($shop_manager_ids as $shop_manager_id){
            $model = new ShopCustomeRelation();
            $model->system_user_id = $this->id;
            $model->shop_manager_id = $shop_manager_id;
            $model->stype = ShopCustomeRelation::TYPE_STYPE_SHOPMANAGER;
            $model->save();
            if($model->getErrors()){
                var_dump($model->errors);exit;
            }
        }
        $this->_shop_ids = Shop::find()
        ->select(['id'])
        ->andFilterWhere(['in','shop_manager_id', $shop_manager_ids])
        ->column();
        $this->saveShopIds();
        return true;
    }
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
        $res = ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
        if(!\Yii::$app->user->can(AuthItem::SYSTEM_ROLE_SUPER_ADMIN)){
            unset($res[AuthItem::SYSTEM_ROLE_SUPER_ADMIN]);
        }
        return $res;
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
        return ArrayHelper::merge(parent::rules(), [
            [['username', 'mobile', 'password'], 'required'],
            [['password', 'repassword'], 'required', 'on' => ['admin-create']],
            [['username', 'email', 'mobile', 'password', 'repassword'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['username', 'email', 'mobile'], 'unique'],
            ['mobile', 'match', 'pattern'=>'/[\d]{11,11}/'],
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
            [['shopIds', 'shopManagerIds'], 'safe'],
            // Status
            ['roles', 'safe'],
        ]);
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
    private $_shop_manager_ids;
    public function setShopManagerIds($ids)
    {
        $this->_shop_manager_ids = $ids;
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
    private $_shop_ids;
    public function setShopIds($ids)
    {
        $this->_shop_ids = $ids;
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
        $auth = \Yii::$app->authManager;
        return !$auth->checkAccess($this->id, AuthItem::SYSTEM_ROLE_ADMIN);
    }
    /**
     * 小家政组
     */
    public function isMiNiBoss()
    {
        $auth = \Yii::$app->authManager;
        return $auth->checkAccess($this->id, 'system_group_shop_manger') || $auth->checkAccess($this->id, 'group_shopmanager_admin');
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