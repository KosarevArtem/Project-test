<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Tasks;
use app\models\Categories;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::find()->all();
        
        $task = new Tasks();
        $task->load(Yii::$app->request->post());

        $taskQuery = $task->getSearchQuery();
        $categories = Categories::find()->all();
        $models = $taskQuery->all();
        $check = $task->filterPeriod;

        return $this->render('index', ['models' => $models, 'task' => $task, 'categories' => $categories]);
    }
}