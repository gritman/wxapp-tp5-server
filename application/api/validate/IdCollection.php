<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2018/6/5
 * Time: 16:59
 */

namespace app\api\validate;


class IdCollection extends BaseValidate {
    protected $rule = [
        'ids' => 'require|checkIds'
    ];

    protected $message = [
        'ids' => 'ids参数必须是以逗号分隔的多个正整数id1,id2,id3,...'
    ];

    /**
     * @param $value id1,id2,id3,...
     * @return bool
     */
    protected function checkIds($value) {
        $values = explode(',', $value);
        if(empty($values)) {
            return false;
        }
        foreach($values as $id) {
            if($this->isPositiveInteger($id) !== true) {
                return false;
            }
        }
        return true;
    }
}