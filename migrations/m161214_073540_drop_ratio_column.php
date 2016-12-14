<?php

use yii\db\Migration;

class m161214_073540_drop_ratio_column extends Migration
{
    public function up()
    {
        $this->dropColumn('ratio', 'ratio_name');
    }

    public function down()
    {
        echo "m161214_073540_drop_ratio_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
