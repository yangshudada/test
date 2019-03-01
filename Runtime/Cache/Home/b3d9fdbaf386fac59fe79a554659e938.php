<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>全景后台管理</title>
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
        .layui-table-cell{
            height: auto !important;
        }
    </style>
</head>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a href="<?php echo U('index/roleList');?>">角色列表</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#x1002;</i>
    </a>
</div>
<div class="x-body">
    <div class="layui-row">
        <div class="demoTable layui-col-md12 x-so">
            <input type="text" name="select"  placeholder="请输入角色名称" autocomplete="off" class="layui-input" id="demoReload">
            <button class="layui-btn"  lay-submit="" lay-filter="sreach" data-type="reload"><i class="layui-icon">&#xe615;</i></button>
    </div>
</div>
<xblock>
    <button class="layui-btn" onclick="x_admin_show('添加角色','roleAdd.html')"><i class="layui-icon"></i>添加角色</button>
    <span class="x-right" style="line-height:40px"></span>
</xblock>
<table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
<script type="text/html" id="status">
    {{# if(d.status==1){ }}
    <span class="layui-btn layui-btn-normal layui-btn-sm">已启用</span>
    {{# }else if(d.status==5){ }}
    <span class="layui-btn layui-btn-danger layui-btn-sm">停&nbsp;&nbsp;&nbsp;用</span>
    {{# } }}
</script>
<script type="text/html" id="barDemo">
    {{# if(d.status==1){ }}
    <a lay-event="use" href="javascript:;"  title="停用">
        <i class="layui-icon">&#xe601;</i>
    </a>
    {{# }else if(d.status==5){ }}
    <a lay-event="use" href="javascript:;"  title="启用">
        <i class="layui-icon">&#xe62f;</i>
    </a>
    {{# } }}
    <a title="编辑" lay-event="edit" href="javascript:;">
        <i class="layui-icon">&#xe642;</i>
    </a>
    <a title="删除"  href="javascript:;"  lay-event="del">
        <i class="layui-icon">&#xe640;</i>
    </a>
</script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use('table', function(){
        var table = layui.table;
        //方法级渲染
        table.render({
            elem: '#LAY_table_user'
            ,url: "<?php echo U('auth/roleList');?>"
            ,cols: [[
                {field:'id', title: 'ID',sort: true,align: 'center'}
                ,{field:'title', title: '角色名',  align: 'center'}
                ,{field:'remark',  title: '描述',  align: 'center'}
                ,{title: '状态',  align: 'center', templet: '#status'}
                ,{title: '操作',  toolbar: '#barDemo',align: 'center'}
            ]]
            ,id: 'testReload'
            ,page: true
            ,height: 600
            ,cellMinWidth:60
            ,limits:[10,20,30]
            ,limit:10
    });

        <!--搜索-->
        var $ = layui.$, active = {
            reload: function(){
                var demoReload = $('#demoReload');
                var demoReload1 = $('#demoReload1');
                //执行重载
                table.reload('testReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        name: demoReload.val()
                    }
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //监听工具条
        table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            console.log(data);
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

            if(layEvent === 'use'){ //启用
                var show = data.status;
                var tip,xiao;
                if(show==1){
                    tip = '确认停用么';
                    xiao = 5;
                }else{
                    tip = '确认启用么';
                    xiao = 6;
                }

                layer.confirm(tip, function(index){
                    $.post("<?php echo U('auth/roleshow');?>",{'id':data.id,'show':data.status},function(res){
                        if(res.code==200) {
                            layer.msg(res.info, {icon: xiao});
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                            layer.close(index);
                            //向服务端发送删除指令
                            location.reload();
                        }else{
                            layer.msg(res.info, {icon: 5});
                            layer.closeAll();
                        }
                    });
                });
                //do somehing
            } else if(layEvent === 'del'){ //删除
                layer.confirm('确认删除么', function(index){
                    $.post("<?php echo U('auth/roleDelete');?>",{'id':data.id},function(res){
                        if (res.code == 200) {
                            layer.msg(res.info, {icon: 6, time: 1000}, function () {
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                layer.close(index);
                                //向服务端发送删除指令
                                location.reload();
                            });
                        } else {
                            layer.alert(res.info, {icon: 5}, function () {
                                layer.closeAll();
                            })
                        }
                    });
                });
            } else if(layEvent === 'edit'){ //编辑
                x_admin_show('编辑管理员',"<?php echo U('auth/roleEdit');?>?id="+data.id);
            }
        });

    });

</script>
</div>
















<!--&lt;!&ndash;content S&ndash;&gt;-->
<!--<div class="super-content RightMain" id="RightMain">-->

    <!--&lt;!&ndash;header&ndash;&gt;-->
    <!--<div class="superCtab">-->
        <!--<div class="ctab-Main">-->
            <!--<div class="ctab-Main-title">-->
                <!--<ul class="clearfix">-->
                    <!--<li class="cur"><a href="http://5d.8lei.cn/baleijia/vr.php/Home/Index/vrList.html?id=<?php echo ($id); ?>">全景列表</a></li>-->
                    <!--<?php if($admin == 1): ?>-->
                        <!--<li><a href="http://5d.8lei.cn/baleijia/vr.php/Home/Index/admin.html?id=<?php echo ($id); ?>">权限管理</a></li>-->
                        <!--<?php else: ?>-->
                    <!--<?php endif; ?>-->
                <!--</ul>-->
            <!--</div>-->

            <!--<div class="ctab-Mian-cont">-->
                <!--<div>-->
                    <!--<div class="Mian-cont-btn clearfix">-->
                        <!--<div class="operateBtn">-->
                            <!--<?php if($add == 1): ?>-->
                                <!--<a href="javascript:;" class="greenbtn add sp-add">添加全景</a>-->
                                <!--<?php else: ?>-->
                            <!--<?php endif; ?>-->
                        <!--</div>-->
                        <!--<div class="searchBar">-->
                            <!--<form action="" method="POST" enctype="multipart/form-data" target="_self">-->
                                <!--<input type="text" id="" name="select" value="" class="form-control srhTxt" placeholder="输入全景名称">-->
                                <!--<input type="submit" class="srhBtn" value="">-->
                            <!--</form>-->
                        <!--</div>-->
                    <!--</div>-->

                    <!--<div class="Mian-cont-wrap">-->
                        <!--<div class="defaultTab-T">-->
                            <!--<table border="0" cellspacing="0" cellpadding="0" class="defaultTable">-->
                                <!--<tbody>-->
                                <!--<tr>-->
                                    <!--<th class="t_1">ID</th>-->
                                    <!--<th class="t_1">图片</th>-->
                                    <!--<th class="t_1">名称</th>-->
                                    <!--<th class="t_1">-->
                                        <!--<select class="select" name="type" id="typeSelect"-->
                                                <!--onchange="window.location=('http://5d.8lei.cn/baleijia/vr.php/Home/Index/vrList?id=<?php echo ($id); ?>&type=' + this.value)">-->
                                            <!--<option value="" selected="selected">类型</option>-->
                                            <!--<option value="毛坯" >毛坯</option>-->
                                            <!--<option value="水电" >水电</option>-->
                                            <!--<option value="验房" >验房</option>-->
                                            <!--<option value="量房" >量房</option>-->
                                            <!--<option value="设计" >设计</option>-->
                                            <!--<option value="追溯" >追溯</option>-->
                                            <!--<option value="对比" >对比</option>-->
                                            <!--<option value="精装" >精装</option>-->
                                            <!--<option value="样板间" >样板间</option>-->
                                            <!--<option value="实景" >实景</option>-->
                                            <!--<option value="智能全景" >智能全景</option>-->
                                            <!--&lt;!&ndash;<?php if(is_array($typeArr)): foreach($typeArr as $key=>$Data): ?>&ndash;&gt;-->
                                                <!--&lt;!&ndash;<option value="<?php echo ($Data); ?>"><?php echo ($Data); ?></option>&ndash;&gt;-->
                                            <!--&lt;!&ndash;<?php endforeach; endif; ?>&ndash;&gt;-->
                                        <!--</select>-->
                                    <!--</th>-->
                                    <!--<th class="t_1">-->
                                        <!--<select class="select" name="style" id="styleSelect"-->
                                                <!--onchange="window.location=('http://5d.8lei.cn/baleijia/vr.php/Home/Index/vrList?id=<?php echo ($id); ?>&style=' + this.value)">-->
                                            <!--<option value="" selected="selected">  风格</option>-->
                                            <!--<?php if(is_array($styleArr)): foreach($styleArr as $key=>$Data): ?>-->
                                                <!--<option value="<?php echo ($Data); ?>"><?php echo ($Data); ?></option>-->
                                            <!--<?php endforeach; endif; ?>-->
                                        <!--</select>-->
                                    <!--</th>-->
                                    <!--<th class="t_1">面积</th>-->
                                    <!--<th class="t_1">房屋类型</th>-->
                                    <!--<th class="t_1">-->
                                        <!--<select class="select" name="premises" id="premisesSelect"-->
                                                <!--onchange="window.location=('http://5d.8lei.cn/baleijia/vr.php/Home/Index/vrList?id=<?php echo ($id); ?>&premises=' + this.value)">-->
                                            <!--<option value="" selected="selected">楼盘</option>-->
                                            <!--<?php if(is_array($premisesArr)): foreach($premisesArr as $key=>$Data): ?>-->
                                                <!--<option value="<?php echo ($Data); ?>"><?php echo ($Data); ?></option>-->
                                            <!--<?php endforeach; endif; ?>-->
                                        <!--</select>-->
                                    <!--</th>-->
                                    <!--<th class="t_1">装企</th>-->
                                    <!--<th class="t_1">材料商</th>-->
                                    <!--<th class="t_1">空间</th>-->
                                    <!--<th class="t_1">访问量</th>-->
                                    <!--<th class="t_1">点赞量</th>-->
                                    <!--<th class="t_1">时间</th>-->
                                    <!--<th class="t_1">是否公开</th>-->
                                    <!--<th class="t_1">操作</th>-->
                                <!--</tr>-->
                                <!--</tbody>-->
                            <!--</table>-->
                        <!--</div>-->
                        <!--<table border="0" cellspacing="0" cellpadding="0" class="defaultTable defaultTable2">-->
                            <!--<tbody>-->
                                <!--<?php if(is_array($info)): $k = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($k % 2 );++$k;?>-->
                                    <!--<tr>-->
                                        <!--<td class="t_1"><?php echo ($k); ?></td>-->
                                        <!--<td class="t_1"><a href="<?php echo ($data["vr_url"]); ?>" target="_blank"><img class="imgs" src="<?php echo ($data["vr_image"]); ?>"></a></td>-->
                                        <!--<td class="t_1"><a href="<?php echo ($data["vr_url"]); ?>" target="_blank"><?php echo ($data["vr_name"]); ?></a></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_type"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_style"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_area"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_house_type"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_premises"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_company"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_material"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_space"]); ?></td>-->

                                        <!--<td class="t_1"><?php echo ($data["vr_number"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_zan"]); ?></td>-->
                                        <!--<td class="t_1"><?php echo ($data["vr_time"]); ?></td>-->
                                        <!--<td class="t_1">-->
                                            <!--<?php if($data["vr_show"] == 1): ?>-->
                                                <!--公开-->
                                                <!--<?php else: ?>-->
                                                <!--不公开-->
                                            <!--<?php endif; ?>-->
                                        <!--</td>-->
                                        <!--<td class="t_1">-->
                                            <!--<div class="btn">-->
                                                <!--<?php if($change == 1): ?>-->
                                                    <!--<a href="http://5d.8lei.cn/baleijia/vr.php/Home/Index/vr_edit?id=<?php echo ($data["vr_id"]); ?>" class="modify sp-modify" id="sp-modify">编辑</a>-->
                                                    <!--<?php else: ?>-->
                                                <!--<?php endif; ?>-->
                                                <!--<?php if($delete == 1): ?>-->
                                                    <!--<a href="http://5d.8lei.cn/baleijia/vr.php/Home/Index/vr_delete?id=<?php echo ($data["vr_id"]); ?>" onclick="return confirm('确定删除?');" class="delete del" id="del">删除</a>-->
                                                    <!--<?php else: ?>-->
                                                <!--<?php endif; ?>-->
                                            <!--</div>-->
                                        <!--</td>-->
                                    <!--</tr>-->
                                <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                            <!--</tbody>-->
                        <!--</table>-->
                    <!--</div>-->

                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
        <!--&lt;!&ndash;main&ndash;&gt;-->
    <!--</div>-->

    <!--&lt;!&ndash;content E&ndash;&gt;-->
    <!--<div class="layuiBg"></div>&lt;!&ndash;公共遮罩&ndash;&gt;-->
    <!--&lt;!&ndash;点击添加全景弹出&ndash;&gt;-->
    <!--<div class="addFeileibox layuiBox">-->
        <!--<div class="layer-title clearfix"><h2>添加全景</h2><span class="layerClose"></span></div>-->
        <!--<div class="layer-content">-->
            <!--<div class="cell">-->
                <!--<form action="http://5d.8lei.cn/baleijia/vr.php/Home/Index/add_vr.html" method="POST" enctype="multipart/form-data" target="_self">-->
                    <!--<table class="cell_center">-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">名 &emsp;称</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="name"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">图 &emsp;片</td>-->
                            <!--<td><input type="file" name="image"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">链 &emsp;接</td>-->
                            <!--<td><input type="text" class="cell_input" name="url"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">类 &emsp;型</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="type"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">风 &emsp;格</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="style"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">面 &emsp;积</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="area"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">房屋类型</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="house_type"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">楼 &emsp;盘</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="premises"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">公 &emsp;司</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="company"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">材料商</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="material"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">空 &emsp;间</td>-->
                            <!--<td><input type="text" class="cell_input" value="" name="space"></td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<td class="td_left">公&emsp;开</td>-->
                            <!--<td>-->
                                <!--<input style="width: 20px; height: 20px" type="checkbox" checked="checked" name="show" value="1">-->
                            <!--</td>-->
                        <!--</tr>-->
                        <!--<tr class="cell_tr">-->
                            <!--<th class="aFlBtn" colspan="2" ><input type="submit" value="保存" class="saveBtn"></th>-->
                        <!--</tr>-->
                    <!--</table>-->
                <!--</form>-->
            <!--</div>-->

        <!--</div>-->

    <!--</div>-->

<!--</div>-->
<!--<div style="width: 500px; margin: 0 auto">-->
    <!--<?php echo ($page); ?>-->
<!--</div>-->
</body>
</html>