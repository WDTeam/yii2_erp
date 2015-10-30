<?php

namespace dbbase\models\customer;

/**
 * This is the ActiveQuery class for [[CustomerExtBalance]].
 *
 * @see CustomerExtBalance
 */
class CustomerExtBalanceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerExtBalance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerExtBalance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}