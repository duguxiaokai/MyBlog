<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>地图</title>
<link href='__PUBLIC__/Themes/yellow1/css/common.css' rel='stylesheet' type='text/css'>
<link href="__PUBLIC__/Landz/css/global.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Landz/css/right.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/jusaas/mcmap/McssMap.css" type="text/css" rel="stylesheet"   />

<script type="text/javascript" src="__PUBLIC__/jusaas/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/autoform.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js" ></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/mcmap/mcmap.js"></script>
<script type="text/javascript" src='__PUBLIC__/js/popup.js'></script>
<script type="text/javascript">

        var Sys = {};
        var ua = navigator.userAgent.toLowerCase();
        alert(ua);

$(document).ready(function() 
{
	test1()})
function showprovince(e)
{
	alert($(e).attr("action-data"));
} 

function show(e)
{
	return $(e).attr("title")+":(4)";
}

function test1()
{	
	
	var url="http://qingbaogou.com:9999/search/";
	$.getJSON(url,function(data)
	{
		alert(data);
	})
}




</script>
</head>

<body>
<div id='mjs'></div>

<img id='img1' src="__PUBLIC__/jusaas/mcmap/map/images/map/map-24.png"/>
<div id='chinamap'></div>
 
</body>
</html>