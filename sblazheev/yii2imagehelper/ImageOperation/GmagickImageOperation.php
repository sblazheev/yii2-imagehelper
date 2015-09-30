<?php
/**
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Класс инкапсулирующий работу с изображениями через Gmagick
 */

namespace sblazheev\yii2imagehelper\ImageOperation;


use sblazheev\yii2imagehelper\Exception\IOException;
use sblazheev\yii2imagehelper\ImageOperation\AbstractImageOperation;
use sblazheev\yii2imagehelper\ImageOperation\IImageOperation;

/**
 * Class GmagickImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Класс инкапсулирующий работу с изображениями через Gmagick
 */
class GmagickImageOperation extends AbstractImageOperation  implements IImageOperation {
    /**
     * @var Image object
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
            $this->ImgRes = new \Gmagick($path);
        }else{
            throw new IOException('File '.$path.' not exist');
        }
    }
    public function saveImage($path)
    {
        if(is_object($this->ImgRes)) {
            $ext=pathinfo($path, PATHINFO_EXTENSION);
            $ext=strtoupper($ext);
            switch ($ext){

                default:
                    $this->ImgRes->writeImage( $path );
            }
        }
    }
    public function resizeImage($width,$height=0,$crop=false){
        //Высчитываем ширину и высоту нового изображения
        $this->ImgNewHeight=$height;
        $this->ImgNewWidth=$width;
        $this->Crop=$crop;
        $this->calcSize();

        // изменение размера
        if($this->ImgRes->resizeimage($this->ImgResizeNewWidth, $this->ImgResizeNewHeight,1,2))
        {
            if($crop)
            {
                $y=round($this->ImgResizeNewHeight/2)-round($this->ImgNewHeight/2);
                $this->ImgRes->cropimage($this->ImgNewWidth, $this->ImgNewHeight,0,$y);
            }
            $this->ImgWidth=$this->ImgNewWidth;
            $this->ImgHeight=$this->ImgNewHeight;

        }else{
            throw new \Exception('imagecopyresampled Error');
        }
    }
    function __destruct(){
        if(is_object($this->ImgRes))
        {
            unset($this->ImgRes);
        }
    }
}