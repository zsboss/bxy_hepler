<?php

namespace  Bxy\Helper\Util;

class RegularHelper
{
    /**
     * 正则
     *
     * @param string $url
     *
     * @return int
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function checkUrl($url)
    {
        $rule = "/((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*/";
        preg_match($rule, $url, $result);
        return $result;
    }


    /**
     * 正则
     *
     * @param string $url
     *
     * @return int
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function checkImage($url)
    {
        $rule = "/((http|https):\/\/)?\w+\.(jpg|jpeg|gif|png)/";
        preg_match($rule, $url, $result);
        return $result;
    }
}
