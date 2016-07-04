<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 14/05/2016
 * Time: 3:08
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ClientList extends Model
{

    public $category;
    public $sector;
    public $faculty;
    public $department;
    public $search;
    public $location;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['search', 'string'], 'trim'],
            ['search', 'string'],
            ['category', 'string'],
            ['sector', 'string'],
            ['faculty', 'string'],
            ['department', 'string'],
            ['location', 'string'],
        ];
    }

    /*
     * get normal list
     * @return \yii\db\ActiveRecord
     */
    public function search() {
        $app = Yii::$app;
        $dataProvider = new ActiveDataProvider([
            'query' => SpyClient::find()
                ->joinWith('contacts')
                ->where(['spy_client.commercial_id' => $app->user->id]),
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);
        $dataProvider->query->andFilterWhere([
            'category_id' => $this->category,
            'sector_id' => $this->sector,
            'faculty_id' => $this->faculty,
            'department_id' => $this->department
        ]);
        $dataProvider->query->andFilterWhere(['LIKE', 'spy_client.name', $this->search]);
        $dataProvider->query->orFilterWhere(['LIKE', 'spy_contact.phone', $this->search,])->andFilterWhere([ 'spy_client.commercial_id' => $app->user->id]);
        $dataProvider->query->andFilterWhere(['LIKE', 'province', $this->location]);
        $dataProvider->query->orFilterWhere(['LIKE', 'city', $this->location,])->andFilterWhere([ 'spy_client.commercial_id' => $app->user->id]);
        return $dataProvider;
    }

}