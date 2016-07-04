<?php

namespace app\models;


use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property SpyCommercial|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;
    public $_hash = false;
    public $auth_key = false;
    public $status;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['username', 'email', 'message' => 'Usuario desconocido'],
            ['username', 'required', 'message' => 'Introduce tu nombre de usuario'],
            ['password', 'required', 'message' => 'Escribe tu clave de acceso'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->getUser() || !$this->validateKey()) {
            $this->addError($attribute, 'Usuario o password incorrecto');
            return false;
        }
        return true;
    }

    public function login()
    {
        if ($this::validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 0000 * 0000 * 30 : 0);
        }
        return null;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = SpyCommercial::findByEmail($this->username);
        }
        return $this->_user;
    }

    public function validateKey()
    {
        return SpyCommercial::validatePassword($this->username, $this->password);
    }
}
