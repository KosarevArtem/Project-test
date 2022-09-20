<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity) {
            return $this->redirect(['tasks/index']);
        }

        $this->layout = 'landing';
        
        $loginForm = new LoginForm();

        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(\Yii::$app->request->post());
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                \Yii::$app->user->login($user);
                return $this->redirect(['tasks/index']);
            }
        }

        return $this->render('index', ['model' => $loginForm]);
    }

    public function actionValidateForm() 
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = new LoginForm();
            if($model->load(Yii::$app->request->post())) {
                return \yii\widgets\ActiveForm::validate($model);
            }
        }
        throw new \yii\web\BadRequestHttpException('Bad request!');
    }

    public function actionError()
    {
    $exception = Yii::$app->errorHandler->exception;
    if ($exception !== null) {
        if ($exception->statusCode == 404)
            return $this->render('errors/error404', ['exception' => $exception]);
        else
            return $this->render('errors/error', ['exception' => $exception]);
    }
}
}