<?php
namespace boss\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class UpdateOwnerShopManagerRule extends Rule
{
    public $name = 'update_owner_shopmanager';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params GET传过来的数据
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        $shop_manager_id = $params['id'];
        $ids = \Yii::$app->user->identity->getShopManagerIds();
        return in_array($shop_manager_id, $ids);
    }
    
    public static function add()
    {
        $auth = \Yii::$app->authManager;
        $rule = new self();
        $_rule = $auth->getRule($rule->name);
        if(empty($_rule)){
            $rule = new self();
            $auth->add($rule);
            $update_owner_shopmanager = $auth->createPermission('update_owner_shop_manager');
            $update_owner_shopmanager->description = '更新自己的小家政';
            $update_owner_shopmanager->ruleName = $rule->name;
            $auth->add($update_owner_shopmanager);
            
            $update_shopmanager = $auth->getPermission('shopmanager/shop-manager/view');
            if(empty($update_shopmanager)){
                $update_shopmanager = $auth->createPermission('shopmanager/shop-manager/view');
                $update_shopmanager->description = '更新所有小家政';
                $auth->add($update_shopmanager);
            }
            $auth->addChild($update_owner_shopmanager, $update_shopmanager);
        }
    }
}