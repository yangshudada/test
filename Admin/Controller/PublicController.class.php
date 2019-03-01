<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {

    //通用文字识别
    public function OrcImage()
    {


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
            $imgurl = '/Upload/'.$name.'/'.$action.$info["file"]["savepath"] . $info["file"]["savename"];

            vendor('ImageAI.AipOcr');
            $APP_ID = '11734758';
            $API_KEY = 'HgTqLaSxFP9VFX0hh7SqvKBN';
            $SECRET_KEY = 'TBw8cIr1sLXiSG6uy6PjuY0eQm1LP0VE';
            $client = new \ImageAI\AipOcr($APP_ID, $API_KEY, $SECRET_KEY);

            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$imgurl;
            $image = file_get_contents($url);

// 调用通用文字识别（高精度版）
            $client->basicAccurate($image);

// 如果有可选参数
            $options = array();
            $options["detect_direction"] = "true";
            $options["probability"] = "true";

// 带参数调用通用文字识别（高精度版）
            $result = $client->basicAccurate($image, $options);
            $string = '';
            if($result['error_msg']>0){
                unlink($imgurl);
            }else{
                if(empty($result['words_result'])){
                    $word = '';
                }else{
                    foreach($result['words_result'] as $v){
                        $string .= $v['words'].';';
                    }
                    $word = rtrim($string,';');
                }
            }
            return $this->ajaxReturn(array('code'=>200,'info'=>$imgurl,'word'=>$word));
        }

    }
}