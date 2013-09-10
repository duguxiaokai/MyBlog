/*
一些偶尔用到的函数
需要时，可以把该文件引入。但最后直接把需要用的函数复制到需要用的页面再使用
作者：陈坤极


函数索引：
//获得网站跟路径url
function getrooturl()

//动态导入js文件，代替原来在html的header中固定的导入
function importJS(jsfile)

//用时间创建惟一值
function getUniqueCode(prevchar)

//有些字符从html客户端传到php服务器时，会被转义，或处理出错，需要用其他自定义字符替换，到后台后在转化回原来的字符
function convertSpecialChar(str)

*/
g_dinamic_cssjs.push("jusaas/js/utils.js");

//获得网站跟路径url
function getrooturl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	var prePath=strFullPath.substring(0,pos);
	var postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1);
	var r=prePath+postPath;
	if (r.indexOf('index.php')>-1)
	{
		r=r.substr(0,r.length-9);
	}
 	return r;	
}

//动态导入js文件，代替原来在html的header中固定的导入
function importJS(jsfile)
{
		var oHead = document.getElementsByTagName('HEAD').item(0);
		var oScript= document.createElement("script");
		oScript.type = "text/javascript";
		var js=getrooturl()+"/Public/"+jsfile;
		oScript.src=js;
		oHead.appendChild( oScript);
}
//用时间创建惟一值
function getUniqueCode(prevchar)
{
	var d = new Date();
	var y = d.getFullYear();
	var m = d.getMonth() + 1;
	var day = d.getDate();
	var h=d.getHours();
	var mi=d.getMinutes();
	var s=d.getSeconds();
	var ms=d.getMilliseconds();
	return prevchar+"_"+y+"_"+m+"_"+day+"_"+"_"+mi+"_"+s+"_"+ms+"_by_"+getCookie("mcss_loginuser");
	
}

