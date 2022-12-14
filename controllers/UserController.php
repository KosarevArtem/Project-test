<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Users;
use app\models\UserCategories;
use app\models\Opinions;
use app\models\Tasks;
use app\models\Statuses;
use app\models\Replies;
use app\models\Categories;
use yii\web\NotFoundHttpException;

class UserController extends SecuredController
{
    public function actionView($id)
    {
        $profile = $this->findOrDie($id, Users::class);
        $categories = new UserCategories();
        $userCategories = $categories->find()->where(['user_id' => $id])->all();
        $opinions = new Opinions;
        $userOpinions = $opinions->find()->where(['performer_id' => $id])->all();
        $tasks = new Tasks();
        $completedTasks = $tasks->find()->where(['performer_id' => $id])->andWhere(['status_id' => Statuses::STATUS_COMPLETE])->count();
        $expiredTasks = $tasks->find()->where(['performer_id' => $id])->andWhere(['status_id' => Statuses::STATUS_EXPIRED])->count();


        return $this->render('view', ['profile' => $profile, 'categories' => $userCategories, 'opinions' => $userOpinions, 'completedTasks' => $completedTasks, 'expiredTasks' => $expiredTasks]);
    }
}