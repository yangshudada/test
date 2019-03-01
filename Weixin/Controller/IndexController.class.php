<?php
namespace Weixin\Controller;
use Think\Controller;
class IndexController extends Controller {
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
//                $msgType  =  'text';
//                $content  = "欢迎关注我们的微信公众账号\n官网地址:<a href='http://21c.xyz'>http://21c.xyz</a>\n";
//                $template = "<xml>
//                            <ToUserName><![CDATA[%s]]></ToUserName>
//                            <FromUserName><![CDATA[%s]]></FromUserName>
//                            <CreateTime>%s</CreateTime>
//                            <MsgType><![CDATA[%s]]></MsgType>
//                            <Content><![CDATA[%s]]></Content>
//                            </xml>";
//                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
//                echo $info;

                $arr=array(
                    array('title'=>'欢迎关注',
                        'description'=>'欢迎关注我们的微信公众账号',
                        'picUrl'=>'https://mmbiz.qpic.cn/mmbiz_jpg/XicoJa8HUbvwY2TStkcrQujoeInia6D7Vrw8qrfHic4ia1R2m7iciantqgHicY1sqgIPvFwsYSOqic9gaIvSibXl5ibtEwRA/0?wx_fmt=jpeg'),
                    array('title'=>'官网地址',
                        'description'=>'官网地址',
                        'picUrl'=>'http://wx.qlogo.cn/mmopen/eS4e5QGgKB7UeSnvnE6ht0RvaQ8GXibOb6IicxWsgyAialkgicGFMxCDoBWsWq94eqK5SkE4GuGAxSmkTvKndDnuZKhbSSKnib5Qib/64',
                        'url'=>'21c.xyz'),
                    array('title'=>'测试公众号',
                        'description'=>'测试公众号二维码',
                        'picUrl'=>'https://mmbiz.qpic.cn/mmbiz_jpg/XicoJa8HUbvwY2TStkcrQujoeInia6D7VrMM8Njjb5W5vLWbTfPplyKltOGUibYhoxarmJn9q4xPMudic50fbXznsQ/0?wx_fmt=jpeg',
                        'url'=>'21c.xyz/Public/images/cs.jpg'),
                    array('title'=>'小程序',
                        'description'=>'小程序',
                        'picUrl'=>'https://mmbiz.qpic.cn/mmbiz_jpg/XicoJa8HUbvwY2TStkcrQujoeInia6D7VrmjyDAyV8fSawUWKhwyVnm6ribw0YOzKjIFwsPjX4gOz4A7fMNMNFZ6w/0?wx_fmt=jpeg',
                        'url'=>'https://mmbiz.qpic.cn/mmbiz_jpg/XicoJa8HUbvwY2TStkcrQujoeInia6D7VrmjyDAyV8fSawUWKhwyVnm6ribw0YOzKjIFwsPjX4gOz4A7fMNMNFZ6w/0?wx_fmt=jpeg')

                );
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
                $info2     = sprintf($template_tuWen, $toUser,$fromUser,$time,'news');
                echo $info2;


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
}