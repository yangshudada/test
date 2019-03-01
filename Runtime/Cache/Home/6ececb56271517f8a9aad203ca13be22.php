<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>模型后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/company/Public/css/font.css">
    <link rel="stylesheet" href="/company/Public/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/company/Public/lib/layui/layui.all.js" charset="utf-8"></script>
    <script type="text/javascript" src="/company/Public/js/xadmin.js"></script>
    <style>
       body{
            overflow-y: scroll;
        }
        table>th{
            text-align: center !important;
        }
        .layui-table-cell{
            height: auto !important;
        }
    </style>
</head>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a href="<?php echo U('index/vrList');?>">模型列表</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#x1002;</i>
    </a>
</div>
<div class="x-body">
    <!--<div class="layui-row">-->
        <!--<div class="demoTable layui-col-md12 x-so">-->
            <form action="" method="post" name="fileForm" class="layui-form">
                <div class="layui-form-item"  class="demoTable layui-col-md12 x-so" style="text-align:center">
                    <div class="layui-inline">
                        <label class="layui-form-label">价格</label>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="number" name="pricemin" placeholder="￥" autocomplete="off" class="layui-input" value="<?php echo ($pricemin); ?>" lay-verify="pricemin" id="min">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="number" name="pricemax" placeholder="￥" autocomplete="off" class="layui-input" value="<?php echo ($pricemax); ?>" lay-verify="pricemax">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="select"  placeholder="请输入搜索条件" autocomplete="off" class="layui-input" value="<?php echo ($select); ?>">
                        </div>
                        <div class="layui-input-inline">
                            <button class="layui-btn"  lay-submit="" id="sreach" data-type="reload"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="company_id" autocomplete="off" class="layui-input" id="company_id" value="<?php echo ($company_id); ?>">
                <input type="hidden" name="typeid" autocomplete="off" class="layui-input" id="typeid" value="<?php echo ($vr_typeid); ?>">
                <input type="hidden" name="typesid" autocomplete="off" class="layui-input" id="typesid" value="<?php echo ($vr_typesid); ?>">
                <input type="hidden" name="space" autocomplete="off" class="layui-input" id="space" value="<?php echo ($vr_space); ?>">
                <input type="hidden" name="style" autocomplete="off" class="layui-input" id="style" value="<?php echo ($vr_style); ?>">
                <input type="hidden" name="color" autocomplete="off" class="layui-input" id="color" value="<?php echo ($vr_color); ?>">
                <input type="hidden" name="vendor" autocomplete="off" class="layui-input" id="vendor" value="<?php echo ($vr_vendor); ?>">
                <input type="hidden" name="designer" autocomplete="off" class="layui-input" id="designer" value="<?php echo ($vr_designer); ?>">
                <input type="hidden" name="order" autocomplete="off" class="layui-input" id="order" value="<?php echo ($order); ?>">
            </form>
    <!--</div>-->
<!--</div>-->
<xblock>
    <button class="layui-btn" onclick="x_admin_show('添加模型','vrAdd.html',650)"><i class="layui-icon"></i>添加模型</button>
    <span class="x-right" style="line-height:40px"></span>
