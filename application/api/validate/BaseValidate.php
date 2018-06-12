<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/5/31
 * Time: 19:42
 */

namespace app\api\validate;


use app\lib\exception\ParamException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck() {
        // 获取http传入的参数
        $request = Request::instance();
        $params = $request->param();
        // 对这些参数做校验
        $result = $this->batch()->check($params);
        if($result) { // ture
            return true;
        } else { // false
            $e = new ParamException([
                'msg' => $this->error
            ]);
            throw $e;
        }
    }

    protected function isPositiveInteger(
        $value, $rule = '', $data = '', $field = '')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function isNotEmpty($value) {
        if(empty($value)) {
            return false;
        } else {
            return true;
        }
    }

    protected function isMobile($value) {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    // 根据验证规则来提取参数，节省很多重复代码
    public function getDataByRule($paramArray) {
        if(array_key_exists('user_id', $paramArray) || array_key_exists('uid', $paramArray)) {
            // 不允许包含user_id或uid, 防止恶意覆盖外键user_id
            throw new ParamException([
                'msg' => '参数中包含有非法的参数名user_id或uid'
            ]);
        }
        $newArray = [];
        foreach($this->rule as $key => $value) {
            $newArray[$key] = $paramArray[$key];
        }
        return $newArray;
    }
}












