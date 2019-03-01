<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/18
 * Time: 9:58
 */
namespace Home\Controller;
use Think\Controller;
use Think\Auth;

/**
 * 老板端全景查看类
 * Class BossController
 * @package Home\Controller
 */
class CommonController extends Controller
{
	public function _initialize()
	{
		if (session("internal_id") == '') {
			$this->redirect('Public/loginb','',1,"请先登录");
            exit;
		}
		$auth = new Auth();
		$uid  = session("internal_id");
		$name = ltrim(MODULE_NAME. '/' .CONTROLLER_NAME . '/' . ACTION_NAME,'./');
		if (!$auth->check($name, $uid)) {
			 if(strpos(strtolower(ACTION_NAME),'delete')!== false){
                $this->ajaxReturn(array('code'=>404,'info'=>'没有访问权限'));
            }
		    $this->assign('data',"没有访问权限");
			$this->display('Public:404');
			exit;
		}
	}
}