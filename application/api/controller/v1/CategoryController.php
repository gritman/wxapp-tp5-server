<?php

namespace app\api\controller\v1;

use app\api\model\Category;
use app\lib\exception\CategoryException;
use think\Controller;
use think\Exception;
use think\Request;

class CategoryController extends Controller
{
    public function getAllCategories() {
        // all的第一个参数，可以传入一组id，第二个参数是with
        // 等同于
        // Category::with('img')->select();
        $result = Category::all([], 'img');
        if(empty($result)) {
            throw new CategoryException();
        }
        return $result;
    }
}
