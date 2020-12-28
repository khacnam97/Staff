<?php

namespace app\controllers;

use app\models\ProjectStaff;
use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'rules' => [
            //         [
            //             'allow' => true,
            //             'actions' => ['index'],
            //             'roles' => ['@'],
            //         ],
            //         [
            //             'allow' => true,
            //             'actions' => ['view'],
            //             'roles' => [1],
            //         ],
            //         [
            //             'allow' => true,
            //             'actions' => ['create'],
            //             'roles' => [1],
            //         ],
            //         [
            //             'allow' => true,
            //             'actions' => ['update'],
            //             'roles' => [1],
            //         ],
            //         [
            //             'allow' => true,
            //             'actions' => ['delete'],
            //             'roles' => [1],
            //         ],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
//        if(Yii::$app->user->identity->role ==1){
            $searchModel = new ProjectSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $staff =(new \yii\db\Query())->select('userId,projectId,user.username,GROUP_CONCAT(user.username) as nameStaff ')->from('project_staff')
                                         ->leftJoin('project' ,'project.id = project_staff.projectId')
                                         ->leftJoin('user' , 'user.id = project_staff.userId')->groupBy('projectId')->all();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'staff' => $staff
            ]);
//        }
//        else{
//            throw new ForbiddenHttpException;
//        }
    
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $staff =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 3])->all();
        if ($model->load(Yii::$app->request->post()) ) {
            $userId =Yii::$app->user->identity->id;
            $model->projectManagerId = $userId;
            $model->save();
            $StaffList = $_POST['Project']['staff'];
            foreach ($StaffList as $value)
            {
                $newProjectStaff = new ProjectStaff();
                $newProjectStaff->userId = $value;
                $newProjectStaff->projectId = $model->id ;
                $newProjectStaff->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'staff' => $staff
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $staff =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 3])->all();
        $projectStaff =(new \yii\db\Query())->select('userId')->from('project_staff')->where(['projectId' => $id])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $StaffList = $_POST['Project']['staff'];
            foreach ($StaffList as $value)
            {
                $newProjectStaff = new ProjectStaff();
                $newProjectStaff->userId = $value;
                $newProjectStaff->projectId = $model->id ;
                $newProjectStaff->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'staff' => $staff,
            'projectStaff' => $projectStaff
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
