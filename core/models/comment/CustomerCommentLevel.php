<?php

namespace core\models\comment;

use Yii;

class CustomerCommentLevel extends \common\models\CustomerCommentLevel
{

    /**
     * 获取评价级别
     * @param int type 级别
     */
    public static function getCommentLevel()
    {
        return self::find()->select('id,customer_comment_level,customer_comment_level_name,is_del')->asArray()->all();
    }

}
