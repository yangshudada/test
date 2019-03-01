<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/21
 * Time: 15:04
 */
namespace Home\Controller;
use Think\Controller;
use Think\MyAuth;

/**
 * 房变家模块数据上传
 * Class HomeController
 * @package Home\Controller
 */
class BargainController extends Controller
{
    /**
     *  白皮书列表
     */
    public function paperStyle ()
    {

        if(I('limit/d')&&I('page/d')) {
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("bargain_style")->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("bargain_style")->order('bargain_sid desc')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->display();
        }
    }

    /*
     * 白皮书添加
     */
    public function paperStyleAdd ()
    {
        if(I('post.')){
            $data["bargain_style"]      = I("style");
            $result = M("bargain_style")->add($data);
            if ($result) {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else
        {
            $this ->display();
        }
    }

    /*
* 白皮书删除
*/
    public function paperStyleDel ()
    {

            $arr['bargain_sid'] = I('id');
            $result = M("bargain_style")->where($arr)->find();
            if ($result) {
                $bool = M("bargain_style")->where($arr)->delete();
                if($bool){
                    $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
                }else{
                    $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
                }
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'id不存在'));
            }

    }

    /**
     *  白皮书列表
     */
    public function paperDetail ()
    {

        if(I('limit/d')&&I('page/d')) {
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M('bargain_style')->alias('s')->join('t_bargain_detail d on s.bargain_sid=d.bargain_sid')->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        =  M('bargain_style')->alias('s')->join('t_bargain_detail d on s.bargain_sid=d.bargain_sid')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->display();
        }
    }

    /*
     * 白皮书详情添加
     */
    public function paperDetailAdd ()
    {
        if(I('post.')){
            $data["bargain_name"]      = I("name");
            $data["bargain_sid"]      = I("sid/d");
            $data["bargain_to"]      = I("to");
            $data["bargain_remark"]      = I("remark");
            $data["bargain_image"]      = I("image");
            $data["bargain_time"]      = date('Y-m-d H:i:s');
            $result = M("bargain_detail")->add($data);
            if($result){
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }

        }else
        {
            $info = M("bargain_style")->select();
            if(!$info){
                $this->assign('请先添加合同分类信息');
            }
            $this->assign('info',$info);
            $this ->display();
        }
    }

    /*
    * 白皮书详情添加
    */
    public function paperDetailEdit ()
    {
        $arr['bargain_did']= I('id/d');
        if(I('post.')){
            $data["bargain_name"]      = I("name");
            $data["bargain_sid"]      = I("sid/d");
            $data["bargain_to"]      = I("to");
            $data["bargain_remark"]      = I("remark");
            $data["bargain_image"]      = I("image");
            $result = M("bargain_detail")->where($arr)->save($data);
            if($result!==false){
                $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }

        }else
        {
            $info = M("bargain_style")->select();
            if(!$info){
                $this->assign('请先添加合同分类信息');
            }
            $this->assign('info',$info);
            $datas = M("bargain_detail")->where($arr)->find();
            $this->assign('data',$datas);
            $this ->display();
        }
    }

    /*
* 白皮书删除
*/
    public function paperDetailDel ()
    {

            $arr['bargain_did'] = I('id');
            $result = M("bargain_detail")->where($arr)->find();
            if ($result) {
                $bool = M("bargain_detail")->where($arr)->delete();
                if($bool){
                    $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
                }else{
                    $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
                }
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'id不存在'));
            }

    }


//
//
//
//    public function addPaperList()
//    {
//        if (!empty($_FILES)) {
//            $upload = new \Think\Upload();// 实例化上传类
//            $upload->maxSize   =     3145728 ;// 设置附件上传大小
//            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
//            // 设置附件上传类型
//            $upload->rootPath  =     './image/whitebook/'; // 设置附件上传根目录
//            $upload->savePath  =     ''; // 设置附件上传（子）目录
//            $upload->saveName  =    array('date','His'.rand(100,999));
//
//            // 上传文件
//            $info   =   $upload->upload();
//
//            if(!$info) {//上传错误提示错误信息
//                $this->error($upload->getError());
//            }else{// 上传成功
//
//                $whitebook = M("whitebook");
//
//                $data["user_id"] = $_POST["userid"];
//                $data["whitebook_space"] = $_POST["space"];
//                $data["whitebook_image"] = "http://114.55.67.68:80/baleijia/image/whitebook/".$info["image"]["savepath"].$info["image"]["savename"];
//
//                $result = $whitebook->add($data);
//
//                if ($result) {
//                    $this->success('上传成功！');
////                    $this->ajaxReturn(array("code"=>"200","reason"=>"sucess","result"=>$data["whitebook_image"]),"JSON");
//                }else{
//                    $this->success('上传失败！');
////                    $this->ajaxReturn(array("code"=>"500","reason"=>"error"),"JSON");
//                }
//            }
//        }else
//        {
//            $this ->disPlay();
//        }
//    }

}