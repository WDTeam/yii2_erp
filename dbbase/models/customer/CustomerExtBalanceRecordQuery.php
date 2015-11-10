<?php

namespace dbbase\models\customer;

/**
 * This is the ActiveQuery class for [[CustomerExtBalanceRecord]].
 *
 * @see CustomerExtBalanceRecord
 */
class CustomerExtBalanceRecordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerExtBalanceRecord[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerExtBalanceRecord|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}