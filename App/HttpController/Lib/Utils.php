<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2019/5/15
 * Time: 22:13
 */
namespace App\HttpController\Lib;

class Utils{

    /**
     * 随机字符
     * @param $length
     * @return null|string
     */
    public static function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }

}