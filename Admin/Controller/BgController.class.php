<?php
namespace Admin\Controller;
use Think\Controller;
class BgController extends Controller
{
    // public function _initialize()
    // {
    //     if (session('yonghu_id') == '') {
    //         $this->error("请先登录", U('Login/index'));
    //     }
    //     $data['user_id'] = addslashes(session("yonghu_id"));
    //     $data['user_name'] = addslashes(session("yonghu_name"));
    //     $bool = M('user')->where($data)->find();
    //     if(!$bool) {
    //         $this->error("用户信息不存在", U('Login/index'));
    //     }
    // }

    public function index()
    {
        $data['user_id'] = addslashes(session("yonghu_id"));
        $data['user_name'] = addslashes(session("yonghu_name"));
        $arr = M('user')->where($data)->find();
        $this->assign('arr',$arr);
        $this->display();
    }

    public function person()
    {
        $this->display();
    }
}