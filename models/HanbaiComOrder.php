<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hanbai_com_order".
 *
 * @property int $hanbai_com_order_id
 * @property int|null $order_num 並び順
 * @property int|null $com_kubun 会社区分
 * @property int|null $com_cd 会社CD
 */
class HanbaiComOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hanbai_com_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_num', 'com_kubun', 'com_cd'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hanbai_com_order_id' => 'Hanbai Com Order ID',
            'order_num' => 'Order Num',
            'com_kubun' => 'Com Kubun',
            'com_cd' => 'Com Cd',
        ];
    }
}
