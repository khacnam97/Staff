<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\AuthItem;
use app\models\HanbaiSokuhouData;
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
                        'actions' => ['index','create','delete','chart-month'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view','update'],
                        'roles' => ['staff'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['change-password','export'],
                        'roles' => ['@'],
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
        if (\Yii::$app->user->can('admin') || Yii::$app->user->identity->id == $id) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new NotFoundHttpException;
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
                    $transaction =Yii::$app->db->beginTransaction();
                    try {
                        $password = $_POST['User']['password'];
                        $model->password = Yii::$app->security->generatePasswordHash($password);
                        $model->save();

                        $permisstionId = $_POST['User']['role'];
                        $auth = \Yii::$app->authManager;
                        $authorRole = $auth->getRole($permisstionId);
                        $auth->assign($authorRole, $model->getId());
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    catch(Exception $e)
                    {
                        $transaction->rollBack();
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }

                }
                else{
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            $model->id ='';
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
        if (\Yii::$app->user->can('admin') || Yii::$app->user->identity->id == $id) {
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
            Yii::$app->db->createCommand()->delete('project_staff',['userId' => $id])->execute();
            Yii::$app->db->createCommand()->delete('project',['projectManagerId' => $id])->execute();
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
        $users = (new \yii\db\Query())->select('user.username,count(*) as count_user')->from('user')->innerJoin('project_staff','project_staff.userId = user.id')->groupBy('project_staff.userId')->all();

        $dataProject =[];
        foreach ($projects as $project){
            $data = [];
                array_push($data,$project['createDate']);
                array_push($data,$project['count_project']);
                array_push($dataProject,$data);
        }
        $dataNameUser = [];
        $dataCountProject = [];
        foreach ($users as $user){
            array_push($dataNameUser,$user['username']);
            array_push($dataCountProject,$user['count_user']);
        }
        return $this->render('chart-month',['dataProject' => $dataProject,'dataNameUser' => $dataNameUser,'dataCountProject' => $dataCountProject]);
    }
    public function getDataHanbai(){
        $data = (new \yii\db\Query())->select('*')->from('company')
                ->innerJoin('hanbai_sokuhou_data','hanbai_sokuhou_data.com_code = company.com_cd')
                ->innerJoin('hanbai_com_order','hanbai_com_order.com_cd = company.com_cd')
                ->where(['like','hanbai_sokuhou_data.trn_date','2004-09'])
                ->andWhere(['hanbai_sokuhou_data.sokuhou_id' => 2])
                ->orderBy('hanbai_com_order.hanbai_com_order_id')->all();
        return $data;
    }
    public function  actionExport()
    {
        $model = new HanbaiSokuhouData();
        if ($model->load(Yii::$app->request->post())){
            $data = $this->getDataHanbai();
            $pathFileTemplate ='C:\xampp\htdocs\Staff\views\excel\販売速報統計_白紙.xlsx';
            $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($pathFileTemplate);
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
            $objPhpSpreadsheet = $objReader->load($pathFileTemplate);
            $fileName ='販売速報統計_白紙.xlsx';
            $this->addData($objPhpSpreadsheet->setActiveSheetIndex('0'), $data);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'. $fileName .'"');
            header('Cache-Control: max-age=0');
            $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPhpSpreadsheet, 'Xlsx');
            $objWriter->save('php://output');
        }
        return $this->render('export',['model' =>$model]);
    }
    public function  addData($setCell, $data){
        $setCell->setCellValue('A2', '対象年月(YYYY年MM月)　統計種類名　社別販売速報明細');
        $index = 1;
        $rowBegin = 7;
        $rowCurrent = 7;
        $length = count($data);
        $setCell->insertNewRowBefore($rowBegin, $length); //insert row len before rowbegin
        for ($i = 0; $i < $length; $i++) {
            $setCell
                ->setCellValue('B' . $rowCurrent, $index)
                ->setCellValue('E' . $rowCurrent, 'HYO')
                ->setCellValue('F' . $rowCurrent, 'F')
                ->setCellValue('G' . $rowCurrent, 'F')
                ->setCellValue('H' . $rowCurrent, 'HYO')
                ->setCellValue('J' . $rowCurrent, 'F')
                ->setCellValue('K' . $rowCurrent, 'F')
                ->setCellValue('M' . $rowCurrent, 'HYO')
                ->setCellValue('N' . $rowCurrent, 'F')
                ->setCellValue('O' . $rowCurrent, 'F')
                ->setCellValue('P' . $rowCurrent, 'HYO')
                ->setCellValue('Q' . $rowCurrent, 'F')
                ->setCellValue('R' . $rowCurrent, 'F')
                ->setCellValue('T' . $rowCurrent, 'F');
            $index++;
            $rowCurrent++;
        }
        $setCell->removeRow($rowBegin -1);
        return $this;
    }

}
