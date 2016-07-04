<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "spy_guide".
 *
 * @property integer $guide_id
 * @property string $short_description
 * @property string $text
 *
 * @property SpyFile[] $spyFiles
 */
class SpyGuide extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_guide';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_description', 'text'], 'required'],
            [['text'], 'string'],
            [['short_description'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'guide_id' => 'Guide ID',
            'short_description' => 'Short Description',
            'text' => 'Text',
        ];
    }

    /**
     * @return SpyFile
     */
    public function getFiles()
    {
        return $this->hasMany(SpyFile::className(), ['guide_id' => 'guide_id']);
    }
}
