<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

use think\Route;

// api.rest.com => rest.com/index.php/api
Route::domain('api', 'api');//前apu代表api.rest.com后则代表后的

//api.rest.com/user/2 => www.rest.com/index.php/api/user/index/id/2
//Route::rule('user/:id', 'user/index');
//Route::rule('user/login', 'user/login');

//登录
Route::post('user', 'user/login');
//获取验证码
Route::get('code/:time/:token/:username/:is_exist', 'code/getcode');
//用户注册
Route::post('user/register', 'user/register');
//用户登录
Route::post('user/login', 'user/login');
//用户上传头像
Route::post('user/icon', 'user/upload_head_img');
//用户修改密码
Route::post('user/change_pwd', 'user/change_pwd');
//用户找回密码
Route::post('user/find_pwd', 'user/find_pwd');
//用户绑定手机号
//Route::post('user/bind_phone', 'user/bind_phone');
//用户绑定邮箱
//Route::post('user/bind_email', 'user/bind_email');
//用户绑定电话/邮箱
Route::post('user/bind_username', 'user/bind_username');

