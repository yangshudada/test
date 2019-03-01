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
    <script src="/company/Public/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/company/Public/js/xadmin.js"></script>
</head>
<body>
<div class="x-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                分类
            </label>
            <div class="layui-input-block">
                <select name="sid" lay-verify="required">
                    <?php if(is_array($info)): foreach($info as $key=>$value): ?><option value="<?php echo ($value['whitepaper_sid']); ?>"><?php echo ($value['whitepaper_style']); ?></option><?php endforeach; endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                楼盘
            </label>
            <div class="layui-input-block">
                <input type="text" name="building" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                品名
            </label>
            <div class="layui-input-block">
                <input type="text" name="name" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                规格
            </label>
            <div class="layui-input-block">
                <input type="text" name="size" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                数量(输入单位)
            </label>
            <div class="layui-input-block">
                <input type="text" name="num" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                品牌
            </label>
            <div class="layui-input-block">
                <input type="text" name="brand" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                图片
            </label>
            <div class="layui-upload-drag" id="test1">
                <div class="images">
                    <i class="layui-icon">&#xe67c;</i>
                    <p>点击上传，或将图片拖拽到此处</p>
                </div>
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
    layui.use(['form','layer','upload'], function(){
        $ = layui.jquery;
        var form = layui.form
                ,layer = layui.layer
                ,upload = layui.upload;

        upload.render({
            elem: '#test1'
            ,url: "<?php echo U('public/upload');?>"
            ,method:'post'
            ,data: {name: 'whitePaper1'}
            ,done: function(res){
                //如果上传失败
                if(res.code==200){
                    $('.images').remove();
                    $('#test1').append("<div class='images'><img src="+res.info+" style='width:168px;height:70px;'><input type='hidden' name='image' value='"+res.info+"'></div>");
                }else{
                    layer.msg(res.info);
                }
                //上传成功
            }
        });


        //监听提交
        form.on('submit(add)', function(data){
            console.log(data.field);
            $.post("<?php echo U('WhitePaper/paperDetailAdd');?>",data.field,function(res){
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