</xblock>
    <img src="" id="displayImg" style="display: none" alt="">
    <table class="layui-table" lay-filter="test" id="cc" style="text-align: center">
        <thead>
        <tr>
            <?php if($level==1): ?><th style="text-align: center;width: 6%;">
                <select name="company_id" onchange="changeSelect('company_id')" >
                    <option value="">公司</option>
                    <?php if(is_array($company)): foreach($company as $key=>$co): ?><option value="<?php echo ($co['company_id']); ?>" <?php if($co['company_id']==$company_id): ?>selected<?php endif; ?> ><?php echo ($co['company_name']); ?></option><?php endforeach; endif; ?>
                </select>
            </th><?php endif; ?>
            <th style="text-align: center;width: 6%;">
                <select name="typeid" onchange="changeSelect('typeid')" >
                    <option value="">分类</option>
                    <?php if(is_array($typeid)): foreach($typeid as $key=>$ty): ?><option value="<?php echo ($ty['id']); ?>" <?php if($ty['id']==$vr_typeid): ?>selected<?php endif; ?> ><?php echo ($ty['name']); ?></option><?php endforeach; endif; ?>
                </select>
            </th>
            <th  style="text-align: center;">
                图片
            </th>
            <th style="text-align: center;width: 10%;">
                名称
            </th>


            <th style="text-align: center;width: 5%;">
                <select name="typesid" onchange="changeSelect('typesid')" >
                    <option value="">类型</option>
                    <?php if(is_array($typesid)): foreach($typesid as $key=>$tys): ?><option value="<?php echo ($tys['id']); ?>" <?php if($tys['id']==$vr_typesid): ?>selected<?php endif; ?> ><?php echo ($tys['name']); ?></option><?php endforeach; endif; ?>
                </select>
            </th>
            <th style="text-align: center;width: 6%;">
                型号
            </th>
            <th style="text-align: center;width: 6%;">
                规格
            </th>
            <th style="text-align: center;width: 5%;">
                <select name="style" onchange="changeSelect('style')">
                    <option value="">风格</option>
                    <?php if(is_array($style)): foreach($style as $key=>$st): ?><option value="<?php echo ($st['vr_style']); ?>" <?php if(($st['vr_style']==$vr_style) and ($vr_style != '')): ?>selected<?php endif; ?>><?php echo ($st['vr_style']); ?></option><?php endforeach; endif; ?>
                </select>
            </th>

            <th style="text-align: center;width: 5%;">
                <select name="color" onchange="changeSelect('color')">
                    <option value="">色系</option>
                    <?php if(is_array($color)): foreach($color as $key=>$co): ?><option value="<?php echo ($co['vr_color']); ?>" <?php if(($co['vr_color']==$vr_color) and ($vr_color != '')): ?>selected<?php endif; ?>><?php echo ($co['vr_color']); ?></option><?php endforeach; endif; ?>
                </select>
            </th>
            <th style="text-align: center;width: 5%;">
                <select name="vendor" onchange="changeSelect('vendor')">
                    <option value="">产商</option>
                    <?php if(is_array($vendor)): foreach($vendor as $key=>$ve): ?><option value="<?php echo ($ve['vr_vendor']); ?>" <?php if(($ve['vr_vendor']==$vr_vendor) and ($vr_vendor != '')): ?>selected<?php endif; ?>><?php echo ($ve['vr_vendor']); ?></option><?php endforeach; endif; ?>

                </select>
            </th>
            <th style="text-align: center;width: 5%;">
                <select name="space" onchange="changeSelect('space')">
                    <option value="">空间</option>
                    <?php if(is_array($space)): foreach($space as $key=>$sp): ?><option value="<?php echo ($sp['vr_space']); ?>" <?php if(($sp['vr_space']==$vr_space) and ($vr_space != '')): ?>selected<?php endif; ?> ><?php echo ($sp['vr_space']); ?></option><?php endforeach; endif; ?>
                </select>
            </th>

            <th style="text-align: center;width: 5%;">
                <select name="designer" onchange="changeSelect('designer')">
                    <option value="">设计师</option>
                    <?php if(is_array($designer)): foreach($designer as $key=>$de): ?><option value="<?php echo ($de['vr_designer']); ?>" <?php if(($de['vr_designer']==$vr_designer) and ($vr_designer != '')): ?>selected<?php endif; ?>><?php echo ($de['vr_designer']); ?></option><?php endforeach; endif; ?>

                </select>
            </th>
            <th style="text-align: center;width: 5%">
                楼盘
            </th>
            
            <th style="text-align: center;width: 8%">
                备注
            </th>

            <th style="text-align: center;width: 5%">
                时间
            </th>
            <?php if($level==1): ?><th style="text-align: center;width: 4%">
                <div class="layui-table-cell laytable-cell-1-vr_count" align="center" style="padding: 0px;">
                    <span>下载次数</span>
                    <span class="layui-table-sort layui-inline" style="margin: 0px;">
                        <i class="layui-edge layui-table-sort-asc" onclick="sorts('asc')" id="asc" style="left: 0px;"></i>
                        <i class="layui-edge layui-table-sort-desc" onclick="sorts('desc')"  id="desc" style="left: 0px;"></i>
                    </span>
                </div>
            </th><?php endif; ?>
            <th style="text-align: center;width: 5%">
                价格
            </th>
            <th style="text-align: center">
               操作
            </th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($data)): foreach($data as $key=>$value): ?><tr>
            <?php if($level==1): ?><td><?php echo ($value['company_name']); ?></td><?php endif; ?>
            <td><?php echo ($value['typename']); ?></td>
            <td>
                <?php if($value['vr_image']!=''): ?><img src="<?php echo ($value["vr_image"]); ?>" style="width: 100px;height: 100px;" lay-event="displayImg"><?php endif; ?>
            </td>
            <td><?php echo ($value['vr_name']); ?></td>


            <td>
                <?php echo ($value['typesname']); ?>
            </td>
            <td><?php echo ($value['vr_model']); ?></td>
            <td><?php echo ($value['vr_specification']); ?></td>
            <td><?php echo ($value['vr_style']); ?></td>
            <td><?php echo ($value['vr_color']); ?></td>
            <td><?php echo ($value['vr_vendor']); ?></td>
            <td><?php echo ($value['vr_space']); ?></td>
            <td><?php echo ($value['vr_designer']); ?></td>
            <td><?php echo ($value['vr_premises']); ?></td>
            <td><?php echo ($value['vr_remark']); ?></td>
            <td><?php echo ($value['vr_time']); ?></td>
            <?php if($level==1): ?><td><?php echo ($value['vr_count']); ?></td><?php endif; ?>
            <td><?php echo ($value['vr_price']); ?></td>
            <td>
                <?php if((in_array($value['vr_admin_id'],$levelss)) && ($level==2)): else: ?>
                <a title="编辑" lay-event="edit" href="javascript:;" onclick="x_admin_show('编辑模型','<?php echo U('Index/vrEdit');?>?id=<?php echo ($value['vr_id']); ?>',650)">
                    <i class="layui-icon">&#xe642;</i>
                </a>
                <a title="删除"  href="javascript:;"  lay-event="del" onclick="sc('<?php echo ($value['vr_id']); ?>')">
                    <i class="layui-icon">&#xe640;</i>
                </a><?php endif; ?>
                <a title="下载"   href="javascript:;"  lay-event="down" onclick="down('<?php echo ($value['vr_id']); ?>','<?php echo ($value['vr_folderurl']); ?>')">
                    <i class="layui-icon">&#xe655;</i>
                </a>
            </td>
        </tr><?php endforeach; endif; ?>
        </tbody>
    </table>


    <div id="demo7"></div>

