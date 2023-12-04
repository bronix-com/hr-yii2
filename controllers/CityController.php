<?php

namespace app\controllers;

use app\models\City;
use MyFunc;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Контроллер для городов
 */
class CityController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Выводит список городов
     * @return string
     */
    public function actionIndex(): string
    {
        $cities = (new City())->getAllCities();

        return $this->render(
            'index',
            [
                'cities' => $cities,
            ]
        );
    }

    /**
     * Выводит расширенную информацию о городах
     * @return string
     */
    public function actionExtendedCitiesInfo(): string
    {
        // Получаем все города
        $cities = City::find()->all();

        // Для каждого города получаем создателя
        foreach ($cities as $city) {
            $creator = User::find()->where(['id' => $city->creator_id])->one();
            $city->creator = $creator;
            MyFunc::setWeather($city);
        }

        // Сортируем города по дате создания
        usort($cities, function ($a, $b) {
            return strtotime($a->created_at) - strtotime($b->created_at);
        });

        // Формируем массив для вывода
        $result = [];
        foreach ($cities as $city) {
            $result[] = [
                'city_name' => $city->name,
                'creator_name' => $city->creator->name,
                'created_at' => $city->created_at,
                'weather' => $city->weather ?? 'No weather data available',
            ];
        }

        // Рендерим представление
        return $this->render(
            'extended-cities-info',
            [
                'result' => $result,
            ]
        );
    }

    /**
     * Выводит детальную информацию о городе
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Находит модель города по ее id
     * @param integer $id
     * @return City
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): City
    {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
