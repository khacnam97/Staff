<?php

namespace app\controllers;

use app\models\ProjectStaff;
use app\models\User;
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
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                     [
                         'allow' => true,
                         'actions' => ['index'],
                         'roles' => ['staff'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['view'],
                         'roles' => ['staff'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['create'],
                         'roles' => ['manager'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['update'],
                         'roles' => ['manager'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['delete'],
                         'roles' => ['manager'],
                     ],
                 ],
             ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
//        if(Yii::$app->user->identity->role !=3){
            $searchModel = new ProjectSearch();
            $model = new ProjectStaff();

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $staff =(new \yii\db\Query())->select('userId,projectId,user.username,GROUP_CONCAT(user.username) as nameStaff ')->from('project_staff')
                                         ->leftJoin('project' ,'project.id = project_staff.projectId')
                                         ->leftJoin('user' , 'user.id = project_staff.userId')->groupBy('projectId')->all();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'staff' => $staff,
                'model' => $model
            ]);
//        }
//        else {
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
        $userId['userId'] = Yii::$app->user->identity->id;
        $staffId = (new \yii\db\Query())->select('userId')->from('project_staff')->where(['projectId' => $id])->all();
        if(Yii::$app->user->identity->role != 3 || in_array($userId,$staffId)){
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else {
            throw new ForbiddenHttpException;
        }
    }

    public function action($id)
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
//        if(Yii::$app->user->identity->role !=3){
            $model = new Project();
            $staff =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 3])->all();

            $projectManager =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 2])->all();
            if ($model->load(Yii::$app->request->post()) ) {
                if (Yii::$app->user->identity->role == 1){
                    $userId =$_POST['Project']['project_manager'];
                }
                else{
                    $userId =Yii::$app->user->identity->id;
                }
                $model->projectManagerId = $userId;
                $model->save();
                if (Yii::$app->user->identity->role == 2) {
                    $StaffList = $_POST['Project']['staff'];
                    foreach ($StaffList as $value)
                    {
                        $newProjectStaff = new ProjectStaff();
                        $newProjectStaff->userId = $value;
                        $newProjectStaff->projectId = $model->id ;
                        $newProjectStaff->save();
                    }
                }


                return $this->redirect(['view', 'id' => $model->id]);
            }
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                $projectId= $data['projectId'];
                $StaffList = $data['userId'];
                $staffId = (new \yii\db\Query())->select('userId')->from('project_staff')->where(['projectId' => $projectId])->all();
                $countUserId = count($StaffList);

                for ($i=0 ;$i<$countUserId ; $i++)
                {
                    $idStaff['userId'] = $StaffList[$i];
                    if(!in_array($idStaff,$staffId)){
                        $newProjectStaff = new ProjectStaff();
                        $newProjectStaff->userId = $StaffList[$i];
                        $newProjectStaff->projectId = $projectId ;
                        $newProjectStaff->save();
                    }

                }
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
//                'search' => $search,
                    'code' => 100,
                ];
            }

            return $this->render('create', [
                'model' => $model,
                'staff' => $staff,
                'projectManager' => $projectManager
            ]);
//        }
//        else{
//            throw new ForbiddenHttpException;
//        }

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
//        if(Yii::$app->user->identity->role !=3){
            $model = $this->findModel($id);
            $staff =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 3])->all();
            $staffId = (new \yii\db\Query())->select('userId')->from('project_staff')->where(['projectId' => $id])->all();
            $projectStaff =(new \yii\db\Query())->select('userId')->from('project_staff')->where(['projectId' => $id])->all();
            $projectManager =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 2])->all();

            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->user->identity->role == 1){
                    $userId =$_POST['Project']['project_manager'];
                }
                else{
                    $userId =Yii::$app->user->identity->id;
                }
                $model->projectManagerId = $userId;
                $model->save();
                if (Yii::$app->user->identity->role == 2) {
                    $StaffList = $_POST['Project']['staff'];
                    foreach ($StaffList as $value) {
                        $valueId =array();
                        $valueId['userId'] =$value;

                        if(!in_array($valueId,$staffId)){
                            $newProjectStaff = new ProjectStaff();
                            $newProjectStaff->userId = $value;
                            $newProjectStaff->projectId = $model->id;
                            $newProjectStaff->save();
                        }
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
                'staff' => $staff,
                'projectStaff' => $projectStaff,
                'projectManager' => $projectManager
            ]);
//        }
//        else{
//            throw new ForbiddenHttpException;
//        }
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
//        if(Yii::$app->user->identity->role !=3){
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
//        }
//        else{
//            throw new ForbiddenHttpException;
//        }
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
