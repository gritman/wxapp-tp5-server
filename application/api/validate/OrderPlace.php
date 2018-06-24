<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/13
 * Time: 23:16
 */

namespace app\api\validate;

use app\lib\exception\ParamException;

class OrderPlace extends BaseValidate {

    // 要验证的参数示例
    protected $products = [
        [
            'product_id' => 1,
            'count' => 3
        ],
        [
            'product_id' => 2,
            'count' => 3
        ],
        [
            'product_id' => 3,
            'count' => 3
        ]
    ];

    // 要验证参数是一个数组，验证一数组的元素还是数组，验证二子数组元素是字典，验证三字典的键和值
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected $elementRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    protected function checkProducts($values) {
        if(!is_array($values)) {
            throw new ParamException([
                'msg' => '参数必须是数组'
            ]);
        }
        if(empty($values)) {
            throw new ParamException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach($values as $value) {
            $this->checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value) {
        // 这是验证器最基本最直接的用法
        // 用验证器和验证规则来复用以前写过的isPositiveInteger
        $validate = new BaseValidate($this->elementRule);
        $result = $validate->check($value);
        if(!$result) {
            throw new ParamException([
                'msg' => '商品列表的元素的键值错误'
            ]);
        }
    }
}