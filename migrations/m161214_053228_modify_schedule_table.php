<?php

use yii\db\Migration;

class m161214_053228_modify_schedule_table extends Migration
{
    public function up()
    {
        $this->dropColumn('schedule', 'hratio_r');
        $this->dropColumn('schedule', 'cratio_r');
        $this->dropColumn('schedule', 'r_pankou');
        $this->dropColumn('schedule','cratio_dx');
        $this->dropColumn('schedule', 'hratio_dx');
        $this->dropColumn('schedule', 'dx_pankou');
        $this->dropColumn('schedule', 'cp_hratio_r');
        $this->dropColumn('schedule', 'cp_cratio_r');
        $this->dropColumn('schedule', 'cp_r_pankou');
        $this->dropColumn('schedule', 'cp_cratio_dx');
        $this->dropColumn('schedule', 'cp_hratio_dx');
        $this->dropColumn('schedule', 'cp_dx_pankou');
    }

    public function down()
    {
        echo "m161214_053228_modify_schedule_table cannot be reverted.\n";

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
