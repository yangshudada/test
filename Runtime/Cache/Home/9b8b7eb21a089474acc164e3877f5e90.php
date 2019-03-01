<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>造价后台页面</title>
    <style type="text/css">
        html, body { width: 100%; height: 100%; margin: 0; padding: 0;}
        p { margin: 0; padding: 0; }
        .list_top_style_a {  width: 100%; display: flex;  text-align: center; border-bottom: 1px solid; }
        .list_top_style_a1 {  width: 50%; height: 40px; line-height: 40px; cursor: pointer; }
        .style_a_h {  background-color:  #009688;color:#fff;  }

        .list_top_style_b { width: 100%; display: flex;  text-align: center; border-bottom: 1px solid; }
        .style_b { width: 25%; height: 40px; line-height: 40px; cursor: pointer; }
        .style_b2 { width: 50%; height: 40px; line-height: 40px; cursor: pointer; }
        .style_b_h {   background-color:#009688;color:#fff; }
        iframe {  width: 100%;  height: auto; }
    </style>
</head>
<body>

<!--顶部导航栏-->
<div class="top" style="padding: 20px 0;">

    <div class="list_top_style_a">
        <div id="a1" class="list_top_style_a1 style_a_h"><p>全装</p></div>
        <div id="a2" class="list_top_style_a1"><p>精装</p></div>
    </div>
    <div class="list_top_style_b" id="style_b1">
        <div id="b1" class="style_b style_b_h"><p>硬装</p></div>
        <div id="b2" class="style_b"><p>部品主材</p></div>
        <div id="b3" class="style_b"><p>软装</p></div>
        <div id="b4" class="style_b"><p>家电</p></div>
    </div>
    <div class="list_top_style_b" id="style_b2" style="display: none;">
        <div id="b2_1" class="style_b2 style_b_h"><p>硬装</p></div>
        <div id="b2_2" class="style_b2"><p>部品主材</p></div>
    </div>
</div>

<iframe id="myIframe" src="<?php echo U('Test/up_cost');?>?style_a=style_a1&style_b=style_b1&id=<?php echo ($id); ?>" frameborder="0" scrolling="no" style="width:100%;height:100%;border-color: purple;"></iframe>
<script type="text/javascript" src="/baleijia/Inter/Public/jq/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
    $("#a1").click(function () {
        $(this).attr("class", "list_top_style_a1 style_a_h");
        $("#a2").attr("class", "list_top_style_a1");
        $("#style_b1").show();
        $("#style_b2").hide();
        $("#myIframe").attr("src", "up_cost?style_a=style_a1&style_b=style_b1&id=<?php echo ($id); ?>");
    });
    $("#a2").click(function () {
        $(this).attr("class", "list_top_style_a1 style_a_h");
        $("#a1").attr("class", "list_top_style_a1");
        $("#style_b1").hide();
        $("#style_b2").show();
        $("#myIframe").attr("src", "up_cost?style_a=style_a2&style_b=style_b1&id=<?php echo ($id); ?>");
    });
    $("#b1").click(function () {
        $(this).attr("class", "style_b style_b_h");
        $("#b2, #b3, #b4").attr("class", "style_b");
        $("#myIframe").attr("src", "up_cost?style_a=style_a1&style_b=style_b1&id=<?php echo ($id); ?>");
    });
    $("#b2").click(function () {
        $(this).attr("class", "style_b style_b_h");
        $("#b1, #b3, #b4").attr("class", "style_b");
        $("#myIframe").attr("src", "up_cost?style_a=style_a1&style_b=style_b2&id=<?php echo ($id); ?>");
    });
    $("#b3").click(function () {
        $(this).attr("class", "style_b style_b_h");
        $("#b2, #b1, #b4").attr("class", "style_b");
        $("#myIframe").attr("src", "up_cost?style_a=style_a1&style_b=style_b3&id=<?php echo ($id); ?>");
    });
    $("#b4").click(function () {
        $(this).attr("class", "style_b style_b_h");
        $("#b2, #b3, #b1").attr("class", "style_b");
        $("#myIframe").attr("src", "up_cost?style_a=style_a1&style_b=style_b4&id=<?php echo ($id); ?>");
    });
    $("#b2_1").click(function () {
        $(this).attr("class", "style_b2 style_b_h");
        $("#b2_2").attr("class", "style_b2");
        $("#myIframe").attr("src", "up_cost?style_a=style_a2&style_b=style_b1&id=<?php echo ($id); ?>");
    });
    $("#b2_2").click(function () {
        $(this).attr("class", "style_b2 style_b_h");
        $("#b2_1").attr("class", "style_b2");
        $("#myIframe").attr("src", "up_cost?style_a=style_a2&style_b=style_b2&id=<?php echo ($id); ?>");
    });
</script>
<!--<script type="text/javascript">-->
    <!--var winH = $(document).height();-->
    <!--var topH = $(".top").height();-->
    <!--$("#myIframe").height(winH - topH);-->
    <!--console.log(winH, topH, winH - topH);-->
<!--</script>-->
</body>
</html>