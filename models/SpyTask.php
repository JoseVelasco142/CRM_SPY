<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "spy_task".
 *
 * @property integer $task_id
 * @property integer $state
 * @property string $subject
 * @property string $description
 * @property string $date
 * @property string $alert
 * @property string $report
 * @property integer $client_id
 * @property integer $taskT_id
 *
 * @property SpyFile[] $spyFiles
 * @property SpyTasktype $taskT
 * @property SpyClient $client
 */
class SpyTask extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state', 'client_id', 'taskT_id'], 'integer'],
            [['subject', 'description', 'client_id', 'taskT_id'], 'required'],
            [['description'], 'string'],
            [['date', 'alert'], 'safe'],
            [['subject', 'report'], 'string', 'max' => 50],
            [['taskT_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyTasktype::className(), 'targetAttribute' => ['taskT_id' => 'taskT_id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyClient::className(), 'targetAttribute' => ['client_id' => 'client_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'state' => 'State',
            'subject' => 'Subject',
            'description' => 'Description',
            'date' => 'Date',
            'alert' => 'Alert',
            'report' => 'Report',
            'client_id' => 'Client ID',
            'taskT_id' => 'Task T ID',
        ];
    }

    /**
     * @return SpyFile
     */
    public function getFiles()
    {
        return $this->hasMany(SpyFile::className(), ['task_id' => 'task_id'])->select(['file_id','short_description','path','date']);
    }

    /**
     * @return SpyTaskType
     */
    public function getTaskType()
    {
        return $this->hasOne(SpyTasktype::className(), ['taskT_id' => 'taskT_id']);
    }

    /**
     * @return SpyClient
     */
    public function getClient()
    {
        return $this->hasOne(SpyClient::className(), ['client_id' => 'client_id']);
    }

    /*
     * find the imminent task and save state of client as this taskType
     */
    public function setNewState($client){
        $time = new \DateTime('now');
        if($client instanceof SpyClient){
            $nexTask =  self::find()->where(['client_id' => $client->client_id])
                ->andWhere(['state' => true])
                ->andWhere(['>=', 'alert', $time->format('Y-m-d h:m:s')])
                ->orderBy('alert ASC')->one();
            if($nexTask instanceof SpyTask ){
                if($nexTask){
                    $client->state = $nexTask->taskType->taskT_id;
                    $client->save(false);
                } else {
                    $client->state = 0;
                    $client->save(false);
                }
            }
        }
        return null;
    }
}
