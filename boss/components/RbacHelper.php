<?php
namespace boss\components;

use yii\base\Component;
class RbacHelper extends Component
{
    /**
     * 处理菜单
     * @param array $menus
     */
    public static function menu($menus)
    {
        return $menus;
    }
}