<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/22
 * Time: 14:11
 */
namespace Home\Controller;
use Think\Controller;
use Think\Auth;


/**
 * 五位一体8月23号测试数据
 * Class TestController
 * @package Home\Controller
 */
class TestController extends CommonController
{
    function index (){  }

    //TODO:互联网大会准备之数据交互测试
    /**
     * 接受数据并存储
     */

    public function up_msg()
    {
        // header("Access-Control-Allow-Origin");
        header('Access-Control-Allow-Origin:*');
        if (!empty($_POST)) {
            $name = I("name");
            if (!empty($name))
            {
                // 推送的url地址，使用自己的服务器地址
                $push_api_url = "http://5d.8lei.cn:2121/.";
                header("Content-type: application/json");

                $Inter = new InternetController();
                //                $design = $this->get_design($name, $push_api_url);
                //                $operation = $this->get_operation($name, $push_api_url);
                //                $construct = $this->get_construct($name, $push_api_url);
                //                $cost = $this->get_cost($name, $push_api_url);
                //                $purchase = $this->get_purchase($name, $push_api_url);

                $design     = $Inter ->get_design($name, $push_api_url);
                $purchase   = $Inter ->get_purchase($name, $push_api_url);
                $cost       = $Inter ->get_cost($name, $push_api_url);
                $construct  = $Inter ->get_construct($name, $push_api_url);
                $operation  = $Inter ->get_operation($name, $push_api_url);

                 
                $newData["internet_name"] = $name;
                $newData["internet_time"] = date("Y-m-d H:i:s");
                $data = M("internet")->add($newData);
                if ($design || $purchase || $cost || $construct || $operation) {
                    $this->ajaxReturn(array("code" => "200", "design" => $design, "purchase" => $purchase, "cost" => $cost, "construct" => $construct, "operation" => $operation), "JSON");
                } else {
                    $this->ajaxReturn(array("code" => "500", "result" => array($design, $purchase, $cost, $construct, $operation)), "JSON");
                }
            }else
            {
                $this -> ajaxReturn(array("code" => "500", "reason" => "参数为空"), "JSON");
            }
        }else {
            $this->display();
        }
    }

    /**
     *  1、设计
     */
    public function design ()
    {
        $this->display();
    }
    public function get_design ($key,$push_api_url)
    {
            $public_id = M("internet_public")->where("public_key='$key'")->getField('public_id');
        $result = M("internet_design")->where("design_key=$public_id")->select();
            if ($result) {
                //判断是否重复请求数据，手机端刷新，PC不刷新
                $internetData = M("internet") ->order("internet_id DESC") -> select();
                // var_dump($internetData);
                if ($key != $internetData[0]["internet_name"]) {
                    $design_data = array(
                        "type" => "publish",
                        "content" => json_encode($result[0]),
                        "to" => "design", // 指明给谁推送，为空表示向所有在线用户推送
                    );
                    //设计
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $push_api_url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $design_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                    $return = curl_exec($ch);
                    curl_close($ch);
                }
                return $result[0];
            }
    }

    /**
     *  2、采购
     */
    public function purchase ()
    {
        $this ->display();
    }
    public function get_purchase ($key, $push_api_url)
    {
        $public_id = M("internet_public")->where("public_key='$key'")->getField('public_id');
        $result = M("internet_purchase") -> where("purchase_key= $public_id") -> select();
        if ($result) {
            $spaceArr = array();
            for ($i = 0; $i < count($result); $i ++ )
            {
                $spaceData = $result[$i]["purchase_space"];
                if (!in_array($spaceData,$spaceArr))
                {
                    //对最底层数组重置
                    $otherArr = array();
                    $spaceArr[] = $spaceData;
                    for ($j = 0; $j < count($result); $j ++){
                        if ($spaceData == $result[$j]["purchase_space"]){
                            $otherArr[] = $result[$j];
                        }
                    }
                    $otherArrs[] = $otherArr;
                }
            }
            $allArr = array($spaceArr,$otherArrs);
            $internetData = M("internet") ->order("internet_id DESC") -> select();
            if ($key != $internetData[0]["internet_name"]) {
                $purchase_data = array(
                    "type" => "publish",
                    "content" => json_encode($allArr),
                    "to" => "purchase",
                );
                //采购
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $push_api_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $purchase_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                $return = curl_exec($ch);
                curl_close($ch);
            }
            return $allArr;
        }
    }
    public function up_purchase ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_id');
        if(!$key){
            $this->error('信息不存在');
        }

