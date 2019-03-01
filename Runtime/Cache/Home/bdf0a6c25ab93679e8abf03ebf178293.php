<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>运维页面</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/company/Public/css/font.css">
    <link rel="stylesheet" href="/company/Public/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/company/Public/lib/layui/layui.js" charset="utf-8"></script>
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
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('添加数据','<?php echo U('Test/operationAdd');?>?id=<?php echo ($id); ?>',650,500)"><i class="layui-icon"></i>添加数据</button>
        <span class="x-right" style="line-height:40px">
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
                <i class="layui-icon" style="line-height:30px">&#x1002;</i>
            </a>
        </span>
    </xblock>
    <img src="" id="displayImg" style="display: none;width: 100%;height: 100%;" alt="">
    <table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
    <script type="text/html" id="url">
        {{# if(d.operation_url!=''){ }}
        <a href="{{d.operation_url}}" target="_blank">{{d.operation_url}}</a>
        {{# } }}
    </script>
    <script type="text/html" id="image">
        {{# if(d.operation_image_jpg){ }}
        <img src="{{d.operation_image_jpg}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# }else if(d.operation_image){ }}
        <img src="{{d.operation_image}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# } }}
    </script>
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
                ,text: {
                    none: '暂无相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
                }
                ,url: "<?php echo U('Test/operationHome');?>?id=<?php echo ($id); ?>"
                ,cols: [[
                    {field:'operation_name', title: '名称',  align: 'center','sort':true}
                    ,{title: '链接',  align: 'center',templet: '#url'}
                    ,{title: '图片',  align: 'center',templet: '#image',width:130,style:'height:50px;'}
                    ,{title: '操作',  toolbar: '#barDemo',align: 'center'}
                ]]
                ,id: 'testReload'
                ,page: true
                ,height: 600
                ,cellMinWidth:60
                ,limits:[10,20,30]
                ,limit:10
            });

            //监听工具条
            table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                console.log(data);
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

                if(layEvent === 'edit'){ //编辑
                    x_admin_show('运维编辑',"<?php echo U('Test/editOperation');?>?id="+data.operation_id,650,550);
                } else if (layEvent === 'displayImg') { //删除
                    $('#displayImg').attr('src', data.operation_image);
                    var height = $("#displayImg").height();//拿的图片原来宽高，建议自定义宽高
                    var width = $("#displayImg").width();
                    var _w = parseInt($(window).width());
                    var _h = parseInt($(window).height());
                    if (width >= _w) {
                        var blw = _w / 2 / width;
                        width = _w / 2;
                        height = height * blw;
                        if (height >= _h / 2) {
                            var blh = _h / 2 / height;
                            height = _h / 2;
                            width = width * blh;
                        }
                    }
                    layer.open({
                        type: 1,
                        title: false,
                        closeBtn: 0,
                        shadeClose: true,
                        area: [width + 'px', height + 'px'], //宽高
                        content: "<img alt=" + data.operation_name + " title=" + data.operation_name + " src=" + data.operation_image + " width=" + width + " height=" + height + "/>"
                    });
                    if(!data.operation_image_jpg){
                        $.post("<?php echo U('Public/displayImg');?>",{'image':data.operation_image,'mysql':'operation','id':data.operation_id},function(res){
                            console.log(res.info);
                        });
                    }
                }
            });

        });

    </script>
</div>
</body>
</html>