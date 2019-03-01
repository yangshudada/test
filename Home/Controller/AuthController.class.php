<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/18
 * Time: 9:58
 */
namespace Home\Controller;
use Home\Controller\CommonController;

/**
 * 老板端全景查看类
 * Class BossController
 * @package Home\Controller
 */
class AuthController extends CommonController
{
    /*
     * 管理员列表
     */
    public function adminList()
    {
        if(I('limit/d')&&I('page/d')) {
            $pageCount = I('limit/d');
            $page = I('page/d');
            if (I('name')) {
                $name = trim(I('name'));
                $sql['admin_name|admin_remark']  = array('like','%'.$name.'%');
            }
            $sql['admin_show'] = array('in','1,5');

            $orderSql = "admin_id DESC";

            //TODO:数据分页
            $count = M("company_admin")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("company_admin")->where($sql)->order($orderSql)->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => "暂无数据"));
            }
        }else {
            $this->display();
        }
    }

    /**
     *  添加管理员
     */
    public function adminAdd ()
    {
        if(I('')){
            $allVr = M("company_admin");
            $arrData["admin_show"] = array('in','1,5');
            $count = $allVr->where($arrData)->count();
            $gid = I('gid');
            $group_id = array_keys($gid);
            $new["admin_name"] =  I('name');
            $new["admin_pwd"] = md5(I('pwd'));
            $new["admin_show"] = array('in','1,5');
            $bool = $allVr->where($new)->find();
            if($bool){
                 $this->ajaxReturn(array('code' => 400, 'info' => '用户名称已存在'));
            }
            $newData["admin_name"] =  I('name');
            $newData["admin_pwd"] = md5(I('pwd'));
            $newData["admin_remark"] = I('remark');
            $newData["admin_show"] = I('show')?1:5;
            $newData["admin_create_time"] = date("Y-m-d H:i:s");
            $newData["admin_update_time"] = date("Y-m-d H:i:s");
            M()->startTrans();
            $result = $allVr ->add($newData);
            if(!$result){
                M()->rollback();
                $this->ajaxReturn(array('code'=>200,'info'=>'添加用户失败'));
            }else{
                if(!empty($group_id)) {
                    $arr = array();
                    foreach ($group_id as $value) {
                        $data['group_id'] = $value;
                        $data['uid']      = $result;
                        $arr[]            = $data;
                    }
                    $result1 = M('company_authgroup_access')->addAll($arr);
                    if ($result1) {
                        M()->commit();
                        $logs = D('Logs');
                        $logs->action_log($result);
                        $this->ajaxReturn(array('code' => 200, 'info' => '添加成功'));
                    } else {
                        M()->rollback();
                        $this->ajaxReturn(array('code' => 400, 'info' => '添加失败'));
                    }
                }else{
                    M()->commit();
                    $logs = D('Logs');
                    $logs->action_log($result);
                    $this->ajaxReturn(array('code' => 200, 'info' => '添加成功'));
                }
            }
        }else {

            $data['status'] = 1;
            $group = M('company_authgroup')->where($data)->select();
            if(!$group){
                $this->error('请先创建角色');
            }
            $this->assign('group',$group);
            $this->display();
        }
    }

    /**
     *  编辑管理员
     */
    public function adminEdit ()
    {
        $allVr = M("company_admin");
        $data['admin_id'] = I('id/d');
        if(I('post.')){
            $before['admin_pwd'] =$allVr ->where($data)->getField('admin_pwd');
            $before['admin_name'] = I('name');
            $before['admin_id'] = array('neq',I('id/d'));
            $before["admin_show"] = array('in','1,5');
            $beforebool = $allVr ->where($before)->find();
            if($beforebool){
                $this->ajaxReturn(array('code' => 400, 'info' => '用户名称已存在'));
            }
            $gid = I('gid');
            $group_id = array_keys($gid);
            $newData["admin_company_id"] =  I('company_id/d');
            $newData["admin_name"] =  I('name');
            $newData["admin_remark"] = I('remark');
            $newData["admin_show"] = I('show')?1:5;
            $newData["admin_update_time"] = date("Y-m-d H:i:s");
            M()->startTrans();
            $result = $allVr ->where($data)->save($newData);
            if($result!==false){
                $arr1['uid'] = I('id/d');
                M('company_authgroup_access')->where($arr1)->delete();
                if(!empty($group_id)) {
                    $arr = array();
                    foreach ($group_id as $value) {
                        $data['group_id'] = $value;
                        $data['uid']      = I('id/d');
                        $arr[]            = $data;
                    }
                    $result1 = M('company_authgroup_access')->addAll($arr);
                    if ($result1) {
                        M()->commit();
                        $logs = D('Logs');
                        $logs->action_log($data['admin_id']);
                        $this->ajaxReturn(array('code' => 200, 'info' => '修改成功'));
                    } else {
                        M()->rollback();
                        $this->ajaxReturn(array('code' => 400, 'info' => '修改失败'));
                    }
                }else{
                    M()->commit();
                    $logs = D('Logs');
                    $logs->action_log($data['admin_id']);
                    $this->ajaxReturn(array('code' => 200, 'info' => '修改成功'));
                }

            }else{
                M()->rollback();
                $this->ajaxReturn(array('code'=>400,'info'=>'修改用户信息失败'));
            }
        }else {
            $status['status'] = 1;
            $group = M('company_authgroup')->where($status)->select();

            $data = $allVr ->where($data) ->find();
            $this ->assign("data", $data);
            $arr['uid'] = I('id/d');
            $group_id = M('company_authgroup_access')->where($arr)->getField('group_id',true);
            $this ->assign("gid", $group_id);

            if(!$group){
                $this->error('请先创建角色');
            }
            $this->assign('group',$group);
            $this ->display();
        }
    }

    /**
     *  删除
     */
    public function adminDelete ()
    {
        if (I('id/d')){
            $data['admin_id'] = I('id/d');
            $newData["admin_show"] = 0;
            $result = M("company_admin") ->where($data) ->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['admin_id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }

    /**
     *  启用停用
     */
    public function changeshow ()
    {
        if (I('id/d')){
            $data['admin_id'] = I('id/d');
            $newData["admin_show"] = I('show')==1?5:1;
            if($newData['admin_show']==5){
                $tip = '已停用';
            }else{
                $tip = '已启用';
            }
            $result = M("company_admin") ->where($data) ->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['admin_id']);
                $this->ajaxReturn(array('code'=>200,'info'=>$tip));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }

    /***********************************************角色模块******************************************************/

    /*
 * 角色列表
 */
    public function roleList()
    {
        if(I('limit/d')&&I('page/d')) {
            $pageCount = I('limit/d');
            $page = I('page/d');
            if (I('name')) {
                $name = trim(I('name'));
                $sql['title']  = array('like','%'.$name.'%');
            }

            $sql['status'] = array('in','1,5');

            $orderSql = "id DESC";

            //TODO:数据分页
            $count = M("company_authgroup")->where($sql)->count();// 查询满足要求的总记录数

            $firstRow = ($page-1)*$pageCount;
            $data        = M("company_authgroup")->where($sql)->order($orderSql)->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => "暂无数据"));
            }
        }else {
            $this->display();
        }
    }

    /**
     *  添加角色
     */
    public function roleAdd ()
    {
        $allVr = M("company_authgroup");
        $data['status'] = array('neq',0);
        if(I('')){
            $data['title'] = I('name');
            $bool = $allVr->where($data)->find();
            if ($bool){
                $this->ajaxReturn(array('code'=>200,'info'=>'角色名称不能重复'));
            }
            $newData["title"] =  I('name');
            $newData["group_company_id"] =  I('company_id/d');
            $newData["remark"] = I('remark');
            $newData["status"] = I('show')?1:5;
            $newData["rules"] = ltrim(I('ruleid'),',');
            $newData["admin_create_time"] = date("Y-m-d H:i:s");
            $newData["admin_update_time"] = date("Y-m-d H:i:s");
            $result = $allVr ->add($newData);
            if ($result){
                $logs = D('Logs');
                $logs->action_log($result);
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else {
            $this->display();
        }
    }

    /**
     *  编辑角色
     */
    public function roleEdit ()
    {
        $allVr = M("company_authgroup");
        $data['id'] = I('id/d');
        if(I('post.')){
            $datas['status'] = array('neq',0);
            $datas['title'] = I('name');
            $datas['id'] = array('neq',$data['id']);
            $bool = $allVr->where($datas)->find();
            if ($bool){
                $this->ajaxReturn(array('code'=>200,'info'=>'角色名称不能重复'));
            }
            $newData["title"] =  I('name');
            $newData["remark"] = I('remark');
            $newData["status"] = I('show')?1:5;
            $newData["rules"] = ltrim(I('ruleid'),',');
            $newData["admin_update_time"] = date("Y-m-d H:i:s");
            $result = $allVr ->where($data)->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        }else {
            $data = $allVr ->where($data) ->find();
            $this ->assign("data", $data);
            $this ->display();
        }
    }

    /**
     *  删除
     */
    public function roleDelete ()
    {
        if (I('id/d')){
            $data['id'] = I('id/d');
            $newData["status"] = 0;
            $result = M("company_authgroup") ->where($data) ->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }

    /**
     *  启用停用
     */
    public function roleshow ()
    {
        if (I('id/d')){
            $data['id'] = I('id/d');
            $newData["status"] = I('show')==1?5:1;
            if($newData['status']==5){
                $tip = '已停用';
            }else{
                $tip = '已启用';
            }
            $result = M("company_authgroup") ->where($data) ->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['id']);
                $this->ajaxReturn(array('code'=>200,'info'=>$tip));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }


    /***********************************************权限模块******************************************************/

    /*
 * 权限列表
 */
    public function ruleList()
    {
        if(I('limit/d')&&I('page/d')) {
            $allVr     = M("company_authrule");
            $pageCount = I('limit/d');
            $page = I('page/d');
            if (I('name')) {
                $name = trim(I('name'));
                $sql['title']  = array('like','%'.$name.'%');
            }
            $sql['status'] = array('in','1,5');

            $orderSql = "id DESC";
            //TODO:数据分页
            $count = $allVr->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = $allVr->where($sql)->order($orderSql)->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => "暂无数据"));
            }
        }else {
            $this->display();
        }
    }

    /**
     *  添加权限
     */
    public function ruleAdd ()
    {
        $allVr = M("company_authrule");
        $newData["status"] = 1;
        if(I('')){
            $newData["pid"] = I('pid/d');
            $newData["name"] =  strtolower(trim(I('name')));
            $bool = $allVr -> where($newData)->find();
            if ($bool){
                $this->ajaxReturn(array('code'=>400,'info'=>'规则标识不可重复'));
            }
            $newData["title"] = trim(I('title'));
            $newData["create_time"] = date("Y-m-d H:i:s");
            $result = $allVr ->add($newData);
            if ($result){
                $logs = D('Logs');
                $logs->action_log($result);
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else {
            $data = $allVr ->where($newData)->field('id,title')->select();
            $this->assign('data',$data);
            $this->display();
        }
    }

    /**
     *  编辑权限
     */
    public function ruleEdit ()
    {
        $allVr = M("company_authrule");
        $data['id'] = I('id/d');
        $newData["status"] = 1;
        if(I('post.')){
            $newData["pid"] = I('pid/d');
            $newData["name"] =  strtolower(trim(I('name')));
            $newData["id"] = array('neq',$data['id']);
            $bool = $allVr -> where($newData)->find();
            if ($bool){
                $this->ajaxReturn(array('code'=>400,'info'=>'规则标识不可重复'));
            }
            $newData["title"] = trim(I('title'));
            $result = $allVr ->where($data)->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        }else {
            $data = $allVr ->where($data) ->find();
            $this ->assign("data", $data);
            $arr = $allVr ->where($newData)->field('id,title')->select();
            $this->assign('arr',$arr);
            $this ->display();
        }
    }

    /**
     *  删除
     */
    public function ruleDelete ()
    {
        if (I('id/d')){
            $data['id'] = I('id/d');
            $newData["status"] = 0;
            $result = M("company_authrule") ->where($data) ->save($newData);
            if ($result!==false){
                $logs = D('Logs');
                $logs->action_log($data['id']);
                $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }
}