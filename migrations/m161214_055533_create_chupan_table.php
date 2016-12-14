<?php

use yii\db\Migration;

/**
 * 初盘
 * Handles the creation of table `chupan`.
 */
class m161214_055533_create_chupan_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('chupan', [
            'id' => $this->primaryKey(),
            'scheid'=> $this->integer(),
            'pan'=> $this->string(10),
            'company'=> $this->string(30),
            'h_value'=> $this->float(),
            'c_value'=> $this->float(),
            'pankou' => $this->string(10),
            
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('chupan');
    }
}
