<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OperationCategory]].
 *
 * @see OperationCategory
 */
class OperationCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return OperationCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OperationCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}