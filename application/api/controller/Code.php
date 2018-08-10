<?php
/**
 * Created by PhpStorm.
 * User: 15736
 * Date: 2018/8/10
 * Time: 17:20
 */

namespace app\api\controller;


class Code extends Common
{
    public function getCode()
    {
        $username = $this->params['username'];
        $exist    = $this->params['is_exist'];
        $username_type = $this->checkUsername($username);
        switch ($username_type)
        {
            case 'phone':
                $this->getCodeByUsername($username, 'phone', $exist);
                break;
            case 'email':
                $this->getCodeByUsername($username, 'email', $exist);
                break;
        }
        echo 'code';
    }

    /**
     * 通过哦手机/油箱盖获取验证码
     * @param $username 手机号/邮箱
     * @param $exist 手机号/邮箱是否应该存于数据库中1：是 0：否
     */
    public function getCodeByUsername($username, $type, $exist)
    {
        if ($type == 'phone'){
            $type_name = '手机';
        }else{
            $type_name = '邮箱';
        }
        //phone/email是否存在
        $this->checkExist($username, $type, $exist);
        //检查验证码请求频率(30s一次)
        if (session("?".$username.'_last_end_time')){  //判断是否存在该session
            if (time() - session($username.'_last_end_time') < 30){
                $this->returnMsg(400, $type_name.'验证码,每30s只能发送一次');
            }
        }
        //生成验证码入session,
        $code = $this->makeCode(6);
        //md5加密
        $md5_code = md5($username.'_'.md5($code));
        session($username, '_code', $md5_code);
        //使用session存储验证码的发送时间
        session($username.'_last_end_time', time());
        //发送验证码
        if ($type == 'phone'){
            $this->sendCodeToPhone($username, $code);
        }else{
            $this->sendCodeToEmail($username, $code);
        }
    }

    public function getCodeByEmail()
    {

    }

}