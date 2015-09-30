<?php
/**
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Класс инкапсулирующий работу с изображениями через Imagick
 */

namespace sblazheev\yii2imagehelper\ImageOperation;


use sblazheev\yii2imagehelper\Exception\IOException;
use sblazheev\yii2imagehelper\ImageOperation\AbstractImageOperation;
use sblazheev\yii2imagehelper\ImageOperation\IImageOperation;

/**
 * Class ImagickImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Класс инкапсулирующий работу с изображениями через Imagick
 */
class ImagickImageOperation extends AbstractImageOperation  implements IImageOperation {
    /**
     * @var \Imagick object
     */
    protected $ImgRes=false;
    protected $NewImgRes=false;
    protected $ImgType=null;
    protected $ImgAttr=null;

    protected static $ArrFormat=array();

    public function createImage($path)
    {
        if(file_exists($path))
        {
            parent::createImage($path);

           $this->SrcImgByte=filesize($path);
           list($this->ImgWidth, $this->ImgHeight,$this->ImgType,$this->ImgAttr) = getimagesize($path);
           $this->ImgRation=$this->ImgWidth/$this->ImgHeight;
            $this->ImgRes = new \Imagick($path);
            $this->ImgRes->setImageCompressionQuality(100);
        }else{
            throw new IOException('File '.$path.' not exist');
        }
    }
    public function saveImage($path)
    {
        if(empty($path))
        {
            header('Content-type: '.$this->ImgRes->getImageMimeType());
            header('Content-Disposition: inline; filename=' . $this->FileName);
            echo $this->ImgRes;

        }else {
            if (is_object($this->ImgRes)) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $ext = strtoupper($ext);
                switch ($ext) {

                    default:
                        $this->ImgRes->writeImage($path);
                }
            }
        }
    }
    public function resizeImage($width,$height=0,$crop=false)
    {
        //Высчитываем ширину и высоту нового изображения
        $this->ImgNewHeight = $height;
        $this->ImgNewWidth = $width;
        $this->Crop = $crop;
        $this->calcSize();

        // изменение размера
        if ($crop) {
            $this->ImgRes->cropThumbnailImage($this->ImgNewWidth, $this->ImgNewHeight);
        }else{
            if ($this->ImgRes->thumbnailImage($this->ImgResizeNewWidth, $this->ImgResizeNewHeight)) {
                $this->ImgWidth = $this->ImgNewWidth;
                $this->ImgHeight = $this->ImgNewHeight;

            }else{
                throw new \Exception('imagecopyresampled Error');
            }
       }

    }
    function __destruct(){
        if(is_object($this->ImgRes))
        {
            $this->ImgRes->clear();
            $this->ImgRes->destroy();
        }
    }
}