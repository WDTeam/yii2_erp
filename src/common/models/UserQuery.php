<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see AdminUser
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
     * @return AdminUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AdminUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}