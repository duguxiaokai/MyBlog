//alert("看到这行表明dom.js文件被加载了。");
g_dinamic_cssjs.push("jusaas/js/dom.js");

function MCDom(params)
{
}

/*
 * urlParameters 获取js文件后的url参数组，如：test.js?id=1&classid=2中的?后面的内容
 */
urlParameters = (function(script){
 var l = script.length;
 for(var i = 0; i < l; i++){
  me = !!document.querySelector ? script[i].src : script[i].getAttribute('src',4);
  if( me.substr(me.lastIndexOf('/')).indexOf('dom') !== -1 ){
      break;
  }
 }
 return me.split('?')[1]; 
})(document.getElementsByTagName('script'))
//alert(urlParameters);
/*
 * 获取url参数值函数
 */
getParameters = function ( name ){
    if( urlParameters){
     var parame = urlParameters.split('&'), i = 0, l = parame.length, arr;
  	for(var i=0 ; i < l; i++ ){
      arr = parame[i].split('=');
   	if( name === arr[0] ){
    	return arr[1];
  	 	}
  	}
 }
 return null;
}

var mcdom=new MCDom();

MCDom.prototype.createOptions=function(data,parent)
{
	var arr=data;
	if (typeof(data)=="string")
	{
		arr=data.split(",");
	}else return "错误。data参数必须是sring类型！";
	var option="";
	for(i in arr)
	{
		var arr1=arr[i].split(":");
		var v=arr1[0];
		var n=arr1[0];
		if (arr1.length==2)
			n=arr1[1];
		if (parent)
			v=parent+":"+v;
		if (lang[n])
			n=lang[n];
		option+="<option value='"+v+"'>"+n+"</option>";
	}
	return option;
}
//data:"1:选项1,2:选项2"
//createSelect("1:选项1,2:选项2");
function createSelect(data)
{
	var arr=data;
	if (typeof(data)=="string")
	{
		arr=data.split(",");
	}else return "错误。data参数必须是sring类型！";
	var option="";
	for(i in arr)
	{
		var arr1=arr[i].split(":");
		var v=arr1[0];
		var n=arr1[0];
		if (arr1.length==2)
			n=arr1[1];
		option+="<option value='"+v+"'>"+n+"</option>";
	}
	return option;
}

//获取下拉列表选中项的文本   
function getSelectedText(obj){ 
	for(i=0;i<obj.options.length;i++){   
	   if(obj.options[i].selected==true){   
		return obj.options[i].innerHTML; 
	   }   
	}   
}  

function getRadioValue(name)
{
	var radios=document.getElementsByName(name);
	for(var k=0;k<radios.length;k++)
	{
		if (radios[k].checked)
		{
			return radios[k].value;
		}
	}
}

function getTDtextByID(tr,id) {
    var r="";
    for(var i=0;i<tr.childNodes.length;i++)
    {
        if (tr.childNodes[i].id==id)
        {
            r =tr.childNodes[i].innerHTML;
        }
    }
    return r;
}

MCDom.prototype.popup=function(html,params)
{
	if (!params)
		params={};
	return this.showPopup(params.obj,html,params.position,params.top,params.left,params.height,params.width,params.title,{fadeOut:params.fadeOut});	
}

