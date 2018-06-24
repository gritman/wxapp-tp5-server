<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/6
 * Time: 20:51
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WxException;
use think\Exception;

class UserTokenService extends TokenService {

    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code) {
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(
            config('wx.login_url'), $this->wxAppId, $this->wxAppSecret, $this->code);
    }

    public function get($code) {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true); // true返回数组，false返回对象
        if(empty($wxResult)) {
            // 要用内部异常，因为不要返回到客户端，而是要记录成日志，所以不用自定义异常
            throw new Exception('获取openid和session_key时异常，微信内部错误');
        } else {
            if(array_key_exists('errcode', $wxResult)) {
                $this->processLoginError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }

    }

    private function grantToken($wxResult) {
        // 拿到openid
        // 去数据库里看一下这个openid是否已经存在
        // 如果不存在，则新增一条user记录（主要是为了获得openid插入数据库的记录id）
        // 生成令牌，准备缓存数据，写入缓存
        // 把令牌返回到客户端
        // 缓存：key是令牌；value是wxResult，uid（表主键），scope权限范围
        // 存缓存的目的是，用户通过携带的令牌，快速找到用户相关的变量
        $openid = $wxResult['openid'];
        $user = User::getByOpenId($openid);
        if(empty($user)) {
            $uid = $this->newUser($openid);
        } else {
            $uid = $user->id;
        }
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue) {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        // 令牌过期时间转化成缓存过期时间
        $finalUrl = config('setting.img_prefix');
        $expire_in = config('setting.token_expire_in');
        // tp5自带缓存，默认是文件系统缓存，可以配置成redis或memcache，但是接口一样
        $request = cache($key, $value, $expire_in);
        if(!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult, $uid) {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        // scope 16代表用户的权限数值，32代表管理员CMS的权限
        $cachedValue['scope'] = ScopeEnum::User; // 数字越大，权限越大
        return $cachedValue;
    }

    /**
     * 用openid在数据库新创建一条记录
     * @param $openid
     * @return mixed 数据库新记录的id
     */
    private function newUser($openid) {
        $user = User::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult) {
        throw new WxException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}