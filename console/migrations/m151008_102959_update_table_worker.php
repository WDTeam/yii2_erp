<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151008_102959_update_table_worker extends Migration
{
    public function Up()
    {
        $this->addColumn('{{%worker}}',
            'worker_is_vacation',Schema::TYPE_BOOLEAN . '(3) DEFAULT NULL COMMENT \'阿姨是否请假 0正常1请假中\'');
    }

    public function Down()
    {
        $this->dropColumn('{{%worker}}','worker_is_vacation');
    }
}
