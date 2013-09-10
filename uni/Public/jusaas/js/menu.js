/*
MCSS功能菜单处理器
作者：陈坤极
*/
//一级菜单
var g_allmenu;
function first_createMenu(mcss_menu_id)
{
	var appurl=getHomeUrl();
	$.getJSON(appurl+"/Home/Index/getLoginUserFunc",{},function(data){
		g_allmenu=data;
		var h="";
		var s="";
		for(var i=0;i<data.length;i++) //一级菜单
		{	
			if (!data[i].groupno)
				h+="<li class='top' id='li_"+data[i].no+"'><a  id='"+data[i].no+"' onclick='openMenu(this);'><span id='"+data[i].no+"' onclick='createSecondMenu(this)'>"+data[i].name+"</span></a>";
		}
		$("#"+mcss_menu_id).children("ul").append(h);
	})
	
}

function createSecondMenu(obj)
{	

	var h2="";
	var data=g_allmenu;
	h2+="<ul>";
	for(var j=0;j<data.length;j++) //二级菜单
	{
		if (data[j].groupno==obj.id)
		{
			if (menu_has_sub_menu(data,data[j].no))
			{
				h2+="<li><a  id='"+data[j].no+"' onclick='openMenu(this);'>"+data[j].name+"</a>";
				h2+="<ul>";
				for(var k=0;k<data.length;k++) //三级菜单
				{
					if (data[k].groupno==data[j].no)
					{
						h2+="<li><a id='"+data[k].no+"' onclick='openMenu(this);'>"+data[k].name+"</a></li>";
					}
				}								
				h2+="</ul>";
				//break;
			}
			else
				h2+="<li><a  id='"+data[j].no+"' onclick='openMenu(this);'>"+data[j].name+"</a>";


			h2+="</li>";
			
		}
	}
	h2+="</ul>";
	$("#second_Menu").html("");
	$("#second_Menu").append(h2);
	$(".Tabs li").removeClass("nav");
	$("#postionConnet").hide().addClass('position_menu');
	$("#second_Menu").show();
	
	on_resize();


}
//在指定的div出创建菜单
function menu_createMenu(mcss_menu_id)
{	
	var appurl=getHomeUrl();
	$.getJSON(appurl+"/Home/Index/getLoginUserFunc",{},function(data){
		//alert(data.length);
		var h="";
		var s="";
		for(var i=0;i<data.length;i++) //一级菜单
		{
			if (!data[i].groupno)
			{
			h+="<li class='top' id='"+data[i].no+"'><a class='top_link'><span id='"+data[i].no+"' onclick='openMenu(this);'>"+data[i].name+"</span></a>";
			if (menu_has_sub_menu(data,data[i].no))
			{
				h+="<ul class='sub'>";
				for(var j=0;j<data.length;j++) //二级菜单
				{
					if (data[j].groupno==data[i].no)
					{
						s+=data[j].name+"-";
						if (menu_has_sub_menu(data,data[j].no))
						{
							h+="<li class='mid'><a  class='fly'  id='"+data[j].no+"' onclick='openMenu(this);'>"+data[j].name+"</a>";
							h+="<ul>";
							for(var k=0;k<data.length;k++) //三级菜单
							{
								if (data[k].groupno==data[j].no)
								{
									h+="<li><a  id='"+data[k].no+"' onclick='openMenu(this);'>"+data[k].name+"</a></li>";
								}
							}								
							h+="</ul>";

						}
						else
							h+="<li><a  id='"+data[j].no+"' onclick='openMenu(this);'>"+data[j].name+"</a>";


						h+="</li>";
						
					}
				}
				h+="</ul>";
			}
			}
			h+="</li>";
		}
		//h=createMenu(data,"");
		$("#"+mcss_menu_id).append(h);
	})
}

function createMenu(data,parent_menucode)
{
	var h="";
	var li_css="";
	var a_css="";
	for(var i=0;i<data.length;i++) //一级菜单
	{
		if (data[i].groupno==parent_menucode)
		{
			if (menu_has_sub_menu(data,data[i].no))
			{
				if (parent_menucode=="")
				{
					li_css="top";
					a_css="top_link";
				}
				else
				{
					li_css="mid";
					a_css="fly";
				}				
				h+="<li class='"+li_css+"'><a class='"+a_css+"'><span id='"+data[i].no+"' onclick='openMenu(this);'>"+data[i].name+"</span></a>";

				h+="<ul class='sub'>";
				h+=createMenu(data,data[i].no);
				h+="</ul>";
			}
			else
				h+="<li><a><span id='"+data[i].no+"' onclick='openMenu(this);'>"+data[i].name+"</span></a>";

			h+="</li>";
		}
	}
	return h;
}

