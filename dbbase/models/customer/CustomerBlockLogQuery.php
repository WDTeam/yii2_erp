<?php

namespace dbbase\models\customer;

/**
 * This is the ActiveQuery class for [[CustomerBlockLog]].
 *
 * @see CustomerBlockLog
 */
class CustomerBlockLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerBlockLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerBlockLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}