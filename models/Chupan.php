<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chupan".
 *
 * @property integer $id
 * @property integer $scheid
 * @property string $pan
 * @property string $company
 * @property double $h_value
 * @property double $c_value
 * @property string $pankou
 */
class Chupan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chupan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheid'], 'integer'],
            [['h_value', 'c_value'], 'number'],
            [['pan', 'pankou'], 'string', 'max' => 10],
            [['company'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scheid' => 'Scheid',
            'pan' => 'Pan',
            'company' => 'Company',
            'h_value' => 'H Value',
            'c_value' => 'C Value',
            'pankou' => 'Pankou',
        ];
    }
}
