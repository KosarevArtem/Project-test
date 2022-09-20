<?php
 
namespace app\models;
 
use Yii;
use yii\base\Model;
use app\models\Users;
use app\models\Cities;
 
/**
 * This is the model class for SignupForm.
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property int $city_id
 * @property string $password
 * @property string $verification_token
 * @property string $auth_key
 * @property boolean is_performer
 *
 */
class SignupForm extends Model
{
    public $id;
    public $email;
    public $name;
    public $city_id;
    public $password;
    public $password_repeat;
    public $is_performer;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'unique', 'targetClass' => Users::className(), 'message' => 'This username has already been taken.'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => Users::className(), 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 64],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
            ['city_id', 'integer'],
            ['city_id', 'required'],
            [['city_id'], 'exist', 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id'], 'message' => 'Выберите город из списка'],
            [['is_performer'], 'safe'],
            [['is_performer'], 'default', 'value' => '1'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return Users|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Users();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->city_id = $this->city_id;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->is_performer = $this->is_performer;
        return $user->save(false) ? $user : null;
    }
}