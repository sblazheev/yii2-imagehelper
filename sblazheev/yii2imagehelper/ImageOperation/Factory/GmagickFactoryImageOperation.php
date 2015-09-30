<?php
/**

 */

namespace sblazheev\yii2imagehelper\ImageOperation\Factory;
use sblazheev\yii2imagehelper\ImageOperation\Factory\AbstractFactoryImageOperation;
use sblazheev\yii2imagehelper\ImageOperation\GmagickImageOperation;

/**
 * Class GmagickFactoryImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation\Factory
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Фабрика для создания GmagickImageOperation
 */
class GmagickFactoryImageOperation extends AbstractFactoryImageOperation{
    public function getImageOperation()
    {
        return new GmagickImageOperation();
    }
}