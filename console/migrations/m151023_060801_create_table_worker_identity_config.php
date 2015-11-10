<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151023_060801_create_table_worker_identity_config extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨身份配置表\'';
        }
        $this->createTable('{{%worker_identity_config}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨身份配置表自增id\'' ,
            'worker_identity_name' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'阿姨身份名称\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
            'admin_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'操作管理员id\'',
            'isdel' => Schema::TYPE_DOUBLE . '(1) DEFAULT NULL COMMENT \'是否删除 0正常1删除\'',
        ], $tableOptions);
        $this->execute(
            "INSERT INTO {{%worker_identity_config}} VALUES ('1', '全时', null, null, null, '0');
             INSERT INTO {{%worker_identity_config}} VALUES ('2', '兼职', null, null, null, '0');
             INSERT INTO {{%worker_identity_config}} VALUES ('3', '高峰', null, null, null, '0');
             INSERT INTO {{%worker_identity_config}} VALUES ('4', '时段', null, null, null, '0');"
        );
    }

    public function down()
    {
        $this->dropTable('{{%worker_identity_config}}');
        return true;
    }
}
