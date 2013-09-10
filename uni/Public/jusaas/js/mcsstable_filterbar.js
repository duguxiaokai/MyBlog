/*
高级自动过滤处理代码
作者：陈坤极,刘海风
创建时间：2012-5-2
*/
//alert("高级自动过滤处理代码");


//创建高级过滤栏，即把mcss对象模型中标记了设置模型的字段列出来，单击查询输入框时显示浮出层然后选择过滤条件 
function mcsstable_createFilterBar(tableid)
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
	for(i in filterArr)
	{	
		if (filterArr[i].filter)
		{
			if (filterModels!="")
			filterModels+=",";
			filterModels+=filterArr[i].filter;
			h="<div class='"+tableid+"filterCon' ><span id='"+filterArr[i].id+"'>"+mcsstable.getFieldInfo(filterArr[i].id,"name")+"</span><input type='text' class='mcss_filter_input'  id=\""+tableid+"_filter_"+filterArr[i].id+"\" onclick='mcsstable_showfilterdiv(\""+tableid+"\",this)' value='' style='width:70px;' readonly='true'/></div>";
			$("#"+tableid+"_filterbar").append(h);
			$("#"+tableid+"_filter_"+filterArr[i].id).data("filter",filterArr[i].filter);
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
	
	h="<input type='button' value='清除所有' onclick='clearAll(\""+tableid+"\");' style='border:#E6B15C 1px solid; background-color:#F8EBC7; color:#B57D27; font-weight:bold;cursor:pointer'/>";
	$("#"+tableid+"_filterbar").append(h);
	
}

//把从控制器返回的过滤数据记录在mcsstable对象中，便于后面每个过来字段调用，避免每个字段都要单独去数据库获取数据
function mcsstable_setFilterData(tableid,fielterdata)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	mcsstable.filterdata=fielterdata;

}

//单击查询输入框时显示浮出层然后选择过滤条件。改方法时创建一个字段的查询页面，放在一个浮出层中 。参数obj时过来字段的查询输入框input
function mcsstable_showfilterdiv(tableid,obj){
	
	var filetermodel=$("#"+obj.id).data("filter");
	$("#"+tableid+"filter").remove();
	$(".fuchuceng").remove();
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var arr=mcsstable.filterdata;
	var newline=false;
	var content="";
	var datatype='';
	for(i in arr)
	{	
		if (arr[i].modelcode==filetermodel)
		{
			datatype=arr[i].datatype;
			break;
		}
	}
	if (datatype=="s")	//判断类型为字符型多选
	{	
		content+=mcsstable_createFilter_string(arr,filetermodel,tableid,obj); 
	}	
	if (datatype=="r") //判断类型为数字型单选
	{	
		content+=mcsstable_createFilter_radio(arr,filetermodel,tableid,obj); 
	}	
	if (datatype=="f")	//判断类型为数字区间类型
	{	
		content+=mcsstable_createFilter_float(arr,filetermodel,tableid,obj);
	}
	if (datatype=="b")	//判断类型为布尔类型
	{	
		content+=mcsstable_createFilter_ifnull(arr,filetermodel,tableid,obj); 
	}	
	if(datatype=="sd")  //判断是否发送
	{
		content+=mcsstable_createFilter_ifsend(arr,filetermodel,tableid,obj); 
	}
	if(datatype=='tq')
	{
		content+=mcsstable_createFilter_timearea(arr,filetermodel,tableid,obj);
	}
	if (content=="")
		content="(缺少过滤项目)";
		var h="<div id='"+tableid+"filter' style='overflow:hidden;padding:10px;'>"+content+"<div class='closeDiv' style='float:right;cursor:pointer;'><a id='"+tableid+"_clearFilter' class='closebox' title='清除条件' onclick='clearFilter(this)'>清除</a></div></div>"
		//$(obj.parentNode).append(h);
		var title=$(obj).prev().text();
		mcdom.showPopup(obj,h,null,null,null,300,375,title)
		$("#"+tableid+"_clearFilter").data("filter_input_id",obj.id);
		$("#"+tableid+"_clearFilter").data("tableid",tableid);
		
	if (mcsstable.params.afterShowFilter){
		var func=mcsstable.params.afterShowFilter;
		func(arr,filetermodel,tableid,obj);
	}
}

//清除所有条件
function clearAll(tableid)
{
	var text=$("."+tableid+"filterCon :text").length;
	for(i=0;i<text;i++)
	{
		var textvalue=$("."+tableid+"filterCon :text:eq("+i+")").val();
		if(textvalue != '')
		{
			$("."+tableid+"filterCon :text:eq("+i+")").attr('value','');
			$("."+tableid+"filterCon :text:eq("+i+")").data("filter_sql","");
		}
	}
	applyAllFilter(tableid);
}

