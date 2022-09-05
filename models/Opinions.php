<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "opinions".
 *
 * @property int $id
 * @property int $task_id
 * @property int $owner_id
 * @property int $performer_id
 * @property int $rate
 * @property string $description
 * @property string|null $dt_add
 *
 * @property Users $owner
 * @property Users $performer
 * @property Tasks $task
 */
class Opinions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opinions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'owner_id', 'performer_id', 'rate', 'description'], 'required'],
            [['task_id', 'owner_id', 'performer_id', 'rate'], 'integer'],
            [['description'], 'string'],
            [['dt_add'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['performer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'owner_id' => 'Owner ID',
            'performer_id' => 'Performer ID',
            'rate' => 'Rate',
            'description' => 'Description',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(Users::className(), ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'task_id']);
    }
}
