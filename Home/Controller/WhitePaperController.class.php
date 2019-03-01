<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/21
 * Time: 15:04
 */
namespace Home\Controller;
use Think\Controller;
use Think\Auth;

/**
 * 房变家模块数据上传
 * Class HomeController
 * @package Home\Controller
 */
class WhitePaperController extends CommonController
{

    /**
     *  白皮书列表
     */
    public function paperList ()
    {

        if(I('type_id/d')){
            $type = I('type_id/d');
        }else{
            $type = M('type')->where('type_pid!=0')->getField('type_id');
        }
        if(I('limit/d')&&I('page/d')) {
            $list = M('menu_type')->where('tdid='.$type)->getField('menuid',true);
            if(!$list){
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => 0, 'data' => ""));
            }
            if(I('name')){
                $sql['menu_name'] = array('like','%'.trim(I('name')).'%');
            }
            $sql['menu_status'] = 1;
            $sql['menu_id'] = array('in',$list);
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("menu")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("menu")->where($sql)->order('menu_sort desc,menu_id desc')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->assign('type',$type);
            $this->display();
        }
    }

    /*
     * 白皮书添加
     */
    public function paperAdd ()
    {
        if(I('post.name')){
            if(!I('post.image')){
                $this->ajaxReturn(array('code'=>400,'info'=>'请上传图片'));
            }
            $data["menu_image"]      = I("image");
            $data["menu_image_jpg"]      = I("image_jpg");
            $data["menu_name"]      = I("name");
            $data["menu_serves"]      = I("serves");
            $data["menu_preptime"]      = I("preptime");
            $data["menu_cooking"]      = I("cooking");
            $data["menu_calories"]      = I("calories");
            $data["menu_content"]      = I("content");
            $data["menu_createtime"]      = date('Y-m-d H:i:s');
            $data["menu_ingredients"]      = I("ingredients");
            $data["menu_directions"]      = I("directions");
            $data["menu_status"]      = 1;
            $data["menu_check"]      = 1;
            $data["menu_chaeck_time"]      = date('Y-m-d H:i:s');
            $data["menu_checkid"]      = session("internal_id");
            $data["menu_sort"]      = I("sort");
            $data["menu_types"] = ltrim(I('ruleid'),',');

            M()->startTrans();
            $result = M("menu")->add($data);
            if ($result) {
                $types = explode(',',$data["menu_types"]);
                foreach($types as $v){
                    $pid = M('type')->where('type_id='.$v)->getField('type_pid');
                    if($pid>0){
                        $dataList[]=array('menuid'=>$result,'tdid'=>$v);
                    }
                }
                $bool = M('menu_type')->addAll($dataList);
                if($bool){
                    M()->commit();
                    $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
                }else{
                    M()->rollback();
                    $this->ajaxReturn(array('code'=>400,'info'=>'分类信息添加失败'));
                }
            } else {
                M()->rollback();
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else
        {
            $this ->display();
        }
    }

    /*
    * 白皮书添加
    */
    public function paperAddDetail ()
    {
        $building = I('building');
        $info = M('whitepaper_style')->select();
        foreach($info as $k=>$v){
            $info[$k]['detail'] = M('whitepaper_detail')->where("whitepaper_sid='".$v['whitepaper_sid']."'  and whitepaper_building='".$building."'")->select();
        }

        $html = '';
        $html.="<div id='detail'><table class='layui-table layui-input-block'><tbody>";
        foreach($info as $value){
            $html.="<tr><td>";
            $html.=$value['whitepaper_style'];
            $html.="<input class='checkbox_v{$value['whitepaper_sid']}' type='checkbox' name='checkbox_v{$value['whitepaper_sid']}' value='{$value['whitepaper_sid']}' lay-filter='checkbox_v'>";
            $html.="</td><td><div class='layui-input-block'>";
            foreach($value['detail'] as $val) {
                if($val['whitepaper_dname']!=''){
                    $html.="<input name='checkbox{$value['whitepaper_sid']}[{$val['whitepaper_did']}]' type='checkbox' value='{$val['whitepaper_sid']}' class='checkbox{$value['whitepaper_sid']}' lay-filter='checkbox'>{$val['whitepaper_dname']}";
                }
            }
            $html.="</div></td></tr>";
        }
        $html.="</tbody></table></div>";
        $this->ajaxReturn(array('code'=>200,'info'=>$html));
    }




    /*
    * 白皮书编辑
    */
    public function paperEdit ()
    {
        $arrs['menu_id'] = I('id/d');
        $datas = M('menu')->where($arrs)->find();
        $menu_types = $datas['menu_types'];
        if(I('post.name')){
            if(!I('post.image')){
                $this->ajaxReturn(array('code'=>400,'info'=>'请上传图片'));
            }
            $data["menu_image"]      = I("image");
            $data["menu_image_jpg"]      = I("image_jpg");
            $data["menu_name"]      = I("name");
            $data["menu_serves"]      = I("serves");
            $data["menu_preptime"]      = I("preptime");
            $data["menu_cooking"]      = I("cooking");
            $data["menu_calories"]      = I("calories");
            $data["menu_content"]      = I("content");
            $data["menu_createtime"]      = date('Y-m-d H:i:s');
            $data["menu_ingredients"]      = I("ingredients");
            $data["menu_directions"]      = I("directions");
            $data["menu_status"]      = 1;
            $data["menu_check"]      = 1;
            $data["menu_chaeck_time"]      = date('Y-m-d H:i:s');
            $data["menu_checkid"]      = session("internal_id");
            $data["menu_sort"]      = I("sort");
            $data["menu_types"] = ltrim(I('ruleid'),',');
            M()->startTrans();
            $result = M("menu")->where($arrs)->save($data);
            if ($result!==false) {
                $beforetype = explode(',',$menu_types);
                foreach($beforetype as $val){
                    $beforepid = M('type')->where('type_id='.$val)->getField('type_pid');
                    if($beforepid>0){
                        M('menu_type')->where(array('menuid'=>I('id/d'),'tdid'=>$val))->delete();
                    }
                }
                $types = explode(',',$data["menu_types"]);
                foreach($types as $v){
                    $pid = M('type')->where('type_id='.$v)->getField('type_pid');
                    if($pid>0){
                        $dataList[]=array('menuid'=>I('id/d'),'tdid'=>$v);
                    }
                }
                $bool = M('menu_type')->addAll($dataList);
                if($bool){
                    M()->commit();
                    $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
                }else{
                    M()->rollback();
                    $this->ajaxReturn(array('code'=>400,'info'=>'分类信息修改失败'));
                }
            } else {
                M()->rollback();
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        }else
        {
            $this->assign('data',$datas);
            $this ->display();
        }
    }

    /*
    * 白皮书删除
    */
    public function paperDel ()
    {

            $arr['menu_id'] = I('id');
            $result = M("menu")->where($arr)->find();
            if ($result) {
                $bool = M("menu")->where($arr)->delete();
                if($bool){
                    $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
                }else{
                    $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
                }
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'id不存在'));
            }

    }

    /*
    * 白皮书生成
    */
    public function paperCreate ()
    {
        $id = I('id/d');
        $cz['whitepaper_id'] = $id;
        $tou = M('whitepaper')->where($cz)->find();
        if($tou){
            $filename = $tou['whitepaper_id'].'.html';
            $arr['whitepaper_did'] = array('in',$tou['whitepaper_details']);

            //分组合并
            $allstyle = M()->query("select d.whitepaper_sid,group_concat(d.whitepaper_did) result from t_whitepaper_detail d where d.whitepaper_did in (".$tou['whitepaper_details'].") group by d.whitepaper_sid");
//            $all = M('whitepaper_detail')->where($arr)->group('whitepaper_sid')->order('whitepaper_did desc')->field('*,count(whitepaper_did)')->select();

//            $A = M()->GETLASTSQL();

//            $arr['whitepaper_sid'] = I("sid/d");
//            $style = M('whitepaper_style')->where($arr)->find();
//
//            //获取头部信息
//            $arr1['whitepaper_id'] = $style['whitepaper_wid'];
//            //获取分类
//            $arr11['whitepaper_wid'] = $style['whitepaper_wid'];
//            $allstyle= M('whitepaper_style')->where($arr11)->field('whitepaper_sid,whitepaper_style')->select();


            $htmlTitle = $tou['whitepaper_name'];
            $html1 = "";
            //定义html文件标签
            $html1 = $html1 . "<html>";
            $html1 = $html1 . "<head>";
            $html1 = $html1 . "<title>";
            $html1 = $html1 . $htmlTitle;
            $html1 = $html1 . "</title>";
            $html1 = $html1 . "<meta name='renderer' content='webkit'>";
            $html1 = $html1 . "<meta charset='utf-8'>";
            $html1 = $html1 . "<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>";
            $html1 = $html1 . "<meta name='viewport' content='width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi' />";
            $html1 = $html1 . "<link rel='stylesheet' href='/baleijia/Admin/Public/assets/css/font.css'>";
            $html1 = $html1 . "<link rel='stylesheet' href='/baleijia/Admin/Public/assets/css/xadmin.css'>";
            $html1 = $html1 . "</head>";
            $html1 = $html1 . "<body>";
            $html1 = $html1 . "<div class='layui-box' style='background-color: #336581;'><div class='layui-row'><div class='layui-col-xs3'>
            <img src='".$tou['whitepaper_image']."' style='width:80%;padding: 1.3rem 1.2rem 0.8rem 1.2rem;'/></div><div class='layui-col-xs9' style='color:#fff;text-align:center;font-size:5vw;padding: 1.3rem 7rem 0.8rem 1.2rem;'>".$tou['whitepaper_name']."</div></div>
            </div><div style='margin:0 0.5rem;'>";
            $content = '';

            //分类  详情整合
            foreach($allstyle as $k=>$val){
                $arr3['whitepaper_sid']=$val['whitepaper_sid'];
                //分类名称
                $whitepaper_style = M('whitepaper_style')->where($arr3)->getField('whitepaper_style');
                $arr4['whitepaper_did'] = array('in',$val['result']);
                $detail = M('whitepaper_detail')->where($arr4)->select();
                $content .= "<div style='font-size: 1.5rem;color: #336581;'><br/>".$whitepaper_style."</div>";
                $content .= "<table class='layui-table'>";
                $content .= "<colgroup><col width='150'><col width='200'><col></colgroup><thead><tr>";
                $content .= "<th style='text-align: center'>图片</th><th style='text-align: center;width: 25%;'>品名</th><th style='text-align: center;width: 25%;'>规格</th><th style='text-align: center;width: 10%;min-width: 30px;'>数量</th><th style='text-align: center;width: 15%;min-width: 30px;'>品牌</th></tr></thead>";
                $content .= "<tbody>";

                foreach($detail as $value){
                    $content .= "<tr>";
                    $content .= "<td style='text-align: center'><img src='".$value['whitepaper_wimage']."' style='width:100px;height:100px;'/></td>";
                    $content .= "<td style='text-align: center'>".$value['whitepaper_dname']."</td>";
                    $content .= "<td style='text-align: center'>".$value['whitepaper_size']."</td>";
                    $content .= "<td style='text-align: center'>".$value['whitepaper_num']."</td>";
                    $content .= "<td style='text-align: center'>".$value['whitepaper_brand']."</td>";
                    $content .= "</tr>";
                }

                $content .= "</tbody></table>";
            }
            $html1 = $html1 . $content;
            $html1 = $html1 . "</div></body>";
            $html1 = $html1 . "</html>";

            define("ROOT_PATH", "D:/baleijia/wamp/www/company/Public/whitepaper/");
            //判断今天的文件夹是否存在
            if (!is_dir(ROOT_PATH)) {
                //如果不存在就建立
                mkdir(ROOT_PATH, 0777);
            }else{
                unlink(ROOT_PATH);
                mkdir(ROOT_PATH, 0777);
            }
            //写成html文件

            $fp = fopen("D:/baleijia/wamp/www/company/Public/whitepaper/" . "$filename", "w");
            fwrite($fp, $html1);
            fclose($fp);

            $fileUrl = "D:/baleijia/wamp/www/company/Public/whitepaper/$filename";
            if (file_exists($fileUrl)) {
                $fileData["whitepaper_url"] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."/company/Public/whitepaper/$filename";
                $bool1 = M("whitepaper")->where($cz)->save($fileData);
                if($bool1!==false){
                    $this->ajaxReturn(array('code'=>200,'info'=>'success'));
                }else{
                    $this->ajaxReturn(array('code'=>400,'info'=>'更新url失败'));
                }

            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'生成html失败'));
            }

        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'id不存在'));
        }
    }

    /**
     *  白皮书列表
     */
    public function paperStyle ()
    {
        $this->display();
    }

    /*
     * 白皮书添加
     */
    public function paperStyleAdd ()
    {
        if(I('post.')){
            $data["type_name"]      = I("style");
            $data["type_explain"]      = I("explain");
            $result = M("type")->add($data);
            if ($result) {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else
        {
            $data = M("type")->select();
            $this->assign('data',$data);
            $this ->display();
        }
    }

    /*
* 白皮书删除
*/
    public function paperStyleDel ()
    {

            $arr['whitepaper_sid'] = I('id');
            $result = M("whitepaper_style")->where($arr)->find();
            if ($result) {
                $bool = M("whitepaper_style")->where($arr)->delete();
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
     *  美食评论列表
     */
    public function paperDetail ()
    {

        if(I('limit/d')&&I('page/d')) {
            $pageCount = I('limit/d');
            $page = I('page/d');
            $where['r.review_status'] = 1;
            //TODO:数据分页
            $count = M('review')->alias('r')->join('t_menu m on r.review_menuid=m.menu_id')->where($where)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        =  M('review')->alias('r')->join('t_menu m on r.review_menuid=m.menu_id')->limit($firstRow,$pageCount)->where($where)->select();
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
    * 美食评论审核
    */
    public function paperDetailEdit ()
    {
        if(I('post.id')){
            $arr['review_id']= I('id/d');
            $data['review_check']= I('pass/d');
            $result = M("review")->where($arr)->save($data);
            if($result!==false){
                $this->ajaxReturn(array('code'=>200,'info'=>'操作成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'操作失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'id不存在'));
        }
    }

    /*
    * 美食评论删除
    */
    public function paperDetailDel ()
    {

            $arr['review_id'] = I('id');
            $result = M("review")->where($arr)->find();
            if ($result) {
                $data['review_status'] = 0;
                $bool = M("review")->where($arr)->save($data);
                if($bool){
                    $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
                }else{
                    $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
                }
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'id不存在'));
            }

    }

    /*
   * 美食评论备注
   */
    public function paperDetailAdd ()
    {
        if(I('post.id')){
            $arr['review_id']= I('id/d');
            $data['review_remark']= I('remark');
            $result = M("review")->where($arr)->save($data);
            if($result!==false){
                $this->ajaxReturn(array('code'=>200,'info'=>'操作成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'操作失败'));
            }
        }else{
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