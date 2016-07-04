<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "spy_note".
 *
 * @property integer $note_id
 * @property string $short_description
 * @property string $text
 * @property integer $shared
 * @property string $date
 * @property integer $commercial_id
 *
 * @property SpyCommercial $commercial
 */
class SpyNote extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_description'], 'required'],
            [['short_description'], 'string'],
            [['shared', 'state', 'commercial_id'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string', 'max' => 45],
            [['commercial_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyCommercial::className(), 'targetAttribute' => ['commercial_id' => 'commercial_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note_id' => 'Note ID',
            'short_description' => 'Título',
            'text' => 'Descripción',
            'shared' => 'Compartida',
            'date' => 'Date',
            'commercial_id' => 'Commercial ID',
        ];
    }

    /**
     * @return SpyCommercial
     */
    public function getCommercial()
    {
        return $this->hasOne(SpyCommercial::className(), ['commercial_id' => 'commercial_id']);
    }
}
