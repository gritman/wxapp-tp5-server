<?php

namespace app\api\model;

class Theme extends BaseModel
{
    public function topicImg() {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImg() {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    // theme表中的product_id是product表的主键，theme_id和product_id在theme_product表中关联
    public function products() {
        return $this->belongsToMany(
            'Product', 'theme_product', 'product_id', 'theme_id');
    }

    // 不需要topicImg，也应该传topicImg，因为这样才全面，保证了数据的完整性
    // 让客户端尽量拿到完整数据，不管客户端需求是什么，只管把模型里的所有数据都返回出去
    // 因为是基于资源的，传出去一个ID没意思。但是也要有一个度，不要太重
    public static function getThemeWithProducts($id) {
        $theme = self::with('products,topicImg,headImg')->find($id);
        return $theme;
    }
}
