<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html class=" js csstransforms3d">
<head>
    <meta charset="utf-8">
    <title>巴雷家内部系统</title>
    <meta name="keywords" content="后台管理,管理系统" />
    <meta name="description" content="巴雷家模型后台管理系统" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/Public/css/font.css">
    <link rel="stylesheet" href="/Public/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/Public/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/Public/js/xadmin.js"></script>
</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="<?php echo U('Index/index');?>">巴雷家内部管理系统</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"><?php echo ($name); ?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a onclick="x_admin_show('修改密码','<?php echo U('public/change_psw');?>',500,300)">修改密码</a></dd>
                <dd><a id="out">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index">前台首页</li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <!--<?php if(($data[0]==1)): ?>-->
            <!--<li>-->
                <!--<a href="javascript:;">-->
                    <!--<i class="layui-icon">&#xe841;</i>-->
                    <!--<cite>模型管理</cite>-->
                    <!--<i class="iconfont nav_right">&#xe697;</i>-->
                <!--</a>-->
                <!--<ul class="sub-menu">-->
                    <!--<?php if($data[0]==1): ?>-->
                    <!--<li>-->
                        <!--<a _href="<?php echo U('index/vrList');?>">-->
                            <!--<i class="iconfont">&#xe6a7;</i>-->
                            <!--<cite>生成全景</cite>-->
                        <!--</a>-->
                    <!--</li >-->
                    <!--<?php endif; ?>-->
                    <!--&lt;!&ndash;<?php if($data[0]==1): ?>&ndash;&gt;-->
                    <!--&lt;!&ndash;<li>&ndash;&gt;-->
                        <!--&lt;!&ndash;<a _href="<?php echo U('index/vrList');?>">&ndash;&gt;-->
                            <!--&lt;!&ndash;<i class="iconfont">&#xe6a7;</i>&ndash;&gt;-->
                            <!--&lt;!&ndash;<cite>模型列表</cite>&ndash;&gt;-->
                        <!--&lt;!&ndash;</a>&ndash;&gt;-->
                    <!--&lt;!&ndash;</li >&ndash;&gt;-->
                    <!--&lt;!&ndash;<?php endif; ?>&ndash;&gt;-->
                <!--</ul>-->
            <!--</li>-->
            <!--<?php endif; ?>-->
            <?php if(($data[1]==1) or ($data[2]==1) or ($data[3]==1)): ?><li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>管理员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <?php if($data[1]==1): ?><li>
                        <a _href="<?php echo U('auth/adminList');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员列表</cite>
                        </a>
                    </li ><?php endif; ?>
                    <?php if($data[2]==1): ?><li>
                        <a _href="<?php echo U('auth/roleList');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>角色管理</cite>
                        </a>
                    </li ><?php endif; ?>
                    <?php if($data[3]==1): ?><li>
                        <a _href="<?php echo U('auth/ruleList');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限管理</cite>
                        </a>
                    </li ><?php endif; ?>
                </ul>
            </li><?php endif; ?>
            <?php if($data[4]==1): ?><li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>日志管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo U('log/logsList');?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>日志列表</cite>
                        </a>
                    </li >
                </ul>
            </li><?php endif; ?>
            <?php if($data[15]==1): ?><li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe674;</i>
                        <cite>五位一体</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <?php if($data[15]==1): ?><li class="">
                                <a _href="<?php echo U('test/publicFive');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>详情</cite>
                                </a>
                            </li><?php endif; ?>
                    </ul>
                </li><?php endif; ?>
            <?php if(($data[23]==1) or ($data[24]==1) or ($data[25]==1)): ?><li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe705;</i>
                        <cite>白皮书</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <?php if($data[23]==1): ?><li class="">
                                <a _href="<?php echo U('WhitePaper/paperStyle');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>白皮书分类</cite>
                                </a>
                            </li><?php endif; ?>
                        <?php if($data[24]==1): ?><li class="">
                                <a _href="<?php echo U('WhitePaper/paperDetail');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>白皮书详情</cite>
                                </a>
                            </li><?php endif; ?>
                        <?php if($data[25]==1): ?><li class="">
                                <a _href="<?php echo U('WhitePaper/paperList');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>白皮书列表</cite>
                                </a>
                            </li><?php endif; ?>
                    </ul>
                </li><?php endif; ?>
            <?php if(($data[26]==1)): ?><li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe656;</i>
                        <cite>沙盘模型</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <?php if($data[26]==1): ?><li class="">
                                <a _href="<?php echo U('Sand/houseList');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>沙盘列表</cite>
                                </a>
                            </li><?php endif; ?>
                    </ul>
                </li><?php endif; ?>

            <?php if(($data[28]==1) or ($data[29]==1)): ?><li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe674;</i>
                        <cite>合同管理</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <?php if($data[28]==1): ?><li class="">
                                <a _href="<?php echo U('Bargain/paperStyle');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>合同分类</cite>
                                </a>
                            </li><?php endif; ?>
                        <?php if($data[29]==1): ?><li class="">
                                <a _href="<?php echo U('Bargain/paperDetail');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>合同详情</cite>
                                </a>
                            </li><?php endif; ?>
                    </ul>
                </li><?php endif; ?>

            <?php if(($data[6]==1) or ($data[30]==1)): ?><li>
                    <a href="javascript:;">
                        <i class="layui-icon">&#xe841;</i>
                        <cite>楼盘信息</cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <?php if($data[6]==1): ?><li class="">
                                <a _href="<?php echo U('House/showBuildNotice');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>楼盘公告</cite>
                                </a>
                            </li><?php endif; ?>

                        <?php if($data[30]==1): ?><li class="">
                                <a _href="<?php echo U('House/houseDetail');?>">
                                    <i class="iconfont">&#xe6a7;</i>
                                    <cite>楼盘户型</cite>
                                </a>
                            </li><?php endif; ?>
                    </ul>
                </li><?php endif; ?>


        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li>系统后台</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src="<?php echo U('public/welcome');?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer">
    <div class="copyright">Copyright ©2017 All Rights Reserved</div>
</div>
<!-- 底部结束 -->

</body>

<script>
    $('#out').click(function(){
        $.post("<?php echo U('Public/loginout');?>",function(res){
            if(res.code==200){
                layer.msg(res.info, {icon: 6,time:2000},function () {
                    var url = "<?php echo U('Public/loginb');?>"; //
                    setTimeout(window.location.href=url,2000);
                });
            }else{
                layer.msg(res.info, {time: 2000});
            }
        },'json');
    })
</script>
</html>