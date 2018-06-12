<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/1
 * Time: 20:41
 */

namespace app\lib\exception;

use think\exception\Handle;
use Exception;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle {

    // 用于返回给客户端错误信息
    private $code;
    private $msg;
    private $errorCode;
    // 还需要返回客户端当前请求的URL

    /**
     * 所有异常都会经过render方法来渲染，改写错误返回格式，
     * 最后决定返回到客户端是什么样式，JSON或者HTML
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(Exception $e) {
        if($e instanceof BaseException) {
            // 如果是用户异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            // 如果是内部异常
            if(config('app_debug')) { // Config::get('app_debug')
                // 显示默认的报错HTML页面，包含详细信息
                return parent::render($e);
            } else {
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }

    private function recordErrorLog(Exception $e) {
        Log::init([
            'type' => 'File',
            'path'  => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}