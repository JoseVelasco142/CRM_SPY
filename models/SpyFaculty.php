<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "spy_faculty".
 *
 * @property integer $faculty_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $coordinates
 * @property string $postal_code
 *
 * @property SpyClient[] $spyClients
 */
class SpyFaculty extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_faculty';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province', 'city', 'address', 'coordinates', 'postal_code'], 'required'],
            [['name', 'province', 'city', 'address', 'coordinates'], 'string'],
            [['postal_code'], 'integer', 'max' => '9'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faculty_id' => 'Faculty ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'address' => 'Address',
            'coordinates' => 'Coordinates',
            'postal_code' => 'Postal Code',
        ];
    }

    /**
     * @return SpyClient
     */
    public function getClients()
    {
        return $this->hasMany(SpyClient::className(), ['faculty_id' => 'faculty_id']);
    }
}
