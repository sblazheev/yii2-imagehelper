<?php

namespace sblazheev\yii2imagehelper\ImageOperation\Factory;

use sblazheev\yii2imagehelper\Config;
use sblazheev\yii2imagehelper\ImageOperation\Factory\GdFactoryImageOperation as GdFactory;
use sblazheev\yii2imagehelper\ImageOperation\Factory\ImagickFactoryImageOperation as ImagickFactory;
use sblazheev\yii2imagehelper\ImageOperation\Factory\GmagickFactoryImageOperation as GmagickFactory;

/**
 * Class AbstractFactoryImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation\Factory
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Асбтрактная фабрика для семейства объектов AbstractImageOperation
 */
abstract class AbstractFactoryImageOperation {

    /**
     * Возвращает фабрику
     * @var string $factory IMAGICK/GMAGICK/GD(default)
     * @return AbstractFactoryImageOperation - дочерний объект
     * @throws Exception
     */
    public static function getFactory($factory='GD')
    {
        switch ($factory) {
            case 'IMAGICK':
                return new ImagickFactory();
                break;
            case 'GMAGICK':
                return new GmagickFactory();
                break;
            default:
            case 'GD':
                return new GdFactory();
            break;
        }
        throw new Exception('Bad config');
    }

    /**
     * Возвращает объект ImageOperation
     * @return \sblazheev\yii2imagehelper\ImageOperation\AbstractImageOperation
     */
    abstract public function getImageOperation();


}