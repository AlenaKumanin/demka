<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $password
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $fio
 * @property int|null $role_id
 *
 * @property Report[] $reports
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public function __toString()
    {
        return $this->login;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id'], 'integer'],
            [['login', 'password', 'email', 'phone', 'fio'], 'string', 'max' => 255],
            ['email', 'email'],
            ['phone', 'string', 'min' => 11, 'max' => 11],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'email' => 'Email',
            'phone' => 'Phone',
            'fio' => 'Fio',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Reports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }
        /**
     * @return User|null
     */

    public static function getInstance() {
        return Yii::$app->user->identity;
    }


    public static function login ($login, $password){
        $user = static::find()->where(['login' => $login])->one();
        if ($user && $user->validatePassword($password)) {
            return $user;
        }
        return null;
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function findIdentity($id)
    {
        return static::Find()->where(['id' => $id])->one();
    }

    public static function findIdentityByAccessToken($token, $tupe = null)
    {
        return null;
    }

    public function detId()
    {
        return $this->id;
    }

    public function detAuthKey()
    {
        return null;
    }

    public function validateAuthKey($autthKey)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    public function isAdmin() {
        return $this->role_id == Role::ADMIN_ROLE_ID;
    }   
}
