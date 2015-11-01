<?php
namespace sblazheev\yii2imagehelper;

//use Yii;
//use Yii\base\Component;
//use Yii\base\InvalidConfigException;
use sblazheev\yii2imagehelper\ImageOperation\Factory\AbstractFactoryImageOperation as AbstractFactory;
use sblazheev\yii2imagehelper\Exception\IOException;
use sblazheev\yii2imagehelper\CacheMetaInfo\CacheMetaInfo;
/**
 * ResizeImage Component
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 */
class ImageHelper// extends Component
{
    /**
     * @var string Драйвер ('IMAGICK'/'GD'/'GMAGICK')
     */
    public static $driver='GD';//'IMAGICK';
    protected $savePath;
    protected static $crop=true;
    /* Ресайз изображения
     * @param string $imagePath Путь к файлу
     * @param int $width требуемая ширина изображения
     * @param int $height требуемая высота изображения
     * @param boolean $crop Обрезка изображения
     * @param string $savePath Путь сохранения изображения
     * @param mixed $var Массив дополнительных переменных
     * @return string Путь к файлу
     */
    public static function resizeImage($imagePath,$width,$height,$crop=false,$savePath=null,$var=null){
        $image = AbstractFactory::getFactory(self::$driver)->getImageOperation();
        try{

            $image->createImage($imagePath);
            $image->resizeImage($width,$height,$crop);
            if(!empty($savePath)) {
                $arg=$var;
                $savePath = $image->prepareSavePath($savePath,$arg);
            }
            $image->saveImage($savePath);
            return $savePath;
        }catch (IOException $e){
            echo $e->getTraceAsString();
        }catch (\Exception $e){
            echo $e->getTraceAsString();
        }
    }
    /* Ресайз изображения с кэшированием
     * @param string $imagePath Путь к файлу
     * @param int $width требуемая ширина изображения
     * @param int $height требуемая высота изображения
     * @param string $saveCachePath Путь кэширования изображения
     * @param mixed $var Массив дополнительных переменных
     * @param int $expire Время жизни кэша в часах ( По умолчанию 365 дней [87600])
     * @return string Путь к файлу
     */
    public static function resizeImageCache($imagePath,$width,$height,$saveCachePath=null,$var=array(),$expire=87600){
        $image = AbstractFactory::getFactory(self::$driver)->getImageOperation();
        if(empty($saveCachePath))
            $saveCachePath=dirname($imagePath).'/tmp/'.$width.'_'.$height.'_'.basename($imagePath);
        $saveCachePath = $image->prepareSavePath($saveCachePath,$var);

        $CacheMetaInfo = new CacheMetaInfo();
        $CacheMetaInfo->LoadMetaInfo(dirname($saveCachePath).'/');

        if(file_exists($saveCachePath)&&!$CacheMetaInfo->IsExpired($saveCachePath)){
            return $saveCachePath;
        }
        try{
            $image->createImage($imagePath);
            $image->resizeImage($width,$height,self::$crop);
            $image->saveImage($saveCachePath);
            $CacheMetaInfo->AddMetaInfo(basename($saveCachePath),$expire,$imagePath);
            $CacheMetaInfo->SaveMetaInfo();
            return $saveCachePath;
        }catch (IOException $e){
            echo $e->getTraceAsString();
        }catch (\Exception $e){
            echo $e->getTraceAsString();
        }
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->savePath))
            throw new InvalidConfigException('Set "savePath" at $config["components"]["ImageHelper"]["savePath"].');
        $this->savePath = Yii::getAlias($this->savePath);
    }

}