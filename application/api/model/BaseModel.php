<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    protected $hidden = ['update_time', 'delete_time', 'create_time'];

    public function getUrlAttr($value, $data) {
        return $this->prefixUrl($value, $data['from'] == 1);
    }

    protected function prefixUrl($url, $isLocal) {
        $finalUrl = $url;
        if($isLocal) {
            $finalUrl = config('setting.img_prefix') . $url;
        }
        return $finalUrl;
    }
}
