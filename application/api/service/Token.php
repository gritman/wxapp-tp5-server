<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/8
 * Time: 14:09
 */

namespace app\api\service;

// 还有一个AppToken，除了UserToken之外
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token {
    public static function generateToken() {
        // 32个字符组成一组随机字符串
        $randChars = getRandChars();
        // 用三组字符串，进行md5加密
        $timeStamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt
        $salt = config('secure.token_salt');
        return md5($randChars.$timeStamp.$salt);
    }

    public static function getCurrentTokenVar($key) {
        // Request是全局的，静态的，不止在控制器里用，其他地方也可以用
        // token要放在header里
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(empty($vars)) { // 是否只用empty就可以？查看empty的定义即可
            throw new TokenException();
        } else {
            // 有些类型的缓存，可以直接保存数组，比如redis，所以不用再转
            if(!is_array($vars)) {
                // 把字符串转成数组，因为保存缓存的时候，是对数组进行了json_encode
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                // 这种类型的错误没必要返回到客户端，所以不用自定义Exception
                throw new Exception('常识获取的token缓存变量并不存在');
            }
        }
    }

    public static function getCurrentUid() {
        return self::getCurrentTokenVar('uid');
    }
}