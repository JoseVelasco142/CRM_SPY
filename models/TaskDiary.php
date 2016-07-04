<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 31/05/2016
 * Time: 12:26
 */

namespace app\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class taskDiary extends Model
{

    public $category;
    public $sector;
    public $faculty;
    public $department;
    public $client;
    public $date;
    public $type;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['category', 'string'],
            ['sector', 'string'],
            ['faculty', 'string'],
            ['department', 'string'],
            ['client', 'string'],
            ['type', 'string'],
            ['date', 'string'],
        ];
    }

    /*
     * get normal list
     * @return \yii\db\ActiveRecord
     */

    public function search()
    {
        $app = Yii::$app;
        $dataProvider = new ActiveDataProvider([
            'query' => SpyTask::find()
                ->joinWith('client')
                ->where(['commercial_id' => $app->user->getId()])
                ->andWhere(['spy_task.state' => true]),
            'sort' => [
                'defaultOrder' => [
                    'alert' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        $dataProvider->query->andFilterWhere([
            'spy_client.category_id' => $this->category,
            'spy_client.sector_id' => $this->sector,
            'spy_client.faculty_id' => $this->faculty,
            'spy_client.department_id' => $this->department,
            'spy_task.client_id' => $this->client,
            'spy_task.taskT_id' => $this->type
        ]);
        $dataProvider->query->andFilterWhere(['LIKE', 'alert', $this->date]);
        return $dataProvider;
    }

}