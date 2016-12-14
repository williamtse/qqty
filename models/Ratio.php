<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ratio".
 *
 * @property integer $id
 * @property integer $match_id
 * @property string $company
 * @property string $pan
 * @property double $h_value
 * @property string $pankou
 * @property double $c_value
 * @property integer $create_at
 */
class Ratio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ratio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['match_id', 'create_at'], 'integer'],
            [['h_value', 'c_value'], 'number'],
            [['company'], 'string', 'max' => 30],
            [['pan', 'pankou'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'company' => 'Company',
            'pan' => 'Pan',
            'h_value' => 'H Value',
            'pankou' => 'Pankou',
            'c_value' => 'C Value',
            'create_at' => 'Create At',
        ];
    }
}
