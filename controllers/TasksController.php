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
        $tasks = Tasks::findAll(['status_id' => 1]);

        $task = new Tasks();
        $task->load(Yii::$app->request->post());

        $taskQuery = $task->getSearchQuery();
        $categories = Categories::find()->all();

        return $this->render('index', ['models' => $tasks, 'task' => $task, 'categories' => $categories]);
    }
}