<?php

namespace app\api\model;

class Product extends BaseModel
{
    // 一个商品对应多个描述图片
    public function imgs() {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    // 一个商品对应多个属性
    public function properties() {
        return $this->hasMany('ProductProperty', 'product_id','id');
    }

    public function getMainImgUrlAttr($value, $data) {
        return $this->prefixUrl($value, $data['from'] == 1);
    }

    public static function getMostRecent($count) {
        $products = self::limit($count)->order('create_time desc')->select();
        return $products;
    }

    public static function getProductByCategoryId($categoryId) {
        $result = self::where('category_id', '=', $categoryId)->select();
        return $result;
    }

    public static function getProductDetail($productId) {
        // 每一个链式方法，都会返回一个Query对象
        // $product = self::with(['imgs.imageUrl'])->with(['properties'])->find($productId);
        $product =
            self::with(['imgs' => function($query) {
                $query->with(['imageUrl'])->order('order', 'asc');
            }])
            ->with(['properties'])
            ->find($productId);
        return $product;
    }

}
