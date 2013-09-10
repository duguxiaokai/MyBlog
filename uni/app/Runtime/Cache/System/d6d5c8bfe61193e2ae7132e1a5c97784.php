<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> <!--dtd协议-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js" ></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/lang/cn/language.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/dom.js"></script>
<script type="text/javascript" src="__PUBLIC__/jusaas/js/utils.js"></script>
</head>

<body>
<span id='loading'>load...</span>
<div id='main' style="width:400px;text-align:center;margin:10px;display:none">
	<input type='radio' name='share' value='all' id='shareAll' />
	<label style='padding:5px;' for='shareAll'  title='完全共享' id='label_fullshare'>完全共享</label>
	<input type='radio' name='share' value='PartiallyShared' id='PartiallyShared' />
	<label style='padding:5px;' for='PartiallyShared'  title='共享给某些用户'  id='label_partshare'>共享给某些用户</label>
	<input type='radio' name='share' value='cancel' checked='true' id='cancelShared' />
	<label style='padding:5px;' for='cancelShared'  id='label_noshare'>不共享</label><br/>
	<div id='sharetitle' style='margin-top:10px;'></div>
	<div style='margin-left:10px;margin-top:10px;display:none' id='sharinginfo' >
		<input  type='checkbox' id='option_canedit' style='float:left;margin-left:6px' />
		<label for='option_canedit' style='float:left'  id='label_canedit'>可修改</label>
		<a target='_blank' id='a_preview' style='float:left;margin-left:20px' >预览</a>
		<input type='text' id='sharingurl' style='width:450px;margin-top:2px'  />
	</div>
	<div id='shareIds' style='display:none;text-align:left;margin:14px;'><span id='label_shareaccount'>共享账号</span>:
		<input style='margin-top:5px;width:450px;' type='text' id='shareId' placeholder='' title='请输入共享账号' />
	</div>
	<a id='saveShareMes' class='btn btn-green' style='display:none;'>确定</a>
</div>
</body>
</html>

