<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Tasks;
use app\models\Replies;
use app\models\Categories;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;

class TasksController extends SecuredController
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
        $model = $this->findOrDie($id, Tasks::class);
        $replies = new Replies();
        $reply = $replies->find()->where(['task_id' => $id])->all();

        return $this->render('view', ['model' => $model, 'reply' => $reply]);
    }

    public function actionCreate()
    {
        $task = new Tasks();
        $categories = Categories::find()->all();

        if (Yii::$app->request->isPost) {
            $task->load(Yii::$app->request->post());
            var_dump($task);
            $task->save();

            if ($task->id) {
                return $this->redirect(['tasks/view', 'id' => $task->id]);
            }
        }

        return $this->render('create', ['model' => $task, 'categories' => $categories]);
    }

    public function actionValidateForm() 
    {

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $model = new Tasks();
            if($model->load(Yii::$app->request->post()))
                return \yii\widgets\ActiveForm::validate($model);
        }
        throw new \yii\web\BadRequestHttpException('Bad request!');
    }
}