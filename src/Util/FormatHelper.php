<?php



namespace  Bxy\Helper\Util;

use Bxy\Helper\YiiSoft\Arrays\ArrayHelper;
use Bxy\Helper\YiiSoft\StringHelper;

/**
 * Class FormatHelper
 *
 * @package Lengbin\Helper\Util
 */
class FormatHelper
{

    /**
     * 字节格式化
     *
     * @param int $size 字节
     *
     * @return string
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function formatBytes($size)
    {
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * 金额格式化
     *
     * @param $number
     * @param $decimals
     *
     * @return string
     */
    public static function formatNumbers($number, $decimals)
    {
        return sprintf("%.{$decimals}f", $number);
    }

    /**
     * 数字随机数
     *
     * @param int $length 位数
     *
     * @return string
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function randNum($length = 6)
    {
        $min = pow(10, ($length - 1));
        $max = pow(10, $length) - 1;
        $mem = rand($min, $max);
        return $mem;
    }

    /**
     * 字母数字混合随机数
     *
     * @param int $num 位数
     *
     * @return string
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function randStr($num = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = "";
        for ($i = 0; $i < $num; $i++) {
            $string .= substr($chars, rand(0, strlen($chars)), 1);
        }
        return $string;
    }

    /**
     * 数字金额转换为中文
     *
     * @param double $num 数字
     * @param bool   $sim 大小写
     *
     * @return string
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function numberToChinese($num, $sim = false)
    {
        if (!is_numeric($num)) {
            return '含有非数字非小数点字符！';
        }
        $char = $sim ? [
            '零',
            '一',
            '二',
            '三',
            '四',
            '五',
            '六',
            '七',
            '八',
            '九',
        ] : [
            '零',
            '壹',
            '贰',
            '叁',
            '肆',
            '伍',
            '陆',
            '柒',
            '捌',
            '玖',
        ];
        $unit = $sim ? ['', '十', '百', '千', '', '万', '亿', '兆'] : ['', '拾', '佰', '仟', '', '萬', '億', '兆'];
        $retval = '';
        $num = sprintf("%01.2f", $num);
        [$num, $dec] = explode('.', $num);
        // 小数部分
        if ($dec['0'] > 0) {
            $retval .= "{$char[$dec['0']]}角";
        }
        if ($dec['1'] > 0) {
            $retval .= "{$char[$dec['1']]}分";
        }
        // 整数部分
        if ($num > 0) {
            $retval = "元" . $retval;
            $f = 1;
            $out = [];
            $str = strrev(intval($num));
            for ($i = 0, $c = strlen($str); $i < $c; $i++) {
                if ($str[$i] > 0) {
                    $f = 0;
                }
                if ($f == 1 && $str[$i] == 0) {
                    $out[$i] = "";
                } else {
                    $out[$i] = $char[$str[$i]];
                }
                $out[$i] .= $str[$i] != '0' ? $unit[$i % 4] : '';
                if ($i > 1 and $str[$i] + $str[$i - 1] == 0) {
                    $out[$i] = '';
                }
                if ($i % 4 == 0) {
                    $out[$i] .= $unit[4 + floor($i / 4)];
                }
            }
            $retval = join('', array_reverse($out)) . $retval;
        }
        return $retval;
    }

    /**
     * 时间格式化
     *
     * @param string /int  $date  时间/时间戳
     * @param bool $isInt 是否为int
     *
     * @return array
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function formatDay($date, $isInt = true)
    {
        return self::formatDays($date, $date, $isInt);
    }

    /**
     * 双日期 格式化
     *
     * @param string $date      双日期
     * @param string $separator 分割符
     * @param bool   $isInt     是否为int
     *
     * @return array
     */
    public static function formatDoubleDate($date, $separator = ' - ', $isInt = true)
    {
        $dates = explode($separator, $date);
        return self::formatDays($dates[0], $dates[1], $isInt);
    }

