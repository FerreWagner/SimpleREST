<?php
/**
 * Created by PhpStorm.
 * User: 15736
 * Date: 2018/8/10
 * Time: 17:20
 */

namespace app\api\controller;


use PHPMailer\PHPMailer\PHPMailer;
//use lib\submail\MESSAGEXsend;

class Code extends Common
{
    public function getCode()
    {
        $username      = $this->params['username'];
        $exist         = $this->params['is_exist'];
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

    /**
     * 生成验证码
     * @param $num
     * @return int
     */
    public function makeCode($num)
    {
        $max = pow(10, $num) - 1;
        $min = pow(10, $num - 1);
        return rand($min, $max);
    }

    /**
     * 向手机发送验证码
     * @param $phone 手机号
     * @param $code 生成的验证码
     */
    public function sendCodeToPhone($phone, $code)
    {
        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, 'https://api.mysubmail.com/message/send');
        curl_setopt($curl, CURLOPT_URL, 'https://api.mysubmail.com/message/xsend');

        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $data = [
            'appid'     => '',
            'to'        => $phone,
            'project'   => '',  //项目id
            'vars'      => '{"code":'.$code.',"time":"60"}',
            'signature' => '',  //appkey
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($res);
        if ($res->status !== 'success'){    //当错误时该短信api的status会返回的status为error，所以当不等于success时即判断
            $this->returnMsg(400, $res->msg);
        }else{
            $this->returnMsg(200, '手机验证码已发送,每天发送5次，请在一分钟内验证');
        }

        dump($res->status);die;
    }
//    public function sendCodeToPhone($phone, $code)
//    {
//        $submail = new MESSAGEXsend();
//
//        $submail->SetTo($phone);
//        $submail->SetProject('6AI9I3');
//        $submail->AddVar('code', $code);
//        $submail->AddVar('time', 60);
//        $xsend = $submail->xsend();
//        if ($xsend['status'] !== 'success'){
//            $this->returnMsg(400, $xsend['msg']);
//        }else{
//            $this->returnMsg(200, '手机验证码已发送,每天发送5次，请在一分钟内验证');
//        }
//    }

    /**
     * 向邮箱发送验证码
     * @param $email
     * @param $code
     */
    public function sendCodeToEmail($email, $code)
    {
        $toemail = $email;
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->CharSet = 'utf-8';
        $mail->setLanguage('zh_cn');
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;


        $mail->Username = '18408229270@163.com';
        $mail->Password = '';
        $mail->isHTML(true);

        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465; //994
        $mail->setFrom('18408229270@163.com', '接口测试');
        $mail->addAddress($toemail, 'test');
        $mail->addReplyTo('18408229270@163.com', 'ReplyFerre');
        $mail->Subject = "你有新的验证码";
        $mail->Body = "这是一个测试邮件$code,哈哈哈哈哈";
        if (!$mail->send()){
            $this->returnMsg(400, $mail->ErrorInfo);
        }else{
            $this->returnMsg(200, '验证码已发送成功，请注意查收');
        }

//        $mail=new PHPMailer;
//        $mail->isSMTP();
//        $mail->Host="smtp.163.com";//发件人使用的smtp服务地址
//        $mail->SMTPAuth=true;
//        $mail->Username="18408229270@163.com";//发件人邮箱地址
//        $mail->Password="WZW19953000";//发件人密码
//
//        $mail->setFrom("18408229270@163.com","aaaaaa");
//        $mail->addAddress("1573646491@qq.com","bbbbbb");//收件人地址和姓名
//
//        $mail->Subject="PHPMailer测试";//标题
//        $mail->Body="PHPMailer是一个用来发送电子邮件的函数包，这是使用它发送邮件的一个demo";//正文
//
//        if(!$mail->send()){
//            echo "send failed!";
//            echo "error:".$mail->ErrorInfo;
//        }else {
//            echo "send success!";
//        }
    }

}