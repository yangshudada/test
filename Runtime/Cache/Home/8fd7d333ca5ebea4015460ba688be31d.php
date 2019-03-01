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
    <script type="text/javascript" src="/company/Public/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/company/Public/js/xadmin.js"></script>
</head>
<body>
<div class="x-body">
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                楼盘
            </label>
            <div class="layui-input-block">
                <input type="text" name="premises" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                房屋
            </label>
            <div class="layui-input-block">
                <input type="text" name="house" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                单元
            </label>
            <div class="layui-input-block">
                <input type="text" name="unit" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                房号
            </label>
            <div class="layui-input-block">
                <input type="text" name="number" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                <span class="x-red">*</span>显示模块
            </label>
            <div class="layui-input-block">
                <table class="layui-table layui-input-block">
                    <tbody>
                    <tr>
                        <td>
                            模块
                            <input class="checkbox_v1" type="checkbox" name="checkbox_v1" value="模块" lay-filter="checkbox_v1">
                        </td>
                        <td>
                            <div class="layui-input-block">
                                <input name="checkbox1[]" type="checkbox" value="1" class="checkbox1" lay-filter="checkbox1">毛坯
                                <input name="checkbox1[]" type="checkbox" value="2" class="checkbox1" lay-filter="checkbox1">量房
                                <input name="checkbox1[]" type="checkbox" value="3" class="checkbox1" lay-filter="checkbox1">方案
                                <input name="checkbox1[]" type="checkbox" value="4" class="checkbox1" lay-filter="checkbox1">设计
                                <input name="checkbox1[]" type="checkbox" value="5" class="checkbox1" lay-filter="checkbox1">隐蔽
                                <input name="checkbox1[]" type="checkbox" value="6" class="checkbox1" lay-filter="checkbox1">模型
                                <input name="checkbox1[]" type="checkbox" value="7" class="checkbox1" lay-filter="checkbox1">对比
                                <input name="checkbox1[]" type="checkbox" value="8" class="checkbox1" lay-filter="checkbox1">追溯
                                <input name="checkbox1[]" type="checkbox" value="9" class="checkbox1" lay-filter="checkbox1">介绍
                                <input name="checkbox1[]" type="checkbox" value="10" class="checkbox1" lay-filter="checkbox1">我家的景色
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
                ,layer = layui.layer;

        form.on('checkbox(checkbox_v1)', function(data){
            var a = data.elem.checked;
            if(a == true){
                $(".checkbox1").prop("checked", true);
                form.render('checkbox');
            }else {
                $(".checkbox1").prop("checked", false);
                form.render('checkbox');
            }
        });

        form.on('checkbox(checkbox1)', function(data) {
            var b = data.elem.checked;
            if(b == true){
                $(".checkbox_v1").prop("checked", true);
                form.render('checkbox');
            }else {
                var item = $(".checkbox1");
                var bool = false;
                for (var i = 0; i < item.length; i++) {
                    if(item[i].checked == true){
                        bool = true;
                        break;
                    }
                }
                if(bool==false) {
                    $(".checkbox_v1").prop("checked", false);
                    form.render('checkbox');
                }
            }

        });


        //监听提交
        form.on('submit(add)', function(data){
            console.log(data.field);
            $.post("<?php echo U('Sand/houseAdd');?>",data.field,function(res){
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