<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/15
 * Time: 21:00
 */
namespace App\HttpController\Lib\Upload;

use App\HttpController\Lib\Utils;

class Base{

    /**
     * 上传文件的键名
     * @var string
     */
    public $type = '';

    /**
     * 文件大小
     * @var
     */
    public $size = 0;

    public function __construct($request)
    {
        $this->request = $request;
        $result = $this->request->getSwooleRequest()->files;
        $types = array_keys($result);
        $this->type = $types[0];
    }

    public function upload()
    {
        if($this->type !== $this->fileType){
            return false;
        }

        $video = $this->request->getUploadedFile($this->type);
        //文件大小
        $this->size = $video->getSize();
        $this->checkSize();
        //原文件名称
        $fileName = $video->getClientFilename();

        //文件类型  video/mp4
        $this->ClientMediaType = $video->getClientMediaType();

        $this->checkMediaType();

        //获取文件后缀
        $suffix = pathinfo($fileName);
        $suffix = $suffix['extension'];

        $fileUrl = '/static/'.$this->type.'/'.date('Y').'/'.date('m').'/';
        //保存文件名称
        $filesName = Utils::getRandChar(16).'.'.$suffix;

        $dir = EASYSWOOLE_ROOT.'/webroot'.$fileUrl;
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $file = $fileUrl.$filesName;
        $flag = $video->moveTo($dir.$filesName);
        if($flag){
            return $file;
        }else{
            return false;
        }
    }


    /**
     * 文件类型判断
     * @throws \Exception
     */
    public function checkMediaType()
    {
        $mediaType = explode('/',$this->ClientMediaType);
        $mediaType = $mediaType[1];
        if (empty($mediaType) || !in_array($mediaType,$this->fileExtTypes)){
            throw new \Exception("上传{$this->type}不合法");
        }
    }


    /**
     * 文件是否为空
     * @return bool
     */
    public function checkSize()
    {
        if(empty($this->size)){
            return false;
        }
    }
}