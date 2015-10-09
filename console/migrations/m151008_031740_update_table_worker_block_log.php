<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151008_031740_update_table_worker_block_log extends Migration
{
    public function Up()
    {
        $this->addColumn('{{%worker_block_log}}',
            'worker_id',Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨id\'');

    }

    public function Down()
    {
        $this->dropColumn('{{%worker_block_log}}','worker_id');
    }
}
