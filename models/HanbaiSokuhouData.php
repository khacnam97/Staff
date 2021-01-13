<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hanbai_sokuhou_data".
 *
 * @property int $hanbai_sokuhou_data_id
 * @property string|null $trn_date 対象年月
 * @property int|null $com_code 会社CD
 * @property int|null $sokuhou_id 速報ID
 * @property int|null $chiku_id 地区ID
 * @property int|null $hanbai_daka 販売高
 * @property string|null $input_date 登録日
 */
class HanbaiSokuhouData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hanbai_sokuhou_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trn_date', 'input_date'], 'safe'],
            [['com_code', 'sokuhou_id', 'chiku_id', 'hanbai_daka'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hanbai_sokuhou_data_id' => 'Hanbai Sokuhou Data ID',
            'trn_date' => 'Trn Date',
            'com_code' => 'Com Code',
            'sokuhou_id' => 'Sokuhou ID',
            'chiku_id' => 'Chiku ID',
            'hanbai_daka' => 'Hanbai Daka',
            'input_date' => 'Input Date',
        ];
    }
}
