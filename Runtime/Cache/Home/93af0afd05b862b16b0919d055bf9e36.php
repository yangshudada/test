<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加全景信息</title>
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
                <span class="x-red">*</span>所属模块
            </label>
            <div class="layui-input-block">
                <select name="pid" lay-verify="required">
                    <option value="0">顶级模块</option>
                    <?php if(is_array($data)): foreach($data as $key=>$value): ?><option value="<?php echo ($value['id']); ?>"><?php echo ($value['title']); ?></option><?php endforeach; endif; ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>规则名称
            </label>
            <div class="layui-input-block">
                <input type="text" name="title" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>规则标识
            </label>
            <div class="layui-input-block">
                <input type="text" id="L_pass" name="name" required=""
                       autocomplete="off" class="layui-input" lay-verify="require">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                增加
            </button>
            <button type="reset" class="layui-btn layui-btn-primary">
                重置
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data){
            $.post("<?php echo U('Auth/ruleAdd');?>",data.field,function(res){
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
</body>
</html>