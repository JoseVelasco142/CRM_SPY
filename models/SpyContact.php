<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spy_contact".
 *
 * @property integer $contact_id
 * @property string $name
 * @property string $phone
 * @property string $mail
 * @property integer $main
 * @property integer $position_id
 * @property integer $client_id
 *
 * @property SpyClient $client
 * @property SpyPosition $position
 */
class SpyContact extends \yii\db\ActiveRecord
{

    public static $MAIN = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'Necesitas una persona de contacto'],
            [['phone'], 'required', 'message' => 'Necesitas un teléfono'],
            [['client_id'], 'required', 'message' => 'El contacto debe de esta asignado a un cliente'],
            [['phone'], 'unique', 'message' => "Ese telefono ya esta registrado"],
            [['contact_id', 'main', 'position_id', 'client_id'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 45],
            [['mail'], 'string', 'max' => 120],
            [['mail'], 'trim'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyClient::className(), 'targetAttribute' => ['client_id' => 'client_id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyPosition::className(), 'targetAttribute' => ['position_id' => 'position_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => 'Contact ID',
            'name' => 'Nombre',
            'phone' => 'Teléfono',
            'mail' => 'Email',
            'main' => 'Main',
            'position_id' => 'Cargo',
            'client_id' => 'Client ID',
        ];
    }
    /**
     * @return SpyClient
     */
    public function getClient()
    {
        return $this->hasOne(SpyClient::className(), ['client_id' => 'client_id']);
    }

    /**
     * @return SpyPosition
     */
    public function getPosition()
    {
        return $this->hasOne(SpyPosition::className(), ['position_id' => 'position_id']);
    }
}
