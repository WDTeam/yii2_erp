<?php

namespace dbbase\models\customer;

/**
 * This is the ActiveQuery class for [[CustomerExtScore]].
 *
 * @see CustomerExtScore
 */
class CustomerExtScoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerExtScore[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerExtScore|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}