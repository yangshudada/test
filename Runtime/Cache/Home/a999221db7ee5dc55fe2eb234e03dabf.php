<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>五位一体添加</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
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
                楼盘
            </label>
            <div class="layui-input-block">
                <input type="text" name="building" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>

        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                城市
            </label>
            <div class="layui-input-inline">
                <select name="province" lay-filter="province" class="province">
                    <option value="">请选择省</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <select name="city" lay-filter="city">
                    <option value="">请选择市</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                户型
            </label>
            <div class="layui-input-block">
                <input type="text" name="type"
                       autocomplete="off" class="layui-input">
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">
                面积
            </label>
            <div class="layui-input-block">
                <input type="text" name="area"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                风格
            </label>
            <div class="layui-input-block">
                <input type="text" name="style"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                房型
            </label>
            <div class="layui-input-block">
                <input type="text" name="number"
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
                    <p>点击上传，或将文件拖拽到此处</p>
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

        var uploadInst = upload.render({
            elem: '#test1'
            ,url: "<?php echo U('public/upload');?>"
            ,method:'post'
            ,data: {name: 'house'}
            ,done: function(res){
                //如果上传失败
                if(res.code==200){
                    $('.images').remove();
                    $('#test1').append("<div class='images'><img src="+res.info+" style='width:168px;height:70px;'><input type='hidden' name='image' value='"+res.info+"'><input type='hidden' name='image_jpg' value='"+res.info1+"'></div>");
                }else{
                    layer.msg(res.info);
                }
                //上传成功
            }
        });


        $.get("/company/Public/Resource/city.json", function (data) {
            var proHtml = '';
            for (var i = 0; i < data.length; i++) {
                proHtml += '<option value="' + data[i].province + '">' + data[i].province + '</option>';
            }
            //初始化省数据
            $("select[name=province]").append(proHtml);
            form.render();
        })

        form.on('select(province)', function(data){
            $("select[name=city]").empty();
            form.render();
            $.get("/company/Public/Resource/city.json", function (msg) {
                var cityHtml = '';
                cityHtml += '<option value="">请选择市</option>';
                for (var i = 0; i < msg.length; i++) {
                    if(msg[i].province==data.value){
                        for (var j = 0; j < msg[i].city.length; j++) {
                            cityHtml += '<option value="' + msg[i].city[j].city_name + '">' + msg[i].city[j].city_name + '</option>';
                        }
                        break;
                    }
                }
                //初始化省数据
                $("select[name=city]").append(cityHtml);
                form.render();
            })
        });

        //监听提交
        form.on('submit(add)', function(data){
            $.post("<?php echo U('House/houseAdd');?>",data.field,function(res){
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