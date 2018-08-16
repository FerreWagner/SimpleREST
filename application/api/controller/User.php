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
        //接收参数
        $data = $this->params;
        //检测用户名
        $user_name_type = $this->checkUsername($data['user_name']);
        switch ($user_name_type)
        {
            case 'phone':
                $this->checkExist($data['user_name'], 'phone', 1);
                $db_res = db('user')
                    ->field('user_id, user_name, user_phone, user_email, user_rtime, user_pwd')
                    ->where('user_phone', $data['user_name'])
                    ->find();
                break;
            case 'email':
                $this->checkExist($data['user_name'], 'email', 1);
                $db_res = db('user')
                    ->field('user_id, user_name, user_phone, user_email, user_rtime, user_pwd')
                    ->where('user_email', $data['user_name'])
                    ->find();
                break;
        }
        if ($db_res['user_pwd'] !== $data['user_pwd']){
            $this->returnMsg(400, '用户名或密码不正确');
        }else{
            unset($db_res['user_pwd']); //密码永不返回
            $this->returnMsg(200, '登陆成功', $db_res);
        }

    }

    /**
     * 用户注册
     */
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

    public function upload_head_img()
    {
        //接收参数
        $data = $this->params;
        //上传文件，获得路径
        $head_img_path = $this->uploadFile($data['user_icon'], 'head_img');
        //存入数据库
        $res = db('user')->where('user_id', $data['user_id'])->setField('user_icon', $head_img_path);
        if ($res){
            $this->returnMsg(200, '头像上传成功', $head_img_path);
        }else{
            $this->returnMsg(400, '上传头像失败');
        }
    }

    public function change_pwd()
    {
        //接收参数
        $data = $this->params;
        //检测用户名并取出数据库中的密码
        $user_name_type = $this->checkUsername($data['user_name']);
        switch ($user_name_type)
        {
            case 'phone':
                $this->checkExist($data['user_name'], 'phone', 1);
                $where['user_phone'] = $data['user_name'];
                break;
            case 'email':
                $this->checkExist($data['user_name'], 'email', 1);
                $where['user_email'] = $data['user_name'];
                break;
        }

        //判断原始密码是否正确
        $db_ini_pwd = db('user')->where($where)->value('user_pwd');
        if ($db_ini_pwd !== $data['user_ini_pwd']){
            $this->returnMsg(400, '原密码错误');
        }
        //新密码入库
        $res = db('user')->where($where)->setField('user_pwd', $data['user_pwd']);
        if ($res !== false){
            $this->returnMsg(200, '密码修改成功');
        }else{
            $this->returnMsg(400, '密码修改失败');
        }
    }

    public function find_pwd()
    {
        //接收参数
        $data = $this->params;
        //检测验证码
        $this->checkCode($data['user_name'], $data['code']);
        //检测用户名
        $user_name_type = $this->checkUsername($data['user_name']);
        switch ($user_name_type)
        {
            case 'phone':
                $this->checkExist($data['user_name'], 'phone', 1);
                $where['user_phone'] = $data['user_name'];
                break;
            case 'email':
                $this->checkExist($data['user_name'], 'email', 1);
                $where['user_email'] = $data['user_name'];
                break;
        }
        //修改数据库
        $res = db('user')->where($where)->setField('user_pwd', $data['user_pwd']);
        if ($res !== false){
            $this->returnMsg(200, '密码修改成功');
        }else{
            $this->returnMsg(400, '密码修改失败');
        }
    }



}