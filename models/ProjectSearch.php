<?php

namespace app\models;

use app\models\Project;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProjectSearch represents the model behind the search form of `app\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * {@inheritdoc}
     */
    public $username;
    public $name_project;
    public $staff;
    public $idProject;
    public $projectManager;
    public function rules()
    {
        return [
            [['id', 'projectManagerId'], 'integer'],
            [['name', 'description', 'createDate','idProject', 'updateDate','username','projectManager','staff','name_project'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }



    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $userId = \Yii::$app->user->identity->id;

        if(\Yii::$app->user->can('staff')) {
            $query = Project::find()->rightJoin('project_staff','project.id = project_staff.projectId')->where(['project_staff.userId' => $userId]);
        }
        if(\Yii::$app->user->can('manager')) {
            $query = Project::find()->innerJoin('user','user.id = project.projectManagerId')->where(['project.projectManagerId' => $userId]);
        }
        if(\Yii::$app->user->can('admin')) {
            $query = Project::find();
        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'description',
                'username',
                'staff'
            ]
        ]);
        $this->load($params);
        $a= $params;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->joinWith(['user']);
            return $dataProvider;
        }

        $query->andFilterWhere(['project.id' => $this->id]);// where ==
        $query->andFilterWhere(['like','project.name' , $this->name]);// where like
        $query->andFilterWhere(['like','project.description' , $this->description]);
        $query->joinWith(['user as userManager' => function ($q) {
            $q->andwhere('userManager.username LIKE "%' . $this->username . '%"');
        }]);
        if(!empty($this->staff)){
            $query->innerJoin('project_staff as p2', 'p2.projectId = project.id')
            ->innerJoin('user u3', 'u3.id = p2.userId')
            ->andFilterWhere(['like','u3.username' , $this->staff]);
        }

        return $dataProvider;
    }
}
