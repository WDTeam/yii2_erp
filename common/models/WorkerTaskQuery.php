<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[WorkerTask]].
 *
 * @see WorkerTask
 */
class WorkerTaskQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return WorkerTask[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WorkerTask|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}