function clearFilter(obj,tableid)
{
var input_id=$("#"+obj.id).data("filter_input_id");
var tableid=$("#"+obj.id).data("tableid");

$("#"+input_id).val("");
$("#"+input_id).data("filter_sql","");
applyAllFilter(tableid);
$(".fuchuceng").remove();

}
function getThisModelFilter(allFilerDataArr,filetermodel)
{
	var r=new Array();
	for(i in allFilerDataArr)
	{
		if (allFilerDataArr[i].modelcode==filetermodel)
		{
			r.push(allFilerDataArr[i]);
		}
	}
	return r;
}
function mcsstable_createFilter_ifnull(arr,filetermodel,tableid,obj)
{	
	var h="";
	h+="<input type='radio'  name='ifNull' value='有' onchange='filterIfNull(\""+ obj.id+"\",\""+tableid+"\",this);' /><span>有</span>";
	h+="<input type='radio'  name='ifNull' value='无' onchange='filterIfNull(\""+ obj.id+"\",\""+tableid+"\",this);'/><span>无</span>";	
	return h;
}
//创建多选字符项目过滤器DIV
function mcsstable_createFilter_string(arr,filetermodel,tableid,obj)
{	
	var newline=false;
	var content="";
	var lineN=0;
	for(i in arr)
	{	
		if(i==0)
		{	
			content+="<div style='overflow:hidden; margin-top:5px;'><span style='float:left'>请输入:</span><input type='text' value='关键字搜索' id=\""+ obj.id+"_"+filetermodel+"\" onchange='inputFilter(\""+ obj.id+"\",\""+tableid+"\",this);' onfocus='if (value ==\"关键字搜索\"){value =\"\"}' onblur='if (value ==\"\"){value=\"关键字搜索\"}'  /></div>";
		}
		if (arr[i].modelcode==filetermodel)
		{
			content+="<div style='width:100px;float:left;' ><input style='float:left;' type='checkbox' name='checkboxoption'  value='"+arr[i].name+"' onchange='applyFilter_String(this,\""+ obj.id+"\",\""+tableid+"\",\""+filetermodel+"\");'/>"+arr[i].name+arr[i].unit+"</div>";
			datatype=arr[i].datatype;
			lineN++;
			if(lineN==3)
			{
				content+="<br />";
				lineN=0;
			}
			if (newline==3)
			{
				content+="<br />";
			}
		}
		newline=!newline;
	}
	return content;
}

//创建数字区间过滤器DIV
function mcsstable_createFilter_float(arr,filetermodel,tableid,obj)
{	
	var h="";
	var n=0;
	var lineN=0;
	arr=getThisModelFilter(arr,filetermodel);
	for(i in arr)
	{
		if (n==0)
			{
				h+="<div style='width:100px;float:left;' ><input  name=\""+obj.id+"_"+filetermodel+"\" style='float:left;'  type='radio' value='"+arr[i].name+"' onchange='applyFilter_Radio(this,\""+tableid+"\",\""+arr[i].unit+"\",\""+ obj.id+"\");' />"+"<span>"+arr[i].name+arr[i].unit+"以下"+"</span></div>";
				lineN++;
			}
		var j=i;
		j++;
		if (arr[j])
		{
			h+="<div style='width:100px;float:left;' ><input name=\""+obj.id+"_"+filetermodel+"\"  type='radio' value='"+arr[i].name+"' onchange='applyFilter_Radio(this,\""+tableid+"\",\""+arr[i].unit+"\",\""+ obj.id+"\");'/>"+"<span>"+arr[i].name+"-"+arr[j].name+arr[j].unit+"</span></div>";
			lineN++;
			
		}
		if (!arr[j])
		{
			h+="<div  class='price' style='width:100px;float:left;' ><input  name=\""+obj.id+"_"+filetermodel+"\" type='radio' value='"+arr[i].name+"' onchange='applyFilter_Radio(this,\""+tableid+"\",\""+arr[i].unit+"\",\""+ obj.id+"\");' />"+"<span>"+arr[i].name+arr[i].unit+"以上</span></div>";
			lineN++;
			h+="<div style='float:left;'>手选:<input type='text' id='"+tableid+"_filter_low' style='float:none; width:50px;'/>&nbsp;-&nbsp;<input type='text' style='float:none; width:50px;' id='"+tableid+"_filter_high' /><input type='button' style='float:none;'  value='确定'  onclick='opreatFilter(this,\""+tableid+"\",\""+ obj.id+"\",\""+filetermodel+"\");' ／></div>";
		}
		if (lineN==3)
		{
			h+="<br />";
			lineN=0;
		}	
		n++;
	}	
	return h;
}

