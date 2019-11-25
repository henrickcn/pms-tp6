<?php
// +----------------------------------------------------------------------
// | Title   : 生成工具
// +----------------------------------------------------------------------
// | Created : Henrick (me@hejinmin.cn)
// +----------------------------------------------------------------------
// | From    : Shenzhen wepartner network Ltd
// +----------------------------------------------------------------------
// | Date    : 2019-11-06 15:34
// +----------------------------------------------------------------------


namespace app\common\tools;


class GenerateTools
{
    /**
     * 缓存名称生成
     * @param $name
     * @return string
     */
    public static function cacheName($name){
        return config('app.cache_prefix').$name;
    }

    /**
     * 生成36位UUID
     * @return string
     */
    public static function uuid()
    {
        list($usec, $sec) = explode(" ", microtime(false));
        $usec = (string)($usec * 10000000);
        $timestamp = bcadd(bcadd(bcmul($sec, "10000000"), (string)$usec), "621355968000000000");
        $ticks = bcdiv($timestamp, 10000);
        $maxUint = 4294967295;
        $high = bcdiv($ticks, $maxUint) + 0;
        $low = bcmod($ticks, $maxUint) - $high;
        $highBit = (pack("N*", $high));
        $lowBit = (pack("N*", $low));
        $guid = str_pad(dechex(ord($highBit[2])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($highBit[3])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($lowBit[0])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($lowBit[1])), 2, "0", STR_PAD_LEFT) . "-" . str_pad(dechex(ord($lowBit[2])), 2, "0", STR_PAD_LEFT) . str_pad(dechex(ord($lowBit[3])), 2, "0", STR_PAD_LEFT) . "-";
        $chars = "abcdef0123456789";
        for ($i = 0; $i < 4; $i++) {
            $guid .= $chars[mt_rand(0, 15)];
        }
        $guid .= "-";
        for ($i = 0; $i < 4; $i++) {
            $guid .= $chars[mt_rand(0, 15)];
        }
        $guid .= "-";
        for ($i = 0; $i < 12; $i++) {
            $guid .= $chars[mt_rand(0, 15)];
        }
        return $guid;
    }

    /**
     * 生成36UUID
     * @param bool $opt
     * @return string
     */
    public static function guid($opt = false)
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);    // "-"
        $left_curly = $opt ? chr(123) : "";     //  "{"
        $right_curly = $opt ? chr(125) : "";    //  "}"
        $uuid = $left_curly
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . $right_curly;
        return $uuid;
    }

    /**
     * 生成32位商户系统内部的订单号
     * @return string
     */
    public static function tradeNo()
    {
        return strtoupper(md5(uniqid(mt_rand(), true)));
    }

    /**
     * 生成16位唯一ID
     * @return string
     */
    public static function tradeNo16()
    {
        return uniqid(mt_rand(100, 999));
    }

    /**
     * 产生随机字符串,默认32位
     * @param int $length
     * @return string
     */
    public static function makeNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        $strLen = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, $strLen), 1);
        }
        return $str;
    }

    /**
     * 返回错误信息方法
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    public static function error($code=0, $msg='成功', $data=[]){
        return [
            'errcode' => $code,
            'errmsg'  => $msg,
            'data'    => $data
        ];
    }
}