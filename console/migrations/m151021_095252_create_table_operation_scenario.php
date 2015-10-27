<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151021_095252_create_table_operation_scenario extends Migration
{
  public function up()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户积分表\'';
    }
      $this->createTable('{{%operation_scenario}}', [
          'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'场景编号\'' ,
          'operation_scenario_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'场景名称\'',
          'operation_scenario_standard' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'场景标准\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',


          'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
          'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
      ], $tableOptions);

 }

  public function down()
  {
    $this->dropTable('{{%operation_scenario}}');
  }
}
