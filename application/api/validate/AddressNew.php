<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/11
 * Time: 13:17
 */

namespace app\api\validate;


class AddressNew extends BaseValidate {
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isNotEmpty',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
        // 这里不能传uid，因为如果随意传，被劫持，就修改了寄件地址，应该用token来代替uid
    ];
}