<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property integer $id
 * @property string $hteam
 * @property string $cteam
 * @property integer $start_time
 * @property integer $start_date
 * @property string $cup_name
 * @property integer $hyel_card
 * @property integer $cyel_card
 * @property integer $hred_card
 * @property integer $cred_card
 * @property integer $hrank
 * @property integer $crank
 * @property integer $hscore
 * @property integer $cscore
 * @property integer $h_haf_score
 * @property integer $c_haf_score
 * @property integer $ended
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'start_date', 'hyel_card', 'cyel_card', 'hred_card', 'cred_card', 'hrank', 'crank', 'hscore', 'cscore', 'h_haf_score', 'c_haf_score', 'ended'], 'integer'],
            [['hteam', 'cteam'], 'string', 'max' => 30],
            [['cup_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hteam' => 'Hteam',
            'cteam' => 'Cteam',
            'start_time' => 'Start Time',
            'start_date' => 'Start Date',
            'cup_name' => 'Cup Name',
            'hyel_card' => 'Hyel Card',
            'cyel_card' => 'Cyel Card',
            'hred_card' => 'Hred Card',
            'cred_card' => 'Cred Card',
            'hrank' => 'Hrank',
            'crank' => 'Crank',
            'hscore' => 'Hscore',
            'cscore' => 'Cscore',
            'h_haf_score' => 'H Haf Score',
            'c_haf_score' => 'C Haf Score',
            'ended' => 'Ended',
        ];
    }
}
