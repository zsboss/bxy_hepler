<?php

declare(strict_types=1);

namespace  Bxy\Helper\Directory;



class TimeHelper
{
    /**
     * 返回今日开始和结束的时间戳
     *
     * @return array
     */
    public static function today()
    {
        return [
            mktime(0, 0, 0, (int)date('n'), (int)date('j'), (int)date('Y')),
            mktime(23, 59, 59, (int)date('n'), (int)date('j'), (int)date('Y'))
        ];
    }
    
    /**
     * 返回昨日开始和结束的时间戳
     *
     * @return array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, (int)date('n'), (int)$yesterday, (int)date('Y')),
            mktime(23, 59, 59, (int)date('n'), (int)$yesterday, (int)date('Y'))
        ];
    }
    
    /**
     * 返回本周开始和结束的时间戳
     *
     * @return array
     */
    public static function week()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("this week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("this week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }
    
    /**
     * 返回上周开始和结束的时间戳
     *
     * @return array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }
    
    /**
     * 返回本月开始和结束的时间戳
     *
     * @return array
     */
    public static function month($everyDay = false)
    {
        return [
            mktime(0, 0, 0, (int)date('n'), 1, (int)date('Y')),
            mktime(23, 59, 59, (int)date('n'), (int)date('t'), (int)date('Y'))
        ];
    }
    
    /**
     * 返回上个月开始和结束的时间戳
     *
     * @return array
     */
    public static function lastMonth()
    {
        $begin = mktime(0, 0, 0, (int)date('n') - 1, 1, (int)date('Y'));
        $end = mktime(23, 59, 59, (int)date('n') - 1, (int)date('t', $begin), (int)date('Y'));
        
        return [$begin, $end];
    }
    
    /**
     * 返回今年开始和结束的时间戳
     *
     * @return array
     */
    public static function year()
    {
        return [
            mktime(0, 0, 0, 1, 1, (int)date('Y')),
            mktime(23, 59, 59, 12, 31, (int)date('Y'))
        ];
    }
    
    /**
     * 返回去年开始和结束的时间戳
     *
     * @return array
     */
    public static function lastYear()
    {
        $year = (int)date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year)
        ];
    }
    
    public static function dayOf()
    {
    
    }
    
    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow($day = 1, $now = true)
    {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }
        
        return [
            mktime(0, 0, 0, (int)date('n'), (int)date('d') - $day, (int)date('Y')),
            $end
        ];
    }
    
    /**
     * 返回几天前的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAgo($day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }
    
    /**
     * 返回几天后的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAfter($day = 1)
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }
    
    /**
     * 天数转换成秒数
     *
     * @param int $day
     * @return int
     */
    public static function daysToSecond($day = 1)
    {
        return $day * 86400;
    }
    
    /**
     * 周数转换成秒数
     *
     * @param int $week
     * @return int
     */
    public static function weekToSecond($week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }
    
    private static function startTimeToEndTime()
    {
    
    }
}