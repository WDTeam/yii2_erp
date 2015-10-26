<?php

namespace common\models\shop;

/**
 * This is the ActiveQuery class for [[ShopManager]].
 *
 * @see ShopManager
 */
class ShopManagerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ShopManager[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ShopManager|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}