//判断是否有子菜单
function menu_has_sub_menu(data,menucode)
{
	for(var i=0;i<data.length;i++)
	{
		if (data[i].groupno==menucode)
		{
			return true;
		}
	}
	return false;
}

function stuHover() 
{
	var cssRule;
	var newSelector;
	for (var i = 0; i < document.styleSheets.length; i++)
		for (var x = 0; x < document.styleSheets[i].rules.length ; x++)
			{
			cssRule = document.styleSheets[i].rules[x];
			if (cssRule.selectorText.indexOf("LI:hover") != -1)
			{
				newSelector = cssRule.selectorText.replace(/LI:hover/gi, "LI.iehover");
				document.styleSheets[i].addRule(newSelector , cssRule.style.cssText);
			}
		}
	var getElm = document.getElementById("mcss_menu").getElementsByTagName("LI");
	for (var i=0; i<getElm.length; i++)
	{
		getElm[i].onmouseover=function()
		{
			this.className+=" iehover";
		}
		getElm[i].onmouseout=function()
		{
			this.className=this.className.replace(new RegExp(" iehover\\b"), "");
		}
	}
}
//if (window.attachEvent) window.attachEvent("onload", stuHover);

var winindex=0;
//打开菜单指定的功能
function openMenu(a)
{
	var iframeurl=getHomeUrl()+"/Home/Index/openMenuUrl/func_code/"+a.id;
	if (a.url)
		iframeurl=a.url;
	setCookie("mcss_iframeurl",iframeurl);
	setCookie("mcss_iframename",a.innerHTML);
	setCookie("mcss_lastmenuid",a.id);
	//记录关闭之前菜单的Cook.
	$.getJSON(getHomeUrl()+"/Home/Index/getParentMenuIds",{"func_code":a.id},function(data){
		if(data[0])
			setCookie("mcss_first_level_menu",data[0]);
		else
			setCookie("mcss_first_level_menu",'');
		if(data[1])
			setCookie("mcss_second_level_menu",data[1]);
		else
			setCookie("mcss_second_level_menu",'');
		if(data[2])
			setCookie("mcss_third_level_menu",data[2]);
		else
			setCookie("mcss_third_level_menu",'');
		if(data[3])
			setCookie("mcss_fourth_level_menu",data[3]);
		else
			setCookie("mcss_fourth_level_menu",'');
	})
	
	if ($("#hideheader").attr("checked")==true)
	{
		window.open(iframeurl);
	}
	else
	{
		if ($("#subwindow").size()>0)
		{
			createSubWin(a);
		}
		else
			$("#mainiframe").attr("src",iframeurl);
	}
	
	//随浏览器增加减少宽度函数
	on_resize();
	
}


