<?php
/**
* 评价标签扩展管理
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

class CustomerCommentTag extends \dbbase\models\customer\CustomerCommentTag
{

    /**
     * 获取评价级别
     * @param int type 级别
     * @return array  
     */
    public static function getCommentTag($type)
    {
        return self::find()->select('id,customer_tag_name,customer_comment_level,is_online')->where(["customer_comment_level" => $type])->asArray()->all();
    }
    
    
    
     
    
    
    

}
