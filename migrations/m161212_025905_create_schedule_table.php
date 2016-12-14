<?php

use yii\db\Migration;

/**
 * Handles the creation of table `schedule`.
 */
class m161212_025905_create_schedule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('schedule', [
            'id' => $this->primaryKey(),
            'hteam' => $this->string(30), //主队名称
            'cteam' => $this->string(30), //客队名称
            'start_time' => $this->integer(11), //开赛时间
            'start_date' => $this->integer(8), //开赛日期
            'cup_name' => $this->string(20),  //杯赛
            'hyel_card' => $this->smallInteger(3),
            'cyel_card' => $this->smallInteger(3),
            'hred_card' => $this->smallInteger(3),
            'cred_card' => $this->smallInteger(3),
            'hratio_r' => $this->float(), //主队让球赔率
            'cratio_r' => $this->float(), //客队让球赔率
            'r_pankou' => $this->string(10), //让球盘口
            'cratio_dx' => $this->float(),
            'hratio_dx' => $this->float(),
            'dx_pankou' => $this->string(10),
            'cp_hratio_r' => $this->float(), //初盘主队让球赔率
            'cp_cratio_r' => $this->float(), //初盘客队让球赔率
            'cp_r_pankou' => $this->string(10), //初盘让球盘口
            'cp_cratio_dx' => $this->float(),
            'cp_hratio_dx' => $this->float(),
            'cp_dx_pankou' => $this->string(10),//初盘大小盘口
            'hrank' => $this->smallInteger(3), //主队rank
            'crank' => $this->smallInteger(3), //客队rank
            'hscore' => $this->smallInteger(3),
            'cscore' => $this->smallInteger(3),
            'h_haf_score' => $this->smallInteger(3),
            'c_haf_score' => $this->smallInteger(3),
            'ended' => $this->boolean()->notNull()->defaultValue(0), //比赛结束
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('schedule');
    }
}
