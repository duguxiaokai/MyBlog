<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
 
<script src="__PUBLIC__/js/jquery.js" ></script>
<script src="__PUBLIC__/jusaas/js/common.js"></script>
<script src="__PUBLIC__/jusaas/js/autoform.js"></script>

<script >
setCookie("mcss_iframeurl",document.location.href);
var form1;
$(document).ready(function() {
	s=location.href;
	var modelid=getParamValue(s,"model");
	form1=new Autoform('formdata',{recordid:'url:KEYFIELD',modelid:modelid});   
    form1.run();
});

function saveRecord()
{
 	form1.save(true);
}
</script>
</head>
<body>
<div id='mcss_pagebar'></div>
<div class="formBox">
	<h3><span>编辑</span> <font color="#FF0000">*为必填项</font></h3>
	<div id="formdata" class="formdataCon"></div>
</div>
<div style='text-align:center; padding:10px'>
		<a href="javascript:void(0)" onclick='saveRecord()' class="mcssingbutton">保存</a>
</div>

</body>
</html>