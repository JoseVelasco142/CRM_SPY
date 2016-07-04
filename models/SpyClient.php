<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "spy_client".
 *
 * @property integer $client_id
 * @property string $name
 * @property integer $state
 * @property string $CIF
 * @property string $address
 * @property string $city
 * @property string $province
 * @property string $postal_code
 * @property string $coordinates
 * @property string $photo
 * @property string $DISC
 * @property string $comment
 * @property string $equipment
 * @property integer $commercial_id
 * @property integer $sector_id
 * @property integer $category_id
 * @property integer $faculty_id
 * @property integer $department_id
 *
 * @property SpyCategory $category
 * @property SpyCommercial $commercial
 * @property SpyDepartment $department
 * @property SpyFaculty $faculty
 * @property SpySector $sector
 * @property SpyContact[] $spyContacts
 * @property SpyTask[] $spyTasks
 */
class SpyClient extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spy_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'El nombre es obligatorio'],
            [['sector_id', 'category_id', 'state', 'postal_code', 'department_id', 'commercial_id'], 'integer'],
            [['comment', 'equipment'], 'string'],
            [['name', 'address', 'city', 'province', 'coordinates'], 'string', 'max' => 45],
            [['CIF'], 'string', 'max' => 9],
            [['photo'], 'file'],
            [['DISC'], 'string', 'max' => 18],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyCategory::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpySector::className(), 'targetAttribute' => ['sector_id' => 'sector_id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyDepartment::className(), 'targetAttribute' => ['department_id' => 'department_id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyFaculty::className(), 'targetAttribute' => ['faculty_id' => 'faculty_id']],
            [['commercial_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpyCommercial::className(), 'targetAttribute' => ['commercial_id' => 'commercial_id']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Nombre',
            'CIF' => 'CIF',
            'address' => 'Dirección',
            'city' => 'Población',
            'province' => 'Provincia',
            'postal_code' => 'C. Postal',
            'DISC' => 'DISC',
            'comment' => 'Comentarios',
            'equipment' => 'Equipamiento',
            'sector_id' => 'Sector',
            'category_id' => 'Categoría',
            'faculty_id' => 'Facultad',
            'department_id' => 'Depart.',
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
     * @return SpyCategory
     */
    public function getCategory()
    {
        return $this->hasOne(SpyCategory::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return SpySector
     */
    public function getSector()
    {
        return $this->hasOne(SpySector::className(), ['sector_id' => 'sector_id']);
    }

    /**
     * @return SpyDepartment
     */
    public function getFaculty()
    {
        return $this->hasOne(SpyFaculty::className(), ['faculty_id' => 'faculty_id']);
    }

    /**
     * @return SpyDepartment
     */
    public function getDepartment()
    {
        return $this->hasOne(SpyDepartment::className(), ['department_id' => 'department_id']);
    }

    /**
     * @return SpyContact
     */
    public function getContacts()
    {
        return $this->hasMany(SpyContact::className(), ['client_id' => 'client_id'])
            ->orderBy('main DESC');
    }
    /*
    * @return SpyContact main
    */
    public function getContactMain(){
        return $this->hasOne(SpyContact::className(), ['client_id' => 'client_id'])->where(['main' => true]);
    }


    /**
     * @return SpyTask
     */
    public function getTasks()
    {
        return $this->hasMany(SpyTask::className(), ['client_id' => 'client_id']);
    }

    /**
     * @return SpyTask count
     */
    public function getTasksCount()
    {
        return $this->hasMany(SpyTask::className(), ['client_id' => 'client_id'])->count();
    }

    /*
     * @return SpyTask
     */
    public function getLimitedTasks()
    {
        return $this->hasMany(SpyTask::className(), ['client_id' => 'client_id'])
            ->andWhere(['state' => true])
            ->orderBy('alert ASC')
            ->limit(10);
    }



    /**
     * @return SpyTask with state true
     */
    public function getOpenedTasks(){
        return$this->hasMany(SpyTask::className(), ['client_id' => 'client_id'])
            ->andWhere(['state' => true])
            ->orderBy('alert ASC');

    }

    /**
     * @inheritdoc
     * @return SpyClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SpyClientQuery(get_called_class());
    }
}
