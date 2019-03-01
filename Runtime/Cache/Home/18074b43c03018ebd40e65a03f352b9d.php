<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传全景</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/company/Public/css/font.css">
    <link rel="stylesheet" href="/company/Public/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/company/Public/lib/layui/layui.all.js" charset="utf-8"></script>
    <script type="text/javascript" src="/company/Public/js/xadmin.js"></script>
</head>
<body>
<div class="x-body">
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">
                链接地址
            </label>
            <div class="layui-input-block layui-form-text">
                <input type="text" name="url" required=""
                       autocomplete="off" class="layui-input" value="<?php echo ($data['vr_url']); ?>">
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
        <input type="hidden" name="action" value="<?php echo ($action); ?>">
        <div class="layui-form-item">
            <?php if($data != ''): ?><button  class="layui-btn" lay-filter="edit" lay-submit="">
                修改
            </button>
            <?php else: ?>
            <button  class="layui-btn" lay-filter="edit" lay-submit="">
                添加
            </button><?php endif; ?>
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
                ,layer = layui.layer
                ,upload = layui.upload;


        //监听提交
        form.on('submit(edit)', function(data){
            $.post("<?php echo U('Sand/editVr');?>",data.field,function(res){
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