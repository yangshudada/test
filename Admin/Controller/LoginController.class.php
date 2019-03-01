<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
	
	public function index(){
		if(session("yonghu_id")&&session("yonghu_name")){
			$data['user_name'] = session("yonghu_name");
            $data['user_id'] = session("yonghu_id");
            $bool = M('user')->where($data)->find();
			if($bool){
                $arr['user_lasttime'] = date('Y-m-d H:i:s');
                M('user')->where($data)->save($arr);
                $this->redirect('bg/index');
            }else{
                $this->display();
            }
		}else{
			$this->display();
		}
	}

    public function register(){
        $data['user_name'] =  I('post.name');
        $name = M('user')->where($data)->find();
        if($name){
            $this->error('用户名已被注册');
        }
        $data['user_email'] =  I('post.email');
        $email = M('user')->where($data)->find();
        if($email){
            $this->error('邮箱已被注册');
        }
        $data['user_pwd'] =  md5(I('post.pwd').'789');
        $data['user_ip'] =  $_SERVER['REMOTE_ADDR'];
        $data['user_createtime'] = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip) {
            $ak = "q1ljMH8aFjrxsoQbGZaMG8FlwofTIOl7";
            $url = "http://api.map.baidu.com/location/ip?ak=" . $ak . "&ip=" . $ip;
            $dw = json_decode(file_get_contents($url));//定位
            if($dw) {
                $address = explode('|', $dw->address);
                $data['user_province'] = $address[1];
                $data['user_city'] = $address[2];
            }
        }
        $bool = M('user')->add($data);
        if($bool){
            $this->success('注册成功',"index");
        }
    }

    public function login(){
        $name = addslashes(trim(I("name")));
        $pwd = addslashes(trim(I("pwd")));
        if($name&&$pwd){
            $data['user_name'] = $name;
            $data['user_pwd'] = md5($pwd.'789');
            $bool = M('user')->where($data)->find();
            if($bool){
                $arr['user_lasttime'] = date('Y-m-d H:i:s');
                M('user')->where($data)->save($arr);
                session("yonghu_id", $bool["user_id"]);
                session("yonghu_name", $bool["user_name"]);
                $this->redirect('bg/index','', 1, '登录中...');
            }else{
                $this->error('账号或密码错误');
            }
        }else{
            $this->error('账号或密码为空');
        }
    }

	public function out(){
		session("yonghu_id", null);
		session("yonghu_name", null);
		$this->redirect('index/index');      
    }

}