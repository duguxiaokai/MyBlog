// JavaScript Document
//Mcssing sysInfo Top
$(function(){
	//随浏览器增加减少宽度函数
	 // remove layerX and layerY
    var all = $.event.props,
        len = all.length,
        res = [];
    while (len--) {
      var el = all[len];
      if (el != 'layerX' && el != 'layerY') res.push(el);
    }
    $.event.props = res;

	//左侧快捷菜单和通讯录
	$(".Tabs li").click(function(){
		$("#postionConnet").show();
		$(this).addClass("nav").siblings().removeClass("nav");
		var i=$(".Tabs li").index($(this));
		$("#postionConnet .postionConnet").eq(i).show().siblings().hide();
		
	});
	//框架顶部隐藏显示功能
	$(".clickTop").toggle(function(){
		var RightTopHeight=49;
		$(".RightTop").slideUp();
		$(this).attr("title","展开全景管理菜单").addClass("showTop");
		var mainIframeH=$(".mainIframe").height()+RightTopHeight;
		$(".mainIframe").height(mainIframeH);
		$(".tableCon").height(mainIframeH);
	},function(){
		var RightTopHeight=49;
		$(".RightTop").slideDown();
		$(this).attr("title","收起全景管理菜单").removeClass("showTop");
		var mainIframeH=$(".mainIframe").height()-RightTopHeight;
		$(".mainIframe").height(mainIframeH);
		$(".tableCon").height(mainIframeH);
	});
	//框架左部隐藏显示功能
	$(".showbutton").toggle(function(){
		var secondMenuW=250-43;
		$(".secondMenu").slideUp();
		$("#leftTd").width(43);$(".leftBox").width(43);
		$(this).attr("title","显示左侧工具栏").addClass("hidebutton");
		
	},function(){
		var secondMenuW=207;
		$(".secondMenu").slideDown();$("#leftTd").width(250);$(".leftBox").width(250);
		$(this).attr("title","隐藏左侧工具栏").removeClass("hidebutton");
		
	});
})
