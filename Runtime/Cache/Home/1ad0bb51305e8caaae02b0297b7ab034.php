<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>施工合同</title>
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
        <button class="layui-btn" onclick="x_admin_show('添加详情','<?php echo U('WhitePaper/paperDetailAdd');?>',650,550)"><i class="layui-icon"></i>添加详情</button>
        <span class="x-right" style="line-height:40px">
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
                <i class="layui-icon" style="line-height:30px">&#x1002;</i>
            </a>
        </span>
    </xblock>
    <img src="" id="displayImg" style="display: none;width:100%;height:100%;" alt="">
    <table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
    <script type="text/html" id="image">
        {{# if(d.whitepaper_wimage_jpg){ }}
        <img src="{{d.whitepaper_wimage_jpg}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# }else if(d.whitepaper_wimage){ }}
        <img src="{{d.whitepaper_wimage}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# } }}
    </script>
    <script type="text/html" id="barDemo">
        <a title="编辑"  href="javascript:;"  lay-event="edit">
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
                ,text: {
                    none: '暂无相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
                }
                ,url: "<?php echo U('WhitePaper/paperDetail');?>"
                ,cols: [[
                    {field:'whitepaper_did', title: 'ID',  align: 'center','sort':true}
                    ,{field:'whitepaper_building',title: '楼盘',  align: 'center'}
                    ,{title: '图片',  align: 'center',templet: '#image',width:130,style:'height:50px;'}
                    ,{field:'whitepaper_style',title: '分类',  align: 'center'}
                    ,{field:'whitepaper_dname',title: '品名',  align: 'center'}
                    ,{field:'whitepaper_size',title: '规格',  align: 'center'}
                    ,{field:'whitepaper_num',title: '数量',  align: 'center'}
                    ,{field:'whitepaper_brand',title: '品牌',  align: 'center'}
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

                if(layEvent === 'del'){ //删除
                    layer.confirm('确认删除么', function(index){
                        $.post("<?php echo U('WhitePaper/paperDetailDel');?>",{'id':data.whitepaper_did},function(res){
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
                } else if (layEvent === 'displayImg') { //删除
                    $('#displayImg').attr('src', data.whitepaper_wimage);
                    var height = $("#displayImg").height();//拿的图片原来宽高，建议自定义宽高
                    var width = $("#displayImg").width();
                    var _w = parseInt($(window).width());
                    var _h = parseInt($(window).height());
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
                        content: "<img src=" + data.whitepaper_wimage + " width=" + width + " height=" + height + "/>"
                    });
                    if(!data.whitepaper_wimage_jpg){
                        $.post("<?php echo U('Public/displayImg');?>",{'image':data.whitepaper_wimage,'mysql':'whitepaperDetail','id':data.whitepaper_did},function(res){
                            console.log(res.info);
                        });
                    }
                }else if (layEvent === 'edit') { //删除
                    x_admin_show('编辑白皮书','<?php echo U('WhitePaper/paperDetailEdit');?>?id='+data.whitepaper_did);
                }
            });

        });

    </script>
</div>
</body>
</html>