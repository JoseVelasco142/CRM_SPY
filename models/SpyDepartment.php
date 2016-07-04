<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "spy_department".
 *
 * @property integer $department_id
 * @property string $name
 *
 * @property SpyClient[] $spyClients
 */
class SpyDepartment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departament_id' => 'Departament ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return SpyClient
     */
    public function getClients()
    {
        return $this->hasMany(SpyClient::className(), ['departament_id' => 'departament_id']);
    }
}
