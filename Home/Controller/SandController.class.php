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
class SandController extends CommonController
{

    /**
     *  沙盘列表
     */
    public function houseList ()
    {

        if(I('limit/d')&&I('page/d')) {
            if(I("premises")){
                $sql['house_premises'] = I("premises");
            }
            if(I("house")){
                $sql['house_house'] = I("house");
            }
            if(I("unit")){
                $sql['house_unit'] = I("unit");
            }
            if(I("number")){
                $sql['house_number'] = I("number");
            }
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("sand_house2")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("sand_house2")->where($sql)->order('house_id desc')->limit($firstRow,$pageCount)->select();
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
     * 沙盘添加
     */
    public function houseAdd ()
    {
        if(I('post.')){
            if(I("premises")&&I("house")&&I("unit")&&I("number")){
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'必填数据不完整'));
            }
            $data["house_premises"]      = I("premises");
            $data["house_house"]      = I("house");
            $data["house_unit"]      = I("unit");
            $data["house_number"]      = I("number");
            $data["house_time"]      = date('Y-m-d H:i:s');
            $show = I('checkbox1');
            $data["house_show"]      = implode(',',$show);
            //token
            $token = I("premises").'+'.I("house").'+'.I("unit").'+123';
            $data["house_url"] = 'https://'.$_SERVER['SERVER_NAME'].'/baleijia/admin.php/Home/Electron/house_list2?premises='.I("premises").'&house='.I("house").'&unit='.I("unit").'&token='.md5($token);
            $result = M("sand_house2")->add($data);
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
    * 沙盘编辑
    */
    public function houseEdit ()
    {
        $arrs['house_id'] = I('id/d');
        if(I('post.')){
            if(I("premises")&&I("house")&&I("unit")&&I("number")){
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'必填数据不完整'));
            }
            $data["house_premises"]      = I("premises");
            $data["house_house"]      = I("house");
            $data["house_unit"]      = I("unit");
            $data["house_number"]      = I("number");
            $show = I('checkbox1');
            $data["house_show"]      = implode(',',$show);
            $token = I("premises").'+'.I("house").'+'.I("unit").'+123';
            $data["house_url"] = 'https://'.$_SERVER['SERVER_NAME'].'/baleijia/admin.php/Home/Electron/house_list2?premises='.I("premises").'&house='.I("house").'&unit='.I("unit").'&token='.md5($token);
            $result = M("sand_house2")->where($arrs)->save($data);
            if ($result!==false) {
                $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
            }
        }else
        {
            $data = M('sand_house2')->where($arrs)->find();
            $this->assign('data',$data);
            $show = explode(',',$data["house_show"]);
            $this->assign('show',$show);
            $this ->display();
        }
    }

    /*
    * 沙盘删除
    */
    public function houseDel ()
    {

        $arr['house_id'] = I('id');
        $result = M("sand_house2")->where($arr)->find();
        if ($result) {
            $bool = M("sand_house2")->where($arr)->delete();
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
   * 沙盘批量删除
   */
    public function houseAllDel ()
    {

        $all = I();
        M()->startTrans();
        foreach($all['all'] as $val){
            $arr['house_id']  =$val['house_id'];
            $bool = M("sand_house2")->where($arr)->find();
            if($bool){
                $result = M("sand_house2")->where($arr)->delete();
                if($result){
                }else{
                    M()->rollback();
                    $this->ajaxReturn(array('code'=>400,'info'=>$val['house_id'].'删除失败'));
                }
            }else{
                M()->rollback();
                $this->ajaxReturn(array('code'=>400,'info'=>$val['house_id'].'不存在'));
            }
        }
        M()->commit();
        $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
    }

    /*
* 沙盘添加/编辑
*/
    public function editVr ()
    {
        $arrs['vr_house_id'] = I('id/d');
        if(I('post.')){
            $vr_type = I('action');
            if($vr_type=='毛坯'||$vr_type=='量房'||$vr_type=='隐蔽'||$vr_type=='设计'||$vr_type=='对比'||$vr_type=='追溯'||$vr_type=='方案'||$vr_type=='介绍'||$vr_type=='模型'||$vr_type=='我家的景色'){
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'类型不存在'));
            }
            $arrs['vr_type'] = $vr_type;
            $data['vr_url'] = I('url');
            $bool = M("sand_vr2")->where($arrs)->find();
            if($bool){
                $result = M("sand_vr2")->where($arrs)->save($data);
                if ($result!==false) {
                    $this->ajaxReturn(array('code'=>200,'info'=>'修改成功'));
                } else {
                    $this->ajaxReturn(array('code'=>400,'info'=>'修改失败'));
                }
            }else{
                $data['vr_type'] = $vr_type;
                $data['vr_house_id'] = I('id/d');
                $data["vr_time"]      = date('Y-m-d H:i:s');
                $result = M("sand_vr2")->add($data);
                if ($result) {
                    $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
                } else {
                    $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
                }
            }
        }else
        {
            $vr_type = I('action');
            if($vr_type=='毛坯'||$vr_type=='量房'||$vr_type=='隐蔽'||$vr_type=='设计'||$vr_type=='对比'||$vr_type=='追溯'||$vr_type=='方案'||$vr_type=='介绍'||$vr_type=='模型'||$vr_type=='我家的景色'){
            }else{
                $this->error("类型不存在");
            }
            $arrs['vr_type'] = $vr_type;
            $data = M('sand_vr2')->where($arrs)->find();
            if($data){
                $this->assign('data',$data);
            }
            $this->assign('action',$vr_type);
            $this->assign('id',I('id/d'));
            $this ->display();
        }
    }


