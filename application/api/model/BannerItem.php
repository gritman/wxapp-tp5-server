<?php

namespace app\api\model;

use think\Model;

class BannerItem extends BaseModel
{

    public function img() {
        // BanngerItem belongsTo Image, BannerItem.img_id, Image.id
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