/*
创建浮层，浮层中的html内容有参数html提供
e:鼠标单击的那个对象，如按钮
position：浮层显示位置，如果为middle则显示在屏幕中央
其它参数可以为空
*/
MCDom.prototype.showPopup=function(e,html,position,top,left,height,width,title,params)
{
	if (e)
	{
		var top1=0;
		var left1=0;
		if(window.event)
		{
			var e1 = window.event;
			left1=e1.offsetLeft || e1.clientX+document.body.scrollLeft+document.documentElement.scrollLeft;
			top1=e1.offsetTop || e1.clientY+document.body.scrollTop+document.documentElement.scrollTop;
		}
		else
		{
			if($(e).attr("getBoundingClientRect")){
				var p = e.getBoundingClientRect();
				top1=p.top;
				left1=p.left;
				top1=document.documentElement.scrollTop+p.top+p.height;//+(document.documentElement.clientHeight);
				left1=document.documentElement.scrollLeft+p.left+p.width;//+(document.documentElement.clientWidth); 
			}
		}
	}

	if (!left) left=left1;
	if (!top) top=top1;

	if (!width)
		width=400;
	if (!height)
		height=300;

	var widths=document.documentElement.clientWidth/2;
	var right=document.documentElement.clientWidth-left;
	 var heights=document.documentElement.clientHeight/2;
	if(left < widths){
		var styles="left";
		if (e)
		{
		var fucengleft=e.offsetWidth+left;
		var vals=fucengleft+"px";
		}
		
	}
	else{
		var styles="right";
		var vals=right+"px";
	}
	//如果是居中的浮层
	if (position && position=="middle")
	{	
		top=document.documentElement.scrollTop + document.body.scrollTop+(document.documentElement.clientHeight-height)/2;
		left=(document.documentElement.clientWidth-width)/2;
		
		var styles="left";
		var vals=left+"px";
	}
	if (top<0)
		top=0;
	if (left<0)
		left=0;
	
	//给fuceng一个id
	if(e)
	{
		var objid = e.id;
		if(!objid){
			objid=e.className;
			if (objid)
			{
				objid=objid.split(" ");
				if (objid.length>1)
					objid=objid[0];
				if (!objid)
					objid=e.name;
			}else{
				objid="popup";	
			}
		}
	}
	
	if(!objid||objid==undefined)
		objid="popup";
	$("#"+objid+"_popup_div").remove();$("#"+objid+"_fuceng_Box").remove();
	if (title==undefined)
		title="";
	var h="<div class='fuchuceng' id='"+objid+"_fuceng_Box' style='top:"+top+"px;"+styles+":"+vals+";width:"+width+"px;height:auto;'>";
			h+="<div id='"+objid+"_handmove' class='handmove' style='cursor:move'><span class='title_popup'>"+title+"</span><a href='javascript:void(0)' title='关闭' class='close_popup' id='"+objid+"_close_popup'><i class='i-pop-close'> </i></a></div><div id='"+objid+"_popup_div' class='popup_htmlContent'>"
			+html
			+"</div></div>";

	$("#"+objid+"_popup_div").remove();$("#"+objid+"_fuceng_Box").remove();
	$('body').append(h);	
	$("#"+objid+"_handmove").mousedown(function (e) {
		iDiffX = e.pageX - $(this).offset().left;
		iDiffY = e.pageY - $(this).offset().top;
		var w=$("#"+objid+"_handmove").parent().width();
		$(document).mousemove(function (e) {			
			$("#"+objid+"_handmove").parent().css({ "left": (e.pageX - iDiffX), "top": (e.pageY - iDiffY),"width":w});
		});
	});
	$("#"+objid+"_handmove").mouseup(function () {
		$(document).unbind("mousemove");
	});		
	
	$("#"+objid+"_close_popup").click(function(){
		$("#"+objid+"_popup_div").remove();	$("#"+objid+"_fuceng_Box").remove();	
 	})
	if (params && params.fadeOut)
	{
		var showSeconds=2000;
		if (params.seconds)
			showSeconds=params.seconds*1000;
		if (params.showSeconds)
			showSeconds=params.showSecond*1000;
		setTimeout("$('#"+objid+"_fuceng_Box').remove()",showSeconds); 
	}
	return $("#"+objid+"_fuceng_Box");
}

//把浮层大小改为浮层中的iframe的大小
function dom_setAutoWidth(e)
{
if (typeof(e)=="string")
	e=document.getElementById(e);
var e=$(e.parentNode.parentNode).find('iframe').get(0);
$(e).css('height',e.contentWindow.document.body.scrollHeight+'px');
$(e.parentNode.parentNode).css('width',(e.contentWindow.document.body.scrollWidth+2)+'px');
$(e.parentNode.parentNode).css('height',(e.contentWindow.document.body.scrollHeight+32)+'px');
}
//把浮层大小改为浮层中的iframe的大小
function dom_setFullSize(e)
{
if (typeof(e)=="string")
	e=document.getElementById(e);
var iframe=$(e.parentNode.parentNode).find('iframe').get(0);
$(iframe).css('height',iframe.contentWindow.document.body.scrollHeight+'px');

if (e.title=="最大化")
{
	var fucengmaxWidth=document.documentElement.clientWidth-10;
	var fucengmaxHeight=document.documentElement.clientHeight-10;
	var fucengmaxLeft=(document.documentElement.clientWidth-fucengmaxWidth)/2;
	var fucengmaxTop=(document.documentElement.clientHeight-fucengmaxHeight)/2;
	$(iframe).css('height','100%');
	$(iframe.parentNode.parentNode).css({'top':fucengmaxLeft+'px','left':fucengmaxTop+'px','width':fucengmaxWidth+'px','height':fucengmaxHeight+'px'});
	$(iframe.parentNode).height((fucengmaxHeight-31));
	e.title="还原";
	$(e).addClass('back_popup');
}
else
{
	$(iframe.parentNode.parentNode).css({'top':'10px','left':'100px','width':'400px','height':'350px'});
	$(iframe).css('height','300px');
	e.title="最大化";
	$(e).removeClass('back_popup');
	
}


}

