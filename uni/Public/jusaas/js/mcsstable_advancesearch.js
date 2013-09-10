
function mcsstable_search2(tableid)
{
	var table=mcsstable_getMCSSTable(tableid);
	var div="<div id='"+tableid+"_searchcondition' class='searchcondition' style='display:none'></div>"
	$("#"+tableid).before(div);
	
	var u=location.href;
	var idx=u.indexOf("/index.php");
	var u=u.substr(0,idx);
	if($("#"+tableid+"_searchcondition").css('display')=="none")
	{
		if ($("#"+tableid+"_searchcondition").html()=="")
		{
			$.post(u+"/Public/jusaas/htm/mcsstable_advancesearch.htm",function(data)
			{
				$("#"+tableid+"_searchcondition").html(data);
				$("#"+tableid+"_searchcondition").slideDown(800);
				//html中id增加tableid
				$("#seniorsearch").attr("id",tableid+"_seniorsearch");
				$("#table1").attr("id",tableid+"_table1");
				$("#table2").attr("id",tableid+"_table2");
				$("#table3").attr("id",tableid+"_table3");
				$("#submitsearch").attr("id",tableid+"_submitsearch");
				$("#resetsearch").attr("id",tableid+"_resetsearch");
				$("#savesearch").attr("id",tableid+"_savesearch");
				$("#searchrecord").attr("id",tableid+"_searchrecord");
				$("#hidesearch").attr("id",tableid+"_hidesearch");
				$("#select1").attr("id",tableid+"_select1");
				$("#select2").attr("id",tableid+"_select2");
				$("#select3").attr("id",tableid+"_select3");
				$("#select4").attr("id",tableid+"_select4");
				$("#select5").attr("id",tableid+"_select5");
				$("#select6").attr("id",tableid+"_select6");
				
				$("#"+tableid+"_submitsearch").click(function(){seniorserchlist(tableid)});
				$("#"+tableid+"_resetsearch").click(function(){resetserchlist(tableid)});
				
				$("#"+tableid+"_savesearch").click(function(e){
					$("#"+tableid+"_searchrecord_fuceng_Box").remove();
					var h="<div style='padding:10px;'>"+lang.pleaseinputname+"<input type='text' id='"+tableid+"_savename' /><input type='button' value='"+lang.save+"' id='"+tableid+"_saveallsearch' style='cursor:pointer;' onclick='saveallsearchlist(\""+tableid+"\");' class='setbutton'/></div>";
					var mcdom_condition=mcdom.showPopup(this,h,null,null,null,300,300,lang.save);
					$("#"+tableid+"_savename").keyup(function(){
						if (event.keyCode==13)
							saveallsearchlist(tableid);						
					}).focus();
					//$("#"+tableid).append(h);
				});
				$("#"+tableid+"_hidesearch").click(function(){hideserchlist(tableid)});
				$("#"+tableid+"_searchrecord").click(function(){
					var obj=this;
					$("#"+tableid+"_savesearch_fuceng_Box").remove();
					var modelid = table.modelid;
					$.post(getrooturl()+"/index.php/Sys/System/getSeachValue",{'modelid':modelid},function(data)
					{
						var title = data.split(',');
						$("#"+tableid+"_list p").remove();
						$("#"+tableid+"_list").remove();
						var h="<div id='"+tableid+"_list' style='padding:10px;height:150px; overflow:auto;'>";
						var mcdom_histroy =mcdom.showPopup(obj,h,null,null,null,300,300,"已存的查询名称");
						if(title != '')
						{
							for(i=0;i<title.length;i++)
							{
								$("#"+tableid+"_list").append("<p id='"+tableid+"_biaoti"+i+"'><a href='#' id='"+tableid+"_subject' onclick='changequery(this,\""+tableid+"\","+i+");'>"+title[i]+"</a><span style='float:right;cursor:pointer;padding-right:10px;' onclick='clearName(this,\""+tableid+"\");'>"+lang.del+"</span></p>");
							}
						}
						
					});
				});
				$("#"+tableid+"_select1").change(function(){getSelectedOption(this,tableid)});
				$("#"+tableid+"_select2").change(function(){getSelectedOption(this,tableid)});
				$("#"+tableid+"_select3").change(function(){getSelectedOption(this,tableid)});
				$("#"+tableid+"_select4").change(function(){getSelectedOption(this,tableid)});
				$("#"+tableid+"_select5").change(function(){getSelectedOption(this,tableid)});
				$("#"+tableid+"_select6").change(function(){getSelectedOption(this,tableid)});
				getseniorvalue(tableid);
			});
		}else{
			$("#"+tableid+"_searchcondition").slideDown(800);
		}
	}
	else
		$("#"+tableid+"_searchcondition").slideUp(800);
}
function closeOne(tableid)
{
	$("#"+tableid+"_list").hide();
	
}
function closeTwo(tableid)
{
	$("#"+tableid+"_fucenginput").hide();
}