//创建单选按钮过滤器DIV
function mcsstable_createFilter_radio(arr,filetermodel,tableid,obj)
{	
	var h="";
	var n=0;
	var lineN=0;
	arr=getThisModelFilter(arr,filetermodel);
	for(i in arr)
	{
		h+="<div class='price' style='width:100px;float:left;' ><input type='radio' name=\""+obj.id+"_"+filetermodel+"\" value='"+arr[i].name+"' onchange='applyFilter_RadioString(this,\""+tableid+"\",\""+arr[i].unit+"\",\""+ obj.id+"\");'/>"+"<span >"+arr[i].name+arr[i].unit+"</span></div>";
		lineN++;
	}
	h+="<div style='float:left;'>手选:<input type='text' id='"+tableid+"_filter_low' style='float:none;' size='8px;'/>-<input type='text' style='float:none;' size='8px;' id='"+tableid+"_filter_high' /><input type='button' style='float:none;'  value='确定'  onclick='opreatFilter(this,\""+tableid+"\",\""+ obj.id+"\",\""+filetermodel+"\");' ／></div>";
	if (lineN==3)
	{
			h+="<br />";
			lineN=0;
	}
	return h;
}
function mcsstable_createFilter_ifsend(arr,filetermodel,tableid,obj)
{
	var h="";
	h+="<input type='radio'  name='ifsend' value='是'/><span>已发送</span>";
	h+="<input type='radio'  name='ifsend' value='否'/><span>未发送</span>";	
	return h;
}
function mcsstable_closefilter()
{
	$(".fuchuceng").remove();

}

//类型为字符型的多选过滤处理
function applyFilter_String(objIput,fieldinput,tableid,filetermodel)
{	
	var input=$('#'+fieldinput+"_"+filetermodel).val();
	var checks=$(":checkbox");
	var v="";
	for(i in checks)
	{
		if (checks[i].checked)
		{
			if (v!="")
				v+=",";
			v+=checks[i].value;
		}
	} 
	if(input && input!="关键字搜索")
	{	
		var values=input+","+v;
		$("#"+fieldinput).attr("value",values);
	}else
	{
		var values=v;
		$("#"+fieldinput).attr("value",values);
	}
	var fieldid=$("#"+fieldinput).prev('span').attr("id");
	values=strToArrStr(values);
	var filter=fieldid +" in ("+values+")";
	$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
	applyAllFilter(tableid);
	//var mcsstable=mcsstable_getMCSSTable(tableid);
	//mcsstable.filter=filter;
	//mcsstable_loaddatarows(tableid);
}

function strToArrStr(str)
{
	var r="";
	var arr=str.split(",");
	for(i in arr){
		if (r!="")
			r+=",";
		r+="'"+arr[i]+"'";
	}
	return r;
}

//类型为字符型的输入框过滤处理
function inputFilter(fieldinput,tableid,objIput)
{
	var inputvalue=$(objIput).val();
	$("#"+fieldinput).attr("value",inputvalue);
	var fieldid=$("#"+fieldinput).prev('span').attr("id");
	var checks=$(":checkbox");
	var v="";
	for(i in checks)
	{
		if (checks[i].checked)
		{
			if (v!="")
				v+=",";
			v+=checks[i].value;
		}
	}
	if(inputvalue && v)
	{	
		var val=v;
		var newval=v.split(',');
		for(i in newval)
		{	
			if(newval[i]==inputvalue)
			{
				var values=v;
				
			}else
			{
				var values=v+","+inputvalue;
			}
		}
		$("#"+fieldinput).attr("value",values);
		var fieldid=$("#"+fieldinput).prev('span').attr("id");
		values=strToArrStr(values);
		var filter=fieldid +" in ("+values+")";
		$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
		
	}else
	{
		var filter=fieldid +" like '"+inputvalue+"'";
		$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
	}
	applyAllFilter(tableid);
}

//类型为布尔型的过滤处理
function filterIfNull(fieldinput,tableid,obj)
{	
	var radio=$("input[name='ifNull'][checked]").val(); 
	var fieldid=$("#"+fieldinput).prev('span').attr("id");
	if(radio=='有'){
		var filter=fieldid +" <> ''";
		$("#"+fieldinput).attr("value",radio);
	}
	else if(radio=='无')
	{
		var filter=fieldid +" = ''";
		$("#"+fieldinput).attr("value",radio);
	}
	$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
	applyAllFilter(tableid);	
}



//类型为数字型的单选按钮过滤处理
function applyFilter_RadioString(radioObj,tableid,unit,fieldinput)
{	
	var inputvalue=$("#"+tableid+"_filter_low").val();
	var inputvalue1=$("#"+tableid+"_filter_high").val();
	if(inputvalue && inputvalue1)
	{
		$("#"+tableid+"_filter_low").attr('value','');
		$("#"+tableid+"_filter_high").attr('value','');
	}
	var n=$(radioObj).next("span").html();
	$("#"+fieldinput).attr("value",n);
	var fieldid=$("#"+fieldinput).prev('span').attr("id");
	if(unit=='')
	{	
		var newNumber=n;
	}else
	{
		var number=n.indexOf(unit);
		if(number==1)
		{	
			var newNumber=n.substr(0,number);
		}else
		{
			var newNumber=n.substring(0,number);
		}
	}
	var filter=fieldid +" in ('"+newNumber+"')";
	$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
	applyAllFilter(tableid);
}