        if (!empty($_POST))
        {
            $name = I("name");
            $size = I("size");
            $space = I("space");
            $brand = I("brand");
            $material = I("material");
            $newData["purchase_image"] = I('image');
            $newData["purchase_image_jpg"] = I('image_jpg');
            $newData["purchase_name"] = $name;
            $newData["purchase_key"] = $key;
            $newData["purchase_space"] = $space;
            $newData["purchase_size"] = $size;
            $newData["purchase_brand"] = $brand;
            $newData["purchase_material"] = $material;
            $data = M("internet_purchase") ->add($newData);
            if ($data)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else
            {
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else
        {
            $this->assign('id',$id);
            $this -> display();
        }
    }


    //造价详细信息
    public function purchase_detail ()
    {
        if(empty(I('post.'))) {
            $id   = I("id/d");
            $data = M("internet_purchase")->where("purchase_id='$id'")->find();
            $this->assign("info", $data);
            $this->assign("id", $id);
            $this->display();
        }else{
            $name = I("name");
            $size = I("size");
            $space = I("space");
            $brand = I("brand");
            $material = I("material");
            $id = I("id");
            $newData["purchase_image"] = I('image');
            $newData["purchase_image_jpg"] = I('image_jpg');
            $newData["purchase_name"] = $name;
            $newData["purchase_space"] = $space;
            $newData["purchase_size"] = $size;
            $newData["purchase_brand"] = $brand;
            $newData["purchase_material"] = $material;
            $data = M("internet_purchase") ->where("purchase_id='$id'")->save($newData);
            if ($data!==false)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'更新成功'));
            }else
            {
                $this->ajaxReturn(array('code'=>400,'info'=>'更新失败'));
            }
        }
    }
	
	    //采购删除
    public function purchase_delete ()
    {
        $id = I('id/d');
        if($id){
            $data['purchase_id'] = $id;
            $arr = M("internet_purchase")->where($data)->find();
            if($arr) {
                $bool = M("internet_purchase")->where($data)->delete();
                if ($bool) {
                    $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
                } else {
                    $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
                }
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'数据不存在'));
            }
        }else{
            $this->ajaxReturn(array('code'=>400,'info'=>'非法操作'));
        }
    }

	//更新采购
