/*
高级自动过滤处理代码
作者：陈坤极,刘海风
创建时间：2012-5-2
*/
//alert("高级自动过滤处理代码");

var mcpopup;
//创建高级过滤栏，即把mcss对象模型中标记了设置模型的字段列出来，单击查询输入框时显示浮出层然后选择过滤条件 
function mcsstable_createFilterBar2(tableid)
{
	//alert("创建高级自动过滤处理代码");
	$("#"+tableid+"_caption").height(45);
	var fbar=$("#"+tableid+"_filterbar");
	if (fbar.length>0)
	{
		if (fbar.css("display")=="none")
			fbar.show();
		else{
			fbar.hide();
			$("#"+tableid+"_caption").height(25);
		}
		return;
	}
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var filterArr=mcsstable.modeldata.fields;
	
	var field;
	var h="<div id=\""+tableid+"_filterbar\" style='clear:both'></div>";
	$("#"+tableid+"_caption").append(h);
	var filterModels="";
	var filterbuttom = '';
	for(i in filterArr)
	{	
		if (filterArr[i].filter)
		{	var filterappArr = filterArr[i].filter.split('_');
			if(filterappArr[0] =='mj')
			{
				filterbuttom = filterappArr[0];
				if(filterArr[i].filter == 'mj_timearea')
				{
					h="<div class='"+tableid+"filterCon' id='mj_timearea'><span id='"+filterArr[i].id+"'>"+mcsstable.getFieldInfo(filterArr[i].id,"name")+"</span><input type='text' style='display:none;' class='mcss_filter_input' id=\""+tableid+"_filter_"+filterArr[i].id+"\" ><input type='text' value='' style='width:70px;' readonly='true' onclick='autoform_selectDate(\"yyyy-MM-dd\")' id='start'/><span>-</span><input type='text' value='' style='width:70px;' readonly='true'/ onclick='autoform_selectDate(\"yyyy-MM-dd\")' id='end'>";
				}else if(filterappArr[1] == 'select')
				{	
					filterModels+=",";
					filterModels+=filterArr[i].filter;
					h="<div class='"+tableid+"filterCon'><span id='"+filterArr[i].id+"'>"+mcsstable.getFieldInfo(filterArr[i].id,"name")+"</span><input type='text' class='mcss_filter_input'  id='radioSelect' onclick='mcsstable_showfilterdiv2(this)' value='' style='width:70px;' readonly='true'/></div>";
					$("#"+tableid+"_filter_"+filterArr[i].id).data("filter",filterArr[i].filter);
				
				}else
				{	
					h="<div class='"+tableid+"filterCon'><span id='"+filterArr[i].id+"'>"+mcsstable.getFieldInfo(filterArr[i].id,"name")+"</span><input type='text' class='mcss_filter_input' id=\""+tableid+"_filter_"+filterArr[i].id+"\" name='' style='width:70px;'/></div>";
				}
				$("#"+tableid+"_filterbar").append(h);
			}
			else{
				if (filterModels!="")
				filterModels+=",";
				filterModels+=filterArr[i].filter;
				h="<div class='"+tableid+"filterCon' ><span id='"+filterArr[i].id+"'>"+mcsstable.getFieldInfo(filterArr[i].id,"name")+"</span><input type='text' class='mcss_filter_input'  id=\""+tableid+"_filter_"+filterArr[i].id+"\" onclick='mcsstable_showfilterdiv(\""+tableid+"\",this)' value='' style='width:70px;' readonly='true'/></div>";
				$("#"+tableid+"_filterbar").append(h);
				$("#"+tableid+"_filter_"+filterArr[i].id).data("filter",filterArr[i].filter);
			}
		}else
		{
			if(filterbuttom == 'mj' && filterArr.length-1 == i)
			{
				h ="<input type='button' value='确定' style='border:#E6B15C 1px solid; background-color:#F8EBC7; color:#B57D27; font-weight:bold;cursor:pointer' onclick='totalfilter(\""+tableid+"\")'>";
				$("#"+tableid+"_filterbar").append(h);
			}
		}
	}
	if (mcsstable.params.myFilter)
	{
		var myfilter = mcsstable.params.myFilter;
		 myfilter(tableid,filterArr);
	}
	if (!mcsstable.filterdata)
	{	
		$.getJSON(mcsstable.params.homeUrl+g_systempath+"/getFilterData",{filterModels:filterModels},function(data){
			mcsstable_setFilterData(tableid,data);
		});
	}
	
	h="<input type='button' value='清除' onclick='clearAll2(\""+tableid+"\");' style='border:#E6B15C 1px solid; background-color:#F8EBC7; color:#B57D27; font-weight:bold;cursor:pointer'/>";
	$("#"+tableid+"_filterbar").append(h);
	
}

