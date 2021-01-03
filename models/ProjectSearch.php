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
    public $addStaff;
    public function rules()
    {
        return [
            [['id', 'projectManagerId'], 'integer'],
            [['name', 'description', 'createDate', 'updateDate'], 'safe'],
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
        $query = Project::find();
        $role = \Yii::$app->user->identity->role;
        $userId = \Yii::$app->user->identity->id;
        if($role == 2) {
            $query = Project::find()->where(['projectManagerId' => $userId]);
        }
        if($role == 3) {
            $query = Project::find()->rightJoin('project_staff','project.id = project_staff.projectId')->where(['project_staff.userId' => $userId]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'projectManagerId' => $this->projectManagerId,
            'createDate' => $this->createDate,
            'updateDate' => $this->updateDate,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
