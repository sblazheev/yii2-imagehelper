<?php

namespace sblazheev\yii2imagehelper\ImageOperation;

/**
 * Interface IImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Интерфейсный класс для семейства классов AbstractImageOperation
 */
interface IImageOperation {
    /**
     * Создание объекта изображения из файла
     * @param $path Путь к файлу
     * @throw IOException
     * @return void
     */
    public function createImage($path);

    /**
     * Изменение размеров изображения
     * @param $width Ширина
     * @param int $height Высота
     * @param bool $crop True обрезать точно по размеру
     * @throw Exception
     * @return void
     */
    public function resizeImage($width,$height=0,$crop=false);

    /**
     * Сохранение изображения в файл
     * @param $path путь сохранения
     * @throw IOException
     * @return void
     */
    public function saveImage($path);
}