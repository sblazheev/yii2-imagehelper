<?php

namespace sblazheev\yii2imagehelper\ImageOperation;

use sblazheev\yii2imagehelper\Exception\IOException;
use sblazheev\yii2imagehelper\ImageOperation\AbstractImageOperation;
use sblazheev\yii2imagehelper\ImageOperation\IImageOperation;

/**
 * Class GdImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Класс инкапсулирующий работу с изображениями через GD
 */
class GdImageOperation extends AbstractImageOperation  implements IImageOperation {
    /**
     * @var GD res
     */
    protected $ImgRes=false;
    protected $NewImgRes=false;
    protected $ImgType=null;
    protected $ImgAttr=null;
    protected static $ArrFormat=array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP', 7 => 'TIFF(orden de bytes intel)', 8 => 'TIFF(orden de bytes motorola)', 9 => 'JPC', 10 => 'JP2', 11 => 'JPX', 12 => 'JB2', 13 => 'SWC', 14 => 'IFF', 15 => 'WBMP', 16 => 'XBM');

    public function createImage($path)
    {
        if(file_exists($path))
        {
            parent::createImage($path);

           $this->SrcImgByte=filesize($path);
           list($this->ImgWidth, $this->ImgHeight,$this->ImgType,$this->ImgAttr) = getimagesize($path);
           $this->ImgRation=$this->ImgWidth/$this->ImgHeight;
           $this->ImgRes=imagecreatefromstring(file_get_contents($path));
        }else{
            throw new IOException('File '.$path.' not exist');
        }
    }
    public function saveImage($path)
    {
        if(is_resource($this->ImgRes)) {
            $ext=pathinfo($path, PATHINFO_EXTENSION);
            $ext=strtoupper($ext);
            switch ($ext){
                case 'PNG':
                    imagepng($this->ImgRes, $path, 9);
                    break;
                case 'GIF':
                    imagegif($this->ImgRes, $path);
                    break;
                default:
                imagejpeg($this->ImgRes, $path, 80);
            }
        }
    }
    public function resizeImage($width,$height=0,$crop=false){
        $this->ImgNewHeight=$height;
        $this->ImgNewWidth=$width;
        $this->Crop=$crop;
        $this->calcSize();

        $this->NewImgRes = imagecreatetruecolor($this->ImgResizeNewWidth, $this->ImgResizeNewHeight);
        // изменение размера
        if(imagecopyresampled($this->NewImgRes, $this->ImgRes, 0, 0, 0, 0, $this->ImgResizeNewWidth, $this->ImgResizeNewHeight, $this->ImgWidth, $this->ImgHeight))
        {
            imagedestroy($this->ImgRes);
            $this->ImgRes=$this->NewImgRes;
            if($crop)
            {
                $y=round($this->ImgResizeNewHeight/2)-round($this->ImgNewHeight/2);
                $this->ImgRes=imagecrop($this->ImgRes,array('x'=>0,'y' => $y,'width' => $this->ImgNewWidth,'height'=> $this->ImgNewHeight));
            }
            $this->ImgWidth=$this->ImgNewWidth;
            $this->ImgHeight=$this->ImgNewHeight;

        }else{
            throw new \Exception('imagecopyresampled Error');
        }
    }
    function __destruct(){
        if(is_resource($this->ImgRes))
        {
            imagedestroy($this->ImgRes);
        }
        if(is_resource($this->NewImgRes))
        {
            imagedestroy($this->NewImgRes);
        }
    }
}