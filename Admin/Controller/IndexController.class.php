<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

    //菜谱首页
    public function index(){

		//轮播图模块
		$lb['menu_status'] = 1;
		$lb['menu_check'] = 1;
		$lb['menu_head'] = 1;
		$arr1 = M('menu')->alias('m')->join('t_menu_type t on m.menu_id=t.menuid','left')->where($lb)->group('menu_id')->order('m.menu_sort desc,m.menu_id desc')->field('m.*')->cache(true)->select();


		$this->assign('arr1',$arr1);

        //中间部分
        $zj['menu_status'] = 1;
        $zj['menu_check'] = 1;
        $arr2 = M('menu')->alias('m')->join('t_menu_type t on m.menu_id=t.menuid','left')->where($zj)->group('menu_id')->order('m.menu_sort desc,m.menu_id desc')->field('m.*')->select();
        $this->assign('arr2',$arr2);

        //右侧部分
        $arr3 = M('menu')->alias('m')->join('t_menu_type t on m.menu_id=t.menuid','left')->where($zj)->group('menu_id')->order('m.menu_sort desc,m.menu_id desc')->field('m.*')->limit(4)->select();
        $this->assign('arr3',$arr3);

        $this->display();
    }

    //菜谱详情
    public function recipe(){
		if(I('name')&&I('email')){
			//评论模块
			$pl['review_name'] = I('name');
			$pl['review_email'] = I('email');
			$pl['review_star'] = I('star');
			$pl['review_content'] = I('content');
			$pl['review_time'] = date('Y-m-d H:i:s');
			$pl['review_check'] = 0;
			$pl['review_menuid'] = I('id/d');
			$pl['review_pid'] = 0;
			
			$id = I('id');
			if(!$id){
				$this->error('菜谱不存在');
				exit;
			}
		
			$arr1 = M('review')->add($pl);
			if($arr1){
				$this->redirect('index/recipe',array('id' =>$id), 1, '审核中，请稍后');
			}else{
				$this->error('评论失败');
			}
		}else{
			//详情
			$xq['menu_status'] = 1;
			$xq['menu_check'] = 1;
			$xq['menu_id'] = I('id/d');
			$arr1 = M('menu')->where($xq)->find();
			$this->assign('arr1',$arr1);

			//相似的食谱
			$xs['menuid'] = I('id/d');
			$types = M('menu_type')->where($xs)->getField('tdid',true);
			if($types) {
				$tp['tdid'] = array('in', $types);
				$tp['menuid'] = array('neq', I('id/d'));
				$menuid = M('menu_type')->where($tp)->distinct(true)->getField('menuid', true);
				if($menuid) {
					$list['menu_id'] = array('in', $menuid);
					$arr2 = M('menu')->where($list)->order('menu_sort desc,menu_id desc')->limit(4)->select();
					$this->assign('arr2', $arr2);
				}
			}

			//已有的评论
			$pl['review_menuid'] = I('id/d');
			$review = M('review')->where($pl)->limit(4)->select();
            $this->assign('review', $review);
            //右侧部分
            $zj['menu_status'] = 1;
            $zj['menu_check'] = 1;
            $arr3 = M('menu')->where($zj)->order('menu_sort desc,menu_id desc')->limit(4)->select();
            $this->assign('arr3',$arr3);
			$this->display();
		}
    }

    //菜谱列表
    public function indexList(){
        //轮播图模块
        $lb['menu_status'] = 1;
        $lb['menu_check'] = 1;
        $lb['menu_head'] = 1;
        $arr1 = M('menu')->where($lb)->order('menu_sort desc,menu_id desc')->select();
        $this->assign('arr1',$arr1);

        //中间部分
        if(I('type')){
            $zj['t.tdid'] = I('type/d');
        }
        $zj['m.menu_status'] = 1;
        $zj['m.menu_check'] = 1;
        $arr2 = M('menu')->alias('m')->join('t_menu_type t on m.menu_id=t.menuid','left')->where($zj)->group('menu_id')->order('m.menu_sort desc,m.menu_id desc')->field('m.*')->select();
        $this->assign('arr2',$arr2);

        //右侧部分
        $fl['type_pid'] = array('gt',0);
        $arr3 = M('type')->where($fl)->field('type_id,type_name')->select();
        $this->assign('arr3',$arr3);


        $this->display();
    }
	
	 //地图
    public function map(){
		$this->display();
	}

    //通用文字识别
    public function ImageAI(){
        $this->display();
    }
	
}