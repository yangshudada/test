<?php
namespace Home\Controller;
use Home\Controller\CommonController;
use Think\Auth;

/**
 *  5D部门模型管理
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends CommonController
{
    /******************************************************管理员页面*****************************************************/
    public function index ()
    {
        $auth = new Auth();
        $id  = session("internal_id");
        $arr = array();

        //没有权限则不显示
        $arr[0] = 'home/index/vrlist';
        $arr[1] = 'home/auth/adminList';
        $arr[2] = 'home/auth/roleList';
        $arr[3] = 'home/auth/ruleList';
        $arr[4] = 'home/log/logsList';
        $arr[6] = 'home/house/showBuildNotice';
        $arr[15] = 'home/test/publicFive';
        $arr[23] = 'home/WhitePaper/paperStyle';
        $arr[24] = 'home/WhitePaper/paperDetail';
        $arr[25] = 'home/WhitePaper/paperList';
        $arr[26] = 'home/Sand/houseList';
        $arr[28] = 'home/Bargain/paperStyle';
        $arr[29] = 'home/Bargain/paperDetail';
        $arr[30] = 'home/House/houseDetail';
        foreach($arr as $k=>$v){
            if($auth->check($v, $id)){
                $data[$k]=1;
            }else{
                $data[$k]=2;//没有权限
            }
        }
        $this ->assign("data", $data);
        $this ->assign("id", $id);
        $this ->assign("name", session("internal_name"));
        $this ->display();
    }

    /***********************************生成全景******************/
    public function vrList ()
    {

        /***********搜索*************/
        $pageCount = I('limit/d')?I('limit/d'):10;
        $page = I('page/d')?I('page/d'):1;
        //最小价格
        $pricemin = I('pricemin')?I('pricemin'):'';
        $this->assign('pricemin',$pricemin);
        //最大价格
        $pricemax = I('pricemax')?I('pricemax'):'';
        $this->assign('pricemax',$pricemax);
        if(!empty($pricemin)&&!empty($pricemax)){
            $sql['v.vr_price'] = array('between',array($pricemin,$pricemax));
        }else{
            if(!empty($pricemin)) {
                $sql['v.vr_price'] = array('EGT',$pricemin);
            }
            if(!empty($pricemax)) {
                $sql['v.vr_price'] = array('ELT',$pricemax);
            }
        }
        $select = I('select')?I('select'):'';
        $this->assign('select',$select);
        if(!empty($select)) {
            $sql['v.vr_name|t.name|c.company_name|v.vr_space|v.vr_premises|v.vr_style|v.vr_model|v.vr_specification|v.vr_color|v.vr_vendor|v.vr_designer|ts.name'] = array('like', '%' . $select . '%');
        }
        $company_id = I('company_id/d')?I('company_id/d'):'';
        $this->assign('company_id',$company_id);
        if(!empty($company_id)) {
            $sql['c.company_id'] = $company_id;
        }
        $vr_typeid = I('typeid/d')?I('typeid/d'):'';
        $this->assign('vr_typeid',$vr_typeid);
        if(!empty($vr_typeid)) {
            $sql['v.vr_typeid'] = $vr_typeid;
        }
        $vr_typesid = I('typesid/d')?I('typesid/d'):'';
        $this->assign('vr_typesid',$vr_typesid);
        if(!empty($vr_typesid)) {
            $sql['v.vr_typesid'] = $vr_typesid;
        }
        $vr_space = addslashes(I('space'))?I('space'):'';
        $this->assign('vr_space',$vr_space);
        if(!empty($vr_space)) {
            $sql['v.vr_space'] = $vr_space;
        }
        $vr_style = addslashes(I('style'))?I('style'):'';
        $this->assign('vr_style',$vr_style);
        if(!empty($vr_style)) {
            $sql['v.vr_style'] = $vr_style;
        }
        $vr_color = addslashes(I('color'))?I('color'):'';
        $this->assign('vr_color',$vr_color);
        if(!empty($vr_color)) {
            $sql['v.vr_color'] = $vr_color;
        }
        $vr_vendor = addslashes(I('vendor'))?I('vendor'):'';
        $this->assign('vr_vendor',$vr_vendor);
        if(!empty($vr_vendor)) {
            $sql['v.vr_vendor'] = $vr_vendor;
        }
        $vr_designer = addslashes(I('designer'))?I('designer'):'';
        $this->assign('vr_designer',$vr_designer);
        if(!empty($vr_designer)) {
            $sql['v.vr_designer'] = $vr_designer;
        }

        $order = addslashes(I('order'))?I('order'):'';
        $this->assign('order',$order);
        if(!empty($order)) {
            $orderSql = "vr_count ".$order.",v.vr_time DESC";
        }else{
            $orderSql = "v.vr_time DESC";
        }
        /********list详情****************/
        $all['v.vr_show'] =  1;
        $all['v.vr_company_id'] = array('gt',0);
        $sql['v.vr_show'] = 1;
        $sql['v.vr_company_id'] = array('gt',0);
        $level = session('vr_level');
        $this->assign('level',$level);

        if($level==1){
            //下拉公司搜索
            $company = M('all_vr_model_company')->field('company_id,company_name')->select();
            $this->assign('company',$company);
            //获取全部
        }else{
            $arr['level'] = 1;
            //获取顶级权限的公司的id
            $level1company = M('all_vr_model_company')->where($arr)->getField('company_id',true);
            array_push($level1company,session("vr_company_id"));
            $all['v.vr_company_id'] = array('in',$level1company);
            $sql['v.vr_company_id'] = array('in',$level1company);

        }

        $levelss = M('all_vr_model_company')->where('level=1')->getField('company_id',true);
        $this->assign('levelss',$levelss);

        //TODO:数据分页
        $count = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=ts.id','left')
            ->where($sql)
            ->count();// 查询满足要求的总记录数
        $firstRow = ($page-1)*$pageCount;
        $data        = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=ts.id','left')
            ->where($sql)
            ->order($orderSql)
            ->limit($firstRow,$pageCount)
            ->field('v.*,c.company_name,t.name as typename,ts.name as typesname')
            ->select();
        $this->assign('limit',$pageCount);
        $this->assign('page',$page);
        $this->assign('count',$count);
        $this->assign('data',$data);

        /******搜索条件信息*****/
        //分类
        $typeid = M("all_vr_model_type")->field('id,name')->select();
        $this->assign('typeid',$typeid);
        //类型
        $typesid = M("all_vr_model_types")->field('id,name')->select();
        $this->assign('typesid',$typesid);

        //空间
        $space = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=t.id','left')
            ->where($all)->where("v.vr_space!=''")->group('v.vr_space')->field('v.vr_space')->select();
        $this->assign('space',$space);

        //风格
        $style = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=t.id','left')
            ->where($all)->where("v.vr_style!=''")->group('v.vr_style')->field('v.vr_style')->select();
        $this->assign('style',$style);

        //色系
        $color = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=t.id','left')
            ->where($all)->where("v.vr_color!=''")->group('v.vr_color')->field('v.vr_color')->select();
        $this->assign('color',$color);

        //厂商
        $vendor = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=t.id','left')
            ->where($all)->where("v.vr_vendor!=''")->group('v.vr_vendor')->field('v.vr_vendor')->select();
        $this->assign('vendor',$vendor);

        //设计师
        $designer = M("all_model_vr")
            ->alias('v')
            ->join('t_all_vr_model_company c on v.vr_company_id=c.company_id')
            ->join('t_all_vr_model_type t on v.vr_typeid=t.id','left')
            ->join('t_all_vr_model_types ts on v.vr_typesid=t.id','left')
            ->where($all)->where("v.vr_designer!=''")->group('v.vr_designer')->field('v.vr_designer')->select();
        $this->assign('designer',$designer);

        $this->display();
    }

    /**
     *  添加
     */
    public function vrAdd()
    {
        $level = session('vr_level');
        if(I('')){
            $allVr = M("all_model_vr");
            if(!I('image')){
                $this->ajaxReturn(array('code'=>400,'info'=>'请上传图片'));
            }

            $newData["vr_folder"] = I('folder');
            $newData["vr_folderurl"] = I('folderurl');
            $newData["vr_image"] = I('image');
            $newData["vr_typeid"] =  I('typeid');
            $newData["vr_typesid"] = I('typesid/d');
            $newData["vr_space"] = I('space');
            $newData["vr_premises"] = I('premises');
            $newData["vr_style"] = I('style');
            $newData["vr_model"] = I('model');
            $newData["vr_specification"] = I('specification');
            $newData["vr_color"] =I('color');
            $newData["vr_vendor"] = I('vendor');
            $newData["vr_designer"] = I('designer');
            $newData["vr_remark"] = I('remark');
            $newData["vr_name"] = I('name');
            $newData["vr_price"] = I('price');
            if($level==1){
                $newData["vr_company_id"] = I("company_id/d");
            }else{
                $newData["vr_company_id"] = session("vr_company_id");
            }
            $newData["vr_time"] = date("Y-m-d H:i:s");
            $newData["vr_admin_id"] = session("vr_id");
            $result = $allVr ->add($newData);
            $logs = D('Logs');
            if ($result){
                $logs->action_log($result);
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else {
            $arr['admin_id'] = session("vr_id");
            $arr["admin_show"] = 1;
            $company_id = M('all_vr_model_admin')->where($arr)->getField('admin_company_id');
            $arr1['company_id'] = $company_id;
            $company_name = M('all_vr_model_company')->where($arr1)->getField('company_name_py');
            if($level==1){
                $company = M('all_vr_model_company')->field('company_id,company_name')->order('level')->select();
            }
            $this->assign('company',$company);
            $this->assign('company_name',$company_name);
            $this->assign('level',$level);
            $arr1 = M('all_vr_model_type')->field('id,name')->select();
            $this->assign('arr1',$arr1);
            $arr2 = M('all_vr_model_types')->field('id,name')->select();
            $this->assign('arr2',$arr2);
            $this->display();
        }
    }

    /**
     *  编辑页面
     */
    public function vrEdit ()
    {

        $level = session('vr_level');
        $u = 'D:\baleijia\wamp\www';
        //全景ID
        $allVr = M("all_model_vr");
        if (I('post.')) {
            $level = session('vr_level');
//            if($level==1){
//                $this->ajaxReturn(array('code'=>400,'info'=>'次级权限公司才能添加'));
//            }

            if(!I('image')){
                $this->ajaxReturn(array('code'=>400,'info'=>'请上传图片'));
            }
            $data['vr_id'] = I('id/d');
            $folder = I('folder');
            $folderurl = I('folderurl');
            $image = I('image');
            $old = $allVr ->where($data)->Field('vr_folder,vr_folderurl,vr_image')->find();
            if($old['vr_folderurl']!=$folderurl&&!empty($old['vr_folder'])){
                $rl = strstr($old['vr_folderurl'],__ROOT__);
                $url = $u.$rl;
                unlink($url);
            }
            if($old['vr_image']!=$image&&!empty($old['vr_image'])){
                $al = strstr($old['vr_image'],__ROOT__);
                $ual = $u.$al;
                unlink($ual);
            }
            if($level==1){
                $newData["vr_company_id"] = I("company_id/d");
            }else{
                $newData["vr_company_id"] = session("vr_company_id");
            }
            $newData["vr_folder"] = $folder;
            $newData["vr_folderurl"] = $folderurl;
            $newData["vr_image"] = $image;
            $newData["vr_typeid"] =  I('typeid');
            $newData["vr_typesid"] = I('typesid/d');
            $newData["vr_space"] = I('space');
            $newData["vr_premises"] = I('premises');
            $newData["vr_style"] = I('style');
            $newData["vr_model"] = I('model');
            $newData["vr_specification"] = I('specification');
            $newData["vr_color"] =I('color');
            $newData["vr_vendor"] = I('vendor');
            $newData["vr_designer"] = I('designer');
            $newData["vr_remark"] = I('remark');
            $newData["vr_name"] = I('name');
            $newData["vr_price"] = I('price');
            $data['vr_id'] = I('id/d');
            $result = $allVr ->where($data) ->save($newData);
            $logs = D('Logs');
            if ($result!==false){
                $logs->action_log($data['vr_id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        } else {
            $arr['company_id'] = session("vr_company_id");
            $company_name = M('all_vr_model_company')->where($arr)->getField('company_name_py');
            $this->assign('company_name',$company_name);
            $data1['vr_id'] = I('id/d');
            $data = $allVr ->where($data1) ->find();
            $this ->assign("data", $data);
            if($level==1){
                $company = M('all_vr_model_company')->field('company_id,company_name')->order('level')->select();
                $this->assign('company',$company);
            }
            $this->assign('level',$level);
            $arr1 = M('all_vr_model_type')->field('id,name')->select();
            $this->assign('arr1',$arr1);
            $arr2 = M('all_vr_model_types')->field('id,name')->select();
            $this->assign('arr2',$arr2);
            $this ->display();
        }
    }

    /**
     *  删除
     */
    public function vrDelete ()
    {
        if (I('id/d')){
            $data['vr_id'] = I('id/d');
            $newData["vr_show"] = "0";
            $result = M("all_model_vr") ->where($data) ->save($newData);
            $logs = D('Logs');
            if ($result){
                $logs->action_log($data['vr_id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }

}