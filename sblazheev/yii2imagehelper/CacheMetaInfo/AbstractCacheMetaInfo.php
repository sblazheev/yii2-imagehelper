<?php
namespace sblazheev\yii2imagehelper\CacheMetaInfo;
/**
 * Class AbstractCacheMetaInfo
 * @package sblazheev\yii2imagehelper\CacheMetaInfo
 * @author Sergey Blazheev <s.blazheev@gmail.com>
 * @description Асбтрактный класс по работе с Мета информацией Кэша
 */
//@TODO решить проблему с парарельным чтением и записью
class AbstractCacheMetaInfo {
    /**
     * @var string Имя файла в котором хранится мета информация о кэше, по умолчанию .metainfo
     */
    static $FileNameMetaInfo='.metainfo';
    /**
     * @var Array Массив мета информации о файле, ключ md5(filename)
     */
    protected $ArrMetaInfo=array();
    /**
     * @var  string Путь к файлу в котором хранится мета информация о кэше
     */
    protected $PathToMetaInfoFile;
    /**
     * Загрузка мета информации из файла
     * @param string $path путь к файлу .metainfo
     */
    function LoadMetaInfo($path){
        $this->PathToMetaInfoFile=$path.self::$FileNameMetaInfo;
        if(file_exists($this->PathToMetaInfoFile)) {
            $handle = fopen($this->PathToMetaInfoFile, "r");
            if ($handle) {
                while ($MetaInfo = fscanf($handle, "%s\t%s\t%s\t%s\n")) {
                    $this->ArrMetaInfo[md5($MetaInfo[0])] = $MetaInfo;
                }
                var_dump($this->ArrMetaInfo);
                fclose($handle);
                return true;
            } else {
                return false;
            }
        }else
            return false;

    }
    /**
     * Получить мета информацию файла
     * @param string $FileName имя файла
     * @return mixed Array мета информация по кэшу, или false в случае отсутствия информации
     */
    public function GetMetaInfo($FileName){
        $md5=md5($FileName);
        if(!empty($this->ArrMetaInfo[$md5]))
            return $this->ArrMetaInfo[$md5];
        return false;
    }
    /**
     * Добавить мета информацию по файлу
     * @param string $MetaInfo мета информация о файле
     * @return boolean false в случае ошибки
     */
    public function AddMetaInfo($FilePath,$Expire=86400,$OriginalFilePath=''){
        $this->ArrMetaInfo[]=array($FilePath,$Expire,$OriginalFilePath,sha1_file($OriginalFilePath));
        return true;
    }
    /**
     * Сохранить мета информацию
     * @return boolean false в случае ошибки
     */
    public function SaveMetaInfo(){
        $str='';
        foreach($this->ArrMetaInfo as $key=>$item){
            $str.=implode("\t",$item)."\n";
        }
        return file_put_contents($this->PathToMetaInfoFile,$str);
        return true;
    }
    /**
     * Время жизни кэша истекло
     * @param string $FileName имя кэшируемого файла
     * @return boolean true еслик кэш просрочен
     */
    public function IsExpired($FileName){
        $md5=md5($FileName);
        if(empty($this->ArrMetaInfo[$md5])){
            return true;
        }
        return false;
    }
}