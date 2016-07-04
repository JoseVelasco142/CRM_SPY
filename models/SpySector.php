<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spy_sector".
 *
 * @property integer $sector_id
 * @property string $name
 *
 * @property SpyClient[] $spyClients
 */
class SpySector extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_sector';
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
            'sector_id' => 'Sector ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return SpyClient
     */
    public function getClients()
    {
        return $this->hasMany(SpyClient::className(), ['sector_id' => 'sector_id']);
    }
}
