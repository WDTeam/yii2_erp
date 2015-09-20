<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[OpCategory]].
 *
 * @see OpCategory
 */
class OpCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return OpCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OpCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}