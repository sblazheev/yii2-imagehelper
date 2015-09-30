<?php
/**

 */

namespace sblazheev\yii2imagehelper\ImageOperation\Factory;
use sblazheev\yii2imagehelper\ImageOperation\Factory\AbstractFactoryImageOperation;
use sblazheev\yii2imagehelper\ImageOperation\ImagickImageOperation;

/**
 * Class ImagickFactoryImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation\Factory
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Фабрика для создания ImagickImageOperation
 */
class ImagickFactoryImageOperation extends AbstractFactoryImageOperation{
    public function getImageOperation()
    {
        return new ImagickImageOperation();
    }
}