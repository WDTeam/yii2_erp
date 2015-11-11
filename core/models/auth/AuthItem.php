<?php

namespace core\models\auth;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * Auth type
     */
    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;
    
    /**
     * 系统固定角色
     */
    const SYSTEM_ROLE_SUPER_ADMIN = 'system_group_super_admin';
    const SYSTEM_ROLE_ADMIN = 'system_group_admin';
    const SYSTEM_ROLE_SHOP_MANAGER = 'system_group_shop_manger';
    const SYSTEM_ROLE_MINIBOSS = 'system_group_miniboss';
    
    public static function addDefaultRoles()
    {
        $roles = [
            self::SYSTEM_ROLE_SUPER_ADMIN=>'超级管理员',
            self::SYSTEM_ROLE_ADMIN=>'普通管理员',
            self::SYSTEM_ROLE_SHOP_MANAGER=>'小家政老板',
            self::SYSTEM_ROLE_MINIBOSS=>'MiNiBoss组',
        ];
        $auth = \Yii::$app->authManager;
        foreach ($roles as $name=>$description){
            $role = $auth->getRole($name);
            if(empty($role)){
                $role = $auth->createRole($name);
                $role->description = $description;
                $auth->add($role);
            }
        }
        $super = $auth->getRole(self::SYSTEM_ROLE_SUPER_ADMIN);
        $admin = $auth->getRole(self::SYSTEM_ROLE_ADMIN);
        var_dump($admin->name);
        $has = $auth->hasChild($super, $admin);
        if(empty($has)){
            $auth->addChild($super, $admin);
        }
    }
    
    public $permissions = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'type' => '类型',
            'description' => '描述',
            'rule_name' => '规则',
            'data' => '数据',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }
    
    public function getFullDescription()
    {
        $name = $this->name;
        $description = $this->description;
        $des = [
            self::SYSTEM_ROLE_SUPER_ADMIN=>$description.'（拥有所有权限）',
            self::SYSTEM_ROLE_ADMIN=>$description.'（除权限外的后台管理）',
            self::SYSTEM_ROLE_SHOP_MANAGER=>$description.'（只能管理自己的家政门店）',
            self::SYSTEM_ROLE_MINIBOSS=>$description.'（只能管理自己的数据）',
        ];
        if(isset($des[$name])){
            return $des[$name];
        }else{
            return $description;
        }
    }
}
