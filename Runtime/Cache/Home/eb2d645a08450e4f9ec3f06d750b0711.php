<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>楼盘公告</title>
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
        <a href="<?php echo U('House/showBuildNotice');?>">楼盘公告</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#x1002;</i>
    </a>
</div>
<div class="x-body">
    <blockquote class="layui-elem-quote">
        <strong>说明：</strong>
        楼盘为空的是公共社区
    </blockquote>
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('发布楼盘公告','<?php echo U('House/UEditor');?>',750)"><i class="layui-icon"></i>发布楼盘公告</button>
        <span class="x-right" style="line-height:40px"></span>
    </xblock>
    <img src="" id="displayImg" style="display: none" alt="">
    <table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
    <script type="text/html" id="image">
        {{# if(d.notice_image!=''){ }}
        <img src="{{d.notice_image}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# } }}
    </script>
    <script type="text/html" id="tiao">
        {{# if(d.notice_url!=''){ }}
        <a href="{{d.notice_url}}" target="_blank">{{d.notice_url}}</a>
        {{# } }}
    </script>
    <!--<script type="text/html" id="barDemo">-->

    <!--<a title="删除"  href="javascript:;"  lay-event="del">-->
    <!--<i class="layui-icon">&#xe640;</i>-->
    <!--</a>-->
    <!--</script>-->
    <!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
    <script>
        layui.use('table', function(){
            var table = layui.table;
            //方法级渲染
            table.render({
                elem: '#LAY_table_user'
                ,url: "<?php echo U('House/showBuildNotice');?>"
                ,text: {
                    none: '暂无相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
                }
                ,cols: [[
                    {field:'notice_id', title: '编号',  align: 'center'}
                    ,{field:'notice_premises',  title: '楼盘',  align: 'center'}
                    ,{ title: '图片',  align: 'center',templet: '#image',width:130,style:'height:50px;'}
                    ,{field:'notice_title',  title: '标题',  align: 'center'}
                    ,{field:'notice_date',  title: '时间',  align: 'center'}
                    ,{field:'notice_promulgator',  title: '作者',  align: 'center'}
                    ,{title: '链接',  align: 'center',templet: '#tiao'}

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
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）

                if (layEvent === 'displayImg') { //点击图片
                    $('#displayImg').attr('src', data.notice_image);
                    var height = $("#displayImg").height();//拿的图片原来宽高，建议自定义宽高
                    var width = $("#displayImg").width();
                    var _w = document.documentElement.clientWidth;
                    var _h =  document.documentElement.clientHeight;
                    if (width >= _w) {
                        var blw = _w / 2 / width;
                        width = _w / 2;
                        height = height * blw;
                    }
                    if (height >= _h / 2) {
                        var blh = _h / 2 / height;
                        height = _h / 2;
                        width = width * blh;
                    }
                    layer.open({
                        type: 1,
                        title: false,
                        closeBtn: 0,
                        shadeClose: true,
                        area: [width + 'px', height + 'px'], //宽高
                        content: "<img alt=" + data.notice_title + " title=" + data.notice_title + " src=" + data.notice_image + " width=" + width + " height=" + height + "/>"
                    });
                }
            });

        });

    </script>
</div>
</body>
</html>