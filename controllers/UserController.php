<?php

namespace app\controllers;

use app\models\Project;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PasswordForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['staff'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['staff'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['change-password'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['chart-month'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->identity->role == 1 || Yii::$app->user->identity->id == $id) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException;
        }
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
            $model = new User();
            $model->load(Yii::$app->request->post());
            if ($model->load(Yii::$app->request->post())) {

                if($model->validate()) {
                    $password = $_POST['User']['password'];
                    $model->password = Yii::$app->security->generatePasswordHash($password);
                    $model->save();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->identity->role == 1 || Yii::$app->user->identity->id == $id) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->identity->id != $id) {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangePassword($id){
        $model = new PasswordForm;
        $userId = Yii::$app->user->identity->id;
        $modeluser =  User::findOne($userId) ;

        if($model->load(Yii::$app->request->post())){
            $a = $model->validate();
            if($model->validate()){
                try{
                    $modeluser->password =Yii::$app->security->generatePasswordHash($_POST['PasswordForm']['new_password']);
                    if($modeluser->save()){
                        Yii::$app->getSession()->setFlash(
                            'success','Password changed'
                        );
                        return $this->redirect(['index']);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error','Password not changed'
                        );
                        return $this->redirect(['index']);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('change-password',[
                        'model'=>$model
                    ]);
                }
            }else{
                return $this->render('change-password',[
                    'model'=>$model
                ]);
            }
        }else{
            return $this->render('change-password',[
                'model'=>$model
            ]);
        }
    }
    public function actionChartMonth()
    {
        $projects = (new \yii\db\Query())->select('createDate , count(*) as count_project')->from('project')->groupBy('year(createDate),month(createDate),date(createDate)')->orderBy(['createDate' => SORT_ASC])->all();
        
        $countData = count($projects);
        $dataProject =[];
       
        foreach ($projects as $project){
            $data = [];
                array_push($data,$project['createDate']);
                array_push($data,$project['count_project']);
                array_push($dataProject,$data);
        }
        return $this->render('chart-month',['dataProject' => $dataProject]);
    }
}
