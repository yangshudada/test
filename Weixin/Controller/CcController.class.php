<?php
namespace Weixin\Controller;
use Think\Controller;
class CcController extends Controller {
    public function index(){
        //获得参数 signature nonce token timestamp echostr
        $nonce     = $_GET['nonce'];
        $token     = 'weixin';
        $timestamp = $_GET['timestamp'];
        $echostr   = $_GET['echostr'];
        $signature = $_GET['signature'];
        //形成数组，然后按字典序排序
        $array = array();
        $array = array($nonce, $timestamp, $token);
        sort($array);
        //拼接成字符串,sha1加密 ，然后与signature进行校验
        $str = sha1( implode( $array ) );
        if( $str  == $signature && $echostr ){
            //第一次接入weixin api接口的时候
            echo  $echostr;
            exit;
        }else{
            $this->reponseMsg();
        }
    }
    public function  show(){
        echo phpinfo();
        exit;
        echo 'imooc';
    }

    //消息处理函数
    public function reponseMsg(){
        //1.获取到微信推送过来post数据（xml格式）
        $postArr = file_get_contents("php://input");
        //2.处理消息类型，并设置回复类型和内容
        /*<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[FromUser]]></FromUserName>
<CreateTime>123456789</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
</xml>*/
        $postObj = simplexml_load_string( $postArr );
        //$postObj->ToUserName = '';
        //$postObj->FromUserName = '';
        //$postObj->CreateTime = '';
        //$postObj->MsgType = '';
        //$postObj->Event = '';
        // gh_e79a177814ed
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = "欢迎关注我们的微信公众账号\n官网地址:<a href='http://21c.xyz'>http://21c.xyz</a>\n";
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
                /*<xml>
                <ToUserName><![CDATA[toUser]]></ToUserName>
                <FromUserName><![CDATA[fromUser]]></FromUserName>
                <CreateTime>12345678</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[你好]]></Content>
                </xml>*/


            }
        }
        //根据用户输入消息进行回复
        else if(strtolower( $postObj->MsgType) == 'text'){
            $toUser   = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            $time     = time();
            $msgType  =  'text';
            //$content  = 'imooc is very good'.$postObj->FromUserName.'-'.$postObj->ToUserName;
            $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
            switch( trim($postObj->Content)){
                case 1:
                    $content = '您输入的数字是1';
                    break;
                case 2:
                    $content = '您输入的数字是2';
                    break;
                case 3:
                    $content = '<a href="http://www.baidu.com">百度</a>';
                    break;
                case tuwen:
                    $arr=array(
                        array('title'=>'imooc',
                            'description'=>'imooc描述',
                            'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                            'url'=>'http://www.baidu.com'),
                        array('title'=>'hao123',
                            'description'=>'hao123描述',
                            'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                            'url'=>'http://www.hao123.com'),
                        array('title'=>'baidu',
                            'description'=>'baidu描述',
                            'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                            'url'=>'http://www.baidu.com'),
                    );
                    $content = '<a href="http://www.baidu.com">百度</a>';
                    $template_tuWen = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <ArticleCount>".count($arr)."</ArticleCount>
                                <Articles>";
                    foreach($arr as $k=>$v){
                        $template_tuWen .= "<item>
                                <Title><![CDATA[".$v['title']."]]></Title>
                                <Description><![CDATA[".$v['description']."]]></Description>
                                <PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
                                <Url><![CDATA[".$v['url']."]]></Url>
                                </item>";
                    }

                    $template_tuWen .="</Articles>
                                </xml>";
                    $info     = sprintf($template_tuWen, $toUser,$fromUser,$time,'news');
                    echo $info;
                    break;
            }

            $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
            echo $info;
        }

    }

    //$url  接口url string
    //$type 请求类型string
    //$res  返回类型string
    //$arr= 请求参数string
    public function http_curl($url,$type='get',$res='json',$arr=''){
        //1.初始化curl
        $ch  =curl_init();
        //2.设置curl的参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        }
        //3.采集
        $output =curl_exec($ch);
        //4.关闭
        curl_close($ch);
        if($res=='json'){
            if(curl_error($ch)){
                //请求失败，返回错误信息
                return curl_error($ch);
            }else{
                //请求成功，返回错误信息

                return json_decode($output,true);
            }
        }
        echo var_dump( $output );
    }

    //获取测试号微信AccessToken
    /*function  getWxAccessToken(){

        //2初始化
        $ch  =curl_init();
        //3设置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //4.调用接口
        $res =curl_exec($ch);
        //5.关闭curl
        curl_close($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
        }
        $arr=json_decode($res,true);
        var_dump($arr);
    }*/

    //获取微信服务器IP地址
    function  getWxServerIp(){
        $accessToken ="13_aAkk4XVKvKVFrdlICaV6l_4rQBG-gMKWzXgvApsiX59g8bpbxr03NqGDIR2vJ8VBlSdqNzoeot3dALAg3wlfL8cttgJjUvXWEkxNEpS6QU-0ouFWxmp72enjoy7Yl12rBr85cMX5ANB-91cNUDRjAIAZIW";
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
        $ch  =curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $res =curl_exec($ch);
        //5.关闭curl
        curl_close($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
        }
        $arr=json_decode($res,true);
        echo "<pre>";
        var_dump($arr);
        echo "</pre>";
    }
//返回access_token *session解决办法 存mysql memcache
    public function  getWxAccessToken(){
        if( $_SESSION['access_token'] && $_SESSION['expire_time']>time()){
            //如果access_token在session没有过期
            return $_SESSION['access_token'];
        }
        else{
            //如果access_token比存在或者已经过期，重新取access_token
            //1 请求url地址
            $AppId='wx95640fb9db576015';
            $AppSecret='d6513cd58dcdc58faa708f7b37fc1a77';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$AppId."&secret=".$AppSecret;
            $res=$this->http_curl($url,'get','json');
            $access_token =$res['access_token'];
            echo $access_token;
            //将重新获取到的aceess_token存到session
            $_SESSION['access_token']=$access_token;
            $_SESSION['expire_time']=time()+7000;
            return $access_token;
        }
    }


    public function  definedItem(){
        //创建微信菜单
        //目前微信接口的调用方式都是通过 curl post/get
        header('content-type:text/html;charset=utf-8');
        echo $access_token=$this ->getWxAccessToken();
        echo $url ='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $postArr='{
            "button":[
                        {
                            "name": "扫码",
                            "sub_button": [
                                {
                                    "type": "scancode_waitmsg",
                                    "name": "扫码带提示",
                                    "key": "rselfmenu_0_0",
                                    "sub_button": [ ]
                                },
                                {
                                    "type": "scancode_push",
                                    "name": "扫码推事件",
                                    "key": "rselfmenu_0_1",
                                    "sub_button": [ ]
                                }
                            ]
                        },
                        {
                            "name": "发图",
                            "sub_button": [
                                {
                                    "type": "pic_sysphoto",
                                    "name": "系统拍照发图",
                                    "key": "rselfmenu_1_0",
                                    "sub_button": [ ]
                                },
                                {
                                    "type": "pic_photo_or_album",
                                    "name": "拍照或者相册发图",
                                    "key": "rselfmenu_1_1",
                                    "sub_button": [ ]
                                },
                                {
                                    "type": "pic_weixin",
                                    "name": "微信相册发图",
                                    "key": "rselfmenu_1_2",
                                    "sub_button": [ ]
                                }
                            ]
                        },
                        {
                            "name": "其他",
                            "sub_button": [
                                {
                                    "type": "location_select",
                                    "name": "发送位置",
                                    "key": "rselfmenu_2_0"
                                },
                                {
                                    "type": "view", 
                                    "name": "我的网站", 
                                    "url": "http://21c.xyz", 
                                    "sub_button": [ ]
                                }
                            ]
                        }
                    ]
                }';
        echo  $postJson = urldecode($postArr);
        $res = $this->http_curl($url,'post','json',$postJson);
        var_dump($res);
    }
}