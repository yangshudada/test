<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>五位一体</title>
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
<div class="x-nav">
      <span class="layui-breadcrumb" lay-separator="|">
        <a>首页</a>
        <a href="<?php echo U('test/publicFive');?>">五位一体</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#x1002;</i>
    </a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so layui-form-pane demoTable">
            <input type="text" name="name"  placeholder="请输入楼盘或户型" autocomplete="off" class="layui-input" id="name">
            <div class="layui-btn"  lay-submit="" lay-filter="sreach" data-type="reload"><i class="layui-icon">&#xe615;</i></div>
        </form>
    </div>
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('五位一体添加','<?php echo U('test/publicAdd');?>',650,550)"><i class="layui-icon"></i>添加数据</button>
        <span class="x-right" style="line-height:40px"></span>
    </xblock>
    <img src="" id="displayImg" style="display: none;width:100%;height:100%;">
    <table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
    <script type="text/html" id="image">
        {{# if(d.public_image_jpg){ }}
        <img src="{{d.public_image_jpg}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# }else if(d.public_image){ }}
        <img src="{{d.public_image}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# } }}
    </script>
    <script type="text/html" id="barDemo">
        <a title="编辑" lay-event="edit" href="javascript:;">
            <i class="layui-icon">&#xe642;</i>
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
                ,url: "<?php echo U('test/publicFive');?>"
                ,cols: [[
                    {field:'public_key', title: '楼盘',  align: 'center','sort':true}
                    ,{field:'public_style',  title: '户型',  align: 'center'}
                    ,{field:'public_build',  title: '房号',  align: 'center'}
                    ,{field:'public_room',  title: '风格',  align: 'center'}
                    ,{title: '图片',  align: 'center',templet: '#image'}
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
                    var name = $('#name').val();
                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            key: name
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

                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

                if (layEvent === 'create') { //删除
                    x_admin_show('五位一体详情','<?php echo U('Test/showHome');?>?id='+data.public_id,$(window).width(),$(window).height());
                }else if(layEvent === 'edit'){ //编辑
                    x_admin_show('五位一体编辑',"<?php echo U('Test/publicEdit');?>?id="+data.public_id,650,550);
                }else if (layEvent === 'displayImg') { //图片展示
                    $('#displayImg').attr('src',data.public_image);
                    var height = $("#displayImg").height();//拿的图片原来宽高，建议自定义宽高
                    var width = $("#displayImg").width();
                    var _w = document.documentElement.clientWidth;
                    var _h =  document.documentElement.clientHeight;
                    if(width>=_w){
                        var blw=_w/2/width;
                        width = _w/2;
                        height = height*blw;
                    }
                    if(height>=_h/2){
                        var blh=_h/2/height;
                        height = _h/2;
                        width = width*blh;
                    }
                    layer.open({
                        type: 1,
                        title: false,
                        closeBtn: 0,
                        shadeClose: true,
                        area: [width + 'px', height + 'px'], //宽高
                        content: "<img alt=" + data.public_key + " title=" + data.public_key + " src=" + data.public_image + " width=" + width + " height=" + height + " />"
                    });
                    if(!data.public_image_jpg){
                        $.post("<?php echo U('Public/displayImg');?>",{'image':data.public_image,'mysql':'public','id':data.public_id},function(res){
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