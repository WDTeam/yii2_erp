<?php

namespace core\models\GeneralPay;

/**
 * This is the ActiveQuery class for [[GeneralPay]].
 *
 * @see GeneralPay
 */
class GeneralPayQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return GeneralPay[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return GeneralPay|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}