    /*
  * 沙盘详情
  */
    public function showHome ()
    {
        $this->assign('id',I('id/d'));
        $this->display();
    }

    /*
 * 沙盘批量添加
 */
    public function houseAddExcel ()
    {
        if(I('post.')){
            include_once ("/baleijia/wamp/www/baleijia/Admin/Public/PHPExcel/PHPExcel.php");
            $file_name = I('excel');
            if($file_name){
                $file_name = str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'],"D:/baleijia/wamp/www",$file_name);
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                if (!$PHPReader->canRead($file_name)) {
                    $PHPReader = new \PHPExcel_Reader_Excel5();
                    if (!$PHPReader->canRead($file_name)) {
                        $this->ajaxReturn(array('code'=>400,'info'=>'解析失败'));
                    }
                }
                $objPHPExcel = $PHPReader->load($file_name);

                $sheet =$objPHPExcel->getSheet(0);

                $highestRow = $sheet->getHighestRow();//取得总行数

                $highestColumn =$sheet->getHighestColumn(); //取得总列数

                M()->startTrans();

                for ($i = 2; $i <= $highestRow; $i++) {
    //看这里看这里,前面小写的a是表中的字段名，后面的大写A是excel中位置

                    $data['house_premises'] = $objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();

                    $data['house_house'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();

                    $data['house_unit'] = $objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();

                    $data['house_number'] = $objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();

                    if($data['house_premises']) {

                        $bool = M('sand_house2')->where($data)->find();

                        $token = $data['house_premises'] . '+' . $data['house_house'] . '+' . $data['house_unit'] . '+123';

                        if ($bool) {
                            $arr['house_show'] = $objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
                            $result = M('sand_house2')->where($data)->save($arr);
                            if ($result === false) {
                                M()->rollback();
                                $this->ajaxReturn(array('code' => 400, 'info' => '第' . $i . '行修改失败'));
                            }
                        } else {
                            $data['house_show'] = $objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
                            $data["house_time"] = date('Y-m-d H:i:s');
                            $data["house_url"] = 'https://' . $_SERVER['SERVER_NAME'].'/baleijia/admin.php/Home/Electron/house_list2?premises=' . $data['house_premises'] . '&house=' . $data['house_house'] . '&unit=' . $data['house_unit'] . '&token=' . md5($token);
                            $result = M("sand_house2")->add($data);
                            if ($result) {
                            } else {
                                M()->rollback();
                                $this->ajaxReturn(array('code' => 400, 'info' => '第' . $i . '行添加失败'));
                            }
                        }
                    }
                }
                M()->commit();
                unlink($file_name);
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));

            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'未获取到excel'));
            }

        }else
        {
            $this ->display();
        }
    }

    /*
* 详情批量添加
*/
    public function vrAddExcel ()
    {
        if(I('post.')){
            include_once ("/baleijia/wamp/www/baleijia/Admin/Public/PHPExcel/PHPExcel.php");
            $file_name = I('excel');
            if($file_name){
                $file_name = str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'],"D:/baleijia/wamp/www",$file_name);
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                if (!$PHPReader->canRead($file_name)) {
                    $PHPReader = new \PHPExcel_Reader_Excel5();
                    if (!$PHPReader->canRead($file_name)) {
                        $this->ajaxReturn(array('code'=>400,'info'=>'解析失败'));
                    }
                }
                $objPHPExcel = $PHPReader->load($file_name);

                $sheet =$objPHPExcel->getSheet(0);

                $highestRow = $sheet->getHighestRow();//取得总行数

                $highestColumn =$sheet->getHighestColumn(); //取得总列数

                M()->startTrans();

                for ($i = 2; $i <= $highestRow; $i++) {
                    //看这里看这里,前面小写的a是表中的字段名，后面的大写A是excel中位置

                    $data['vr_type'] = $objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();

                    $data['vr_house_id'] = $objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();

                    if($data['vr_type']&&$data['vr_house_id']) {

                        $bool = M('sand_vr2')->where($data)->find();

                        if ($bool) {
                            $arr['vr_url'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
                            $result = M('sand_vr2')->where($data)->save($arr);
                            if ($result === false) {
                                M()->rollback();
                                $this->ajaxReturn(array('code' => 400, 'info' => '第' . $i . '行修改失败'));
                            }
                        } else {
                            $data['vr_url'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
                            $data["vr_time"] = date('Y-m-d H:i:s');
                            $result = M("sand_vr2")->add($data);
                            if ($result) {
                            } else {
                                M()->rollback();
                                $this->ajaxReturn(array('code' => 400, 'info' => '第' . $i . '行添加失败'));
                            }
                        }
                    }
                }

                M()->commit();
                unlink($file_name);
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));

            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'未获取到excel'));
            }

        }else
        {
            $this ->display();
        }
    }

}