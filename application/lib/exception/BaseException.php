<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/1
 * Time: 20:44
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    public $code = 499; // HTTP状态码 200 404 500等等
    public $msg = '参数错误'; // 具体错误信息
    public $errorCode = 10000; // 自定义的错误码
    public function __construct($param = [])
    {
        if(!is_array($param)) {
            throw new Exception('参数必须是数组');
        }
        if(array_key_exists('code', $param)) {
            $this->code = $param['code'];
        }
        if(array_key_exists('msg', $param)) {
            $this->msg = $param['msg'];
        }
        if(array_key_exists('errorCode', $param)) {
            $this->errorCode = $param['errorCode'];
        }
    }
}