<?php

use app\models\City;

/**
 * Класс для работы с разными функциями в проекте
 */
class MyFunc
{
    /**
     * Получить погоду в Алматы по API
     * @return void
     */
    static private function getWeatherFromApi()
    {
        global $weatherInfo;
        $weatherInfo = json_decode(
            file_get_contents('https://api.weather.yandex.ru/v2/forecast?lat=43.238949&lon=76.889709&lang=ru_RU'),
            true
        );
    }

    /**
     * Получить погоду в Алматы из БД
     * @return array
     */
    private function getWeather(): array
    {
        global $weatherInfo;
        self::getWeatherFromApi();
        // Переводим температуру из Кельвинов в Цельсии
        $weatherInfo['fact']['temp'] = $weatherInfo['fact']['temp'] - 273.15;
        return $weatherInfo;
    }

    /**
     * Получить информацию о пользователе
     * @return array
     */
    function getCityInfo(): array
    {
        $id = $_GET['cityId'];
        $city = City::findOne($id);
        return $city->attributes;
    }

    function writeToFile($filename): void
    {
        $file = fopen($filename, 'a');
        fwrite($file, 'Hello World');
        fclose($file);
    }

    function writeToFileSafe($filename): void
    {
        $file = @fopen($filename, 'a');
        fwrite($file, 'Hello World');
        fclose($file);
    }

    function setWeather(City &$city) {
        $city->weather = self::getWeather();
    }

    public static function b($n): string
    {
        return decbin($n);
    }

    public static function functionForCountingFibonacciNumbers($n): int
    {
        if ($n < 3) {
            return 1;
        }
        return self::functionForCountingFibonacciNumbers($n - 1) + self::functionForCountingFibonacciNumbers($n - 2);
    }

    /**
     * Возвращает текущую дату в формате Y-m-d
     * @return string
     */
    public static function getCurrentDate(): string
    {
        return date('Y-m-d');
    }

    /**
     * Возвращает текущую дату в формате Y-m-d H:i:s
     * @return string
     */
    public static function getCurrentDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Возвращает текущую дату в формате d.m.Y
     * @return string
     */
    public static function getCurrentDateForView(): string
    {
        return date('d.m.Y');
    }

    /**
     * Возвращает текущую дату в формате d.m.Y H:i:s
     * @return string
     */
    public static function getCurrentDateTimeForView(): string
    {
        return date('d.m.Y H:i:s');
    }

    /**
     * Возвращает текущую дату в формате d.m.Y H:i
     * @return string
     */
    public static function getCurrentDateTimeForViewWithoutSeconds(): string
    {
        return date('d.m.Y H:i');
    }

    /**
     * Возвращает текущую дату в формате d.m.Y H:i
     * @return string
     */
    public static function getCurrentDateTimeForViewWithoutSecondsAndYear(): string
    {
        return date('d.m H:i');
    }
}
