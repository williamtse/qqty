<?php

use yii\db\Migration;

class m161214_071033_modfiy_ratio_table extends Migration
{
    public function up()
    {
        $this->alterColumn('ratio', 'pan', $this->string(10));
        $this->alterColumn('ratio', 'company', $this->string(30));
        $this->alterColumn('ratio', 'h_value', $this->float());
        $this->alterColumn('ratio', 'c_value', $this->float());
    }

    public function down()
    {
        echo "m161214_071033_modfiy_ratio_table cannot be reverted.\n";

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
