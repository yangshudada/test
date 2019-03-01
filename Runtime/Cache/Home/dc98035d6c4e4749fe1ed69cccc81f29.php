<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>1</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/Public/css/font.css">
    <link rel="stylesheet" href="/Public/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/Public/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/Public/js/xadmin.js"></script>
</head>
<body class="login-bg">

<div class="login">
    <div class="message">系统1</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form" action="<?php echo U('Index/index');?>" enctype="multipart/form-data">
        <input name="name" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="pwd" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <div>
            <div style="width: 35%;float: left;">
                <input name="admin_verify" lay-verify="required" placeholder="纯数字验证码"  type="text" class="layui-input">
            </div>
            <div style="width: 65%;float: right;">
                <img src="<?php echo U('Public/verify');?>" class="verifyCode" name="admin_verify" title="点击刷新验证码" onclick="this.src='/index.php/Home/Public/verify?id='+Math.random()">
            </div>
        </div>
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>

<script>

    $(function  () {
        layui.use('form', function(){
            var form = layui.form;
            // layer.msg('玩命卖萌中', function(){
            //   //关闭后的操作
            //   });
            //监听提交
            form.on('submit(login)', function(data){
                // alert(888)
                $.post("<?php echo U('Public/loginb');?>",data.field,function(res){
                    if(res.code==200){
                        layer.msg(res.info, {time: 2000});
                        var url = "<?php echo U('Index/index');?>"; //
                        setTimeout(window.location.href=url,2000);
                    }else{
                        layer.msg(res.info, {time: 2000});
                    }
                },'json');
                return false;
            });
        });
    })


</script>


<!-- 底部结束 -->

</body>
</html>