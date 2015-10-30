<?php

namespace dbbase\models\customer;

/**
 * This is the ActiveQuery class for [[CustomerExtSrc]].
 *
 * @see CustomerExtSrc
 */
class CustomerExtSrcQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerExtSrc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerExtSrc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}