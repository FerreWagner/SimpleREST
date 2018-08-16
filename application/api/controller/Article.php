<?php
/**
 * Created by PhpStorm.
 * User: 15736
 * Date: 2018/8/10
 * Time: 17:20
 */

namespace app\api\controller;


class Article extends Common
{
    public function add_article()
    {
        //接收参数
        $data = $this->params;
        $data['article_ctime'] = time();
        //入库
        $res = db('article')->insertGetId($data);   //新增并返回新增的id
        if ($res){
            $this->returnMsg(200, '新增文章成功', $res);
        }else{
            $this->returnMsg(400, '新增文章失败');
        }

    }



}