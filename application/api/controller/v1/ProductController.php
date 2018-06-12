<?php

namespace app\api\controller\v1;

use app\api\model\Product;
use app\api\validate\Count;
use app\api\validate\IdMustBePositiveInt;
use app\lib\exception\CategoryException;
use app\lib\exception\ProductException;
use think\Controller;

class ProductController extends Controller
{
    public function getRecent($count = 15) {
        (new Count())->goCheck();
        $result = Product::getMostRecent($count);
        if(empty($result)) {
            throw new ProductException();
        }
        $result = collection($result)->hidden(['summary']);
        return $result;
    }

    /**
     * 注意，把这个接口放在product下，而不是category下，
     * 因为restful是针对资源的，以资源为主，而category只是一个属性，restful要基于模型和资源
     * 要获取的信息是商品的，则该接口要和商品关联起来
     * @param $id category_id
     * @return false|\PDOStatement|string|\think\Collection|\think\model\Collection
     * @throws ProductException
     * @throws \app\lib\exception\ParamException
     */
    public function getAllProductInCategory($id) {
        (new IdMustBePositiveInt())->goCheck();
        $result = Product::getProductByCategoryId($id);
        if(empty($result)) {
            throw new ProductException();
        }
        $result = collection($result)->hidden(['summary']);
        return $result;
    }

    /**
     * @param $id 商品id
     */
    public function getOne($id) {
        (new IdMustBePositiveInt())->goCheck();
        $result = Product::getProductDetail($id);
        if(empty($result)) {
            throw new ProductException();
        }
        return $result;
    }
}
