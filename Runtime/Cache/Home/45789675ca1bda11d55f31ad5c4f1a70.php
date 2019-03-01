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
        .layui-table-cell{
            height: auto !important;
        }
    </style>
</head>

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a>首页</a>
        <a href="<?php echo U('index/vrTypeList');?>">模型分类</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#x1002;</i>
    </a>
</div>
<div class="x-body">
    <div class="layui-row">
</div>
</div>
<xblock>
    <button class="layui-btn" onclick="x_admin_show('添加分类','vrTypeAdd.html',500,200)"><i class="layui-icon"></i>添加分类</button>
    <span class="x-right" style="line-height:40px"></span>
</xblock>
    <img src="" id="displayImg" style="display: none" alt="">
<table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
<script type="text/html" id="barDemo">
    <a title="编辑" lay-event="edit" href="javascript:;">
        <i class="layui-icon">&#xe642;</i>
    </a>
</script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use('table', function(){
        var table = layui.table;
        //方法级渲染
        table.render({
            elem: '#LAY_table_user'
            ,url: "<?php echo U('index/vrTypeList');?>"
            ,cols: [[
                {field:'id', title: 'ID',sort: true,align: 'center'}
                ,{field:'name',title: '名称', align: 'center'}
                ,{field:'create_time', title: '时间', sort: true, align: 'center',width:120}
                ,{title: '操作',  toolbar: '#barDemo',align: 'center',width:120}
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
                        vr_name: demoReload.val(),
                        company_name: demoReload1.val()
                    }
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //监听工具条
        table.on('tool(test)', function(obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

            if (layEvent === 'detail') { //查看
                //do somehing
            } else if (layEvent === 'del') { //删除
                layer.confirm('确认删除么', function (index) {
                    $.post("<?php echo U('Index/vrTypeDelete');?>", {'id': data.id}, function (res) {
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
            } else if (layEvent === 'edit') { //编辑
                x_admin_show('编辑分类', "<?php echo U('index/vrTypeEdit');?>?id=" + data.id,500,200);
            }
        });

    });

</script>
</div>
</body>
</html>