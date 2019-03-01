<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d"><head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>修改密码</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/company/Public/css/font.css">
    <link rel="stylesheet" href="/company/Public/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/company/Public/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/company/Public/js/xadmin.js"></script>
</head>
<body>
<div class="x-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>旧密码
            </label>
            <div class="layui-input-inline">
                <input type="password" name="old_pwd" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" lay-verify="oldPwd">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_pass" class="layui-form-label">
                <span class="x-red">*</span>新密码
            </label>
            <div class="layui-input-inline">
                <input type="password" id="L_pass" name="new_pwd_1" required="" lay-verify="newPwd"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                6到16个字符
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
                <span class="x-red">*</span>确认密码
            </label>
            <div class="layui-input-inline">
                <input type="password" id="L_repass" name="new_pwd_2" required="" lay-verify="confirmPwd"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="save" lay-submit="">
                修改
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        form.verify({
            oldPwd : function(value, item){
                if (value.length == 0) {
                    return '请输入旧密码';
                }
            },

            newPwd : function(value, item){
                if(value.length < 6){
                    return "密码长度不能小于6位";
                }
            },
            confirmPwd : function(value, item){
                if($("#L_pass").val()!=value){
                    return "两次输入密码不一致，请重新输入！";
                }
            }
        });

        //监听提交
        form.on('submit(save)', function(data){
            $.post("<?php echo U('Public/change_psw');?>",data.field,function(res){
                if(res.code==200) {
                    layer.msg(res.info, {icon: 6,time:1000},function () {
                        x_admin_close();
                        window.parent.location.reload();
                    });
                }else{
                    layer.alert(res.info, {icon: 5},function () {
                        layer.closeAll();
                    })
                }
            });
            return false;
        });


    });
</script>
</body>
</html>