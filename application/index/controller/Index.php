<?php
namespace app\index\controller;
//1、大量恶意访问，超过时间则不返回数据;2、修改数据，使用token;3、窃取信息，加密敏感信息 or https

class Index
{
    public function index()
    {
        return json(['hea' => 'read', 'dd' => 'asd', 'tr' => 'freeze']);
    }
}
