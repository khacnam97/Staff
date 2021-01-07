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

            $query =(new \yii\db\Query())->select('po1.id as idProject, GROUP_CONCAT(u1.username) as staff,po1.description,po1.name as name_project,u2.username as projectManager')
                                         ->from('project_staff')
                                         ->innerJoin('project po1', 'po1.id = project_staff.projectId')
                                         ->innerJoin('project_staff as p1', 'p1.projectId = po1.id')
                                         ->innerJoin('user u1', 'u1.id = p1.userId')
                                         ->innerJoin('user u2', 'u2.id = po1.projectManagerId')
                                         ->where(['project_staff.userId' => $userId])
                                         ->groupBy('po1.id');
                                         $a=0;
            }
        if(\Yii::$app->user->can('manager')) {
            $query = ProjectStaff::find()->select('GROUP_CONCAT(user.username) as staff,project.*')

                                    ->rightJoin('project', 'project.id = project_staff.projectId')
                                    ->leftJoin('user', 'user.id = project_staff.userId')
                                    ->where(['project.projectManagerId' => $userId])
                                    ->groupBy('project.id');
        }
        if(\Yii::$app->user->can('admin')) {
            $query = ProjectStaff::find()->select('GROUP_CONCAT(user.username) as staff,project.*')

                                    ->rightJoin('project', 'project.id = project_staff.projectId')
                                    ->leftJoin('user', 'user.id = project_staff.userId')
                                    ->groupBy('project.id');
        }


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        $dataProvider->setSort([
            'attributes' => [
                'idProject',
                'name_project',
                'description',
                'projectManager',
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

        $query->andFilterWhere(['po1.id' => $this->idProject]);// where ==
        $query->andFilterWhere(['like','po1.name' , $this->name_project]);// where like
        $query->andFilterWhere(['like','po1.description' , $this->description]);
        $query->andFilterWhere(['like','u2.username' , $this->projectManager]);// where like
        // $query->andFilterWhere(['like','u1.username' , $this->staff]);
        if(!empty($this->staff)){
            $query->innerJoin('project po2', 'po2.id = project_staff.projectId')
            ->innerJoin('project_staff as p2', 'p2.projectId = po2.id')
            ->innerJoin('user u3', 'u3.id = p2.userId')
            ->andFilterWhere(['like','u3.username' , $this->staff]);
        }
       
        // ->groupBy('project.id');
//        $query->andFilterWhere(['like','project.project_manager' , $this->project_manager]);
        // $query->joinWith(['user as userManager' => function ($q) {
        //     $q->andwhere('userManager.username LIKE "%' . $this->username . '%"');
        // }]);

//        $query->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