<script type="text/javascript">
	var url=getParam('url');
	var pageurl=url.replace(/\[xiegan]/g,"/");
	var thisUrl=pageurl;
	var objectid=common_getParamValue(pageurl,'id');
	var convertedUrl=pageurl.replace(/\//g,"*xiegang*");
	var url=g_homeurl+"/Sys/System/checkShareUrl/";
	var recordid='0',cancel=false,shareto='';
var changed;//是否更改过
$(document).ready(function(){
	 init();
	setTimeout('setLanguage()',1000);	 
})
function init()
{ 
	$.getJSON(url,{url:convertedUrl},function(data){
		if(data)
		{
			recordid=data[0]["id"];
			var sharekey=data[0]["sharekey"];
			if(data[0]["shareto"]=="PUBLIC")
			{
				$("#shareAll").attr('checked','true');
				$("#sharinginfo").show();	
				$("#shareIds").hide();
				var canedit=false;
				if (data[0]["options"].indexOf('[canedit]')>-1)
					canedit=true;
					getShareUrl(thisUrl,sharekey,canedit);
			}else
			{

				$("#PartiallyShared").click();
				$("#sharinginfo").show();				
				$("#shareId").val(data[0]["shareto"]);
				shareto=data[0]["shareto"];
				$("#PartiallyShared").click();
			}
			
		}
	});
	//取消共享
	$("#cancelShared").click(function(){
		cancel=true;
		$("#shareIds").hide();

		$("#saveShareMes").hide();
		$("#sharetitle").hide();
		$("#sharinginfo").hide();
		
		if(!shareto && cancel==true)
			shareto="cancel";
		var url=g_homeurl+"/Sys/System/saveShareInfo/objectid/"+objectid+"/cancel/"+cancel+"/shareto/"+shareto+"/recordid/"+recordid+"/url/"+convertedUrl;
		$.post(url,function(data){
				if (data==1)
				{
					//mcdom.alert(lang.sharingcancled+"！",'','info','fadeout');			
					recordid=undefined;
					changed=true;
					//alert(window.parent);
					//$(mcpopup).remove();
				}
				else
				if(data=="")
					mcdom.alert(lang.cancelfialed+"！",'','fail','fadeout');
		});	
	});
	//部分共享
	$("#PartiallyShared").click(function(){
		$("#saveShareMes").show();		
		$("#shareIds").show();

		$("#shareId").focus();
		$("#sharinginfo").show();
	 	var canedit=$("#option_canedit").attr('checked');
		$("#sharetitle").show().html(lang.sharingurl_needsignin);
		if(shareto && shareto!="PUBLIC" && shareto!="cancel")
			$("#shareId").val(shareto);
		else
			$("#shareId").val("");
		var url=g_homeurl+"/Sys/System/saveShareInfo/cancel/true/recordid/"+recordid;
		$.post(url,function(data){
			recordid=undefined;
		});

		var url=g_homeurl+"/Sys/System/getRandStr";
		$.get(url,function(shareKey){
			getShareUrl(thisUrl,shareKey,canedit);
		});
	});
	
	//完全共享
	$("#shareAll").click(function(){
		$("#saveShareMes").hide();
		$("#shareIds").hide();
		$("#shareId").val("PUBLIC");
		$("#sharinginfo").show();		
		$("#sharetitle").show().html(lang.sharingurl_withoutsignin);		
		if (changed==undefined || changed==true)
		{
			var url=g_homeurl+"/Sys/System/getRandStr";
			$.get(url,function(shareKey){
			 	shareto='PUBLIC';
			 	var canedit=$("#option_canedit").attr('checked');
				var remoteurl=g_homeurl+"/Sys/System/saveShareInfo/objectid/"+objectid+"/shareto/"+shareto+"/recordid/"+recordid+"/sharekey/"+shareKey+"/canedit/"+canedit+"/url/"+convertedUrl;
				$.get(remoteurl,function(newrecordid){
					if(newrecordid==0)
						mcdom.alert(lang.failtoshare+"！",'','fail','fadeout');
					else if(newrecordid>0)
					{
						recordid=newrecordid;
						getShareUrl(thisUrl,shareKey,canedit);
						//mcdom.alert("<br />"+lang.okshare+"！",'','info','fadeout');			
						changed=true;
					}
				});		
			});	
		}	
		
	});
	
	$("#option_canedit").click(function(){
		var canedit=$(this).attr('checked');
		 shareto=$("#shareId").val();
		 if(!shareto && cancel==true)
			shareto="cancel";
		if (!shareto)
			shareto='PUBLIC';
		var remoteurl=g_homeurl+"/Sys/System/saveShareInfo/shareto/"+shareto+"/recordid/"+recordid+"/canedit/"+canedit+"/url/"+convertedUrl;
		$.post(remoteurl,function(newrecordid){
			changed=true;
		});
	})
	
	//保存按钮
	$("#saveShareMes").click(function(){
		if($("#PartiallyShared").attr("checked") && $("#shareId").val()=="")
		{
			mcdom.alert(lang.inputsharingaccount,'','info','fadeout');			
			$("#shareId").focus();
			return;
		}
		 shareto=$("#shareId").val();
		 if(!shareto && cancel==true)
			shareto="cancel";
		var url=g_homeurl+"/Sys/System/saveShareInfo/objectid/"+objectid+"/cancel/"+cancel+"/shareto/"+shareto+"/recordid/"+recordid+"/url/"+convertedUrl;
		$.get(url,function(newrecordid){
				if(newrecordid==0)
					mcdom.alert(lang.failtoshare+"！",'','fail','fadeout');
				else if(newrecordid>0)
				{
					if(newrecordid>1)
						recordid=newrecordid;
					//mcdom.alert(lang.okshare,'','info','fadeout');
					changed=true;					
				}
				else
					mcdom.alert(lang.accountnotexist+":"+newrecordid,'','info','fadeout');			
				
				$("#shareId").focus();
		});
	}); 
}

//生成共享链接
function getShareUrl(thisUrl,shareKey,canedit)
{
	var mcss_lang=getCookie("mcss_lang");
	var url=thisUrl;
	if (url.substr(url.length-1,1)=="#")
		url=url.substr(0,url.length-1);
	if (url.substr(url.length-1,1)!="/")
		url+="/";
	url+="sharekey/"+shareKey+"/";
	if (lang)
		url+="lang/"+mcss_lang+"";
	$("#sharingurl").val(url);
	$("#a_preview").attr('href',url);
	$("#option_canedit").attr('checked',canedit);
}

//把html页面上汉子转化为对应的语言
function setLanguage()
{
	var dom_id_arr="label_fullshare,label_partshare,label_noshare,label_canedit,a_preview,label_shareaccount,shareId,saveShareMes";
	uitls_setLanguage(dom_id_arr);
	$("#loading").hide();
	$("#main").show();
 }
</script>