/*
	创建中央浮层
	参数url为要展示的页面，params为json格式的参数
	params.top
	params.left
	params.height
	params.width
	params.title
	params.size:'auto','100%'
	params.fadeOut
*/
MCDom.prototype.showIframe=function(url,params){


	if (!params)
		params={};
	var width = params.width;
	var height = params.height;
	if (!width)
		width=400;
	if (!height)
		height=300;
	if (params.top)
		var top=params.top;
	else
		var top=(document.documentElement.clientHeight-height)/2;//计算上边距
	if (params.left)
		var left=params.top;
	else
		var left=(document.documentElement.clientWidth-width)/2;//计算左边距
	top=parseFloat(top);
	left=parseFloat(left);		
	
	var styles="left";
	var vals=left+"px";
	width+="";
	height+="";
	if(width.indexOf('%')<0){
		width+='px';
	}else if(width.indexOf('%')>0){
		var num = width.substring(0,width.indexOf('%')); 
		left=(document.documentElement.clientWidth-(document.documentElement.clientWidth*num/100))/2;//计算左边距
		vals=left+"px";
	}
	if(height.indexOf('%')<0){
		height+='px';
	}else if(height.indexOf('%')>0){
		var num = height.substring(0,height.indexOf('%')); 
		top=(document.documentElement.clientHeight-(document.documentElement.clientHeight*num/100))/2;//计算上边距
	}
	var objid="popup";
	var title=params.title;
	if(!title)
		title="";
	$("#"+objid+"_popup_div").remove();$("#"+objid+"_fuceng_Box").remove();
	if(params.size=='100%'){
		top = 0;
		left = 0;
		width = "100%";
		height = "100%";
		vals=left+"px";
	}
	var h="<div class='fuchuceng' id='"+objid+"_fuceng_Box' style='"+styles+":"+vals+";width:"+width+";height:"+height+";z-index: 10003'>";
	h+="<div id='"+objid+"_handmove' class='handmove' style='cursor:move'><span class='title_popup'>"+title+"</span>"
	+"<a id='"+objid+"_setAutoWidth' href='javascript:void(0)' onclick='dom_setAutoWidth(this)' title='自适应大小' class='close_popup shiying_popup'><i class='i-pop-shiying'> </i>123</a>"
	+"<a id='"+objid+"_setFullSize' href='javascript:void(0)' onclick='dom_setFullSize(this)' title='最大化' class='close_popup max_popup'><i class='i-pop-max'> </i></a>"
	+"<a href='javascript:void(0)' title='关闭' class='close_popup' id='"+objid+"_close_popup'><i class='i-pop-close'> </i></a>"
	+"</div><div id='"+objid+"_popup_div' class='popup_htmlContent' style='padding:0; width:100%; height:"+(parseInt(height)-31)+"px;'><iframe style='width:100%;height:100%;border:0;' src='"+url+"' id='"+objid+"_popup_iframe' ";
	if(params.size=='100%')
		h+=" onload='dom_setFullSize(\""+objid+"_setFullSize\")'";
	h+="></iframe></div></div>";
	$("body").append(h);

	if(params.size=='auto')
		setTimeout("dom_setAutoWidth('"+objid+"_setAutoWidth')",1000);
	//拖动以及关闭按钮的方法
	$("#"+objid+"_handmove").mousedown(function (e) {
		iDiffX = e.pageX - $(this).offset().left;
		iDiffY = e.pageY - $(this).offset().top;
		var w=$("#"+objid+"_handmove").parent().width();
		$(document).mousemove(function (e) {			
			$("#"+objid+"_handmove").parent().css({ "left": (e.pageX - iDiffX), "top": (e.pageY - iDiffY),"width":w});
		});
	});
	$("#"+objid+"_handmove").mouseup(function () {
		$(document).unbind("mousemove");
	});		
	
	$("#"+objid+"_close_popup").click(function(){
		$("#"+objid+"_popup_div").remove();	$("#"+objid+"_fuceng_Box").remove();	
 	})
	if (params.fadeOut)
	{
		var showSeconds=2000;
		if (params.showSeconds)
			showSeconds=params.showSecond*1000;
		setTimeout("$('.fuchuceng').remove()",showSeconds); 
	}
	return $("#"+objid+"_fuceng_Box");
}
MCDom.prototype.closePopup=function(e)
{
	var objid = "";
	if(e)
	{
		var objid = e.id;
		if(!objid){
			objid=e.className;
			if (objid)
			{
				objid=objid.split(" ");
				if (objid.length>1)
					objid=objid[0];
				if (!objid)
					objid=e.name;
			}else{
				objid="popup";	
			}
		}
	}
	else
	{
		$(".fuchuceng").remove();
	}
	$("#"+objid+"_popup_div").remove();	$("#"+objid+"_fuceng_Box").remove();
}


MCDom.prototype.getAbsPoint=function(e)
{
	var oRect = e.getBoundingClientRect();
	return {
		top: oRect.top,
		left: oRect.left,
		width: e.offsetWidth,
		height: e.offsetHeight,
		bottom: oRect.bottom,
		right: oRect.right
		}
}

