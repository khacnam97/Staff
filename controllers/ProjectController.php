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
                         'actions' => ['index','view'],
                         'roles' => ['staff'],
                     ],
                     [
                         'allow' => true,
                         'actions' => ['create','update','view' ,'delete'],
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
            $searchModel = new ProjectSearch();
            $model = new ProjectStaff();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $staff =(new \yii\db\Query())->select('userId,projectId,user.username,GROUP_CONCAT(user.username) as nameStaff ')->from('project_staff')
                                         ->innerJoin('project' ,'project.id = project_staff.projectId')
                                         ->innerJoin('user' , 'user.id = project_staff.userId')->groupBy('projectId')->all();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'staff' => $staff,
                'model' => $model
            ]);
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
        $model = $this->findModel( $id );
        $idUser = [];
        array_push($idUser,$model->projectManagerId);
        $staffIds = ProjectStaff::find()->where(['projectId' => $id])->all();
        foreach($staffIds as $staff){
            array_push($idUser,$staff->userId);
        }
        if(\Yii::$app->user->can('viewProject' ,['idUser' => $idUser])){
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else {
            throw new ForbiddenHttpException;
        }
    }




    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
            $model = new Project();
            $staffs =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 3])->all();
            $projectManager = User::find()->where(['role' => 2])->all();
            if ($model->load(Yii::$app->request->post()) ) {
                if (Yii::$app->user->identity->role == 1){
                    $userId =$_POST['Project']['project_manager'];
                }
                else{
                    $userId =Yii::$app->user->identity->id;
                }
                $model->createDate = date('Y-m-d H:i:s');
                $model->updateDate = date('Y-m-d H:i:s');
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
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'code' => 100,
                ];
            }

            return $this->render('create', [
                'model' => $model,
                'staffs' => $staffs,
                'projectManager' => $projectManager
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
        if(\Yii::$app->user->can('updateProject' ,['project' =>$model])){
            // $model = $this->findModel($id);
            $staffs =(new \yii\db\Query())->select('id,username')->from('user')->where(['role' => 3])->all();

            /** @var ProjectStaff[] $projectStaffs */

            $projectStaffs = ProjectStaff::find()->where(['projectId' => $id])->all();
            $arrStaff = [];
            foreach ($projectStaffs as $staff) {
                array_push($arrStaff,$staff->userId);
            }
            $model->staff = $arrStaff;
            $projectManager = User::find()->where(['role' => 2])->all();

            if ($model->load(Yii::$app->request->post())) {
                $transaction =Yii::$app->db->beginTransaction();
                try {
                    if (Yii::$app->user->identity->role == 1) {
                        $userId = $_POST['Project']['project_manager'];
                    } else {
                        $userId = Yii::$app->user->identity->id;
                    }
                    $model->projectManagerId = $userId;
                    $model->updateDate = date('Y-m-d H:i:s');
                    $model->save();
                    if (Yii::$app->user->identity->role == 2) {
                        $StaffList = $_POST['Project']['staff'];
                        foreach ($StaffList as $value) {

                            if (!in_array($value, $model->staff)) {
                                $newProjectStaff = new ProjectStaff();
                                $newProjectStaff->userId = $value;
                                $newProjectStaff->projectId = $model->id;
                                $newProjectStaff->save();

                            }
                        }
                        foreach ($projectStaffs as $item) {
                            if (!in_array($item->userId, $StaffList)) {
                                $item->delete();

                            }
                        }
                    }
                $transaction->commit();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render('update', [
                'model' => $model,
                'staffs' => $staffs,
                'projectManager' => $projectManager
            ]);
        }
        else {
            throw new ForbiddenHttpException;
        }
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
