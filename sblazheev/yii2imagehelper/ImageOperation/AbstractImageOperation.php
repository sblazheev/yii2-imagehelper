<?php
namespace sblazheev\yii2imagehelper\ImageOperation;

use sblazheev\yii2imagehelper\Exception\IOException;

/**
 * Class AbstractImageOperation
 * @package sblazheev\yii2imagehelper\ImageOperation
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Асбтрактный класс по работе с изображениями
 */
abstract class AbstractImageOperation{
    /*
     * @var  Размер файла
     */
    protected $SrcImgByte=0;
    /*
     * @var int Исходная ширина
     */
    protected $ImgWidth=0;
    /*
     * @var int Исходная высота
     */
    protected $ImgHeight=0;
    /*
     * @var int Новая ширина
     */
    protected $ImgNewWidth=0;
    /*
     * @var int Новая высота
     */
    protected $ImgNewHeight=0;
    /*
     * @var int Расчетная ширина для ресайза
     */
    protected $ImgResizeNewWidth=0;
    /*
     * @var int Расчетная высота для ресайза
     */
    protected $ImgResizeNewHeight=0;
    /*
     * @var boolean Кроп изображения
     */
    protected $Crop=false;
    /*
     * @var float Соотношение сторон width/height фиксированная величина при ресайзе
     */
    protected $ImgRation=0;
    /*
     * @var string Имя файла
     */
    protected $FileName=null;
    /** Возвращает отладочную информацию
     * @return array('memory','driver');
     */
    public function debugInfo(){
      return array('memory'=>memory_get_peak_usage(),'classdriver'=>get_class($this),'imgbyte'=>$this->SrcImgByte);
    }
    /**
     * Создать объект Image
     * @param string $imagePath путь к файлу иображения
     */
    public function createImage($imagePath){
        $this->FileName = basename($imagePath);
    }
    /**
     * Пересчет размеров фотографии для ресайза
     *
     */
    public function calcSize(){

        if(empty($this->ImgNewHeight))
        {
            $this->ImgNewHeight=round($this->ImgNewWidth / $this->ImgRation);
        }
        if($this->Crop)
        {
            $this->ImgResizeNewHeight = round($this->ImgNewWidth / $this->ImgRation);
            $this->ImgResizeNewWidth = $this->ImgNewWidth;
        }else{
            $this->ImgResizeNewHeight = round($this->ImgNewWidth / $this->ImgRation);
            $this->ImgResizeNewWidth = $this->ImgNewWidth;
            if($this->ImgResizeNewHeight>$this->ImgNewHeight)
            {
                $this->ImgResizeNewHeight=$this->ImgNewHeight;
                $this->ImgResizeNewWidth=round($this->ImgNewHeight*$this->ImgRation);
            }
        }
    }
    /**
     * Подготавтливает @path и создает целевую папку в случае ее отсутствия
     * @param string $path Путь сохранения файла
     * @parm array @arg Массив замены в @path вида array('KEY'=>'VALUE')
     */
    public function prepareSavePath($path,$arg){
        $key=array_keys($arg);
        $val=array_values($arg);
        $path=str_replace($key,$val,$path);
        if(!file_exists(dirname($path))){
            $res=mkdir(dirname($path), 0755, true);
            if(!$res)
                throw new IOException('Dir not exist '.$path);
        }
        return $path;
    }
}