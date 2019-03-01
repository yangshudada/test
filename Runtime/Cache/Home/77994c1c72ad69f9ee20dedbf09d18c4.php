<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>编辑全景信息</title>
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
                <span class="x-red">*</span>用户名
            </label>
            <div class="layui-input-block">
                <input type="text" name="name" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" value="<?php echo ($data["admin_name"]); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否启用</label>
            <div class="layui-input-block">
                <input type="checkbox"  <?php if($data["admin_show"] == 1): ?>checked<?php endif; ?> name="show" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>角色</label>
            <div class="layui-input-block">
                <?php if(is_array($group)): foreach($group as $key=>$value): ?><input type="checkbox" name="gid[<?php echo ($value['id']); ?>]" lay-skin="primary" title="<?php echo ($value['title']); ?>" <?php if(in_array($value['id'],$gid)){ ?> checked <?php } ?>/><?php endforeach; endif; ?>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入内容" class="layui-textarea"><?php echo ($data["admin_remark"]); ?></textarea>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo ($data["admin_id"]); ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="edit" lay-submit="">
                修改
            </button>
            <button type="reset" class="layui-btn layui-btn-primary">
                重置
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer','upload'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(edit)', function(data){
            $.post("<?php echo U('auth/adminEdit');?>",data.field,function(res){
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
</html>