var g_subwin_index=new Array();
//创建子窗口
function createSubWin(a)
{
	var iframeurl=getCookie("mcss_iframeurl");
	if (!menu_getArrayValue(g_subwin_index,"id",a.id,"id"))
	{
		if ($("#subwinheader").children().size()>10)
		{
			alert('打开的窗口太多，请先关闭部分窗口。');
			return;
		}
		/*设置tab的宽度*/
		var w=$('#subwinheader').width();
		var sizeLi=$("#subwinheader .menuli").size();
		var widthLi=$('.menuli').width();
		var i=(w/sizeLi)-1;
		var leftWidth=sizeLi*(widthLi-22);
		winindex++;
		g_subwin_index.push({index:winindex,id:a.id});
		var menuname=getCookie("mcss_iframename");
		$(".subwindow").hide(); // onmouseover='show(this)' onmouseout='hide(this)'
		var h="<div id='list_"+winindex+"' style='left:"+leftWidth+"px;' onclick='showthiswin(this,"+winindex+")' "
		+" onDblClick='openInNewWindow("+winindex+",\""+a.id+"\",event)' class='menuli'><div class='t_tab_norl'>"
		+" <div class='t_tab_norm'><div class='t_tab_norr'><a title='关闭该页面' href='javascript:void(0);' id='close_"+winindex+"' onclick='closeSubWin("+winindex+",\""+a.id+"\",event)' class='close_tab'></a>"
		+"<a title='' id='winheader_"+winindex+"'>"+menuname+"</a></div></div></div></div>";
		$("#subwinheader").append(h);

		var neww="<div class='subwindow' id='subwindiv_"+winindex+"'><iframe id='mainiframe_"+winindex+"' class='mainIframe' src='"+iframeurl+"' marginwidth='0' marginheight='0' scrolling='yes' border='0' frameborder='0' style='width:100%;height:100%'></iframe></div>";
		$("#subwindow").append(neww);
		
		showCurrentSubwin(winindex);
		
		//打开后就滚动
		var $content = $(".subTab");
		var isize = parseInt(($('.subTab_Con').width()-40)/98);//已知显示的<a>元素的个数
		var m = 10;  //用于计算的变量数
		var count = $content.find(".menuli").length;//总共的<a>元素的个数
		if(count > isize){
			$(".subTab").animate({left: "-=98px"}, 600);
			m++;
		}
		
		//点击左右导航滚动
		$(".menu_huadong_right").click(function(){
			var menuLiWidth=($content.find(".menuli").length)*98;var subTabWidth=$content.width();
			var contentLeft=$content.position().left;
			var jisuanWidth=subTabWidth-contentLeft;
			if(menuLiWidth > subTabWidth || jisuanWidth > subTabWidth){
				if( !$content.is(":animated")){  //判断元素是否正处于动画，如果不处于动画状态，则追加动画。
					if(m<count){  //判断 i 是否小于总的个数
					m++;
				
					$content.animate({left: "-=98px"}, 600);
					}
				}
			}
		});
		$(".menu_huadong_left").click(function(){
			var contentLeft=$content.position().left;
			if(contentLeft < 0){
				if( !$content.is(":animated")){
					if(m>isize){ //判断 i 是否小于总的个数
					m--;
					$content.animate({left: "+=98px"}, 600);
					}
				}
			}
		
		});
		
	}
	else
	{
		for(var i=0;i<g_subwin_index.length;i++)
		{
			if (g_subwin_index[i].id==a.id)
			{
				var index=g_subwin_index[i].index;
				$("#mainiframe_"+index).attr('src',iframeurl);
				showCurrentSubwin(index);
			}
		}
	}

}

function showCurrentSubwin(winindex)
{
	$(".menuli").removeClass('li_hover');
	$("#winheader_"+winindex).parent().parent().parent().parent().addClass('li_hover');
	$(".subwindow").hide();
	$("#subwindiv_"+winindex).show();
	
}
function subWinLeft(){
	var sizeLi=$("#subwinheader .menuli").size();
	var widthLi=$('.menuli').width();
	$(".subTab > .menuli").each(function(){
		var i=$(".subTab > .menuli").index($(this));	
		var leftWidth=i*(widthLi-22);
		$(this).animate({left:leftWidth+"px"})
	});	
}
//在浏览器新tab窗口打开当前页面
function openInNewWindow(winindex,id,event)
{
	for(var i=0;i<g_subwin_index.length;i++)
	{
		if (g_subwin_index[i].id==id)
		{
			g_subwin_index[i].id = '';
		}
	}
	var lengths = $("#list_"+winindex).next().length;
	var url=$("#mainiframe_"+winindex).get(0).contentDocument.location.href;
	window.open(url);
}

