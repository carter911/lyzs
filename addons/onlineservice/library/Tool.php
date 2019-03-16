<?php
/**
 * Note: 工具类.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/21 0021 8:59:30
 */

namespace addons\onlineservice\library;


class Tool
{
    /**
     * 获取当前页面网址
     */
    public static function get_url()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        //$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    }


    public static function expression($content, $filename = '')
    {
        //正则匹配图片  ?(.*?)[\"|\']?\s.*?
        $pattern = '/(.*).(gif|jpg|jpeg|bmp|png)/is';
        preg_match_all($pattern, $content, $matches);
        if (isset($matches[0])) {
            foreach ($matches[0] as $k => $v) {
                $url = '<img  onclick="getimg(this)" src="' . $v . '" class="picture" />';
                $content = str_replace($matches[0][$k], $url, $content);
            }
        }
        //正则匹配文件  /uploads/20190221/4a32c984dd2832f5524f5c958b10c97a.zip
        $pattern = '/(.*).(doc$|docx|xls$|xlsx|zip|rar|pdf)/is';
        preg_match_all($pattern, $content, $matches);
        if (isset($matches[0][0])) {
            if (!$filename) {
                $file_name = explode('/', $matches[0][0]);
                $filename = $file_name[3];
            }
            foreach ($matches[0] as $k => $v) {
                $url = '<div class="customer"><pre><a class="attach-exp" href="' . $v . '" style="display: inline-block;text-align: center;min-width: 70px;text-decoration: none;" download="' . $filename . '"><i class="layui-icon" style="font-size: 60px;"></i><br>' . $filename . '</a></pre></div>';
                $content = str_replace($matches[0][$k], $url, $content);
            }
        }
        //正则匹配表情
        #$pattern = '/:(\w+):/'; //这个会匹配到数组 :29:
        $pattern = '/:([a-z_]*?):/';
        preg_match_all($pattern, $content, $match);
        if (isset($match[1])) {
            $img = '';
            foreach ($match[1] as $k => $v) {
                $img = '<img src="/assets/addons/onlineservice/img/smilies/' . $v . '.png" alt="" class="wp-smiley" style="height: 1.5em;max-height:1.5em;">';
                $content = str_replace($match[0][$k], $img, $content);
            }
        }
        return $content;
    }

    public static function get_uuid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// " - "
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);// "}"
            return $uuid;
        }
    }

    public static function getTaobaoArea($ip)
    {
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
        $content = self::curl($url);
        $content = json_decode($content);
        if (isset($content) && $content->code == 0) {
            $arr['country'] = $content->data->country;
            $arr['region'] = $content->data->region;
            $arr['city'] = $content->data->city;
            return $arr;
        }
    }

    protected static function curl($url, $type = 'get', $post_data = null, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, false);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ('post' == $type) {
            curl_setopt($ch, CURLOPT_POST, 1); //开启POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //POST数据
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output; //返回或者显示结果
    }

    /**
     * 获取客户端浏览器信息
     * @param $agent
     * @return array
     */
    public static function getBrowser($agent)
    {
        $browser = '';
        $browser_ver = '';
        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'OmniWeb';
            $browser_ver = $regs[2];
        }
        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Netscape';
            $browser_ver = $regs[2];
        }
        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Safari';
            $browser_ver = $regs[1];
        }
        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'Internet Explorer';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
            $browser = 'Opera';
            $browser_ver = $regs[1];
        }
        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'NetCaptor';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Maxthon/i', $agent, $regs)) {
            $browser = 'Maxthon';
            $browser_ver = '';
        }
        if (preg_match('/360SE/i', $agent, $regs)) {
            $browser = '360SE';
            $browser_ver = '';
        }
        if (preg_match('/SE 2.x/i', $agent, $regs)) {
            $browser = '搜狗';
            $browser_ver = '';
        }
        if (preg_match("/OPR\/([\d\.]+)/", $agent, $regs)) {
            $browser = "Opera";
            $browser_ver = $regs[1];
        }
        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'FireFox';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Lynx';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Chrome';
            $browser_ver = $regs[1];
        }
        if ($browser != '') {
            return ['browser' => $browser, 'version' => $browser_ver];
        } else {
            return ['browser' => '未知浏览器', 'version' => ''];
        }
    }

    /**
     * PHP判断当前协议是否为HTTPS
     */
    public static function is_https()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

}