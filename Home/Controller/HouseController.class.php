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
class HouseController extends CommonController
{

    /************************评论管理*******************************/

    /**
     *  楼盘公告
     */
    public function showBuildNotice()
    {
        if(I('page')&&I('page')) {
            $allVr     = M("review");
            $pageCount = I('limit/d');
            $page = I('page/d');
            $sql['review_status'] =1;
            //TODO:数据分页
            $count = $allVr->where($sql)->count();// 查询满足要求的总记录数

            $firstRow = ($page-1)*$pageCount;
            $data        = $allVr->where($sql)->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->display();
        }
    }


}