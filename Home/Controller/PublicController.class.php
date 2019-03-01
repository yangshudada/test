<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/18
 * Time: 9:58
 */
namespace Home\Controller;
use Think\Controller;
use Think\Think;


class PublicController extends Controller
{
    /*
     * 脚本
     */
        public function jb(){
        $company = M('house');
        $image = $company->select();
        foreach($image as $v){
            if(strpos($v['house_image_jpg'],'baleijia/Admin/image') !==false){
//                if(strpos($v['company_logo'],'png') !==false||strpos($v['company_logo'],'jpg') !==false||strpos($v['company_logo'],'jpeg') !==false||strpos($v['company_logo'],'mp4') !==false){
                    echo $v['house_id'];
                    echo $logo = str_replace("baleijia/Admin/image","company/Upload",$v['house_image_jpg']);
                    $arr['house_id']=$v['house_id'];
                    $data['house_image_jpg'] = $logo;
echo "<br/>";
                    $company->where($arr)->save($data);
//                }
            }else{

            }
        }
    }
    /**
     * 上传图片
     */
    public function upload(){
        //oss上传
/*         $bucketName = C('ALIOSS_CONFIG.OSS_TEST_BUCKET');
        vendor('OSS.autoload');

        $ossClient = new \OSS\OssClient(C('ALIOSS_CONFIG.OSS_ACCESS_ID'), C('ALIOSS_CONFIG.OSS_ACCESS_KEY'), C('ALIOSS_CONFIG.OSS_ENDPOINT'), false);
        $web=C('ALIOSS_CONFIG.OSS_WEB_SITE');
        //图片
        $fFiles=$_FILES['file'];
        $rs=ossUpPic($fFiles,'s',$ossClient,$bucketName,$web,1);
        if($rs['code']==1){
            //图片
            $img = $rs['msg'];
            //如返回里面有缩略图：
            $thumb=$rs['thumb'];
            return $this->ajaxReturn(array('code'=>200,'info'=>$img,'info1'=>$thumb));
        }else{
            return $this->ajaxReturn(array('code'=>400,'info'=>'图片有误：'.$rs['msg']));
        } */

//
       //$info["file"]["savepath"] . $info["file"]["savename"];
       $name = I('post.name')?I('post.name'):'';
       $action = I('post.action')?I('post.action').'/':'';
       mkdir(iconv('utf-8','gbk','./Upload/'.$name.'/'.$action),0777,true);
       $upload = new \Think\Upload();// 实例化上传类
       $upload->maxSize   =     0 ;// 设置附件上传大小
       $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
       $upload->rootPath  =      './Upload/'.$name.'/'.$action.'/'; // 设置附件上传根目录
       $upload->savePath = ''; // 设置附件上传（子）目录
       $upload->subName  = array('date','Y-m-d');
       $upload->saveName = array('date', 'His' . rand(100, 999));
       $info   =   $upload->upload();
       if(!$info) {// 上传错误提示错误信息
           return $this->ajaxReturn(array('code'=>400,'info'=>$upload->getError()));
       }else{// 上传成功 获取上传文件信息
//            $info["image"]["savepath"] . $info["image"]["savename"]
           $imgurl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/Upload/'.$name.'/'.$action.$info["file"]["savepath"] . $info["file"]["savename"];

           //获取url的后面部分
           //$uri = strchr($imgurl,__ROOT__);
           $uri = $imgurl;
           //绝对路径
           $www = '/home'.$uri;
           //后缀(.gif)
           $hz = strrchr(basename($imgurl),'.');
           if(strstr(strtolower($hz),'jpg')||strstr(strtolower($hz),'jpeg')){
               $imgurl1 = $imgurl;
           }else{
               $trueurl = str_replace($hz,'.jpg',$www);
               $weburl = str_replace($hz,'.jpg',$uri);
               if(strstr(strtolower($hz),'gif')){
                   $upimg=imagecreatefromgif($www);
                   $size=getimagesize($www);
                   $image=imagecreatetruecolor($size[0],$size[1]);
                   imagecopyresampled($image,$upimg,0,0,0,0,$size[0],$size[1],$size[0],$size[1]);
                   imagejpeg($image,$trueurl);
                   move_uploaded_file($www,$trueurl);
                   $imgurl1 = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$weburl;
               }else if(strstr(strtolower($hz),'png')){
                   $image = new \Think\Image();
                   $image->open($www);
                   $width = $image->width(); // 返回图片的宽度
                   $height = $image->height();
                   //$type = pathinfo($www,'PATHINFO_EXTENSION');
                   $image->thumb($width,$height)->save($trueurl,'jpg');
                   //jpg缩略图
                   $imgurl1 = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$weburl;
               }
           }
           return $this->ajaxReturn(array('code'=>200,'info'=>$imgurl,'info1'=>$imgurl1));
       }
    }

