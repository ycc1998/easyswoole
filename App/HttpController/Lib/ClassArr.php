<?php
/**
 * php反射机制
 */
namespace App\HttpController\Lib;
class ClassArr{
    public function uploadClassStat()
    {
        return [
            'image' => 'App\HttpController\Lib\Upload\Image',
            'video' => 'App\HttpController\Lib\Upload\Video'
        ];
    }

    /**
     * ReflectionClass  newInstanceArgs   反射机制
     * @param $type  键名
     * @param $supportedClass
     * @param array $params
     * @param bool $needInstance   是否实例化
     * @return bool|object
     * @throws \ReflectionException
     */
    public function initClass($type,$supportedClass,$params = [],$needInstance = true)
    {
        if (!array_key_exists($type,$supportedClass)){
            return false;
        }

        $className = $supportedClass[$type];
        return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params): $className;
    }


}