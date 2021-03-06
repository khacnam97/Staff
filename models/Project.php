<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $projectManagerId
 * @property string|null $description
 * @property string|null $createDate
 * @property string|null $updateDate
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $staff;
    public $project_manager;
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['projectManagerId'], 'integer'],
            [['createDate', 'updateDate'], 'safe'],
            [['name', 'description'], 'string', 'max' => 255],
            ['name', 'required'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'projectManagerId' => 'Project Manager ID',
            'description' => 'Description',
            'createDate' => 'Create Date',
            'updateDate' => 'Update Date',
            'username'  => 'Project Manager'
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'projectManagerId']);

    }
    public function getUserName() {
        return $this->user->username;
    }
}
