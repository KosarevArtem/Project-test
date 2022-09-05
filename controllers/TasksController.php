<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Tasks;
use app\models\Replies;
use app\models\Categories;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $task = new Tasks();
        $task->load(Yii::$app->request->post());

        $taskQuery = $task->getSearchQuery();
        $categories = Categories::find()->all();

        $countQuery = clone $taskQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);
        $models = $taskQuery->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', ['models' => $models, 'task' => $task, 'categories' => $categories, 'pages' => $pages]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $task = new Tasks();
        $model = $task->find()->where(['id' => $id])->one();
        $replies = new Replies();
        $reply = $replies->find()->where(['task_id' => $id])->all();

        return $this->render('view', ['model' => $model, 'reply' => $reply]);
    }
}