<?php

namespace common\models\shop;

/**
 * This is the ActiveQuery class for [[ShopStatus]].
 *
 * @see ShopStatus
 */
class ShopStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ShopStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ShopStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}