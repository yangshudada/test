<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>沙盘列表</title>
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

<div class="x-body">
    <div class="x-body">
        <div class="layui-row">
            <div class="demoTable layui-col-md12 x-so">
                <input type="text" name="select"  placeholder="请输入楼盘" autocomplete="off" class="layui-input" id="premises">
                <input type="text" name="select"  placeholder="请输入房屋" autocomplete="off" class="layui-input" id="house">
                <input type="text" name="select"  placeholder="请输入单元" autocomplete="off" class="layui-input" id="unit">
                <input type="text" name="select"  placeholder="请输入房号" autocomplete="off" class="layui-input" id="number">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" data-type="reload"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('添加沙盘','<?php echo U('Sand/houseAdd');?>')"><i class="layui-icon"></i>添加沙盘</button>
        <button class="layui-btn" data-type="getCheckLength"><i class="layui-icon"></i>批量删除沙盘</button>
        <button class="layui-btn" onclick="x_admin_show('批量添加沙盘','<?php echo U('Sand/houseAddExcel');?>',500,450)"><i class="layui-icon"></i>批量添加沙盘</button>
        <button class="layui-btn" onclick="x_admin_show('批量添加详情','<?php echo U('Sand/vrAddExcel');?>',500,450)"><i class="layui-icon"></i>批量添加详情</button>

        <span class="x-right" style="line-height:40px">
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
                <i class="layui-icon" style="line-height:30px">&#x1002;</i>
            </a>
        </span>
    </xblock>
    <img src="" id="displayImg" style="display: none" alt="">
    <table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
    <!--<script type="text/html" id="image">-->
        <!--{{# if(d.whitePaper_image!=''){ }}-->
        <!--<img src="{{d.whitepaper_image}}" style="width: 100px;height: 100px;" lay-event="displayImg">-->
        <!--{{# } }}-->
    <!--</script>-->
    <script type="text/html" id="url">
        <a href="{{d.house_url}}" target="_blank">{{d.house_url}}</a>
    </script>
    <script type="text/html" id="barDemo">
        <a title="编辑"  href="javascript:;"  lay-event="edit">
            <i class="layui-icon">&#xe642;</i>
        </a>
        <a title="删除"  href="javascript:;"  lay-event="del">
            <i class="layui-icon">&#xe640;</i>
        </a>
        <a title="详情"  href="javascript:;"  lay-event="create">
            <i class="layui-icon">&#xe60a;</i>
        </a>
    </script>
    <!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
    <script>
        layui.use('table', function(){
            var table = layui.table;

            //方法级渲染
            table.render({
                elem: '#LAY_table_user'
                ,text: {
                    none: '暂无相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
                }
                ,url: "<?php echo U('Sand/houseList');?>"
                ,cols: [[
                    {type:'checkbox', align: 'center'}
                    ,{field:'house_id', title: 'ID',  align: 'center','sort':true}
                    ,{field:'house_premises',title: '楼盘',  align: 'center','sort':true}
                    ,{field:'house_house',title: '房屋',  align: 'center','sort':true}
                    ,{field:'house_unit',title: '单元',  align: 'center','sort':true}
                    ,{field:'house_number',title: '房号',  align: 'center','sort':true}
                    ,{field:'house_time',title: '添加时间',  align: 'center','sort':true}
                    ,{title: 'url',  align: 'center',templet: '#url'}
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
                    var premises = $('#premises');
                    var house = $('#house');
                    var unit = $('#unit');
                    var number = $('#number');
                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            premises: premises.val(),
                            house: house.val(),
                            unit: unit.val(),
                            number: number.val()
                        }
                    });
                }
                ,getCheckLength: function(){ //获取选中数目

                    var checkStatus = table.checkStatus('testReload')
                            ,data = checkStatus.data;
                    layer.confirm('选中了：'+ data.length + ' 个,确认删除么', function(index){
                        $.post("<?php echo U('Sand/houseAllDel');?>",{'all':data},function(res){
                            if (res.code == 200) {
                                layer.msg(res.info, {icon: 6, time: 1000}, function () {
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
                }
            };

            $('.layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            //监听工具条
            table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                console.log(data);
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

                if(layEvent === 'del'){ //删除
                    layer.confirm('确认删除么', function(index){
                        $.post("<?php echo U('Sand/houseDel');?>",{'id':data.house_id},function(res){
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
                } else if (layEvent === 'create') { //删除
                    x_admin_show('沙盘详情','<?php echo U('Sand/showHome');?>?id='+data.house_id,$(window).width(),$(window).height());
                }else if (layEvent === 'edit') { //删除
                    x_admin_show('修改沙盘','<?php echo U('Sand/houseEdit');?>?id='+data.house_id);
                }
            });

        });

    </script>
</div>
</body>
</html>