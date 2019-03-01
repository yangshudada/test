<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加全景信息</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/company/Public/css/zTreeStyle.css" type="text/css">
    <link rel="stylesheet" href="/company/Public/css/font.css">
    <link rel="stylesheet" href="/company/Public/css/xadmin.css">
    <script type="text/javascript" src="/company/Public/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="/company/Public/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/company/Public/js/jquery.ztree.excheck.js"></script>
    <style>
        .ztree>li{
            clear:both !important;
        }

        .ztree>li>ul>li{
            float:left !important;
        }
    </style>
</head>
<body>
<div class="x-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>角色
            </label>
            <div class="layui-input-block">
                <input type="text" name="name" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>

        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否启用</label>
            <div class="layui-input-block">
                <input type="checkbox" checked="" name="show" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入内容" class="layui-textarea"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">拥有权限</label>
            <div class="layui-input-block">
                <div class="zTreeDemoBackground left" style="border: 1px solid #ccc;display: grid;">
                    <ul id="treeDemo" class="ztree"></ul>
                </div>
                <div><input type="hidden" name="ruleid" id="rules"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit=""  onclick="submitBtnClick()">
                增加
            </button>
            <button type="reset" class="layui-btn layui-btn-primary">
                重置
            </button>
        </div>
    </form>
</div>
<script>
    var setting = {
        check: {
            enable: true,
            chkStyle: "checkbox",
            chkboxType:{"Y":"ps","N":"ps"},
        },
        data: {
            simpleData: {
                enable: true,
                pIdKey:"pid"
            }
        }
    };

    $.ajax({ //请求数据,创建树
        type: 'post',
        url: "<?php echo U('Public/rules');?>",
        dataType: "json", //返回的结果为json
        success: function (data) {
            $.fn.zTree.init($("#treeDemo"), setting, data);
        },
        error: function (data) {
            layer.msg("权限显示失败", {icon: 6, time: 2000});
        }
    });

    function submitBtnClick() {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        checkCount = zTree.getCheckedNodes(true);

        var classpurview = "";
        for(var i=0;i<checkCount.length;i++) {
            classpurview += "," + checkCount[i].id
        }
        $("#rules").val(classpurview);
    }
</script>
<script type="text/javascript" src="/company/Public/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript" src="/company/Public/js/xadmin.js"></script>
<script>
    layui.use(['form','layer'], function(){
        var form = layui.form
            ,layer = layui.layer;

        form.verify({
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
        form.on('submit(add)', function(data){
            $.post("<?php echo U('Auth/roleAdd');?>",data.field,function(res){
                if(res.code==200) {
                    layer.msg(res.info, {icon: 6,time:2000},function () {
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
</body>
</html>