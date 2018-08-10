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
}