//有些字符从html客户端传到php服务器时，会被转义，或处理出错，需要用其他自定义字符替换，到后台后在转化回原来的字符
function convertSpecialChar(str)
{
	return str = str.replace(/\'/g, "<yh>");//把单引号转化为"<yh>"
}
//生成标签内容弹出层
function tag_createTag(obj,recordid,type){
	importJS("jusaas/js/dom.js");
	if(!type)
		type="";
	var objid = "";
	if(obj)
	{
		objid=obj.className;
		if (objid)
		{
			objid=objid.split(" ");
			if (objid.length>1)
				objid=objid[0];
			if (!objid)
				objid=obj.name;
		}
	}
	else
		objid="popup";
	var h="<div class='addTagsCon'><div id='commonlyusedtags'></div><div id='owntag' class='tag_list'></div><div id='tagg'></div><div id='tag' class='tag_list'></div><div id='addhtml' class='tag_list'></div><div style='overflow:hidden'><input type='text' id='inputtags' placeholder='自定义' class='addTextCon' onkeyup='tag_Myenter(event)'/><input type='button' style='cursor:pointer;' value='添加' class='addButtonbg selectbutton' onclick='tag_AddHtmlTag()' /></div><div class='fuceng_buttom'><a class='mcssingbutton btn btn-green' style='cursor:pointer;' href='javascript:void(0)' onclick='tag_saveRecordTag("+recordid+",\""+type+"\",\""+objid+"\")'>保存</a></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,380,280,"标签");
	tag_InitialTag(type,recordid);
}
//添加标签html
function tag_AddHtmlTag(){
	if($("#inputtags").val()){
		var k = 0;
		$("input[name=items]").each(function() {   
			if($(this).val()==$("#inputtags").val()){
				alert("请不要填写已经存在的标签！");
				$("#inputtags").val('');
				k++;
				return;
			}
		})
		if(k==1)
			return;
		var len = tag_getStrLength($("#inputtags").val());
		if(len > 12){
			alert('标签内容最多为6个汉字或12个英文字母!');$("#inputtags").val('');return;
		}
		var h = "<span style='width:100px;height:25px;display:block;float:left'><input name='items' value='"+$("#inputtags").val()+
		"' type='checkbox' checked/>"+$("#inputtags").val()+"</span>";
		$("#addhtml").append(h);
		$("#inputtags").val('');
		$("#inputtags").focus();
	}else{
		alert("请填写标签名称!");
	}
}
//自定义标签框回车
function tag_Myenter(event){
	if(!$("#inputtags").val())
		return;
	if (event.keyCode==13){
		tag_AddHtmlTag();
	}
}
//获取标签长度
function tag_getStrLength(s){
        var char_length = 0;
        for (var i = 0; i < s.length; i++){
            var son_char = s.charAt(i);
            encodeURI(son_char).length > 2 ? char_length += 2 : char_length += 1;
        }
        return char_length;
}
//保存记录的标签
function tag_saveRecordTag(recordid,type,objid){
	var tags = "";
	$("input[name='items']").each(function(){
		if($(this).attr('checked')){
			if(tags)
				tags+=',';
			tags+=$(this).val();
		}
	})
	if(!tags){
		alert("请至少选择一个标签!");return;
	}
	$.post(getrooturl()+"/index.php/Util/Tag/saveRecordTag",{tags:tags,recordid:recordid,type:type},function(data){
		if(data>0){
			alert('部分标签保存失败!');
		}else{
			alert('所有标签已成功保存!');
			$("#"+objid+"_popup_div").remove();
			$("#"+objid+"_fuceng_Box").remove();
		}
	})
}
//初始化记录的标签
function tag_InitialTag(type,recordid){
	$.getJSON(getrooturl()+"/index.php/Util/Tag/getSystemTags",{type:type},function(data){
		$.getJSON(getrooturl()+"/index.php/Util/Tag/getRecordTags",{recordid:recordid,type:type},function(result){
			for(var i = 0;i < data.length;i++){
				var h="<span style='width:100px;height:25px;display:block;float:left'><input name='items' value='"+data[i]['name']+
				"' type='checkbox'/>"+data[i]['name']+"</span>";
				$("#tag").append(h);
			}
			for(var i = 0;i < result.length;i++){
				var k = 0;
				$("#tag input[name='items']").each(function() {
					if($(this).val()==result[i]['name']){
						$(this).attr("checked",true);
						k++;
						return;
					}
				})
				if(k==1)
					continue;
				var h="<span style='width:100px;height:25px;display:block;float:left'><input name='items' value='"+result[i]['name']+
				"' type='checkbox' checked/>"+result[i]['name']+"</span>";
				$("#addhtml").append(h);
			}
		})
	})
}
//生成弹出标签层
function tags_search(obj,tagtype,tableid){
	var h ="<div class='tag_style_list'><ul><li class='nav'>系统标签</li><li>自定义标签</li><li>热门标签</li></ul></div><div class='tag_list_Content'><div id='mcss0' class='tag_info'></div><div id='mcss1' class='tag_info' style='display:none'></div><div id='mcss2' class='tag_info' style='display:none'></div></div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,380,280,"标签列表");
	$(".tag_style_list ul li").click(function()
	{
		$(this).addClass("nav").siblings().removeClass("nav");
		var index=$(".tag_style_list ul li").index($(this));
		$(".tag_list_Content > .tag_info").eq(index).show().siblings().hide();
	})	
	$.getJSON(getrooturl()+"/index.php/Util/Tag/getSystemTagByType",{type:tagtype},function(data){
		$.getJSON(getrooturl()+"/index.php/Util/Tag/getRecordTagByType",{type:tagtype},function(result){
			$.getJSON(getrooturl()+"/index.php/Util/Tag/getHotTagByType",{type:tagtype},function(arr){
				$("#mcss0").empty();$("#mcss1").empty();$("#mcss2").empty();
				if(data.length==0){
					$("#mcss0").html('暂时还没有关于此列表的任何系统标签');
				}else{
					for(var i = 0;i < data.length;i++){
						var h = "<span class='tagfilerbg' style='cursor:pointer;' ><a style='cursor:pointer;' onclick='tag_searchrows(\""+data[i]["name"]+"\",\""+tagtype+"\",\""+tableid+"\")'>"+data[i]["name"]+"</a></span>";	
						$("#mcss0").append(h);	
					}
					$("#mcss0").append("<span class='tagfilerbg' style='cursor:pointer;' ><a style='cursor:pointer;' onclick='tag_searchallrows(\""+tableid+"\")'>全部</a></span>");
				}
				
				if(result.length==0){
					$("#mcss1").html('暂时还没有关于此列表的任何自定义标签');
				}else{
					for(var i = 0;i < result.length;i++){
						var h="<span id='tags_"+[i]+"' class='tagfilerbg' style='cursor:pointer;' ><a style='cursor:pointer;' class='' onclick='tag_searchrows(\""+result[i]["name"]+"\",\""+tagtype+"\",\""+tableid+"\")'>"+result[i]["name"]+"</a><span onclick='deltags(\""+result[i]['id']+"\",this)' title='删除'></span></span>";	
						$("#mcss1").append(h);	
					}
					$("#mcss1").append("<span class='tagfilerbg' style='cursor:pointer;' ><a style='cursor:pointer;' onclick='tag_searchallrows(\""+tableid+"\")'>全部</a></span>");
				}
				if(arr.length==0){
					$("#mcss2").html('暂时还没有关于此列表的任何热门标签');
				}else{
					for(var i = 0;i < arr.length;i++){
						var h = "<span class='tagfilerbg' style='cursor:pointer;' ><a style='cursor:pointer;' onclick='tag_searchrows(\""+arr[i]["name"]+"\",\""+tagtype+"\",\""+tableid+"\")'>"+arr[i]["name"]+"</a></span>";	
						$("#mcss2").append(h);	
					}
					$("#mcss2").append("<span class='tagfilerbg' style='cursor:pointer;' ><a style='cursor:pointer;' onclick='tag_searchallrows(\""+tableid+"\")'>全部</a></span>");
				}
			})
		})
	})	
}
//根据标签来获取符合条件的数据
function tag_searchrows(name,tagtype,tableid){
	$.post(getrooturl()+"/index.php/Util/Tag/getRecordidByTypeAndName",{name:name,type:tagtype},function(data){
		var filter = "id in ("+data+")";
		var mcsstable=mcsstable_getMCSSTable(tableid);
		mcsstable.filter=filter;
		mcsstable_loaddatarows(tableid);
	})
}
//获取全部内容
function tag_searchallrows(tableid){
		var filter = "1=1";
		var mcsstable=mcsstable_getMCSSTable(tableid);
		mcsstable.filter=filter;
		mcsstable_loaddatarows(tableid);
}
//根据标签来获取符合条件的数据
function tag_searchrows(name,tagtype,tableid){
	$.post(getrooturl()+"/index.php/Util/Tag/getRecordidByTypeAndName",{name:name,type:tagtype},function(data){
		var filter = "id in ("+data+")";
		var mcsstable=mcsstable_getMCSSTable(tableid);
		mcsstable.filter=filter;
		mcsstable_loaddatarows(tableid);
	})
}

//根据标签id来删除标签
function deltags(id,obj){
	if(confirm("您确认删除此标签吗?")){
		$.post(getrooturl()+"/index.php/Util/Tag/delTagById",{id:id},function(data){
			if(data>0)
				$(obj).parent().remove();
			else
				alert('对不起，系统出现问题，删除失败!');
		})
	}
}





//创建共享按键
function createSharingBtn(){
	$("#mcss_sharingbutton").css("float","right");
	$("#mcss_sharingbutton").html("<a id='share_"+randomString()+"'	 href='javascript:void(0)' onclick='utils_sharePage(this)' title='共享该页面'>共享</a>");
}

//判断是否为共享页面
function utils_isSharingPage()
{
	if((window.location.href).indexOf("/sharekey")>0)
		return true;
	else
		return false;
}

//弹出共享页面
function utils_sharePage(obj,pageurl){
	var h="<div id='PublicBtn'></div><div class='fuceng_buttom' style='padding-left:5px;'>"
	+"<input type='radio' name='share' value='all' id='shareAll' />"
	+"<label style='padding:5px;' for='shareAll'  title='"+lang.sharingurl_withoutsignin+"' >"+lang.fullshare+"</label>"
	+"<input type='radio' name='share' value='PartiallyShared' id='PartiallyShared' />"
	+"<label style='padding:5px;' for='PartiallyShared'  title='"+lang.sharingurl_needsignin+"' >"+lang.selectedshare+"</label>"
	+"<input type='radio' name='share' value='cancel' checked='true' id='cancelShared' />"
	+"<label style='padding:5px;' for='cancelShared'>"+lang.donotshare+"</label><br/>"
	+"<div id='sharetitle' style='margin-top:10px;'></div>"
	+"<div style='margin-left:10px;margin-top:10px;'>"
	+"<input  type='checkbox' id='option_canedit' style='float:left;margin-left:6px' /><label for='option_canedit' style='float:left'>可修改</label>"
	+"<a target='_blank' id='a_preview' style='float:left;margin-left:20px'>"+lang.preview+"</a>"
	+"<div id='sharelink'></div>"
	+"<input type='text' id='sharingurl' style='width:450px;margin-top:2px'  />";
	+"</div>"

	+"</div>"
	+"<br />"
	+"<div id='shareIds' style='display:none;text-align:left;margin:14px;'>"+lang.sharingaccount+":<input style='margin-top:5px;width:450px;' type='text' id='shareId' placeholder='"+lang.accountexample+"' title='"+lang.inputsharingaccount+"' />"
	+"</div><br/><br/><a id='saveShareMes' class='btn btn-green' title='"+lang.confirm+"' style='display:none;'>"+lang.confirm+"</a>"
	+"</div>";
	mcpopup=mcdom.showPopup(obj,h,null,null,null,500,500,lang.setshare);
	var thisUrl;
	if (pageurl)
		thisUrl	=pageurl;
	else
		thisUrl	=window.location.href;
	var objectid=common_getParam('id');
	var convertedUrl=thisUrl.replace(/\//g,"*xiegang*");
	var url=g_homeurl+"/Sys/System/checkShareUrl/";
	var recordid='0',cancel=false,shareto='';
	$.getJSON(url,{url:convertedUrl},function(data){
		if(data)
		{
			recordid=data[0]["id"];
			var sharekey=data[0]["sharekey"];
			if(data[0]["shareto"]=="PUBLIC")
			{
				$("#sharelink").html(getShareUrl(thisUrl,sharekey,data[0]["shareto"]));
				$("#shareAll").attr('checked','true');
			}else
			{
				$("#sharelink").html(getShareUrl(thisUrl,sharekey));	
				$("#PartiallyShared").click();
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
		$("#sharelink").hide();
		$("#saveShareMes").hide();
		$("#sharetitle").hide();
		if(!shareto && cancel==true)
			shareto="cancel";
		var url=g_homeurl+"/Sys/System/saveShareInfo/objectid/"+objectid+"/cancel/"+cancel+"/shareto/"+shareto+"/recordid/"+recordid+"/url/"+convertedUrl;
			$.get(url,function(data){
				if (data==1)
				{
					$("#sharelink").html("");
					mcdom.alert(lang.sharingcancled+"！",'','info','fadeout');			
					recordid=undefined;
					$(mcpopup).remove();
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
		$("#sharelink").show();
		$("#shareId").focus();
		$("#sharetitle").show().html(lang.sharingurl_needsignin);
		if(shareto && shareto!="PUBLIC" && shareto!="cancel")
			$("#shareId").val(shareto);
		else
			$("#shareId").val("");

		if(!$("#sharelink").html())
		{
			var url=g_homeurl+"/Sys/System/getRandStr";
			$.get(url,function(shareKey){
				var h=getShareUrl(thisUrl,shareKey);
				//$("#sharelink").html(h);
			});
		}		
	});
	
	//完全共享
	$("#shareAll").click(function(){
		$("#saveShareMes").hide();
		$("#shareIds").hide();
		$("#sharelink").show();
		$("#shareId").val("PUBLIC");
		$("#sharetitle").show().html(lang.sharingurl_withoutsignin);		
		if(!$("#sharelink").html())
		{
			var url=g_homeurl+"/Sys/System/getRandStr";
			$.get(url,function(shareKey){
			 	shareto='PUBLIC';
			 	var canedit=$("#option_canedit").attr('checked');
				var remoteurl=g_homeurl+"/Sys/System/saveShareInfo/objectid/"+objectid+"/shareto/"+shareto+"/recordid/"+recordid+"/sharekey/"+shareKey+"/url/"+convertedUrl;
				$.get(remoteurl,function(newrecordid){
					if(newrecordid==0)
						mcdom.alert(lang.failtoshare+"！",'','fail','fadeout');
					else if(newrecordid>0)
					{
						recordid=newrecordid;
						var h=getShareUrl(thisUrl,shareKey,shareto);
						$("#sharelink").html(h);
						mcdom.alert("<br />"+lang.okshare+"！",'','info','fadeout');			
					}
				});		
			});		
		}
		else
		{
		 	shareto=$("#shareId").val();
			var remoteurl=g_homeurl+"/Sys/System/saveShareInfo/objectid/"+objectid+"/shareto/"+shareto+"/recordid/"+recordid;
			$.get(remoteurl,function(data){
				if(data=="")
					mcdom.alert(lang.failtoshar+"！",'','fail','fadeout');
				else if(data==1)
				{
					mcdom.alert(lang.okshare+"！",'','info','fadeout');			
				}
			});		
	
		
		}		
	});
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
					mcdom.alert(lang.okshare,'','info','fadeout');			
				}
				$("#shareId").focus();
		});
	}); 
	$("#closebtn").click(function(){
		$(mcpopup).remove();
	}); 
		
}

//生成共享链接
function getShareUrl(thisUrl,shareKey,shareto,canedit)
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
	
	return '';
	var preview="<div style='margin-left:10px;margin-top:10px;'>"
//	+"<a style='float:left'>"+sharehint+":</a>"
//	+"<input  type='checkbox' id='option_canedit' style='float:left;'";
//	if (canedit)
//	preview+=" checked ";
//	preview+=" /><label for='option_canedit' style='float:left'>可修改</label>"
	+"<a target='_blank' style='float:left;margin-left:20px' href='"+url+"'>"+lang.preview+"</a>"
	+"</div>"
	+"<input type='text' style='width:450px;margin-left:0px' value='"+url+"' />";
	return preview;
}


//用时间创建惟一值.例子：91196954120133243426751admin

//生成随机字符串
function randomString(l)
{
   var   x="0123456789qwertyuioplkjhgfdsazxcvbnm";
   var   tmp="";
   if (l==undefined)	l=8;
   for(var   i=0;i<l;i++)   {
   tmp   +=   x.charAt(Math.ceil(Math.random()*100000000)%x.length);
   }
   return   tmp;
}

//去掉所有的html标记
function clearHTML(h)
{
	if (h)
	return h.replace(/<[^>]+>/g,"");
	else
		return h;
}

//把html页面上hmlt()和title中的汉子转化为对应的语言
function uitls_setLanguage(dom_ids)
{
	dom_id_arr=dom_ids.split(",");
	for(var i=0;i<dom_id_arr.length;i++)
	{
		if (lang[$("#"+dom_id_arr[i]).html()])
			$("#"+dom_id_arr[i]).html(lang[$("#"+dom_id_arr[i]).html()]);
		if (lang[$("#"+dom_id_arr[i]).attr('title')])
			$("#"+dom_id_arr[i]).attr('title',lang[$("#"+dom_id_arr[i]).attr('title')]);
		if (lang[$("#"+dom_id_arr[i]).val()])
			$("#"+dom_id_arr[i]).val(lang[$("#"+dom_id_arr[i]).val()]);
	}		
}

function utils_getBeginEndDateSql(beginfield,endfield,begindate,enddate)
{
	return "(("+beginfield+"<='"+begindate+"' and "+endfield+">='"+begindate+"') or  ("+beginfield+">='"+begindate+"' and "+endfield+"<='"+enddate+"') or ("+beginfield+"<='"+enddate+"' and "+endfield+">='"+enddate+"') or ("+beginfield+"<='"+begindate+"' and "+endfield+">='"+enddate+"'))";
}

function utils_publishPage(url)
{
	if (url==undefined)
		url=window.document.location.href;
	url=url.replace(/\//g,"[xiegan]");	
	mcdom.showIframe(g_homeurl+"/System/Model/setShare/url/"+url,{width:500,height:250,title:lang.share});
}