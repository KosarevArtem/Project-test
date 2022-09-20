<?php

namespace app\models;

use Yii;
use taskforce\logic\actions\AbstractAction;
use taskforce\logic\actions\CancelAction;
use taskforce\logic\AvailableActions;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $description
 * @property int|null $city_id
 * @property int|null $budget
 * @property string|null $expire_dt
 * @property string|null $dt_add
 * @property int $client_id
 * @property int|null $performer_id
 * @property int $status_id
 *
 * @property Categories $category
 * @property Events[] $events
 * @property Files[] $files
 * @property Messages[] $messages
 * @property Replies[] $replies
 * @property Statuses $status
 */
class Tasks extends ActiveRecord
{

    public $noResponses;
    public $noLocation;
    public $filterPeriod;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'client_id',
                'updatedByAttribute' => null
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id'], 'default', 'value' => function($model, $attr) {
                return Statuses::find()->select('id')->where('id=1')->scalar();
            }],
            [['city_id'], 'default', 'value' => function($model, $attr) {
                if (\Yii::$app->user->getIdentity()->city_id) {
                    return \Yii::$app->user->getIdentity()->city_id;
                }
    
                return null;
            }],
            [['noResponses', 'noLocation'], 'boolean'],
            [['filterPeriod'], 'number'],
            [['category_id', 'city_id', 'budget', 'client_id', 'performer_id', 'status_id'], 'integer'],
            [['description'], 'string'],
            [['expire_dt', 'dt_add'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Statuses::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['performer_id' => 'id']],
            [['name', 'category_id', 'description', 'client_id', 'status_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'category_id' => 'Категория',
            'description' => 'Описание',
            'city_id' => 'Город',
            'budget' => 'Стоимость',
            'expire_dt' => 'Дата окончания',
            'dt_add' => 'Дата создания',
            'client_id' => 'Заказчик',
            'performer_id' => 'Исполнитель',
            'status_id' => 'Статус',
            'noLocation' => 'Удаленная работа',
            'noResponses' => 'Без откликов'
        ];
    }


    public function getSearchQuery()
    {
        $query = self::find();
        $query->where(['status_id' => Statuses::STATUS_NEW]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        if ($this->noLocation) {
            $query->andWhere('city_id IS NULL');
        }

        if ($this->noResponses) {
            $query->joinWith('replies r')->andWhere('r.id IS NULL');
        }

        if ($this->filterPeriod) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_add) > UNIX_TIMESTAMP() - :period', [':period' => $this->filterPeriod]);
        }

        return $query->orderBy('dt_add DESC');
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Events]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Users::className(), ['id' => 'client_id']);
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
}
