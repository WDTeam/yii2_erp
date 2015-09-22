<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see SystemUser
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SystemUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SystemUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}