//类型为数字区间过滤处理
function applyFilter_Radio(radioObj,tableid,unit,fieldinput)
{	
	var inputvalue=$("#"+tableid+"_filter_low").val();
	var inputvalue1=$("#"+tableid+"_filter_high").val();
	if(inputvalue && inputvalue1)
	{	
		$("#"+tableid+"_filter_low").attr('value','');
		$("#"+tableid+"_filter_high").attr('value','');
	}
	var n=$(radioObj).next("span").html();
	var arr=n.split("-");
	$("#"+fieldinput).attr("value",n);
	if (arr.length==1)
	{	
		var fieldid=$("#"+fieldinput).prev('span').attr("id");
		var string=arr[0];
		if((number=string.indexOf(unit+"以下"))>-1)
		{
			var newNumber=string.substring(0,number);
			var filter=fieldid +" <= "+newNumber;
		}
		else if((number=string.indexOf(unit+"以上"))>-1)
		{	
			var newNumber=string.substring(0,number);
			var filter=fieldid +" >= "+newNumber;
		}
		$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
		applyAllFilter(tableid);		
	}
	else if (arr.length==2)
	{
		var numbertOne=arr[0];
		if ((numbers=numbertOne.indexOf(unit))>-1)
		{	
			var shuziOne=numbertOne.substring(0,numbers);
		}
		else
		{
			shuziOne=numberOne=arr[0];
		}
		var numbertTwo=arr[1];
		if ((numbers=numbertTwo.indexOf(unit))>-1)
		{	
			var shuziTwo=numbertTwo.substring(0,numbers);
		}else
		{
			shuziTwo=numberTwo=arr[1];
		}
		var fieldid=$("#"+fieldinput).prev('span').attr("id");
		var filter=fieldid +" > "+shuziOne+" and "+fieldid +" < "+shuziTwo;
		$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
		applyAllFilter(tableid);
	}
}

//类型为数字区间的输入框过滤处理
function opreatFilter(radioObj,tableid,fieldinput,filetermodel)
{	
	var inputvalue=$("#"+tableid+"_filter_low").val();
	var inputvalue1=$("#"+tableid+"_filter_high").val();
	var radio=$("[name='"+fieldinput+"_"+filetermodel+"'][checked]").length;
	if(radio == 1)
	{
		var radio=$("[name='"+fieldinput+"_"+filetermodel+"'][checked]").attr("checked",false);
	}
	$("#"+fieldinput).attr("value",inputvalue+","+inputvalue1);
	var fieldid=$("#"+fieldinput).prev('span').attr("id");
	var filter=fieldid +">="+inputvalue+" and "+fieldid+"<="+inputvalue1;
	$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
	applyAllFilter(tableid);
	
}

//时间区域过滤
function mcsstable_createFilter_timearea(arr,filetermodel,tableid,obj)
{
	var h="";
	h+="开始日期:<input type='text' id='starttime' onclick='autoform_selectDate(\"yyyy-MM-dd\")'/></br></br>";
	h+="结束日期:<input type='text' id='endtime' onclick='autoform_selectDate(\"yyyy-MM-dd\")'/></br>";
	h+="<input type='button' value='确定' onclick='opreatTimearea(this,\""+tableid+"\",\""+obj.id+"\",\""+filetermodel+"\");' ／>";
	return h;
	
}

function opreatTimearea(radioObj,tableid,fieldinput,filetermodel)
{
	var inputvalue=$("#starttime").val();
	var inputvalue1=$("#endtime").val();
	$("#"+fieldinput).attr("value",inputvalue+"-"+inputvalue1);
	var fieldid=$("#"+fieldinput).prev('span').attr("id");
	var filter="DATE_FORMAT("+fieldid+",'%Y-%m-%d')>='"+inputvalue+"' and "+"DATE_FORMAT("+fieldid+",'%Y-%m-%d')<='"+inputvalue1+"'";
	$("#"+tableid+"_filter_"+fieldid).data("filter_sql",filter);	
	applyAllFilter(tableid);
	$(".fuchuceng").remove();
}

//把所有过滤条件组合起来过滤
function applyAllFilter(tableid)
{	
	var InputArr=$(".mcss_filter_input");
	var filtersql="";
	for(var i=0;i<InputArr.length;i++)
	{
		var sql=$("#"+InputArr[i].id).data("filter_sql");	
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