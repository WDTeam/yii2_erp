<?php

namespace common\models\worker;

/**
 * This is the ActiveQuery class for [[WorkerTaskLogmeta]].
 *
 * @see WorkerTaskLogmeta
 */
class WorkerTaskLogmetaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return WorkerTaskLogmeta[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WorkerTaskLogmeta|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}