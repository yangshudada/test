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
class LogController extends CommonController
{
    /*
     * 管理员列表
     */
    public function logsList()
    {
        if(I('limit/d')&&I('page/d')) {
            $allVr     = M("company_logs");
            $pageCount = I('limit/d');
            $page = I('page/d');
            if (I('name')) {
                $name = trim(I('name'));
                $sql['operator|description']  = array('like','%'.$name.'%');
            }
            $sql['status'] = 0;

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
     *  删除
     */
    public function logsDelete ()
    {
        if (I('id/d')){
            $data['id'] = I('id/d');
            $result = M("company_logs") ->where($data) ->delete();
            if ($result){
                $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
            }else {
                $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'未找到对应的数据'));
        }
    }

}