    /**
     * 时间格式化
     *
     * @param string /int  $start  时间/时间戳
     * @param string /int  $end  时间/时间戳
     * @param bool $isInt 是否为int
     *
     * @return array
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function formatDays($start, $end, $isInt = true)
    {
        if (is_int($start)) {
            $start = date('Y-m-d', $start);
        }
        if (is_int($end)) {
            $end = date('Y-m-d', $end);
        }
        $start = $start . ' 00:00:00';
        $end = $end . ' 23:59:59';
        if ($isInt) {
            $start = strtotime($start);
            $end = strtotime($end);
        }
        return [$start, $end];
    }

    /**
     * 时间格式化
     *
     * @param int  $month 月份
     * @param bool $isInt 是否为int
     *
     * @return array
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function formatMonth($month, $isInt = true)
    {
        if (strlen($month) < 3) {
            $month = date("Y-{$month}-d");
        }
        $timestamp = strtotime($month);
        $startTime = date('Y-m-1 00:00:00', $timestamp);
        $mdays = date('t', $timestamp);
        $endTime = date('Y-m-' . $mdays . ' 23:59:59', $timestamp);
        if ($isInt) {
            $startTime = strtotime($startTime);
            $endTime = strtotime($endTime);
        }
        return [$startTime, $endTime];
    }

    /**
     * 人性化时间显示
     *
     * @param int $time
     *
     * @return false|string
     */
    public static function formatTime($time)
    {
        $rtime = date("Y-m-d H:i:s", $time);
        $time = time() - $time;
        if ($time < 60) {
            $str = '刚刚';
        } elseif ($time < 60 * 60) {
            $min = floor($time / 60);
            $str = $min . '分钟前';
        } elseif ($time < 60 * 60 * 24) {
            $h = floor($time / (60 * 60));
            $str = $h . '小时前 ';
        } elseif ($time < 60 * 60 * 24 * 3) {
            $d = floor($time / (60 * 60 * 24));
            if ($d == 1) {
                $str = '昨天 ' . $rtime;
            } else {
                $str = '前天 ' . $rtime;
            }
        } else {
            $str = $rtime;
        }
        return $str;
    }

    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     */
    public static function camelize($uncamelized_words, $separator = '_')
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }

    /**
     * 驼峰命名转下划线命名
     * 思路:
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     */
    public static function uncamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    /**
     * 验证请求参数字段
     * 支持别名
     *
     * @param array  $requests      请求参数
     * @param array  $validateField 验证字段，支持别名  ['别名' => 字段， 0 => 字段]
     * @param string $defaults
     *
     * @return array
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function validateParams(array $requests, array $validateField, $defaults = '')
    {
        $data = [];
        foreach ($validateField as $key => $field) {
            $default = is_array($defaults) ? ArrayHelper::getValue($defaults, $field) : $defaults;
            $param = ArrayHelper::getValue($requests, $field, $default);
            $index = is_int($key) ? $field : $key;
            $data[$index] = $param;
        }
        return $data;
    }

    /**
     *   except params
     *
     * @param array        $params 请求参数
     * @param string|array $except
     *
     * @return array
     */
    public static function exceptParams(array $params, $except = null)
    {
        if ($except === null) {
            return $params;
        }
        if (!is_array($except)) {
            $except = [$except];
        }
        foreach ($except as $item) {
            ArrayHelper::remove($params, $item);
        }
        return $params;
    }

    /**
     * image 路由
     *
     * @param        $image
     * @param string $path
     *
     * @return string
     */
    public static function formatImage($image, $path = '')
    {
        return RegularHelper::checkImage($image) ? $image : ($path . $image);
    }

    /**
     * 列表的数据转义
     *
     * @param array $data    数据
     * @param array $fields  转义字段
     * @param array $rules   规则 默认为字符串，['xx' => 'html']
     * @param array $configs 配置
     *
     * @return array
     * @author lengbin(lengbin0@gmail.com)
     */
    public static function htmlSpecialChars(array $data, array $fields = [], array $rules = [], array $configs = [])
    {
        if (empty($data) || empty($fields)) {
            return $data;
        }
        $results = isset($data['models']) ? $data['models'] : $data;
        $config = self::validateParams($configs, ['imageUrl']);
        foreach ($results as $key => $result) {
            $params = is_object($result) ? $result->toArray() : $result;
            foreach ($fields as $field) {
                if (empty($rules[$field])) {
                    continue;
                }
                $isUnset = false;
                $rule = $rules[$field];
                if ($rule instanceof \Closure) {
                    $html = call_user_func($rule, $result);
                    $isUnset = $html === null;
                } else {
                    $html = !empty($params[$field]) ? $params[$field] : '';
                    switch ($rule[$field]) {
                        case 'image':
                            $html = self::formatImage($html, $config['imageUrl']);
                            break;
                        case 'images':
                            $html = [];
                            $images = explode('|', $params[$field]);
                            foreach ($images as $image) {
                                if (!empty($image)) {
                                    $html[] = self::formatImage($html, $config['imageUrl']);
                                }
                            }
                            break;
                        case 'distance':
                            $html = self::formatNumbers(($html / 1000), 1) . 'km';
                            break;
                        case 'time':
                            $html = self::formatTime($html);
                            break;
                        case 'date':
                            $html = date('Y-m-d H:i:s', $html);
                            break;
                        case 'int':
                            $html = (int)$html;
                            break;
                        case 'float':
                            $html = (float)$html;
                            break;
                        case 'bool':
                            $html = (bool)$html;
                            break;
                        case 'unset':
                            $isUnset = true;
                            break;
                        default:
                            $html = StringHelper::htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
                            break;
                    }
                }
                if ($isUnset) {
                    unset($params[$field]);
                    continue;
                }
                $params[$field] = $html;
            }
            $results[$key] = $params;
        }
        if (isset($data['models'])) {
            $data['models'] = $results;
        } else {
            $data = $results;
        }
        return $data;
    }

    /**
     *
     * format page data and process params
     *
     * @param array $result
     * @param array $params
     * @param int   $requestPage
     *
     * @return array
     */
    public static function formatPage(array $result, array $params = [], $requestPage = 1)
    {
        $list = [];
        $offset = $pageSize = $totalPage = $totalCount = 0;
        $page = !empty($result['pages']) ? $result['pages'] : [];
        if (!empty($page)) {
            $offset = $requestPage;
            $totalPage = ceil($page->totalCount / $page->pageSize);
            $list = ($totalPage >= $offset) ? $result['models'] : [];
            $pageSize = $page->pageSize;
            $totalCount = $page->totalCount;
            unset($params['access_token']);
        }
        return array_merge($params, [
            'list'        => $list,
            'currentPage' => $offset,
            'pageSize'    => $pageSize,
            'totalPage'   => $totalPage,
            'totalCount'  => $totalCount,
        ]);
    }
}