<script>
    layui.use(['table','laypage'], function(){
        var table = layui.table;
        var laypage = layui.laypage;
        $ = layui.jquery;
        var form = layui.form
                ,layer = layui.layer;

        form.verify({
            pricemin: function(value, item){
                if(value.length>0) {
                    if (value < 0) {
                        return "价格不能小于0";
                    }
                }
            },
            pricemax: function(value, item){
                if(value.length>0) {
                    if (value < 0) {
                        return "价格不能小于0";
                    }
                    if ($("#min").val() >= value) {
                        return "价格不能小于等于最小值";
                    }
                }

            }
        });

        //完整功能
        laypage.render({
            elem: 'demo7'
            ,count: <?php echo ($count); ?>
            ,limit: <?php echo ($limit); ?>
            ,curr:<?php echo ($page); ?>
            ,layout: ['count', 'prev', 'page', 'next', 'limit', 'skip']
            ,jump: function(obj, first){
                if(!first){
                    window.location.href="<?php echo U('Index/vrList');?>?page="+obj.curr+'&limit='+obj.limit+'&company_id=<?php echo ($company_id); ?>&typeid=<?php echo ($vr_typeid); ?>&typesid=<?php echo ($vr_typesid); ?>&space=<?php echo ($vr_space); ?>&style=<?php echo ($vr_style); ?>&color=<?php echo ($vr_color); ?>&vendor=<?php echo ($vr_vendor); ?>&designer=<?php echo ($vr_designer); ?>&order=<?php echo ($order); ?>&pricemin=<?php echo ($pricemin); ?>&pricemax=<?php echo ($pricemax); ?>';//向URL中传递页数并显示


                }
            }
        });
        <!--搜索-->
    });

    function sc(id){
        layer.confirm('确认删除么', function (index) {
            if(id==''){
                layer.msg('id不能为空', {icon: 6, time: 1000});
            }
            $.post("<?php echo U('Index/vrDelete');?>", {'id': id}, function (res) {
                if (res.code == 200) {
                    layer.msg(res.info, {icon: 6, time: 1000}, function () {
                        layer.close(index);
                        //向服务端发送删除指令
                        window.location.reload();
                    });
                } else {
                    layer.alert(res.info, {icon: 5}, function () {
                        layer.closeAll();
                    })
                }
            });
        });
    }

    function changeSelect(lx) {
        var val = $("select[name='" + lx + "']").val();
        $("#" + lx + "").val(val);
        document.fileForm.submit();
    }

    function down(id,url) {
        if(url==''){
            layer.alert('没有可下载文件', {icon: 5}, function () {
                layer.closeAll();
            })
            return  false;
        }
        $.post("<?php echo U('public/vrCount');?>", {'id': id}, function (res) {
            if (res.code == 200) {
                window.location.href = url;
            } else {
                layer.alert(res.info, {icon: 5}, function () {
                    layer.closeAll();
                })
            }
        });
    }

    function sorts(type) {
//        if(type=='asc'){
//            console.log(1);
//            $("#asc").css('border-bottom-color','#000');
//            $("#desc").css('border-top-color','#b2b2b2');
//        }else{
//            $("#asc").css('border-bottom-color','#b2b2b2');
//            $("#desc").css('border-top-color','#000');
//        }
        $("#order").val(type);
        document.fileForm.submit();
    }

    var order;
    order=$('#order').val();
     $(function(){
        if(order!=''){
            if(order=='asc'){
                $("#asc").css('border-bottom-color','#000');
                $("#desc").css('border-top-color','#b2b2b2');
            }else{
                $("#asc").css('border-bottom-color','#b2b2b2');
                $("#desc").css('border-top-color','#000');
            }
        }
     })


    $('img').click(function(){
        var  ac = $(this)[0].src;
        $('#displayImg').attr('src', $(this)[0].src);
        var height = $("#displayImg").height();//拿的图片原来宽高，建议自定义宽高
        var width = $("#displayImg").width();
        var _w = document.documentElement.clientWidth;
        var _h =  document.documentElement.clientHeight;
        console.log(_h);
        if (width >= _w/2) {
            var blw = _w / 2 / width;
            width = _w / 2;
            height = height * blw;
        }
        if (height >= _h / 2) {
            var blh = _h / 2 / height;
            height = _h / 2;
            width = width * blh;
        }
        console.log(height);
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            area: [width + 'px', height + 'px'], //宽高
            content: "<img  src=" + ac + " width=" + width + " height=" + height + "/>"
        });
    })

</script>
</div>
</body>
</html>