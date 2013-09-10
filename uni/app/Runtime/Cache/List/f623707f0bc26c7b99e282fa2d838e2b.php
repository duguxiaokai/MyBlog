<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title></title>
<link href='__PUBLIC__/themes/<?php echo ($mcss_theme); ?>/css/common.css' rel='stylesheet' type='text/css'>
<link href='__PUBLIC__/plugins/colResizable/css/main.css' rel='stylesheet' type='text/css'>
<link href="__PUBLIC__/projects/oa/css/calendar.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="__PUBLIC__/js/jquery.js" ></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/lang/<?php echo ($mcss_lang); ?>/language.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSTable.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/MCSSPicture.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/colResizable/js/colResizable-1.3.min.js"></script>
<script src="__PUBLIC__/jusaas/js/dom.js?dialog=succinct"></script>
</head>

<body class='list2Padding'>
<div id='mcss_pagebar' style="margin-bottom:5px;"></div>
<div id='main'>
<div id="mcss_table" style="width:100%;"></div>
</div>
</body>
</html>

<script type="text/javascript">
var mcssTable;
$(document).ready(function() {
	var lang='<?php echo ($mcss_lang); ?>';
	var modelid=decodeURI(geturlparam("modelid"));
	if (!modelid)
		modelid=decodeURI(geturlparam("table"));
	var searchword=decodeURI(geturlparam("searchword"));
	var params={searchword:searchword,showfirst:true,width:'100%',showtitle:true,
	addNewFunc:addNewFunc,lang:lang,afterLoadRows:ondrag,pageposition:'rightdown',
	showSaveAndAddButton:true,
	view:'celledit123',
	showRecordActionAtLast:false};
	mcssTable=newMCSSTable("mcss_table",modelid,params);//params参数非必须
	mcssTable.run(afterRun);
	//var mcssp=new MCSSPicture('123',"abc");
	//mcssp.test();
});

function afterRun()
{
}
function addNewFunc()
{	
}
function ondrag(mcsstable)
{
//mcssTableEditor_get_editor_html
//var mcdata=new MCSSData(mctable.data,mctable.modeldata);
//var editor=new TableEditor(mctable.tableid,mctable.data,mctable.modeldata,{firstLineCanEdit:false,mcssData:mcdata});
//editor.run();
	//简单搜索
	if(mcsstable.modelid == 'landz_customer_export'){
		$("#mcss_table_table_select").find("option").eq(3).html('业主,客户');
		$("#mcss_table_table_select").find("option").eq(3).val('HY_TYPE:业主,客户');
	}
		
	//导出
	$('#mcss_table_table_action_export').unbind("click"); //移除之前click
	$('#mcss_table_table_action_export').attr('onclick','');
	$('#mcss_table_table_action_export').click(function(){
		var h = $("input[type='checkbox'][name='mcss_table_table_checkbox'][checked]").length;
		if(h == 0){
			if(mcssTable.recordCount>5000){
				alert("导出数量最大为5000");
				return false;
			}else{
				mcsstable_displaymenu(this,"mcss_table");
			}
		}else{
			mcsstable_displaymenu(this,"mcss_table");
		}
	});
	
	return;//可调整表格栏目宽度第一次处理宽度有一点不合理，就暂时不用。如果需要把return注视掉
	$(function(){	
		$("#mcss_table").colResizable({
			liveDrag:true, 
			gripInnerHtml:"<div class='grip'></div>", 
			draggingClass:"dragging" 
			});
		
	});	
	//colResizable对默认生成的mctable的第一栏的宽度处理短了点，这里特意加上
	var w=$(".mcsstable_th_first").css("width");
	if (parseInt(w)<50)
		$(".mcsstable_th_first").css("width",parseInt(w)+10+"px");
}
</script>