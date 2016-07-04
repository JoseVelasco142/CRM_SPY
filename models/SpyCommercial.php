<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "spy_commercial".
 *
 * @property integer $commercial_id
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $password
 *
 * @property SpyClient[] $spyClients
 * @property SpyFile[] $spyFiles
 * @property SpyNote[] $spyNotes
 */
class SpyCommercial extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_commercial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['name', 'password'], 'string', 'max' => 45],
            [['lastname'], 'string', 'max' => 75],
            [['email'], 'string', 'max' => 50],
            [['email'], 'unique'],
            ['email', 'filter', 'filter' => 'trim'],
            ['password', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commercial_id' => 'Commercial ID',
            'name' => 'Nombre',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'password' => 'Contraseña',
        ];
    }

    /**
     * @return SpyClient
     */
    public function getClients()
    {
        return $this->hasMany(SpyClient::className(), ['commercial_id' => 'commercial_id']);
    }

    /**
     * @return SpyCommercial id
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMail()
    {
        return $this->email;
    }

    public function getAuthKey()
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
    }

    /**
     * @return SpyFile
     */
    public function getSpyFiles()
    {
        return $this->hasMany(SpyFile::className(), ['commercial_id' => 'commercial_id']);
    }

    /**
     * @return SpyNote
     */
    public function getSpyNotes()
    {
        return $this->hasMany(SpyNote::className(), ['commercial_id' => 'commercial_id']);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
    }

    public static function validatePassword($user, $pass)
    {
        $user = self::findOne(['email' => $user, 'password' => $pass]);
        if($user){
            return strcmp($user->password, $pass) == 0;
        } else {
            return false;
        }
    }

    public function setPassword($password)
    {
        $this->commercial_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->commercial_auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
