<?php

use yii\db\Migration;

/**
 * 及时
 * Handles the creation of table `ratio`.
 */
class m161213_084120_create_ratio_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ratio', [
            'id' => $this->primaryKey(),
            'match_id' => $this->integer(),
            'company' => $this->smallInteger(2), #公司：1-皇冠 2-澳彩...
            'ratio_name' => $this->string(10),
            'pan' => $this->smallInteger(1), #盘的类别 1-亚盘 2-欧盘...
            'h_value' => $this->string(10),
            'pankou' => $this->string(10),
            'c_value' => $this->string(10),
            'create_at' => $this->integer(11),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ratio');
    }
}
