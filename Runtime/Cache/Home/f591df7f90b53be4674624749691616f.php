<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>采购数据显示</title>
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
    <div class="x-body">
        <div class="layui-row">
            <div class="demoTable layui-col-md12 x-so">
                <input type="text" name="select"  placeholder="请输入空间" autocomplete="off" class="layui-input" id="demoReload">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" data-type="reload"><i class="layui-icon">&#xe615;</i></button>
            </div>
        </div>
    </div>
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('添加数据','<?php echo U('Test/up_purchase');?>?id=<?php echo ($id); ?>',650,600)"><i class="layui-icon"></i>添加数据</button>
        <button class="layui-btn" onclick="x_admin_show('采购数据批量上传','/company/admin.php/Home/Community/excel_purchase')"><i class="layui-icon"></i>采购数据批量上传</button>
        <span class="x-right" style="line-height:40px">
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
                <i class="layui-icon" style="line-height:30px">&#x1002;</i>
            </a>
        </span>
    </xblock>
    <img src="" id="displayImg" style="display: none;width: 100%;height: 100%;" alt="">
    <table class="layui-hide" id="LAY_table_user" lay-filter="test"></table>
    <script type="text/html" id="image">
        {{# if(d.purchase_image_jpg){ }}
        <img src="{{d.purchase_image_jpg}}" style="width: 100px;height: 100px;" lay-event="displayImg">
        {{# }else if(d.purchase_image){ }}
        <img src="{{d.purchase_image}}" style="width: 100px;height: 100px;" lay-event="displayImg">
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
                ,url: "<?php echo U('Test/purchase_home');?>?id=<?php echo ($id); ?>"
                ,cols: [[
                    {field:'purchase_id', title: 'ID',  align: 'center','sort':true}
                    ,{field:'purchase_name',title: '名称',  align: 'center'}
                    ,{field:'purchase_space',title: '空间',  align: 'center'}
                    ,{field:'purchase_size',title: '尺寸',  align: 'center'}
                    ,{field:'purchase_brand',title: '品牌',  align: 'center'}
                    ,{field:'purchase_material',title: '材质',  align: 'center'}
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


            <!--搜索-->
            var $ = layui.$, active = {
                reload: function(){
                    var demoReload = $('#demoReload');
                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            space: demoReload.val()
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

                if(layEvent === 'del'){ //删除
                    layer.confirm('确认删除么', function(index){
                        $.post("<?php echo U('Test/purchase_delete');?>",{'id':data.purchase_id},function(res){
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
                    $('#displayImg').attr('src', data.purchase_image);
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
                        content: "<img src=" + data.purchase_image + " width=" + width + " height=" + height + "/>"
                    });
                    if(!data.purchase_image_jpg){
                        $.post("<?php echo U('Public/displayImg');?>",{'image':data.purchase_image,'mysql':'purchase','id':data.purchase_id},function(res){
                            console.log(res.info);
                        });
                    }
                }else if (layEvent === 'edit') { //删除
                    x_admin_show('编辑采购','<?php echo U('Test/purchase_detail');?>?id='+data.purchase_id,650,600);
                }
            });

        });

    </script>
</div>
</body>
</html>