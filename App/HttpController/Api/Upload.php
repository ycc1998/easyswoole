<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/11
 * Time: 16:37
 */
namespace App\HttpController\Api;

use App\HttpController\Lib\ClassArr;
use App\HttpController\Lib\Upload\Video;
use EasySwoole\Http\Message\UploadFile;


class Upload extends BaseController {

    /**
     * 上传文件
     */
    public function file()
    {
        $request = $this->request();
        try{

            //获取上传文件的键名
            $result = $request->getSwooleRequest()->files;
            $types = array_keys($result);
            $type = $types[0];

            //php反射机制
            $classArr = new ClassArr();
            $classStats = $classArr->uploadClassStat();
            $uploadObj = $classArr->initClass($type,$classStats,[$request,$type]);
            $file = $uploadObj->upload();

        }catch (\Exception $e){
            return $this->writeJson(400,'',$e->getMessage());
        }
        if (empty($file)){
            return $this->writeJson(400,'','上传失败');
        }else{
            $data = [
                'url' => $file
            ];
            return $this->writeJson(200,$data,'OK');
        }
        /*
        $request = $this->request();
        //获取上传文件  对象
        //$viseos = $request->getUploadedFile("file");
        //获取上传文件 数组
        $result = $request->getSwooleRequest()->files;
        var_dump($result);
        //$result = $viseos->moveTo('/1.jpg');
        $this->response()->write("11");*/


    }


}
