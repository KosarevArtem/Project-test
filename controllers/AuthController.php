<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;
use app\models\Users;
use app\models\Cities;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class AuthController extends Controller
{
    public function actionSignup()
    {
        $model = new SignupForm();
        $cities_list = Cities::find()->all();

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', ['model' => $model, 'cities' => $cities_list]);
    }

    public function actionValidateForm() 
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = new SignupForm();
            if($model->load(Yii::$app->request->post()))
                return \yii\widgets\ActiveForm::validate($model);
        }
        throw new \yii\web\BadRequestHttpException('Bad request!');
    }

    public function actionLogout() {
        
        if (Yii::$app->user->identity) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }
}