<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\ForgetPasswordForm;
use app\models\PasswordResetForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('user/index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(Yii::$app->user->identity->role ==1) {
                return $this->redirect('user/index');
            }
            else  return $this->redirect('project/index');
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    if(Yii::$app->user->identity->role ==1) {
                        return $this->redirect('user/index');
                    }
                    else return $this->redirect('project/index');
                }
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionForgetPassword() {
        $model = new ForgetPasswordForm();
        $token = 'q';
        if ($model->load(Yii::$app->request->post())){
            $email= $_POST['ForgetPasswordForm']['email_forget'];
            $emailData =(new \yii\db\Query())->select('email')->from('user')->all();
            $emailPost['email'] =$email;
            if(in_array($emailPost,$emailData)){
                $token =(new \yii\db\Query())->select('password_reset_token')->from('user')->where(['email'=> $email])->one();
                $tokenPass = $token['password_reset_token'];
                Yii::$app->mailer->compose('mail-content', ['model' => $model, 'token' => $tokenPass])
                    ->setFrom([\Yii::$app->params['senderEmail'] => 'Reset Pass'])
                    ->setTo($email)
                    ->setSubject('This is a reset password' )
                    ->send();
                return $this->redirect('site/login');
            }
            return $this->redirect('site/login' );
        }
        return $this->render('forget-password', [
            'model' => $model
        ]);
    }
    public function actionReset($token) {
        $model = new PasswordResetForm();
        if ($model->load(Yii::$app->request->post())){
           $modeluser = User::findOne(['password_reset_token' => $token]);
            if($modeluser){
                $modeluser->password =  Yii::$app->security->generatePasswordHash($_POST['PasswordResetForm']['newpass']);
                $modeluser->generatePasswordResetToken();
                $modeluser->save();
                return $this->redirect('login');
            }
            return $this->render('reset',['model' => $model]);
        }
        return $this->render('reset',['model' => $model]);
    }
}
