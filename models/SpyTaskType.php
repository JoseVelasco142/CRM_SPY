<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "spy_task_type".
 *
 * @property integer $id_task_type
 * @property string $name
 * @property string $color
 *
 * @property SpyTask[] $spyTasks
 */
class SpyTaskType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_taskt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'color'], 'required'],
            [['color'], 'string'],
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taskT_id' => 'Task T ID',
            'name' => 'Name',
            'color' => 'Color',
        ];
    }

    /**
     * @return SpyTask
     */
    public function getTasks()
    {
        return $this->hasMany(SpyTask::className(), ['taskT_id' => 'taskT_id']);
    }

}
