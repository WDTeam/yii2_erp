<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151118_101028_update_record_order_status_dict extends Migration
{
    public function safeUp()
    {
        $this->update('{{%order_status_dict}}',['order_status_customer'=>'待指派'],['order_status_customer'=>'指派中']);
    }

    public function safeDown()
    {
        $this->update('{{%order_status_dict}}',['order_status_customer'=>'指派中'],['order_status_customer'=>'待指派']);
    }
}