//把从控制器返回的过滤数据记录在mcsstable对象中，便于后面每个过来字段调用，避免每个字段都要单独去数据库获取数据
function mcsstable_setFilterData(tableid,fielterdata)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	mcsstable.filterdata=fielterdata;

}

function mcsstable_showfilterdiv2(obj)	
{		
	var h='';
	var url=getrooturl()+"/index.php/Mj/Admin/getcustomer";
		$.getJSON(url,function(data){
			
			 h="<div>";
			 for(var i=0;i<data.length;i++)
			 {
				h+="<input type='radio' name='custname' value="+data[i]['name']+" onclick='returnSelectedRows2()'>"+data[i]['name'];
			 }
			h+="</div>";
			var title=$(obj).prev().text();
			mcpopup = mcdom.showPopup(obj,h,null,null,null,375,600,title);
		});
		
		
		
}

function returnSelectedRows2()
{
	var inputValue =$("input[name='custname']:checked").val();
	$('#radioSelect').attr('value',inputValue);
	$(mcpopup).remove();
}

//清除所有条件
function clearAll2(tableid)
{
	var text=$("."+tableid+"filterCon :text").length;
	for(i=0;i<text;i++)
	{
		var textvalue=$("."+tableid+"filterCon :text:eq("+i+")").val();
		if(textvalue != '')
		{
			$("#mj_timearea :text:eq(0)").data("filter_sql","");
			$("."+tableid+"filterCon :text:eq("+i+")").attr('value','');
			$("."+tableid+"filterCon :text:eq("+i+")").data("filter_sql","");
		}
	}
	applyAllFilter2(tableid);
}

function totalfilter(tableid)
{
	var text=$("."+tableid+"filterCon :text").length;
	for(var i=0;i<text;i++)
	{		
		if($("#mj_timearea").length == 1)
		{
			var inputvalue = $("#start").val();
			var inputvalue1 = $("#end").val();
			if(inputvalue > inputvalue1)
			{
				alert('开始日期大于结束日期');return;
			}else
			{
				if(inputvalue || inputvalue1)
				{
					var spanvalue = $("#mj_timearea span").attr('id');
					filter = "DATE_FORMAT("+spanvalue+",'%Y-%m-%d')>='"+inputvalue+"' and "+"DATE_FORMAT("+spanvalue+",'%Y-%m-%d')<='"+inputvalue1+"'";
					$("#"+tableid+"_filter_"+spanvalue).data("filter_sql",filter);
				}
				
			}
		}
		var textvalue=$("."+tableid+"filterCon :text:eq("+i+")").val();
		if(textvalue)
		{
			var spanvalue=$("."+tableid+"filterCon span:eq("+i+")").attr('id');
			filter = spanvalue+" like '%"+textvalue+"%'";
			$("#"+tableid+"_filter_"+spanvalue).data("filter_sql",filter);
		}
	}
	applyAllFilter2(tableid);
	
}

//把所有过滤条件组合起来过滤
function applyAllFilter2(tableid)
{	
	var InputArr=$(".mcss_filter_input");
	var filtersql="";
	
	for(var i=0;i<InputArr.length;i++)
	{
		var sql=$("#"+InputArr[i].id).data("filter_sql");	
		//alert(sql);
		if (sql)
		{
			var sql="("+sql+")";	
			if (filtersql!="")
				filtersql+=" and ";
			filtersql+=sql;
		}
	}
	var mcsstable=mcsstable_getMCSSTable(tableid);
	mcsstable.filter=filtersql;
	mcsstable_loaddatarows(tableid);
}