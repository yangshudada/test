<?php
namespace Small\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function banner(){
        //轮播图模块
        $lb['menu_status'] = 1;
        $lb['menu_check'] = 1;
        $lb['menu_head'] = 1;
        $arr1 = M('menu')->alias('m')->join('t_menu_type t on m.menu_id=t.menuid','left')->where($lb)->group('menu_id')->order('m.menu_sort desc,m.menu_id desc')->field('m.*')->select();
        $this->ajaxReturn($arr1);
    }

    public function menu(){
        //轮播图模块
        $lb['menu_status'] = 1;
        $lb['menu_check'] = 1;
        $arr1 = M('menu')->alias('m')->join('t_menu_type t on m.menu_id=t.menuid','left')->where($lb)->group('menu_id')->order('m.menu_sort desc,m.menu_id desc')->field('m.*')->select();
        $this->ajaxReturn($arr1);
    }

    public function detail(){
        //轮播图模块
        $data['menu_id'] = I('post.id');
        $arr1 = M('menu')->where($data)->find();
        $this->ajaxReturn($arr1);
    }

    public function login(){
        $name = addslashes(trim(I("name")));
        $pwd = addslashes(trim(I("pwd")));
        $data['user_name'] = $name;
        $data['user_pwd'] = md5($pwd.'789');
        $bool = M('user')->where($data)->find();
        if($bool){
            $this->ajaxReturn(array('code'=>200));
        }else{
            $this->ajaxReturn(array('code'=>300));
        }
    }


}