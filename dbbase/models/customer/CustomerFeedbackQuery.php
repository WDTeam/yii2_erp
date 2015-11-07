<?php

namespace dbbase\models\customer;

/**
 * This is the ActiveQuery class for [[CustomerFeedback]].
 *
 * @see CustomerFeedback
 */
class CustomerFeedbackQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerFeedback[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerFeedback|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}