//关闭当前子窗口
function closeSubWin(winindex,id,event)
{
	for(var i=0;i<g_subwin_index.length;i++)
	{
		if (g_subwin_index[i].id==id)
		{
			g_subwin_index[i].id = '';
		}
	}
	var lengths = $("#list_"+winindex).next().length;
	if($("#mainiframe_"+winindex).attr('src') == getCookie('mcss_iframeurl'))
	{
		if(lengths == 0)
		{
			var	ids=$("#winheader_"+winindex).parent().parent().parent().parent().prev('.menuli').attr('id');
			if(ids)
			{
				var previd=ids.substr(5);
				$("#list_"+winindex).prev().children("span:eq(0)").css("color","");
				$("#subwindiv_"+winindex).prev().show();
				setCookie('mcss_iframeurl',$("#list_"+(winindex-1)).data('src'));
				$("#list_"+winindex).remove();
				$("#subwindiv_"+winindex).remove();
				showCurrentSubwin(previd);
				subWinLeft();
			}else
			{
				$("#list_"+winindex).remove();
				$("#subwindiv_"+winindex).remove();
//				alert("只剩下一个标签，不允许关闭");
			}
		}else if (lengths > 0)
		{
			var	ids=$("#winheader_"+winindex).parent().parent().parent().parent().next('.menuli').attr('id');
			var nextid=ids.substr(5);
			$("#list_"+winindex).next().children("span:eq(0)").css("color","");
			$("#subwindiv_"+winindex).next().show();
			setCookie('mcss_iframeurl',$("#list_"+winindex).next().data('src'));
			
			$("#list_"+winindex).remove();
			$("#subwindiv_"+winindex).remove();
			showCurrentSubwin(nextid);
			subWinLeft();
		}
	}else
	{
		
		var prev=$("#list_"+winindex).next().length;
		if(prev==0)
		{  
			var	ids=$("#winheader_"+winindex).parent().parent().parent().parent().prev('.menuli').attr('id');			
			var previd=ids.substr(5);
			$("#list_"+winindex).remove();
			$("#subwindiv_"+winindex).remove();
			showCurrentSubwin(previd);
			subWinLeft();
		}else
		{
			var	ids=$("#winheader_"+winindex).parent().parent().parent().parent().next('.menuli').attr('id');
			var nextid=ids.substr(5);
			
			$("#list_"+winindex).remove();
			$("#subwindiv_"+winindex).remove();
			showCurrentSubwin(nextid);	
			subWinLeft();		
		}	
	}
	stopBubble(this);
		
	/*设置tab的宽度
		var w=$('#subwinheader').width();
		var sizeLi=$("#subwinheader .menuli").size();
		var widthLi=$('.menuli').width();
		var i=(w/sizeLi)-1;
		if(i<widthLi){
			$('.menuli').width(i);
		}else{
			$('.menuli').width(widthLi);
		}
		var event = event || window.event;
		event.cancelBubble=true;*/ //对火狐无效
}

   

//获得某数组指定key的某字段的值
function menu_getArrayValue(arr,keyname,keyvalue,field)
{	
	var n=arr.length;
	var r;
	for(var i=0;i<n;i++)
	{
		if (arr[i][keyname]==keyvalue)
		{
			r=arr[i][field];
			break;
		}
	}
	return r;
}

function showthiswin(e,winindex)
{
	var arr=e.id.split("_");
	$(".menuli").removeClass('li_hover');
	$("#winheader_"+arr[1]).parent().parent().parent().parent().addClass('li_hover');
	$(".subwindow").hide();
	$("#subwindiv_"+arr[1]).show();
	setCookie('mcss_iframeurl',$("#mainiframe_"+winindex).attr('src'));
	//setCookie('mcss_iframename',$(e).find('a').eq(1).html());
}
//打开菜单指定的功能
function openhome()
{
	var iframeurl=getHomeUrl()+"/OA/Index/home";
	setCookie("mcss_iframeurl",iframeurl);
	if ($("#hideheader").attr("checked")==true)
	{
		window.open(iframeurl);
	}
	else
	{
		$("#mainiframe").attr("src",iframeurl);
	}
}

//打开登陆后或刷新浏览器时显示的页面
function openDefaultPage(homepage,name)
{
	if ($("#subwindow").size()>0)
	{
		var a=new Object();
		var lastmenuid=getCookie("mcss_lastmenuid");
			
		if (lastmenuid && lastmenuid!='null')
		{
			a.id =lastmenuid;
		}
		else if (homepage!='void')
		{
			var app=getCookie('mcss_app');
			var page=g_homeurl+"/"+app+"/Index/home";
			if (homepage)
				page=homepage;
			setCookie("mcss_iframeurl",page);
			if(name)
				setCookie("mcss_iframename",name);
			else
				setCookie("mcss_iframename","工作台");
			a.id ='mcss_homepage';
		}
		if (a.id)
			createSubWin(a);
	}
	else
	{
		iframeurl =getCookie("mcss_iframeurl");
		if (iframeurl != 'null' && iframeurl != null && iframeurl !="" ){
			$("#mainiframe").attr("src",iframeurl);
		}
	}
}

//阻止冒泡
function stopBubble(e)  
{  
    if (e && e.stopPropagation)  
        e.stopPropagation()  
    else 
        window.event.cancelBubble=true 
}  
