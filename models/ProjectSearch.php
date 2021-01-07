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
    public function rules()
    {
        return [
            [['id', 'projectManagerId'], 'integer'],
            [['name', 'description', 'createDate', 'updateDate','username','staff','name_project'], 'safe'],
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

            $query =ProjectStaff::find()->innerJoin('project', 'project.id = project_staff.projectId')
                                         ->innerJoin('project_staff as p1', 'p1.projectId = project.id')
                                         ->innerJoin('user', 'user.id = project_staff.userId')
                                         ->select('project.id, GROUP_CONCAT(user.username) as staff,project.*,project.name as name_project,user.username as project_manager')
                                         ->where(['project_staff.userId' => $userId])
                                         ->groupBy('project.id')->all();
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
                'id',
                'name_project',
                'description',
                'username'
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
        $query->andFilterWhere(['like','project.description' , $this->description]);// where like
//        $query->andFilterWhere(['like','project.project_manager' , $this->project_manager]);
        $query->joinWith(['user as userManager' => function ($q) {
            $q->andwhere('userManager.username LIKE "%' . $this->username . '%"');
        }]);

//        $query->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
