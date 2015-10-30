<?php

namespace core\models\system;

use yii\helpers\ArrayHelper;
class SystemUser extends \dbbase\models\system\SystemUser
{
    public $repassword;
    private $_statusLabel;
    private $_roleLabel;
    
    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
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
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'name');
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
    
        return array_merge(
            $labels,
            [
                'password' => \Yii::t('app', '密码'),
                'repassword' => \Yii::t('app', '确认密码')
            ]
        );
    }
}