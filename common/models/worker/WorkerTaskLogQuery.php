<?php

namespace common\models\worker;

/**
 * This is the ActiveQuery class for [[WorkerTaskLog]].
 *
 * @see WorkerTaskLog
 */
class WorkerTaskLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return WorkerTaskLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WorkerTaskLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}