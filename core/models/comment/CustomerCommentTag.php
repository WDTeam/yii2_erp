<?php

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
        return self::find()->select('id,customer_tag_name,customer_comment_level,is_online,is_del')->where(["customer_comment_level" => $type])->asArray()->all();
    }
    
    
    
     
    
    
    

}
