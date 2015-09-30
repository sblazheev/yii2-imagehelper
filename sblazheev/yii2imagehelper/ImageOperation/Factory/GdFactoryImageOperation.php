<?php
/**

 */

namespace sblazheev\yii2imagehelper\ImageOperation\Factory;
use sblazheev\yii2imagehelper\ImageOperation\Factory\AbstractFactoryImageOperation;
use sblazheev\yii2imagehelper\ImageOperation\GdImageOperation;

/**
 * Class GdFactoryImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation\Factory
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Фабрика для создания GdImageOperation
 */
class GdFactoryImageOperation extends AbstractFactoryImageOperation{
    public function getImageOperation()
    {
        return new GdImageOperation();
    }
}