<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>批量添加沙盘</title>
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
    <fieldset class="layui-elem-field">
        <legend>样例</legend>
        <div class="layui-field-box">
            <a href="/company/Public/excel/1.xlsx" title="沙盘模板">点击查看沙盘样例</a>
        </div>
    </fieldset>
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                导入EXCEL
            </label>
            <div class="layui-upload-drag" id="test1">
                <div class="images">
                    <i class="layui-icon">&#xe67c;</i>
                    <p>点击上传，或将EXCEL拖拽到此处</p>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
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
            ,url: "<?php echo U('public/uploadExcel');?>"
            ,accept: 'file'
            ,exts:'xlsx|xls'

            ,method:'post'
            ,data: {name: 'house'}
            ,done: function(res){
                //如果上传失败
                if(res.code==200){
                    $('.images').remove();
                    $('#test1').append("<div class='images'>"+res.info+"<input type='hidden' name='excel' value='"+res.info+"'></div>");
                }else{
                    layer.msg(res.info);
                }
                //上传成功
            }
        });

        //监听提交
        form.on('submit(add)', function(data){
            console.log(data.field);
            $.post("<?php echo U('Sand/houseAddExcel');?>",data.field,function(res){
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