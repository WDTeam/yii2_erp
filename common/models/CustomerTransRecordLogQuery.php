<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CustomerTransRecordLog]].
 *
 * @see CustomerTransRecordLog
 */
class CustomerTransRecordLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return CustomerTransRecordLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerTransRecordLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}