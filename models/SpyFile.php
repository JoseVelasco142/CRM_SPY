<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spy_file".
 *
 * @property integer $file_id
 * @property string $short_description
 * @property string $path
 * @property integer $shared
 * @property string $date
 * @property integer $commercial_id
 * @property integer $task_id
 * @property integer $guide_id
 *
 * @property SpyCommercial $commercial
 * @property SpyGuide $guide
 * @property SpyTask $task
 */
class SpyFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_description', 'path'], 'required'],
            [['path'], 'string'],
            [['shared', 'commercial_id', 'task_id', 'guide_id'], 'integer'],
            [['date'], 'safe'],
            [['short_description'], 'string', 'max' => 45],
            [['commercial_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyCommercial::className(), 'targetAttribute' => ['commercial_id' => 'commercial_id']],
            [['guide_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyGuide::className(), 'targetAttribute' => ['guide_id' => 'guide_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyTask::className(), 'targetAttribute' => ['task_id' => 'task_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'short_description' => 'Short Description',
            'path' => 'Path',
            'shared' => 'Shared',
            'date' => 'Date',
            'commercial_id' => 'Commercial ID',
            'task_id' => 'Task ID',
            'guide_id' => 'Guide ID',
        ];
    }

    /**
     * @return SpyCommercial
     */
    public function getCommercial()
    {
        return $this->hasOne(SpyCommercial::className(), ['commercial_id' => 'commercial_id']);
    }

    /**
     * @return SpyGuide
     */
    public function getGuide()
    {
        return $this->hasOne(SpyGuide::className(), ['guide_id' => 'guide_id']);
    }

    /**
     * @return SpyTask
     */
    public function getTask()
    {
        return $this->hasOne(SpyTask::className(), ['task_id' => 'task_id']);
    }
}
