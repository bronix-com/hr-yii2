<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель для городов
 *
 * @property int $id
 * @property string $name - Название
 * @property int $creator_id - Создатель
 * @property string $created_at - Дата создания
 */
class City extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'creator_id', 'created_at'], 'required'],
            [['creator_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название города',
            'creator_id' => 'Id создателя',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * Получает все города
     * @return City[]
     */
    public function getAllCities(): array
    {
        $cities = City::find()->all();
        $result = [];

        foreach ($cities as $city) {
            $creator = User::find()->where(['id' => $city->creator_id])->one();
            $city->creator = $creator;
            $result[] = $city;
        }

        return $result;
    }

    /**
     * Получает уникальных создателей городов
     * @return User[]
     */
    public function getAllCreators(): array
    {
        $cities = City::find()->all();
        $result = [];

        foreach ($cities as $city) {
            $creator = User::find()->where(['id' => $city->creator_id])->one();
            $result[] = $creator;
        }

        return $result;
    }
}
