<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spy_category".
 *
 * @property integer $category_id
 * @property string $name
 *
 * @property SpyClient[] $spyClients
 */
class SpyCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
            [['name'], 'unique',]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return SpyClient
     */
    public function getClients()
    {
        return $this->hasMany(SpyClient::className(), ['category_id' => 'category_id']);
    }
}
