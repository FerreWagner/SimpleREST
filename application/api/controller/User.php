<?php
/**
 * Created by PhpStorm.
 * User: 15736
 * Date: 2018/8/10
 * Time: 17:20
 */

namespace app\api\controller;


class User extends Common
{

    public function login()
    {
        $data = $this->params;
        dump($data);
        echo 'welcome!';
    }

    public function register()
    {
        $data = $this->params;
        //检测验证码
        $this->checkCode($data['user_name'], $data['code']);
        //检查用户名电话还是邮箱
        $user_name_type = $this->checkUsername($data['user_name']);
        switch ($user_name_type)
        {
            case 'phone':
                $this->checkExist($data['user_name'], 'phone', 0);
                $data['user_phone'] = $data['user_name'];
                break;
            case 'email':
                $this->checkExist($data['user_name'], 'email', 0);
                $data['user_email'] = $data['user_name'];
                break;
        }
        //用户信息入库
        unset($data['user_name']);  //库中有这个字段，需要先干掉
        $data['user_rtime'] = time();   //register time
        $res = db('user')->insert($data);
        if (!$res){
            $this->returnMsg(400, '用户注册失败');
        }else{
            $this->returnMsg(200, '用户注册成功');
        }

    }



}