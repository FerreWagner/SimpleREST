<?php
/**
 * Created by PhpStorm.
 * User: 15736
 * Date: 2018/8/10
 * Time: 17:20
 */

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Validate;

class Common extends Controller
{
    protected $request; //用来处理参数
    protected $validater;   //验证数据/参数
    protected $params;  //过滤后符合要求的参数
    protected $rules = [
        'User' => [
            'login' => [
                'user_name' => ['require', 'chsDash', 'max' => 20],
                'user_pwd'  => 'require|length:32',
            ]
        ],
        'Code' => [
            'getcode' => [
                'username' => 'require',
                'is_exist' => 'require|number|length:1',
            ]
        ]
    ];
    protected function _initialize()
    {
        parent::_initialize();
        $this->request = Request::instance();
//        $this->checkTime($this->request->only(['time']));
//        $this->checkToken($this->request->param());
        $this->params = $this->checkParams($this->request->except(['time', 'token']));
    }

    /**
     * @param $arr
     */
    public function checkTime($arr)
    {
        if (!isset($arr['time']) || intval($arr['time']) <= 1){
            $this->returnMsg(400, '时间戳错误');
        }
        if (time() - intval($arr['time']) > 60){
            $this->returnMsg(400, '请求超时');
        }
    }

    /**
     * api数据返回 返回信息
     * @param $code  结果码 200正常 4*数据问题 5*服务器问题
     * @param string $msg 提示信息
     * @param array $data 返回数据
     */
    public function returnMsg($code, $msg = '', $data = [])
    {
        $re_data['code'] = $code;
        $re_data['msg']  = $msg;
        $re_data['data'] = $data;

        echo json_encode($re_data);die;
    }

    /**
     * 验证token(防止篡改数据)
     * @param $arr
     */
    public function checkToken($arr)
    {
        if (!isset($arr['token']) || empty($arr['token'])){
            $this->returnMsg(400, 'token不能为空');
        }
        $app_token = $arr['token'];

        //服务端生成token
        unset($arr['token']);
        $service_token = '';
        foreach ($arr as $k => $v){
            $service_token .= md5($v);
        }

        $service_token = md5('api_'.$service_token.'_api');
        //对比token，返回结果
        if ($app_token !== $service_token){
            $this->returnMsg(400, 'token值错误');
        }
    }

    /**
     * 验证参数 参数过滤
     * @param $arr
     * @return mixed
     */
    public function checkParams($arr)
    {
        //获取验证规则
//        halt($this->request->controller().$this->request->action());
        $rule = $this->rules[$this->request->controller()][$this->request->action()];

        //验证参数并返回错误
        $this->validater = new Validate($rule);
        if (!$this->validater->check($arr)){
            $this->returnMsg(400, $this->validater->getError());
        }
        //通过验证
        return $arr;
    }

    /**
     * 检测用户名并返回用户名类别
     * @param $username
     * @return string
     */
    public function checkUsername($username)
    {
        //采用相加的方式来判定是否合乎规则
        $is_email = Validate::is($username, 'email') ? 1 : 0;
        $is_phone = preg_match('/^1[34578]\d{9}$/', $username) ? 4 : 2;
        $flag     = $is_email + $is_phone;

        switch ($flag)
        {
            case 2:
                //not phone not email
                $this->returnMsg(400, '邮箱或手机号不正确');
                break;
            case 3:
                //is email not phone
                return 'email';
                break;
            case 4:
                //is phone not email
                return 'phone';
                break;
        }
    }

    public function checkExist($value, $type, $exist)
    {
        //判断手机还是邮箱
        $type_num = $type == 'phone' ? 2 : 4;
        $flag = $type_num + $exist;
        $phone_res = db('user')->where('user_phone', $value)->find();
        $email_res = db('user')->where('user_email', $value)->find();
        switch ($flag)
        {
            case 2:
                if ($phone_res){
                    $this->returnMsg(400, '此手机号已被占用');
                }
                break;
            case 3:
                if ($phone_res){
                    $this->returnMsg(400, '此手机号不存在');
                }
                break;
            case 4:
                if ($email_res){
                    $this->returnMsg(400, '此邮箱已被占用');
                }
                break;
            case 5:
                if ($email_res){
                    $this->returnMsg(400, '此邮箱不存在');
                }
                break;
        }
    }

}