    /**
     * 上传EXCEL
     */
    public function uploadExcel(){
        //$info["file"]["savepath"] . $info["file"]["savename"];
        $name = I('post.name');
        mkdir(iconv('utf-8','gbk','./Upload/Excel/'.$name),0777,true);
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728000;// 设置附件上传大小
        $upload->exts = array('xlsx', 'xls');
        // 设置附件上传类型
        $upload->rootPath = './Upload/Excel/'.$name.'/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->saveName = array('date', 'YmdHis');

        // 上传文件
        $info = $upload->upload();

        if (!$info) {//上传错误提示错误信息
            return $this->ajaxReturn(array('code'=>400,'info'=>$upload->getError()));
        } else
        {// 上传成功
            $imgurl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].__ROOT__.'/Upload/Excel/'.$name.'/'.$info["file"]["savepath"] . $info["file"]["savename"];
            return $this->ajaxReturn(array('code'=>200,'info'=>$imgurl));
        }
    }

    /**
     * 上传图片
     */
    public function ueditor(){
        //$info["file"]["savepath"] . $info["file"]["savename"];
        mkdir(iconv('utf-8','gbk','./Upload/ueditor'),0777,true);
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     31457280 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Upload/ueditor/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->saveName = array('date', 'His' . rand(100, 999));
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            return $this->ajaxReturn(array('code'=>400,'info'=>$upload->getError()));
        }else{// 上传成功 获取上传文件信息
//            $info["image"]["savepath"] . $info["image"]["savename"]
            $imgurl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].__ROOT__.'/Upload/ueditor/'.$info["file"]["savepath"] . $info["file"]["savename"];
            return $this->ajaxReturn(array('code'=>0,'msg'=>'上传成功','data'=>array('src'=>$imgurl)));
        }
    }


    /**
     * 生成缩略图
     */
    public function displayImg()
    {
        $imgurl = I('post.image');
        //获取url的后面部分
        $uri = strchr($imgurl, __ROOT__);
        //绝对路径
        $www = '/baleijia/wamp/www/' . $uri;
        //后缀(.gif)
        $hz = strrchr(basename($imgurl), '.');
        if (strstr(strtolower($hz), 'jpg')||strstr(strtolower($hz),'jpeg')) {
            $imgurl1 = $imgurl;
        } else {
            $trueurl = str_replace($hz, '.jpg', $www);
            $weburl = str_replace($hz, '.jpg', $uri);
            if(strstr(strtolower($hz),'gif')){
                $upimg=imagecreatefromgif($www);
                $size=getimagesize($www);
                $image=imagecreatetruecolor($size[0],$size[1]);
                imagecopyresampled($image,$upimg,0,0,0,0,$size[0],$size[1],$size[0],$size[1]);
                imagejpeg($image,$trueurl);
                move_uploaded_file($www,$trueurl);
                $imgurl1 = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$weburl;
            }else if(strstr(strtolower($hz),'png')){
                $image = new \Think\Image();
                $image->open($www);
                $width = $image->width(); // 返回图片的宽度
                $height = $image->height();
                //$type = pathinfo($www,'PATHINFO_EXTENSION');
                $image->thumb($width,$height)->save($trueurl,'jpg');
                //jpg缩略图
                $imgurl1 = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$weburl;
            }

        }
        $mysql = I('post.mysql');
        if($mysql=='public'){
            $arr['public_id'] = I('post.id');
            $newData["public_image_jpg"] = $imgurl1;
            $bool = M("internet_public")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='design'){
            $arr['design_id'] = I('post.id');
            $newData["design_image_jpg"] = $imgurl1;
            $bool = M("internet_design")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='design2'){
            $arr['design_id'] = I('post.id');
            $newData["design_real_image_jpg"] = $imgurl1;
            $bool = M("internet_design")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='purchase'){
            $arr['purchase_id'] = I('post.id');
            $newData["purchase_image_jpg"] = $imgurl1;
            $bool = M("internet_purchase")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='cost'){
            $arr['cost_id'] = I('post.id');
            $newData["cost_image_jpg"] = $imgurl1;
            $bool = M("internet_cost")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='operation'){
            $arr['operation_id'] = I('post.id');
            $newData["operation_image_jpg"] = $imgurl1;
            $bool = M("internet_operation")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='whitepaperDetail'){
            $arr['whitepaper_did'] = I('post.id');
            $newData["whitepaper_wimage_jpg"] = $imgurl1;
            $bool = M("whitepaper_detail")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif ($mysql=='whitepaperList'){
            $arr['whitepaper_id'] = I('post.id');
            $newData["whitepaper_image_jpg"] = $imgurl1;
            $bool = M("whitepaper")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }elseif($mysql=='house'){
            $arr['house_id'] = I('post.id');
            $newData["house_image_jpg"] = $imgurl1;
            $bool = M("house")->where($arr)->save($newData);
            if($bool!==false){
                return $this->ajaxReturn(array('code' => 200, 'info' => 'jpg生成了'));
            }
        }
    }

    /**
     * 上传文件
     */
    public function uploadfolder(){
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        ini_set('upload_max_filesize', '8M');
        //$info["file"]["savepath"] . $info["file"]["savename"];
        $name = I('post.name');
        $action = I('post.action');
        mkdir(iconv('utf-8','gbk','./Upload/'.$name.'/'.$action),0777,true);
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     0 ;// 设置附件上传大小
        $upload->exts      =     array('zip', 'rar','7z','rvt','rfa');// 设置附件上传类型
        $upload->rootPath  =      './Upload/'.$name.'/'.$action.'/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->subName  = array('date','Y-m-d');
        $upload->saveName = array('date', 'His' . rand(100, 999));
        $info   =   $upload->upload();

        if(!$info) {// 上传错误提示错误信息
            return $this->ajaxReturn(array('code'=>400,'info'=>$upload->getError()));
        }else{// 上传成功 获取上传文件信息
//            $info["image"]["savepath"] . $info["image"]["savename"]
            $folderurl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].__ROOT__.'/Upload/'.$name.'/'.$action.'/'.$info["file"]["savepath"] . $info["file"]["savename"];
            $folder = $info["file"]["name"];//新的文件夹名称
            return $this->ajaxReturn(array('code'=>200,'info1'=>$folder,'info2'=>$folderurl));
        }
    }

    /*
     * 权限
     */
    public function rules ()
    {
        $id = I('id/d');
        if(!empty($id)) {
            //编辑
            $data['id']   = I('id/d');
            $data['status'] = array('in','1,5');
            $rules                  = M("company_authgroup")->where($data)->getField('rules');
            $arrRule                = explode(',', $rules);
            $arr['name']            = array('neq', '');
            $arr['status']          = 1;
            $level = session("internal_id");
            if($level==2){
                //全部
            }else{
                $notallow[] = '权限管理-列表';
                $notallow[] = '权限管理-添加';
                $notallow[] = '权限管理-编辑';
                $notallow[] = '权限管理-删除';
                $notallow[] = '公司管理-列表';
                $notallow[] = '公司管理-添加';
                $notallow[] = '公司管理-编辑';
                $notallow[] = '公司管理-删除';
                $notallow[] = '日志模块-列表';
                $notallow[] = '日志模块-删除';
                $arr['title']= array('not in',$notallow);
            }
            $purviewData            = M("company_authrule")->where($arr)->field(array( 'id', 'title' => 'name', 'pid'))->select();
            foreach ($purviewData as $k => $value) {
                $purviewData[$k]['open']=true;
                $purviewData[$k]['isLeaf'] = true;
                if (in_array($value['id'], $arrRule)) {
                    $purviewData[$k]['checked'] = true;
                }
            }
            print_r(json_encode($purviewData));
            exit;
        }else{
            //添加
            $arr['name']            = array('neq', '');
            $arr['status']          = 1;
            $level = session("internal_id");
            if($level==2){
                //全部
            }else{
                $notallow[] = '权限管理-列表';
                $notallow[] = '权限管理-添加';
                $notallow[] = '权限管理-编辑';
                $notallow[] = '权限管理-删除';
                $notallow[] = '公司管理-列表';
                $notallow[] = '日志模块-列表';
                $notallow[] = '日志模块-删除';
                $notallow[] = '模型分类';
                $notallow[] = '模型分类-添加';
                $notallow[] = '模型分类-编辑';
                $notallow[] = '模型类型';
                $notallow[] = '模型类型-添加';
                $notallow[] = '模型类型-编辑';
                $arr['title']= array('not in',$notallow);
            }
            $purviewData            = M("company_authrule")->where($arr)->field(array( 'id', 'title' => 'name', 'pid'))->select();
            foreach ($purviewData as $k => $value) {
                            $purviewData[$k]['open']=true;
                $purviewData[$k]['isLeaf'] = true;
            }
            print_r(json_encode($purviewData));
            exit;
        }
    }

    /*
   * 修改密码
   */
    public function change_psw ()
    {
        if(I('post.')) {
            $data['admin_id']  = session("internal_id");
            $data['admin_pwd'] = md5(I("old_pwd"));
            $data = M("company_admin")->where($data)->find();
            if ($data) {
                $newpw1 = I("new_pwd_1");
                $newpw2 = I("new_pwd_2");
                if($newpw1!=$newpw2){
                    $this->ajaxReturn(array('code'=>400,'info'=>'密码不一致'));
                }
                $newData["admin_pwd"] = md5(I("new_pwd_2"));
                $arr["admin_id"] = session("internal_id");
                $result               = M("company_admin")->where($arr)->save($newData);
                $logs = D('Logs');
                if ($result!==false) {
                    $logs->action_log($arr["admin_id"],'修改密码');
                    $this->ajaxReturn(array('code'=>200,'info'=>'修改密码成功'));
                } else {
                    $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
                }
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'旧密码不正确'));
            }
        }else{
            $this->display();
        }
    }

    public function verify()
    {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 20; // 字体大小
        $Verify->codeSet = '0123456789';
        $Verify->length = 4; // 多少个字符
        $Verify->useNoise = false; // 是否添加杂点
        $Verify->imageW = 221; // 验证码宽度
        $Verify->imageH = 50; // 验证码高度
        $Verify->entry();
    }

    function check_verify($code, $id = ""){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }


    //    登录
    public function loginb ()
    {
        if(session('internal_id')&&session("internal_name")) {
            $this->redirect('Index/index');
        }else {
            if (I('post.')) {
                $code = addslashes(I('post.admin_verify'));
                if(!$this->check_verify($code)){
                    $this->ajaxReturn(array("code" => "400", "info" => "验证码不正确"));
                    exit;
                }
                $name = I("name");
                $pwd = md5(I("pwd"));
                if (!empty($name) && !empty($pwd)) {
                    $arr['admin_name'] = $name;
                    $arr['admin_pwd'] = $pwd;
                    $arr['admin_show'] = 1;
                    $arr['admin_company_id'] = array('gt', 0);
                    $data = M("company_admin")
                        ->where($arr)
                        ->find();
                    if ($data) {
                        session("internal_id", $data["admin_id"]);
                        session("internal_name", $data["admin_name"]);
                        $this->ajaxReturn(array('code' => 200, 'info' => '登录成功'));
                    } else {
                        $this->ajaxReturn(array('code' => 400, 'info' => '登录失败'));
                    }
                } else {
                    $this->ajaxReturn(array('code' => 400, 'info' => '账号密码为空'));
                }
            } else {
                $this->display();
            }
        }
    }

    //退出
    public function loginout ()
    {
        session("internal_id", null);
        session("internal_name", null);
        if(session('internal_id')||session('internal_name')){
            $this->ajaxReturn(array('code'=>400,'info'=>'退出失败'));
        }else{
            $this->ajaxReturn(array('code'=>200,'info'=>'退出中'));
        }
    }

    /*
     * main
     */
    public function welcome(){
        $this->display();
    }

    /*
    * main
    */
    public function paperStyle(){
        $f = M('type')->where('type_pid=0')->field('type_id as id,type_name as name,type_explain')->select();
        if(!$f){
            print_r('暂无数据');
            exit;
        }
        foreach($f as $k=>$v){
            $t = M('type')->where('type_pid='.$v['id'])->field('type_id as id,type_name as name,type_explain')->select();
            if($t){
                foreach($t as $key=>$value){
                    $t[$key]['href'] = "paperList?type_id=".$value['id'];
                }
                $f[$k]['children'] = $t;
            }
        }
        print_r(json_encode($f));
        exit;
    }

    /*
   * 权限
   */
    public function paper ()
    {
        $id = I('id/d');
        if(!empty($id)) {
            //编辑
            $data['menu_id']   = I('id/d');
            $rules = M("menu")->where($data)->getField('menu_types');
            $arrRule = explode(',', $rules);
            $purviewData = M("type")->field(array( 'type_id'=>'id', 'type_name' => 'name', 'type_pid'=>'pid'))->select();
            foreach ($purviewData as $k => $value) {
                $purviewData[$k]['open']=true;
                $purviewData[$k]['isLeaf'] = true;
                if (in_array($value['id'], $arrRule)) {
                    $purviewData[$k]['checked'] = true;
                }
            }
            print_r(json_encode($purviewData));
            exit;
        }else{
            $purviewData = M("type")->field(array( 'type_id'=>'id', 'type_name' => 'name', 'type_pid'=>'pid'))->select();
            foreach ($purviewData as $k => $value) {
                $purviewData[$k]['open']=true;
                $purviewData[$k]['isLeaf'] = true;
            }
            print_r(json_encode($purviewData));
            exit;
        }
    }

}