function clearName(e,tableid)
{	
	var s= $(e).prev().text();
	$(e).parent().remove();
	$.post(getrooturl()+"/index.php/Sys/System/clearValue",{'clearvalue':s});
}

function getModeldata(tableid)
{   
	var m=mcsstable_getMCSSTable(tableid);
    var mm=m.modeldata.fields;
	return mm;
}
function getseniorvalue(tableid)//初始化查询条件选项
{   
    var mm=getModeldata(tableid);
	$(".selectmodelname option").remove();
	for(a=0;a<mm.length;a++)
	{   
		if(mm[a].isvisible!='false')
		{	
			if (lang[mm[a].name])
				mm[a].name=lang[mm[a].name];
			$(".selectmodelname").append("<option>"+mm[a].name+"</option>");
		}
	}
	for(e=0;e<$(".selectmodelname").length;e++)
	{
	    $(".selectmodelname").eq(e).children("option").eq(e).attr("selected","selelcted");
		var object=$(".selectmodelname").get(e);
		getSelectedOption(object,tableid);
	}		
}
function getSelectedOption(obj,tableid)
{   
	value = obj.options[obj.options.selectedIndex].text;
	selectGetOption(obj,value,tableid);
		
}
function selectGetOption(obj,value,tableid)
{  
    var mm=getModeldata(tableid);
	for(j=0;j<mm.length;j++)
	{
	    var mmdata=mm[j].data;
	    if(value==mm[j].name)
	    {  
		    var selecttype=mm[j].type;
			var fieldtype=mm[j].fieldtype;
			
		    if(selecttype=="richeditor")
		    {
			    selectRicheditor(obj,tableid);
		    }
		    else if(selecttype=="dropdown" || selecttype=="smartselect")
		    {  
			    selectDropdown(obj,mmdata);
		    }
		    else if(selecttype=="radio")
		    {
			    selectRadio(obj,mmdata);
		    }
		    else if(selecttype=="checkbox")
		    {
			    selectCheckbox(obj);
		    }
		    else if(selecttype=="checkboxlist")
		    {
			    selectCheckboxlist(obj,mmdata);
		    }
		    else if(selecttype=="date" || fieldtype=="date" )
		    {
			    selectDate(obj,tableid);
				mcss_importJS("/js/DatePicker/WdatePicker.js");
		    }
		    else if(selecttype=="datetime" || fieldtype=="datetime")
		    {
			    selectDate(obj,tableid);
				mcss_importJS("/js/DatePicker/WdatePicker.js");
		    }
		    else
		    {
			    selectOther(obj,fieldtype,tableid);
		    }
	    }
	}
}
function selectRicheditor(obj,tableid)
{  
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='contain'>"+lang.contain+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='notincluded'>"+lang.notincluded+"</option>");
	$(obj).parent('td').next('td').next('td').append("<input type='text' name='selectfieldvalue' style='width:90px' onkeyup='startSearch(this,event,\""+tableid+"\")'>");
}
function startSearch(obj,event,tableid){
	if($(obj).val()){
		if(event.keyCode==13)
			seniorserchlist(tableid);
	}
}
function selectDropdown(obj,mmdata)
{   
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').next('td').append("<select name='selectfieldvalue' style='width:90px'><option></option></select>");
	var selectdata=mmdata.split(",");
	for(b=0;b<selectdata.length;b++)
	{ 	
		var selectdata_num=selectdata[b].split(":");
		if(selectdata_num.length==2)
		{
			var optiondata=selectdata_num[1];
		}
		else
		{
			optiondata=selectdata_num[0];
		}
		var showname=optiondata;
		if (lang[showname])
			showname=lang[showname];
		$(obj).parent('td').next('td').next('td').find('select').append("<option value='"+optiondata+"'>"+showname+"</option>");
		
	}
}
function selectRadio(obj,mmdata)
{   
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').next('td').append("<select name='selectfieldvalue' style='width:90px'><option></option></select>");
	var selectdata=mmdata.split(",");
		
	var showname='';
	for(b=0;b<selectdata.length;b++)
	{ 
		var selectdata_num=selectdata[b].split(":");
		if(selectdata_num.length==2)
		{
			var optiondata=selectdata_num[1];
		}
		else
		{
			optiondata=selectdata_num[0];
		}
		showname=optiondata;
		if (lang[showname])
			showname=lang[showname];
		$(obj).parent('td').next('td').next('td').find('select').append("<option value='"+optiondata+"'>"+showname+"</option>");
	}
}
function selectCheckbox(obj)
{
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').next('td').append("<select name='selectfieldvalue' style='width:90px'><option></option><option>"+lang.yes+"</option><option>"+lang.no+"</option></select>");
}
function selectCheckboxlist(obj,mmdata)
{
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='contain'>"+lang.contain+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option value='notincluded'>"+lang.notincluded+"</option>");
	$(obj).parent('td').next('td').next('td').append("<select name='selectfieldvalue' style='width:90px'><option></option></select>");
	var selectdata=mmdata.split(",");
		
	for(b=0;b<selectdata.length;b++)
	{ 
		var selectdata_num=selectdata[b].split(":");
		if(selectdata_num.length==2)
		{
			var optiondata=selectdata_num[1];
		}
		else
		{
			optiondata=selectdata_num[0];
		}
		var showname=optiondata;
		if (lang[showname])
			showname=lang[showname];
		$(obj).parent('td').next('td').next('td').find('select').append("<option value='"+optiondata+"'>"+showname+"</option>");
	}
}
function selectDate(obj,tableid)
{
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.biggerthan+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.lessthan+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.bigandeq+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.lessandeq+"</option>");
	$(obj).parent('td').next('td').next('td').append("<input name='selectfieldvalue' type='text' onkeyup='startSearch(this,event,\""+tableid+"\")' onclick='mcss_selectDate(\"yyyy-MM-dd\")' style='width:90px'>");
}
function selectDatetime(obj,tableid)
{
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.beequalto+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.doesnotequal+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.biggerthan+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.lessthan+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.bigandeq+"</option>");
	$(obj).parent('td').next('td').find('select').append("<option>"+lang.lessandeq+"</option>");
	$(obj).parent('td').next('td').next('td').append("<input name='selectfieldvalue' type='text' onkeyup='startSearch(this,event,\""+tableid+"\")' onclick='mcss_selectDate(\"yyyy-MM-dd HH:mm:ss\")' style='width:90px'>");
}
function selectOther(obj,fieldtype,tableid)
{
	$(obj).parent('td').next('td').next('td').empty();
	$(obj).parent('td').next('td').find('select').empty();
	if(fieldtype=='int' || fieldtype=='real' || fieldtype=='float')
	{
		$(obj).parent('td').next('td').find('select').append("<option value='biggerthan'>"+lang.biggerthan+"</option>");
		$(obj).parent('td').next('td').find('select').append("<option value='lessthan'>"+lang.lessthan+"</option>");
		$(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
		$(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
		$(obj).parent('td').next('td').find('select').append("<option value='bigandeq'>"+lang.bigandeq+"</option>");
		$(obj).parent('td').next('td').find('select').append("<option value='lessandeq'>"+lang.lessandeq+"</option>");
	}
	else
	{
	    $(obj).parent('td').next('td').find('select').append("<option value='beequalto'>"+lang.beequalto+"</option>");
	    $(obj).parent('td').next('td').find('select').append("<option value='doesnotequal'>"+lang.doesnotequal+"</option>");
		$(obj).parent('td').next('td').find('select').append("<option value='contain'>"+lang.contain+"</option>");
	    $(obj).parent('td').next('td').find('select').append("<option value='notincluded'>"+lang.notincluded+"</option>");
	}	
	$(obj).parent('td').next('td').next('td').append("<input name='selectfieldvalue' type='text' style='width:90px' onkeyup='startSearch(this,event,\""+tableid+"\")'>");
}
function mcss_selectDate(timeformdate)
{
	WdatePicker({skin:'whyGreen',dateFmt:timeformdate,alwaysUseStartDate: true});
}
function resetserchlist(tableid)
{  
    getseniorvalue(tableid);
    var table=mcsstable_getMCSSTable(tableid);
	table.searchword="";
    mcsstable_loaddatarows(tableid);
}

//保存查询
function saveallsearchlist(tableid)
{	
	$("#"+tableid+"_fucenginput").css("display","none");
	var valuename = $("#"+tableid+"_savename").val();
	var mcsstable=mcsstable_getMCSSTable(tableid);
	var modelid = mcsstable.modelid;
	var seniorsql = fetchCurrentValue(tableid);
	if(valuename == '')
	{
		mcdom.alert(lang.pleaseinputname,lang.save,'info','fadeout');
	}else
	{
		$.post(g_homeurl+"/Sys/System/saveSeachValue",{'modelid':modelid,'query':seniorsql,'valuename':valuename},function(data){
			
			if(data == 1)
				alert(lang.cannotrepeat);
			else{
				$("#"+tableid+"_action_historysearch").append("<option value='"+valuename+"'>"+valuename+"</option>");
				mcdom.alert(lang.successfullysaved);
				mcdom.alert(lang.successfullysaved,lang.save,'success','fadeout');
				$("#"+tableid+"_savesearch_fuceng_Box").remove();				
			}
		});
	}
	
}
function GetAbsPoint(e)
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
function searchrecordlist(tableid,event)
{
	
}

//执行某个历史查询
function changequery(e,tableid,i)
{
	var title = $("#"+tableid+"_biaoti"+i+" a").html();
	$.post(getrooturl()+"/index.php/Sys/System/getSeachquery",{'title':title},function(data){
		var mcsstable=mcsstable_getMCSSTable(tableid);
		mcsstable.searchword =data.replace(/\'/g,"<yh>");
		mcsstable_loaddatarows(tableid);
	});
	$(e).parent().parent().parent().parent().remove();
}

function hideserchlist(tableid)
{  
	$("#"+tableid+"_searchrecord_fuceng_Box").remove();
	$("#"+tableid+"_savesearch_fuceng_Box").remove();
	$("#"+tableid+"_searchcondition").hide(1000);
}
function seniorserchlist(tableid)
{  
    var table=mcsstable_getMCSSTable(tableid);
	var seniorsql = fetchCurrentValue(tableid);
	table.searchword =seniorsql.replace(/\'/g,"<yh>");

	if(table.searchword!=="")
	{  
		mcsstable_loaddatarows(tableid);
	}
	else
	{
	   alert(lang.pleaseinputquery);
	}
}

function fetchCurrentValue(tableid)//取到选框中的值并转换为sql语句
{ 
    var selectlist1=$("#"+tableid+"_table1 [name='selectfieldvalue']");
	var selectvalues1="";
	for(var x=0;x<selectlist1.length;x++)
	{   
	    var selectitem1=selectlist1.eq(x);
		var searchval="";
		if(selectitem1.attr("type")=="select-one")
		{
			searchval=selectitem1.find("option:selected").text();
		}
		if(selectitem1.attr("type")=="text")
		{
			searchval=selectitem1.val();
		}
		if(searchval!=="")
		{   
		    var itemvalue1 = selectitem1.val();
		    var titles1 = selectitem1.parent("td").prev("td").prev("td").find("select option:selected").val();
		    var operators1 = selectitem1.parent("td").prev("td").find("select option:selected").val();
			var logicoperator1 = selectitem1.parent("td").prev("td").prev("td").prev("td").find("select option:selected").val();
			
			var titleID1 = getTitleID(titles1,tableid);
			var sqlOper1 = getSqlOper(operators1);
			var logic1 = getLogic(logicoperator1);
			
			var titleType1 = getTitleType(titles1,tableid);
			var TitleData1= getTitleData(titles1,tableid);
			if(titleType1=="checkbox")
			{  
			    if(itemvalue1=="是")
				    itemvalue1=1;
				else
                    itemvalue1=0;				
			}
			if(titleType1=="dropdown"  || titleType1=="smartselect")
			{   
				var data1=TitleData1.split(",");
				
				for(f=0;f<data1.length;f++)
	            {
	                var num_data=data1[f].split(":");
					
		            if(itemvalue1==num_data[1])
					{  
					    itemvalue1=num_data[0];
					}
	            }
			}
			if(titleType1=="radio")
			{   
				var data1=TitleData1.split(",");
				
				for(f=0;f<data1.length;f++)
	            {
	                var num_data=data1[f].split(":");
					
		            if(itemvalue1==num_data[1])
					{  
					    itemvalue1=num_data[0];
					}
	            }
			}
			if(titleType1=="checkboxlist")
			{   
				var data1=TitleData1.split(",");
				
				for(f=0;f<data1.length;f++)
	            {
	                var num_data=data1[f].split(":");
					
		            if(itemvalue1==num_data[1])
					{  
					    itemvalue1=num_data[0];
					}
	            }
			}
			
			if(logic1==undefined)
			{
			    logic1="";
			}
			if(sqlOper1=="like" || sqlOper1=="not like")
			{
			   itemvalue1="%"+itemvalue1+"%";
			}
			
			var pretdlength1 = $(selectitem1).parent("td").parent("tr").prev("tr").children("td").length;
			var pretdvalue1 = $(selectitem1).parent("td").parent("tr").prev("tr").children("td").eq(3).children().val();
			
			if(pretdlength1=4 && pretdvalue1 !=="" )
			{
			    selectvalues1 += " "+logic1+" "+titleID1+" "+sqlOper1+"'"+itemvalue1+"'";
			}
			else if(pretdvalue1 =="")
			{
			    selectvalues1 += " "+titleID1+" "+sqlOper1+"'"+itemvalue1+"'";
			}
		}
    }
	
	var selectlist2=$("#"+tableid+"_table3 [name='selectfieldvalue']");
	var selectvalues2="";
	for(var x=0;x<selectlist2.length;x++)
	{ 
	    var selectitem2=selectlist2[x];
		if(selectitem2.value!=="")
		{   
		    var itemvalue2 = selectitem2.value;
		    var titles2 = $(selectitem2).parent("td").prev("td").prev("td").find("select option:selected").text();
		    var operators2 = $(selectitem2).parent("td").prev("td").find("select option:selected").val();
			var logicoperator2 = $(selectitem2).parent("td").prev("td").prev("td").prev("td").find("select option:selected").val();
			var titleID2 = getTitleID(titles2,tableid);
			var sqlOper2 = getSqlOper(operators2);
			var logic2 = getLogic(logicoperator2);
			var titleType2 = getTitleType(titles2,tableid);
			var TitleData2= getTitleData(titles2,tableid);
			if(titleType2=="checkbox")
			{
			    if(itemvalue2=="是")
				    itemvalue2=1;
				else
                    itemvalue2=0;				
			}
			if(titleType2=="dropdown"  || titleType2=="smartselect")
			{   
				var data2=TitleData2.split(",");
			
				for(f=0;f<data2.length;f++)
	            {
	                var num_data=data2[f].split(":");
					
		            if(itemvalue2==num_data[1])
					{   
					    itemvalue2=num_data[0];
					}
	            }
			}
			if(titleType2=="radio")
			{   
				var data2=TitleData2.split(",");
			
				for(f=0;f<data2.length;f++)
	            {
	                var num_data=data2[f].split(":");
					
		            if(itemvalue2==num_data[1])
					{   
					    itemvalue2=num_data[0];
					}
	            }
			}
			if(titleType2=="checkboxlist")
			{   
				var data2=TitleData2.split(",");
			
				for(f=0;f<data2.length;f++)
	            {
	                var num_data=data2[f].split(":");
					
		            if(itemvalue2==num_data[1])
					{   
					    itemvalue2=num_data[0];
					}
	            }
			}
			if(logic2==undefined)
			{
			    logic2="";
			}
			if(sqlOper2=="like" || sqlOper2=="not like")
			{
			   itemvalue2="%"+itemvalue2+"%";
			}
			
			var pretdlength2 = $(selectitem2).parent("td").parent("tr").prev("tr").children("td").length;
			var pretdvalue2 = $(selectitem2).parent("td").parent("tr").prev("tr").children("td").eq(3).children().val();
			if(pretdlength2=4 && pretdvalue2 !=="" )
			{
			    selectvalues2 += " "+logic2+" "+titleID2+" "+sqlOper2+"'"+itemvalue2+"'";
			}
			else if(pretdvalue2 =="")
			{
			    selectvalues2 += " "+titleID2+" "+sqlOper2+"'"+itemvalue2+"'";
			}
		}
    }
	var middlelogic = $("#"+tableid+"_table2 input:checked").val();
	if(selectvalues1!=="" && selectvalues2!=="" )
	{
		var selectvalues = "("+selectvalues1+")"+middlelogic+"("+selectvalues2+")";
	}
	else if(selectvalues1=="")
	{
		var selectvalues =selectvalues2;
	}
	else if(selectvalues2=="")
	{
		var selectvalues =selectvalues1;
	}
	return selectvalues;

}
function getLogic(logicoperator)
{
    if(logicoperator=="并且")
	{
	    logicoperator="and";
	}
	if(logicoperator=="或者")
	{
	    logicoperator="or";
	}
	return logicoperator;
}

function getSqlOper(operators)
{   
	var sqlOper='=';
    if(operators=="beequalto" || operators=='等于')
    {
        sqlOper="="; 
    }
	else if(operators=="doesnotequal" || operators=='不等于')
    {
        sqlOper="<>"; 
    }
	else if(operators=="biggerthan" || operators=='大于')
    {   
        sqlOper=">";
    }
	else if(operators=="lessthan" || operators=='小于')
    {
        sqlOper="<"; 
    }
	else if(operators=="bigandeq" || operators=='大于等于')
    {
        sqlOper=">="; 
    }
	else if(operators=="lessandeq" || operators=='小于等于')
    {
        sqlOper="<="; 
    }
	else if(operators=="contain" || operators=='包含')
    {
        sqlOper="like"; 
    }
	else if(operators=="notincluded" || operators=='不包含')
    {
        sqlOper="not like"; 
    }
	return sqlOper;
}

function getTitleID(title,tableid)
{
    var mm=getModeldata(tableid);
	for(y=0;y<mm.length;y++)
	{  		
	    if(title==mm[y].name)
	    {  
		    var titleid=mm[y].id;
		}
	}
	return titleid;
}
function getTitleType(title,tableid)
{  
    var mm=getModeldata(tableid);
	for(y=0;y<mm.length;y++)
	{  		
	    if(title==mm[y].name)
	    {  
		    var titletype=mm[y].type;
		}
	}
	return titletype;
}
function getTitleData(title,tableid)
{  
	var mm=getModeldata(tableid);
	for(y=0;y<mm.length;y++)
	{  		
	    if(title==mm[y].name)
	    {  
		    var titledata=mm[y].data;
		}
	}
	return titledata;
}
