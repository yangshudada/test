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
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                户型
            </label>
            <div class="layui-input-block">
                <input type="text" name="house" required=""
                       autocomplete="off" class="layui-input" value="<?php echo ($data['whitepaper_house']); ?>">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                名称
            </label>
            <div class="layui-input-block">
                <input type="text" name="name" required=""
                       autocomplete="off" class="layui-input" value="<?php echo ($data['whitepaper_name']); ?>">
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo ($data['whitepaper_id']); ?>">
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                头部图片
            </label>
            <div class="layui-upload-drag" id="test1">
                <div class="images">
                    <?php if($data['whitepaper_image'] != ''): ?><img src="<?php echo ($data['whitepaper_image']); ?>" style='width:168px;height:70px;'/>
                        <input type='hidden' name='image' value="<?php echo ($data['whitepaper_image']); ?>"/>
                        <input type='hidden' name='image_jpg' value="<?php echo ($data['whitepaper_image_jpg']); ?>"/>
                    <?php else: ?>
                    <i class="layui-icon">&#xe67c;</i>
                    <p>点击上传，或将图片拖拽到此处</p><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>楼盘
            </label>
            <div class="layui-input-inline">
                <input type="text" required=""
                       autocomplete="off" class="layui-input" value="<?php echo ($data['whitepaper_building']); ?>" disabled>
            </div>

        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">
                <span class="x-red">*</span>选择详情
            </label>
            <div class="layui-input-block">
                <table class="layui-table layui-input-block">
                    <tbody>
                    <?php if(is_array($info)): foreach($info as $key=>$value): ?><tr>
                        <td>
                            <?php echo ($value['whitepaper_style']); ?>
                            <input class="checkbox_v<?php echo ($value['whitepaper_sid']); ?>" type="checkbox" name="checkbox_v<?php echo ($value['whitepaper_sid']); ?>" value="<?php echo ($value['whitepaper_sid']); ?>" lay-filter="checkbox_v" <?php if(in_array($value['whitepaper_sid'],$style)){ ?> checked <?php } ?> >
                        </td>
                        <td>
                            <div class="layui-input-block">
                                <?php if(is_array($value['detail'])): foreach($value['detail'] as $key=>$val): if($val['whitepaper_dname'] != ''): ?><input name="checkbox<?php echo ($value['whitepaper_sid']); ?>[<?php echo ($val['whitepaper_did']); ?>]" type="checkbox" value="<?php echo ($val['whitepaper_sid']); ?>" class="checkbox<?php echo ($value['whitepaper_sid']); ?>" lay-filter="checkbox" <?php if(in_array($val['whitepaper_did'],$detail)){ ?> checked <?php } ?>><?php echo ($val['whitepaper_dname']); endif; endforeach; endif; ?>
                            </div>
                        </td>
                    </tr><?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-form-item">
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
                    $('#test1').append("<div class='images'><img src="+res.info+" style='width:168px;height:70px;'><input type='hidden' name='image' value='"+res.info+"'><input type='hidden' name='image_jpg' value='"+res.info1+"'></div>");
                }else{
                    layer.msg(res.info);
                }
                //上传成功
            }
        });


        form.on('checkbox(checkbox_v)', function(data){
            var a = data.elem.checked;
            var id = data.elem.value;
            if(a == true){
                $(".checkbox"+id).prop("checked", true);
                form.render('checkbox');
            }else {
                $(".checkbox"+id).prop("checked", false);
                form.render('checkbox');
            }
        });

        form.on('checkbox(checkbox)', function(data) {
            var b = data.elem.checked;
            var ids = data.elem.value;
            if(b == true){
                $(".checkbox_v"+ids).prop("checked", true);
                form.render('checkbox');
            }else {
                var item = $(".checkbox"+ids);
                var bool = false;
                for (var i = 0; i < item.length; i++) {
                    if(item[i].checked == true){
                        bool = true;
                        break;
                    }
                }
                if(bool==false) {
                    $(".checkbox_v"+ids).prop("checked", false);
                    form.render('checkbox');
                }
            }

        });

        //监听提交
        form.on('submit(edit)', function(data){
            console.log(data.field);
            $.post("<?php echo U('WhitePaper/paperEdit');?>",data.field,function(res){
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