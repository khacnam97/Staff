<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hanbai_chiku".
 *
 * @property int|null $chiku_id
 * @property string|null $chiku_name
 */
class HanbaiChiku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hanbai_chiku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chiku_id'], 'integer'],
            [['chiku_name'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'chiku_id' => 'Chiku ID',
            'chiku_name' => 'Chiku Name',
        ];
    }
}
