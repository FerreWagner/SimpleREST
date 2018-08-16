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

    public function article_list()
    {
        //接收参数
        $data = $this->params;
        if (!isset($data['num'])) $data['num'] = 10;
        if (!isset($data['page'])) $data['page'] = 1;

        //查库
        $where['article_uid'] = $data['user_id'];
        $count = db('article')->where($where)->count();
        $page_num = ceil($count/$data['num']);
        $field = 'article_id, article_ctime, article_title, user_nickname';
        $join = [['api_user u', 'u.user_id = a.article_uid']];
        $res = db('article')->alias('a')->field($field)->join($join)->where($where)->page($data['page'], $data['num'])->select();

        //判定输出
        if ($res === false){
            $this->returnMsg(400, '查询失败');
        }elseif(empty($res)) {
            $this->returnMsg(200, '暂无数据');
        }else{
            $return_data['article']  = $res;
            $return_data['page_num'] = $page_num;
            $this->returnMsg(200, '查询成功', $return_data);
        }
    }

    public function article_detail()
    {
        //接收参数
        $data = $this->params;
        //查库
        $field = 'article_id, article_title, article_ctime, article_content, user_nickname';
        $where['article_id'] = $data['article_id'];
        $join = [['api_user u', 'u.user_id = a.article_uid']];
        $res = db('article')->alias('a')->join($join)->field($field)->where($where)->find();
        $res['article_content'] = htmlspecialchars_decode($res['article_content']); //在config里对前端攻击进行了转义，此处转回

        //判定并输出
        if (!$res){
            $this->returnMsg(400 ,'查询失败');
        }else{
            $this->returnMsg(200, '查询成功', $res);
        }
    }



}