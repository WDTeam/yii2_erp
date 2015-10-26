<?php

namespace common\models\operation;

/**
 * This is the ActiveQuery class for [[OperationCategory]].
 *
 * @see OperationCategory
 */
class CommonOperationCategoryQuery extends \yii\db\ActiveQuery
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