<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

// banner
Route::group('api/:v/banner', function() {
    /**
     * 获取banner滚动条的信息，带多个BannerItem
     * http://z.cn/api/v1/banner/1
     */
    Route::get('/:id', 'api/:v.BannerController/getBanner');
});

// theme
Route::group('api/:v/theme', function() {
    /**
     * 获取多个theme的摘要信息，不带产品，带图片
     * http://z.cn/api/v1/theme?ids=1,2,3
     */
    Route::get('', 'api/:v.ThemeController/getSimpleList');
    /**
     * 获取theme的详细信息，带多个产品
     * http://z.cn/api/v1/theme/1
     */
    Route::get('/:id', 'api/:v.ThemeController/getComplexOne');
});

// product
Route::group('api/:v/product', function() {
    /**
     * 获取某个商品详情，根据id
     * http://z.cn/api/v1/product/2
     */
    Route::get('/:id', 'api/:v.ProductController/getOne', [], ['id'=>'\d+']);
    /**
     * 获取最新n条商品记录
     * http://z.cn/api/v1/product/recent?count=5
     */
    Route::get('/recent', 'api/:v.ProductController/getRecent');
    /**
     * 获取某分类id下的所有商品
     * http://z.cn/api/v1/product/by_category?id=2
     */
    Route::get('/by_category', 'api/:v.ProductController/getAllProductInCategory');
});

// category
Route::group('api/:v/category', function() {
    /**
     * 获取所有分类，不包含产品列表
     * http://z.cn/api/v1/category/all
     */
    Route::get('/all', 'api/:v.CategoryController/getAllCategories');
});

// token
Route::group('api/:v/token', function() {
    /**
     * 获取令牌，输入微信返回的code
     * http://z.cn/api/v1/token/user
     * code放在post的body里，key是"code"，以json的形式传过来
     */
    Route::post('/user', 'api/:v.TokenController/getToken');
});

// address
Route::group('api/:v/address', function() {
    /**
     * address 提交用户收件地址
     * http://z.cn/api/v1/address
     * code放在post的body里，key是"code"，以json的形式传过来
     */
    Route::post('', 'api/:v.AddressController/createOrUpdateAddress');
});

