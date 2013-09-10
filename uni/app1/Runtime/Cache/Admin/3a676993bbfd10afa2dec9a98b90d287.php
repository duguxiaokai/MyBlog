<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'>
<link type="text/css" rel="stylesheet" href ="__PUBLIC__/css/style.css" />
<style type="text/css">
	body,p,div,ul,h3{ font-size:12px; margin:0; padding:0; }
	p{ margin-bottom:10px; line-height:20px;}
	.message{ width:297px; height:170px; margin:20px auto;}
	.message h3{ height:21px; color:#FFF;font:bold 12px/21px "宋体";padding-left:5px; background:url(__PUBLIC__/Images/tishibg.png) no-repeat}
	.messageBox{ width:297px; height:149px; background:url(__PUBLIC__/Images/tishibg2.png) no-repeat; padding:10px;}
	.space{ font:bold 15px/30px "黑体", "宋体", "微软雅黑";}
	.messageBox img{ vertical-align:middle}
</style>
</head>
<body>
<div class="message">
<h3>页面提示</h3>
<div class="messageBox">
	<p class="tCenter space">
	<?php if(isset($message)): ?><img src="__PUBLIC__/Images/sucess.png" width="46px" height="46px"/><?php endif; ?>
	<?php if(isset($error)): ?><img src="__PUBLIC__/Images/erro.png" width="50px" height="49px"/><?php endif; ?>
	<?php echo ($msgTitle); ?></p>
	<?php if(isset($message)): ?><p><span style="color:#C08937"><?php echo ($message); ?></span></p><?php endif; ?>
	<?php if(isset($error)): ?><p><span style="color:red"><?php echo ($error); ?></span></p><?php endif; ?>
	<?php if(isset($closeWin)): ?><p>系统将在 <span style="color:red;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击<br>
		<a href="<?php echo ($jumpUrl); ?>">这里</a> 关闭</p><?php endif; ?>
	<?php if(!isset($closeWin)): ?><p>系统将在 <span style="color:red;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击<br>
	<a href="<?php echo ($jumpUrl); ?>">这里</a> 跳转</p><?php endif; ?>
    </div>
</div>
</body>
</html>