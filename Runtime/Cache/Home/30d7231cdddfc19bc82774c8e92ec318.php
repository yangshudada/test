<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>施工监管</title>
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
        <a href="<?php echo U('Test/showHome');?>?id=<?php echo ($id); ?>">五位一体详情</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#x1002;</i>
    </a>
</div>
<div class="x-body">
    <div class="page-content" style="left:0px">
        <div class="layui-tab  layui-tab-brief" lay-filter="test">
            <ul class="layui-tab-title">
                <li class="layui-this">设计</li>
                <li>采购</li>
                <li>造价</li>
                <li>施工</li>
                <li>运维</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show"><iframe src="<?php echo U('Test/designHome');?>?id=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showFiveD');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showWhiteBook');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showContract');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showPlanTime');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showMatPlan');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showProcessMore');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showMatCcept');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
                <!--<div class="layui-tab-item"><iframe src="<?php echo U('HouseToHome/showAcceptImage');?>?userid=<?php echo ($id); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe></div>-->
            </div>
        </div>
    </div>
</div>
<script>
    layui.use('element', function() {
        var element = layui.element;
        element.on('tab(test)', function(data){
            if(data.index==0){
                $('iframe').attr('src',"<?php echo U('Test/designHome');?>?id=<?php echo ($id); ?>");
            }else if(data.index==1){
                $('iframe').attr('src',"<?php echo U('Test/purchase_home');?>?id=<?php echo ($id); ?>");
            }else if(data.index==2){
                $('iframe').attr('src',"<?php echo U('Test/cost_home');?>?id=<?php echo ($id); ?>");
            }else if(data.index==3){
                $('iframe').attr('src',"<?php echo U('Test/constructHome');?>?id=<?php echo ($id); ?>");
            }else if(data.index==4){
                $('iframe').attr('src',"<?php echo U('Test/operationHome');?>?id=<?php echo ($id); ?>");
            }
            console.log(data.index); //得到当前Tab的所在下标
        });
    })
</script>
</body>
</html>