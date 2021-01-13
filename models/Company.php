<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $company_id
 * @property int|null $com_cd
 * @property string|null $com_name
 * @property string|null $com_yomi
 * @property string|null $com_disp1
 * @property string|null $com_disp2
 * @property string|null $post_no
 * @property int|null $pref_cd
 * @property string|null $adr1
 * @property string|null $adr2
 * @property string|null $adr3
 * @property string|null $adr4
 * @property string|null $biko
 * @property string|null $reg_date
 * @property string|null $update_date
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['com_cd', 'pref_cd'], 'integer'],
            [['biko'], 'string'],
            [['reg_date', 'update_date'], 'safe'],
            [['com_name', 'com_yomi', 'com_disp1', 'com_disp2'], 'string', 'max' => 50],
            [['post_no'], 'string', 'max' => 8],
            [['adr1'], 'string', 'max' => 120],
            [['adr2', 'adr3', 'adr4'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'com_cd' => 'Com Cd',
            'com_name' => 'Com Name',
            'com_yomi' => 'Com Yomi',
            'com_disp1' => 'Com Disp1',
            'com_disp2' => 'Com Disp2',
            'post_no' => 'Post No',
            'pref_cd' => 'Pref Cd',
            'adr1' => 'Adr1',
            'adr2' => 'Adr2',
            'adr3' => 'Adr3',
            'adr4' => 'Adr4',
            'biko' => 'Biko',
            'reg_date' => 'Reg Date',
            'update_date' => 'Update Date',
        ];
    }
}