//	public function update_purchase ()
//    {
//        if (!empty($_POST))
//        {
//            $name = $_POST["name"];
//            $size = $_POST["size"];
//            $space = $_POST["space"];
//            $key = $_POST["key"];
//            $brand = $_POST["brand"];
//            $material = $_POST["material"];
//            $id = $_POST["id"];
//            if (empty(I('image')))
//            {//上传文件存在
//                $upload = new \Think\Upload();// 实例化上传类
//                $upload->maxSize = 31457280;// 设置附件上传大小
//                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
//                // 设置附件上传类型
//                $upload->rootPath = './Inter/image/purchase/'; // 设置附件上传根目录
//                $upload->savePath = ''; // 设置附件上传（子）目录
//                $upload->saveName = array('date', 'His' . rand(100, 999));
//
//                // 上传文件
//                $info = $upload->upload();
//
//                if (!$info) {//上传错误提示错误信息
//                    $this->error($upload->getError());
//                } else {// 上传成功
//                    $image = "http://5d.8lei.cn/baleijia/Inter/image/purchase/" . $info["image"]["savepath"] . $info["image"]["savename"];
//                    $newData["purchase_image"] = $image;
//                }
//            }else{
//                $newData["purchase_image"] = I('image');
//            }
//            $newData["purchase_name"] = $name;
//            $newData["purchase_key"] = $key;
//            $newData["purchase_space"] = $space;
//            $newData["purchase_size"] = $size;
//            $newData["purchase_brand"] = $brand;
//            $newData["purchase_material"] = $material;
//            $data = M("internet_purchase") ->where("purchase_id='$id'")->save($newData);
//            if ($data!==false)
//            {
//                $this -> success("更新成功！",'purchase_home');
//            }else
//            {
//                $this -> error("更新失败！");
//            }
//        }else
//        {
//            $this -> display();
//        }
//    }
    public function purchase_home ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_key');
        if(!$key){
            echo "信息不存在";exit;
        }
        if(I('limit/d')&&I('page/d')) {
            if (I("space"))
            {
                $space = I("space");
                $sql['purchase_space'] = $space;
            }
            $sql['purchase_key'] = $id;
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_purchase")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_purchase")->where($sql)->order("purchase_id DESC")->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->assign('id',$id);
            $this->display();
        }
    }

    /**
     *  3、造价
     */
    public function cost ()
    {
        $this ->display();
    }
    public function get_cost ($key, $push_api_url)
    {
        $result = array();
        $key1 = $key;
        if ($key =="杭州公馆2F" || $key == "杭州公馆B1")
        {
            $key = "杭州公馆1F";
        }
        $public_id = M("internet_public")->where("public_key='$key'")->getField('public_id');
        $costData = M("internet_cost") -> where("cost_key=$public_id") -> select();
        if ($costData) {
            for ($i = 0; $i < count($costData); $i++) {
                $style_a = $costData[$i]["cost_style_a"];
                $style_b = $costData[$i]["cost_style_b"];
                $price_a = $costData[$i]["cost_price_a"];
                $price_b = $costData[$i]["cost_price_b"];

                $result[$style_a]["price_a"] = $price_a;
                $result[$style_a][$style_b]["price_b"] = $price_b;

                $cost_id = $costData[$i]["cost_id"];
                $img = $costData[$i]["cost_image"];
                $space = $costData[$i]["cost_space"];
                $result[$style_a][$style_b]["result"]["image"][] = $img;
                $result[$style_a][$style_b]["result"]["space"][] = $space;

                $detailData = M("internet_cost_detail")->where("cost_id='$cost_id'")->select();

                $result[$style_a][$style_b]["result"]["detail"][] = $detailData;
            }
            $result["name"] = $costData[0]["cost_name"];
            $result["key"] = $key1;

            if ($result) {
                $result = array("code" => "200", "result" => $result);
            } else {
                $result = array("code" => "500", "result" => "error");
            }
            $internetData = M("internet") ->order("internet_id DESC") -> select();
            if ($key != $internetData[0]["internet_name"]) {
                $cost_data = array(
                    "type" => "publish",
                    "content" => json_encode($result),
                    "to" => "cost",
                );
                //造价
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $push_api_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $cost_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                $return = curl_exec($ch);
                curl_close($ch);
            }
            return $result;
        }
    }
    //造价数据
    public function cost_home ()
    {
        $id = I("id/d");
        $this->assign('id',$id);
        $this -> display();
    }
    public function up_cost ()
    {
        $id = I("id/d");
        if (I("style_a")) {
            $sql['cost_style_a'] = I("style_a");
        }
        if (I("style_b")) {
            $sql['cost_style_b'] = I("style_b");
        }
        if (I("space")) {
            $sql['cost_space'] = I("space");
        }
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_key');
        if(!$key){
            echo "信息不存在";exit;
        }

        if(I('limit/d')&&I('page/d')) {
            $sql['cost_key'] = $id;
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_cost")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_cost")->where($sql)->limit($firstRow,$pageCount)->select();
            for ($a = 0; $a < count($data); $a ++){
                $detail_id = $data[$a]["cost_id"];
                $detailData = M("internet_cost_detail") ->where("cost_id='$detail_id'") -> select();
                $data[$a]["detail_num"] = count($detailData);
            }
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->assign("style_a", I("style_a"));
            $this->assign("style_b", I("style_b"));
            $this->assign("space",I("space"));
            $this->assign('id',$id);
            $this->display();
        }
    }
    //添加造价数据
    public function up_cost_add ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_id');
        if(!$key){
            $this->error('信息不存在');
        }
        if (!empty(I("post.")))
        {
            $style_a = I("style_a");
            $style_b = I("style_b");
            $price_a = I("price_a");
            $price_b = I("price_b");
            $space = I("space");
            $newData["cost_style_a"] = $style_a;
            $newData["cost_style_b"] = $style_b;
            $newData["cost_price_a"] = $price_a;
            $newData["cost_price_b"] = $price_b;
            $newData["cost_space"] = $space;
            $newData["cost_key"] = $key;
            $newData["cost_image"] = I("image");
            $newData["cost_image_jpg"] = I("image_jpg");

            $result = M("internet_cost") ->add($newData);
            if ($result)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }

        }else
        {
            $this->assign('id',$id);
            $this->assign('style_a',I("style_a"));
            $this->assign('style_b',I("style_b"));
            $this -> display();
        }
    }
    //造价详细信息
    public function cost_detail ()
    {
        $id = I("id/d");
        $space = I("space");
        if(I('limit/d')&&I('page/d')) {
            $sql['cost_id'] = $id;
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_cost_detail")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_cost_detail")->where($sql)->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this -> assign("space", $space);
            $this -> assign("id", $id);
            $this -> display();
        }
    }
    //上传造价详细信息
    public function up_cost_detail ()
    {
        if (I('post.'))
        {
            $id = I("id");
            $name = I("name");
            $number = I("number");
            $brand = I("brand");
            $remark = I("remark");

            $newData["detail_name"] = $name;
            $newData["detail_number"] = $number;
            $newData["detail_brand"] = $brand;
            $newData["detail_remark"] = $remark;
            $newData["cost_id"] = $id;

            $data = M("internet_cost_detail") -> add($newData);
            if ($data)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }

        }else {
            $this->assign('id',I('id'));
            $this -> display();
        }
    }

    /**
     *  4、施工
     */
    public function construct ()
    {
        $this ->display();
    }
    public function get_construct ($key, $push_api_url)
    {
        $data = M("internet_construct") -> where("construct_key='$key'") -> select();
        if ($data) {
//            var_dump($data);
            for ($i = 0; $i < count($data); $i++) {
                $name = $data[$i]["construct_name"];
                switch ($name) {
                    case "node1":
                        $result["video1"] = $data[$i]["construct_url"];
                        break;
                    case "node2":
                        $result["video2"] = $data[$i]["construct_url"];
                        break;
                    case "node3":
                        $result["video3"] = $data[$i]["construct_url"];
                        break;
                    case "node4":
                        $result["video4"] = $data[$i]["construct_url"];
                        break;
                    case "node5":
                        $result["video5"] = $data[$i]["construct_url"];
                        break;
                    default:

                        break;
                }
            }
            $result["key"] = $data[0]["construct_key"];
            $internetData = M("internet") ->order("internet_id DESC") -> select();
            if ($key != $internetData[0]["internet_name"]) {
                $construct_data = array(
                    "type" => "publish",
                    "content" => json_encode($result),
                    "to" => "construct",
                );
                //施工
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $push_api_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $construct_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                $return = curl_exec($ch);
                curl_close($ch);
            }
            return $result;
        }
    }

    /**
     *  5、运维
     */
    public function operation ()
    {
        $this ->display();
    }
    public function get_operation ($key, $push_api_url)
    {
        $result = M("internet_operation") -> where("operation_key='$key'") -> select();
        if ($result) {
            $internetData = M("internet") ->order("internet_id DESC") -> select();
            if ($key != $internetData[0]["internet_name"]) {
                $operation_data = array(
                    "type" => "publish",
                    "content" => json_encode($result[0]),
                    "to" => "operation",
                );

                //运维
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $push_api_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $operation_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                $return = curl_exec($ch);
                curl_close($ch);
            }
            return $result[0];
        }else
        {
            return null;
        }
    }

    /**
     *  五位一体后台页面
     */
    public function home ()
    {
        $this -> display();
    }
    /**
     *  五位一体公共部分
     */
    public function publicFive()
    {
        if(I('limit/d')&&I('page/d')) {
            if (I("key"))
            {
                $key = I("key");
                $sql['public_key|public_style'] =array('like','%'.$key.'%');
            }
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_public")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_public")->where($sql)->order('public_id desc')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->display();
        }
    }

    /**
     *  五位一体添加
     */
    public function publicAdd ()
    {
        if (I('post.'))
        {
            $newData["public_key"] = I('key');
            $newData["public_style"] = I('style');
            $newData["public_build"] = I('build');
            $newData["public_room"] = I('room');
            $newData["public_time"] = date('Y-m-d H:i:s');
            $newData["public_image"] = I('image');
            $newData["public_image_jpg"] = I('image_jpg');
            $result = M("internet_public") ->add($newData);
            if ($result)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }

        }else{
            $this->display();
        }
    }

    /**
     *  五位一体公共部分
     */
    public function publicEdit()
    {
        $id=I("id/d");
        $arr['public_id'] = $id;

        if(I('post.')){
//            echo __ROOT__;EXIT;
//            $logs = D('Image');
//            $infos = "添加项目经理".$name;
//            $logs->action_log($infos);
            $newData["public_key"] = I('key');
            $newData["public_style"] = I('style');
            $newData["public_build"] = I('build');
            $newData["public_room"] = I('room');
            $newData["public_image"]=I('image');
            $newData["public_image_jpg"] = I('image_jpg');
            $result = M("internet_public")->where($arr)->save($newData);
            if ($result!==false) {
                $this->ajaxReturn(array('code'=>200,'info'=>'编辑成功'));
            } else {
                $this->ajaxReturn(array('code'=>400,'info'=>'编辑失败'));
            }
        }else{
            $this->assign('id',$id);
            $data =M("internet_public") -> where($arr) ->find();// 查询满足要求的总记录数
            $this -> assign("info", $data);
            $this -> display();
        }
    }

    /**
     *  五位一体后台页面
     */
    public function showHome ()
    {
        $id = I('id/d');
        $this ->assign("id",$id);
        $this ->display();
    }


    /**
     *  设计
     */
    public function designHome ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_key');
        if(!$key){
            echo "信息不存在";exit;
        }
        if(I('limit/d')&&I('page/d')) {
            $sql['design_key'] = $id;
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_design")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_design")->where($sql)->order('design_id desc')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->assign('id',$id);
            $this->display();
        }
    }
    /**
     *  添加设计数据
     */
    public function designAdd ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_id');
        if (!$key)
        {
            $this -> error("信息不存在");
        }
        if (I('post.'))
        {
            $name = I("name");
            $size = I("size");
            $real_url = I("real_url");
            $newData["design_real_url"] = $real_url;
            $newData["design_name"] = $name;
            $newData["design_url"] = $size;
            $newData["design_key"] = $key;
//
//            if (!empty($_FILES["image"]["name"]))
//            {
//                //上传文件存在
//                $upload = new \Think\Upload();// 实例化上传类
//                $upload->maxSize = 31457280;// 设置附件上传大小
//                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
//                // 设置附件上传类型
//                $upload->rootPath = './Inter/image/design/'; // 设置附件上传根目录
//                $upload->savePath = ''; // 设置附件上传（子）目录
//                $upload->saveName = array('date', 'His' . rand(100, 999));
//
//                // 上传文件
//                $info = $upload->upload();
//
//                if (!$info) {//上传错误提示错误信息
//                    $this->error($upload->getError());
//                } else {// 上传成功
//                    $image = "/baleijia/wamp/www/baleijia/Inter/image/design/" . $info["image"]["savepath"] . $info["image"]["savename"];
////                    图片压缩
//                    $imgInfo = filesize($image);
//                    if($imgInfo/1024 > 200) {
//                        require_once '/baleijia/wamp/www/baleijia/Public/reduce/compressImg.php';
//
//                        $name = $info["image"]["savename"];
//                        $oldImage = new \Image($image);
//                        $oldImage->percent = 0.75;
//                        $oldImage->openImage();
//                        $oldImage->thumpImage();
//                        $basePath = "/baleijia/wamp/www/baleijia/Inter/image/design/" . $info["image"]["savepath"];
////                        var_dump($basePath, $name);
//                        $imgUrl = $oldImage->saveImage($basePath, $name);
//                    }
            $newData["design_image"] = I('image');
            $newData["design_image_jpg"] = I('image_jpg');
            $newData["design_real_image"] = I('image2');
            $newData["design_real_image_jpg"] = I('image_jpg2');
            $result = M("internet_design") ->add($newData);
            if ($result)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
//                }
        }else{
            $this->assign('id',$id);
            $this->display();
        }
    }
	
	 /**
     *  编辑设计数据
     */
    public function editDesign ()
    {
        $id = I("id/d");
        $data['design_id']=$id;
        if (I("post."))
        {
            $name = I("name");
            $size = I("size");
            $newData["design_name"] = $name;
            $newData["design_url"] = $size;
            $newData["design_image"] = I('image');
            $newData["design_image_jpg"] = I('image_jpg');
            $newData["design_real_image"] = I('image2');
            $real_url = I("real_url");
            $newData["design_real_url"] = $real_url;
            $newData["design_real_image_jpg"] = I('image_jpg2');
            $result = M("internet_design") ->where($data)->save($newData);
            if ($result!==false)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'编辑成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'编辑失败'));
            }
        }else{
            $info = M("internet_design") ->where($data)->find();
			$this->assign('data',$info);
            $this->display();
        }
    }

	

    /**
     *  施工
     */
    public function constructHome ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_key');
        if(!$key){
            echo "信息不存在";exit;
        }
        if(I('limit/d')&&I('page/d')) {
            $sql['construct_key'] = $id;
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_construct")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_construct")->where($sql)->order('construct_id desc')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->assign('id',$id);
            $this->display();
        }
    }
    public function constructAdd ()
    {
        $id = I("id/d");
        $this->assign('id',$id);
        if (I('post.'))
        {
            $name = I("name");
            $size = I("url");
            $arr['public_id'] = $id;
            $arr['public_show'] = 1;
            $key = M('internet_public')->where($arr)->getField('public_id');
            if(!$key){
                $this->error("信息不存在");
            }

            $newData["construct_name"] = $name;
            $newData["construct_url"] = $size;
            $newData["construct_key"] = $key;

            $result = M("internet_construct") ->add($newData);
            if ($result)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else
        {
            $this -> display();
        }
    }
    //施工编辑
    public function editConstruct ()
    {
        if (I("post."))
        {
            $name = I("name");
            $size = I("url");
            $id = I('id/d');
            $data['construct_id']=$id;
            $newData["construct_name"] = $name;
            $newData["construct_url"] = $size;
            $result = M("internet_construct") ->where($data)->save($newData);
            if ($result!==false)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'编辑成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'编辑失败'));
            }
        }else
        {
            $id = I("id/d");
            $data['construct_id']=$id;
            $info = M("internet_construct") ->where($data)->find();
            $this->assign('data',$info);
            $this -> display();
        }
    }

    public function deleteConstruct ()
    {
        $id = I('id/d');
        if(!empty($id)){
            $result = M("internet_construct") ->delete($id);
            if ($result)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'删除成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'删除失败'));
            }
        }

    }
    /**
     *  运维
     */
    public function operationHome ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_key');
        if(!$key){
            echo "信息不存在";exit;
        }
        if(I('limit/d')&&I('page/d')) {
            $sql['operation_key'] = $id;
            $pageCount = I('limit/d');
            $page = I('page/d');
            //TODO:数据分页
            $count = M("internet_operation")->where($sql)->count();// 查询满足要求的总记录数
            $firstRow = ($page-1)*$pageCount;
            $data        = M("internet_operation")->where($sql)->order('operation_id')->limit($firstRow,$pageCount)->select();
            if ($data) {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => $data));
            } else {
                $this->ajaxReturn(array('code' => 0, 'msg' => '', 'count' => $count, 'data' => ""));
            }
        }else {
            $this->assign('id',$id);
            $this->display();
        }
    }
    /**
     *  添加运维数据
     */
    public function operationAdd ()
    {
        $id = I("id/d");
        $arr['public_id'] = $id;
        $arr['public_show'] = 1;
        $key = M('internet_public')->where($arr)->getField('public_id');
        if(!$key){
            $this->error("信息不存在");
        }

        if (!empty($_POST))
        {
            $name = I("name");
            $size = I("size");
            $newData["operation_name"] = $name;
            $newData["operation_url"] = $size;
            $newData["operation_key"] = $key;
            $newData["operation_image"] = I('image');
            $newData["operation_image_jpg"] = I('image_jpg');
            $result = M("internet_operation") ->add($newData);
            if ($result)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'添加成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'添加失败'));
            }
        }else{
            $this->assign('id',$id);
            $this->display();
        }
    }

    /**
     *  运维编辑数据
     */
    public function editOperation ()
    {
        if (I("post."))
        {
            $name = I("name");
            $size = I("size");
            $id = I('id/d');
            $newData["operation_name"] = $name;
            $newData["operation_url"] = $size;
            $newData["operation_image"] = I('image');
            $newData["operation_image_jpg"] = I('image_jpg');
            $data['operation_id']=$id;
            $result = M("internet_operation") ->where($data)->save($newData);
            if ($result!==false)
            {
                $this->ajaxReturn(array('code'=>200,'info'=>'编辑成功'));
            }else{
                $this->ajaxReturn(array('code'=>400,'info'=>'编辑失败'));
            }

        }else{
            $id = I("id/d");
            $data['operation_id']=$id;
            $info = M("internet_operation") ->where($data)->find();
            $this->assign('data',$info);
            $this -> display();
        }
    }


    /**
     *  天翼PPT使用
     */
    //采购
    public function pptPurchaseData ()
    {
        if (!empty($_POST)) {
            $key    = $_POST["name"];
            $result = M("internet_purchase")->where("purchase_key='$key'")->select();
            if ($result) {
                $spaceArr = array();
                for ($i = 0; $i < count($result); $i++) {
                    $spaceData = $result[$i]["purchase_space"];
                    if (!in_array($spaceData, $spaceArr)) {
                        //对最底层数组重置
                        $otherArr   = array();
                        $spaceArr[] = $spaceData;
                        for ($j = 0; $j < count($result); $j++) {
                            if ($spaceData == $result[$j]["purchase_space"]) {
                                $otherArr[] = $result[$j];
                            }
                        }
                        $otherArrs[] = $otherArr;
                    }
                }
                $allArr = array($spaceArr, $otherArrs);
                //说明
                $explain = M("internet_explain")->field("explain_img, explain_type")->where("explain_key='%s'", array($key))->select();
                if ($explain) {
                    $allArr["explain"] = $explain;
                } else {
                    $allArr["explain"] = array();
                }
                $this->ajaxReturn(array("code" => "200", "result" => $allArr), "JSON");
            } else {
                return null;
            }
        }else{
            $this ->display();
        }
    }

    //施工
    public function pptConstructData ()
    {
        if (!empty($_POST)) {
            $key = $_POST["name"];
            $key1 = $key;
            if ($key == "杭州公馆2F" || $key == "杭州公馆B1") {
                $key = "杭州公馆1F";
            }
            $data = M("internet_construct")->where("construct_key='$key'")->select();
            if ($data) {
                //说明
                $explain = M("internet_explain")->field("explain_img, explain_type")->where("explain_key='%s'", array($key))->select();
                if ($explain) {
                    $result["explain"] = $explain;
                } else {
                    $result["explain"] = array();
                }
                for ($i = 0; $i < count($data); $i++) {
                    $name = $data[$i]["construct_name"];
                    switch ($name) {
                        case "node1":
                            $result["video1"] = $data[$i]["construct_url"];
                            break;
                        case "node2":
                            $result["video2"] = $data[$i]["construct_url"];
                            break;
                        case "node3":
                            $result["video3"] = $data[$i]["construct_url"];
                            break;
                        case "node4":
                            $result["video4"] = $data[$i]["construct_url"];
                            break;
                        case "node5":
                            $result["video5"] = $data[$i]["construct_url"];
                            break;
                        default:

                            break;
                    }
                }
                $result["key"] = $key1;
                $this->ajaxReturn(array("code" => "200", "result" => $result
                ), "JSON");
            } else {
                return null;
            }
        }else{
            $this -> display();
        }
    }


    /**
     *  施工页面改版测试页面
     */
    public function designTest ()
    {
        $this ->display();
    }

    /**
     *  施工页面改版测试页面
     */
    public function test1 ()
    {
//        $arr = M('internet_operation')->field('operation_id,operation_image,operation_image_jpg')->select();
//        foreach($arr as $value){
//
//                if ($value['operation_image']) {
//                    if (!$value['operation_image_jpg']) {
//                        echo $value['operation_id'] . ',';
//
//                        $imgurl = $value['operation_image'];
//                        //获取url的后面部分
//                        $uri = strchr($imgurl, __ROOT__);
//                        //绝对路径
//                        $www = '/baleijia/wamp/www/' . $uri;
//                        //后缀(.gif)
//                        $hz = strrchr(basename($imgurl), '.');
//                        if (strstr(strtolower($hz), 'jpg') || strstr(strtolower($hz), 'jpeg')) {
//                            $imgurl1 = $imgurl;
//                        } else {
//                            $trueurl = str_replace($hz, '.jpg', $www);
//                            $weburl = str_replace($hz, '.jpg', $uri);
//                            if (strstr(strtolower($hz), 'gif')) {
//                                $upimg = imagecreatefromgif($www);
//                                $size = getimagesize($www);
//                                $image = imagecreatetruecolor($size[0], $size[1]);
//                                imagecopyresampled($image, $upimg, 0, 0, 0, 0, $size[0], $size[1], $size[0], $size[1]);
//                                imagejpeg($image, $trueurl);
//                                move_uploaded_file($www, $trueurl);
//                                $imgurl1 = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $weburl;
//                            } else if (strstr(strtolower($hz), 'png')) {
//                                $image = new \Think\Image();
//                                $image->open($www);
//                                $width = $image->width(); // 返回图片的宽度
//                                $height = $image->height();
//                                //$type = pathinfo($www,'PATHINFO_EXTENSION');
//                                $image->thumb($width, $height)->save($trueurl, 'jpg');
//                                //jpg缩略图
//                                $imgurl1 = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $weburl;
//                            }
//                        }
//                        echo $imgurl1;
//                        echo "<br/>";
//                        $arr1['operation_id'] = $value['operation_id'];
//                        $newData["operation_image_jpg"] = $imgurl1;
//                        $bool = M("internet_operation")->where($arr1)->save($newData);
//                        if ($bool !== false) {
//
//                        } else {
//                            echo $value['cost_id'];
//                        }
//                    }
//                }
//
//        }

//        echo "<pre>";
//        print_r($arr);
//        echo "</pre>";
//        exit;
//        $this ->display();
    }


}