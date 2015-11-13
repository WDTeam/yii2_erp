<?php
/**
* 评价扩展控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace core\models\comment;

use Yii;

class CustomerCommentLevel extends \dbbase\models\customer\CustomerCommentLevel
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