MCDom.prototype.getRadioValue=function(name)
{
	var radios=document.getElementsByName(name);
	for(var m=0;m<radios.length;m++)
	{
		if (radios[m].checked)
		{    
			return radios[m].value;
		}
	}
	return null;
}


//alert简单的提醒;
//str:提示的内容；
//title:标题，不写默认为‘提示’；
//type:warning:警告，info:信息，success：成功，fail：失败；
//fade:是否控制时间消失，fadeout:3秒后自动消失，没有则不自动消失；
//width:提示框的宽度；
//height:提示框的高度；
//params.seconds:经过多少秒后自动消失
MCDom.prototype.alert=function(str,title,type,fade,width,height,params)
{
	if (!params)
		params={};
	if (title==undefined) title="提示";
	if (str==undefined) str="";
	var tips_type="";
	if(type=='warning')
		tips_type="alert_ico";
	else if(type=="info")
		tips_type="info_ico";
	else if(type=="success")
		tips_type="success_ico";
	else if(type=="fail")
		tips_type="fail_ico";
	if(tips_type)
		s="<div class='system_Ico "+tips_type+"'>"+str+"</div>";
	else
		s="<div class='no_type_tip'>"+str+"</div>";
	if(fade && fade=="fadeout")
	{
		var yes=true;
	}else{
		var yes=false;
	}
	this.showPopup(null,s,"middle",null,null,height,width,title,{fadeOut:yes,seconds:params.seconds});
}
//简单的提醒
MCDom.prototype.minialert=function(title)
{
	var top=(document.documentElement.clientHeight-50)/2;
	var left=(document.documentElement.clientWidth-200)/2;
	var h="<div class='titleTips' style='top:"+top+"px;left:"+left+"px;'>"+title+"</div>";
	$('body').append(h);
	//setTimeout("$('.titleTips').remove()",2000); 
}

/*简单的浮层输入框，确定后调用回调函数
params.css
params.position
*/
MCDom.prototype.input=function(callback,initvalue,params)
{
	if (!params)
		params={};
	if (!params.css) params.css="width:220px";
	var h="<input type='text' id='mcssdom_input' style='"+params.css+"' value='"+initvalue+"'>";
	h+="<input type='button' id='mcssdom_confirminput' value='确定'>";
	var e=this.showPopup(event.srcElement,h,params.position,null,null,null,290,"输入");
	$("#mcssdom_input").focus();
	$("#mcssdom_input").keyup(function(){
		if (event.keyCode==13)
		{
			$("#mcssdom_confirminput").click();
		}
	})
	$("#mcssdom_confirminput").click(function(){
		if (callback)
			if (callback($("#mcssdom_input").val()))
				$(e).remove();
	})
}
/*确认提示框
params.focus:是否默认按钮
*/
MCDom.prototype.comfirm=function(str,callback,params)
{	
	var comfirmStyle="<div class='comfirmbox'>"+str+"</div><div class='comfirmbox_bottom'><input type='button' id='mcssdom_confirminput' class='btn btn-green mcssingbutton' value='确定' /><a id='mcssdom_confirmquxiao' class='btn mcssingbutton'>取消</a></div>";
	var e=this.showPopup(e,comfirmStyle,"middle",null,null,200,300,"提示");
	if (params && params.focus=='OK')
		$("#mcssdom_confirminput").focus();	
	if (params && params.focus=='CANCEL')
		$("#mcssdom_confirmquxiao").focus();	
			
	var _this=this;
	$("#mcssdom_confirminput").click(function(){
		if (callback)
		{
			callback(_this.confirm_params);
			_this.closePopup(e);
		}else
			_this.closePopup(e);
	});
	$("#mcssdom_confirmquxiao").click(function(){
		$(e).remove();
	})
}

//div:容纳菜单的div
//srcElement:产生菜单的那个按钮对象
function dom_showPopupMenu(div,srcElement)
{
	div.css("position", "absolute");//让这个层可以绝对定位   
	var p = srcElement.getBoundingClientRect();
	var x = p.left;
	var docWidth = $(document).width();//获取网页的宽  
	if (x > docWidth - div.width() ) {  
		x = p.left - div.width();  
	}  

	var top=parseInt(p.top+20);
	var left=x;
	if(window.event)
	{
		var e1 = window.event;
		left=e1.offsetLeft || e1.clientX+document.body.scrollLeft+document.documentElement.scrollLeft;
		top=e1.offsetTop || e1.clientY+document.body.scrollTop+document.documentElement.scrollTop;
	}
	else
	{
		top=document.documentElement.scrollTop+p.top+p.height;//+(document.documentElement.clientHeight);
		left=document.documentElement.scrollLeft+p.left+p.width;//+(document.documentElement.clientWidth); 
	}
	 
	div.css({"left":left,"top":top});  
	div.show();  
}