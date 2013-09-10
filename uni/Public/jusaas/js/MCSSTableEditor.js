//alert("TableEditor");

g_dinamic_cssjs.push("jusaas/js/MCSSTableEditor.js");


/*
表格编辑器类
params.afterCreateRowEditor:创建了行编辑器后的事件
data:数据记录数组
*/
var tables=new Array;
var TableEditors=new Array;
function TableEditor(tableid,data,modeldata,params){
	TableEditors.push({tableid:tableid,data:this});
    if (!params)
    	params={};    	

	this.editedDataClassName="mcss_editedData";//编辑过的格子的css
	this.editedDataColor="yellow";//编辑过的格子的背景色
	if (params.editedDataClassName)
		this.editedDataClassName=params.editedDataClassName;
	if (params.editedDataColor)
		this.editedDataClassName=params.editedDataColor;
		
	
	this.tableid=tableid;
	this.params=params;
	this.modelid=modeldata.modelid;
	this.mcsstable=params.mcsstable;
	if (!modeldata.keyfield)
		modeldata.keyfield="id";
    this.modeldata=modeldata;//模型定义数据
    this.mcssData = params.mcssData;

	

	this.run=function(){
		this.initEditor();
	}
	this.initEditor=function(){
		
		if (this.mcssData.data.length==0)
		{
			$("#"+this.tableid+"_nodata").remove();
			//$("#"+this.tableid).find("tr:not(:first)").remove();//为什么移除掉呢？
		}
		
		var tableid=this.tableid;
		var modeldata=this.modeldata;
		$("#"+tableid).data("editor",this);
		var _this=this;
		var t1=new Date();
		if (this.mcsstable.view=='tableedit')
		{		
			//进入全表编辑状态
			$("#"+this.tableid).find("tr:not(:first)").find("td[id<>'']").css('padding',"0").each(function(i,item){
				_this.mcssCreateEditor(item);
			})
			//$("#statushint").remove();
		}
		else
		if (this.mcsstable.view=='celledit')		
		{
			//进入单字段编辑状态
			$("#"+this.tableid).find("tr:not(:first)").find("td[id<>'']").css('padding',"0").click(function(){
				if (!$(this).attr('inedit'))
				{
					_this.mcssCreateEditor(this);
					$(this).find('input').focus();
					$(this).attr('inedit',true);
				}
			})
		}
		var t2=new Date();
		//alert(t2-t1);
		
		if(_this.params.auto_fill_fields)
		{
			//自动填充字段.html5
			var fields=_this.params.auto_fill_fields.split(",");
			for(var i=0;i<fields.length;i++)
			{
					var id="datalist_"+modeldata.id+"_"+fields[i];
					var dom="<datalist id='"+id+"'>";
					var arr=this.mcssData.getFieldValueEnum(fields[i]);
					for(var j=0;j<arr.length;j++)
					{
						if (arr[j]!=null)
						dom+="<option value='"+arr[j]+"' />";
					}
					dom+="</datalist>";
					$("#"+tableid).after(dom);
			}
		}		
	}
		
	this.createRowEditor=function(row,cellcount,i)
	{
		if (!cellcount)
			cellcount=row.cells.length;
			for(var j=0;j<cellcount;j++)
			{
				if (row.cells[j])
					this.mcssCreateEditor(row.cells[j]);
			}
		if (this.params.afterCreateRowEditor)
		{
			this.params.afterCreateRowEditor(row);
		}
	
	}
	this.mcssCreateEditor=function(td)
	{
        //参数a 是一个表格的TD
        //a=tableid;
        var a=td;
        if (a.parentNode == undefined)
            return;
        if ($(a).attr("readonly")=='true')
        	return;
        var table=a.parentNode.parentNode.parentNode;
        var tableid=table.id;

		var td=table.rows[0].cells[a.cellIndex];
    	var fieldid=$(td).attr("fieldid");
    	if (!fieldid && td)
    		fieldid=td.id;
    	if (!fieldid) return;

        var pos=GetAbsPoint(a);
    	var tableEditor=$(table).data("editor");
    	var keyfield=tableEditor.modeldata.keyfield;
    	if (!keyfield)
    		keyfield='id';
    	var recordid=$(a.parentNode).attr("recordid");
    	if (!recordid)
    		recordid=$(a.parentNode).data("recordid");
		v=this.mcssData.getFieldValue(recordid,fieldid);
		if (v)
        	a.title=v;
        var html=mcssTableEditor_get_editor_html(tableEditor.tableid,tableEditor.modeldata,fieldid,v,pos,a,recordid,this);
        if (html)
	        $(a).append(html);
}

	//把某字段值显示在table的td的输入框中
    this.showFieldValueInCell=function(recordid,fieldid)
    {
    	var table=$("#"+this.tableid).get(0);
    	var tds=table.rows[0].cells;
    	var fieldIndex;
    	for(var i=0;i<tds.length;i++)
    	{
    		if (tds[i].id==fieldid)
    		{
    			fieldIndex=i;
    			break;
    		}
    	}
    	if (!fieldIndex)
    		return;
    	var rows=table.rows;
    	
	   	for(var i=1;i<rows.length;i++)
	   	{
	   		if ($(rows[i]).attr("recordid")==recordid)
	   		{
	   			var v=this.mcssData.getFieldValue(recordid,fieldid);
	   			$(rows[i].cells[fieldIndex]).children().val(v);
	   			var dom=$(rows[i].cells[fieldIndex]).children().get(0);
	   			tableeditor_dealNewData("",dom,this,recordid,fieldid);

	   			break;
	   		}
	   	}

    }
}


	
	function mcssTableEditor_get_editor_html(tableid,modeldata,fieldid,value,pos,parentNode,recordid,cellEditor)
	{
		var fieldtype='';
		var field=null;
		for(var i=0;i<modeldata.fields.length;i++)
		{
			if (modeldata.fields[i].id==fieldid)
			{
				field=modeldata.fields[i];
				fieldtype=modeldata.fields[i].type;
				break;
			}
		}
		if (!field || field.readonly == "true" || fieldtype=='popupselectone')
			return;

		var html="";
		parentNode.innerHTML="";
		var dom;
		if (fieldtype=='dropdown' || fieldtype=='radio' || fieldtype=='smartselect')
		{
			dom=createMcssSelect(modeldata.id,field,tableid,value,"100%",{parentNode:parentNode,rowIndex:parentNode.parentNode.rowIndex});	
		}
		else
		if (fieldtype=='checkboxlist')
		{
		}
		else
		if (fieldtype=='checkbox')
		{		
			dom=createMcssCheckbox(modeldata.id,field,tableid,value,"100%",{parentNode:parentNode,rowIndex:parentNode.parentNode.rowIndex});	
		}
		else
		if (fieldtype=='richeditor' )
		{
			dom=createMcssRichtext(modeldata.id,field,tableid,value,"100%",{parentNode:parentNode,rowIndex:parentNode.parentNode.rowIndex});	
			
		}
		else
		if (!fieldtype || fieldtype=='text' || fieldtype=='textarea'  || fieldtype=='date'  || fieldtype=='datetime' || fieldtype=='popupselectone123' )
		{
			dom=createMcssText(modeldata.id,field,tableid,value,"98%",{parentNode:parentNode,rowIndex:parentNode.parentNode.rowIndex});	
		}
		if (dom)
		{
			if (field.defaultdata)
			{
				tableeditor_dealNewData(fieldtype,dom,cellEditor,recordid,fieldid);
			}
			if (fieldtype=='richeditor' || fieldtype=='date' || fieldtype=='datetime')
			{
				$(dom).blur(function(e){
					//保存richeditor的数据，没有处理好，有时候保存不上，应该是应为格式的原因
					tableeditor_DealValueChanged(cellEditor,recordid,fieldid,fieldtype,dom,tableid);
				})
			}
			else
			{
				$(dom).change(function(e)
				{
					//ie可能不触发这个事件	，因此用下面dom).bind('keyup'补充tableeditor_DealValueChanged
					tableeditor_DealValueChanged(cellEditor,recordid,fieldid,fieldtype,dom,tableid);
				})
				//处理通过箭头移动格子，但某些浏览器下在单元格内无法移动光标。但为了体现回车后自动新增一行，先启用
				$(dom).bind('keyup', function(){
					//回车，或移动光标触发修改值的事件
					if(event.keyCode=='13' || event.keyCode=='38' || event.keyCode=='40')
						tableeditor_DealValueChanged(cellEditor,recordid,fieldid,fieldtype,dom,tableid);

					teditor_editorOnKeyUp(event,this);
					//下面被我注释了，看似没有问题（因为上面已有了blur和change事件处理了），不知道原来为什么需要
					//tableeditor_dealNewData(fieldtype,dom,cellEditor,recordid,fieldid);
					//dealSpecialFieldAfterChange(tableid,dom,recordid,fieldid);
				})
			
			}
		}

		return html;	
	}

//处理输入框的值变化时
function tableeditor_DealValueChanged(cellEditor,recordid,fieldid,fieldtype,dom,tableid)
{
	tableeditor_dealNewData(fieldtype,dom,cellEditor,recordid,fieldid);
	dealSpecialFieldAfterChange(tableid,dom,recordid,fieldid);
	if (cellEditor.mcsstable.view=='celledit')
	{
		var oldvalue=teditor_getOldValue(cellEditor,recordid,fieldid);
		var newValue=teditor_getNewValue(dom,fieldtype);
		if (oldvalue!=newValue)
			cellEditor.mcssData.save();
	}					
}

//方向键处理
function teditor_editorOnKeyUp(event,obj){
	var currentrowindex = $(obj).parent().parent().attr("rowIndex");
	var currentcellindex = $(obj).parent().attr("cellIndex");
	var maxrowindex = $(obj).parent().parent().parent().children("tr").length-1;
	var maxcellindex = $(obj).parent().parent().children("td").length;
	var inputobj;
	var tableid=$(obj).parent().parent().parent().parent().attr('id');
	var currentRow;
	if(event.keyCode=='13' ){
		//回车默认在下方新增行
		mcsstable_addNewEditRow(mcsstable_getMCSSTable(tableid),'after');
	}
	else
	if(event.keyCode=='37' ){
		//return;
		if(obj.value=='' && currentcellindex!=0){
			inputobj = $(obj).parent().prev().children('input').eq(0);
			if($(obj).val())
				inputobj = "";
		}
			
	}else if(event.keyCode=='38'){
		if(currentrowindex!=0)
		{
			currentRow=$(obj).parent().parent().prev();
			inputobj = $(obj).parent().parent().prev().children('td').eq(currentcellindex).children('input').eq(0);
		}
	}else if(event.keyCode=='39'){
		//return;
		if(obj.value=='' && currentcellindex != maxcellindex){
			inputobj = $(obj).parent().next().children('input').eq(0);
			if($(obj).val())
				inputobj = "";
		}			
	}else if(event.keyCode=='40'){
		if(currentrowindex != maxrowindex)
		{
			currentRow=$(obj).parent().parent().next();
			inputobj = $(obj).parent().parent().next().children('td').eq(currentcellindex).children('input').eq(0);
		}
	}
	if(inputobj)
	{		
		inputobj.focus();
		mcsstable_getMCSSTable(tableid).currentRow=currentRow.get(0);
	}
}


//修改值后处理自定义的事件
function dealSpecialFieldAfterChange(tableid,dom,recordid,fieldid)
{
	var mcsstable=mcsstable_getMCSSTable(tableid);
	if (mcsstable.params.SpecialFieldActionAfterChange)
	{
		mcsstable.params.SpecialFieldActionAfterChange(mcsstable,dom,recordid,fieldid);
	}
}

//获得刚输入的值
function teditor_getNewValue(inputer,fieldtype)
{
	var newValue='';
	if (fieldtype=='richeditor')
		newValue=$(inputer).html();
	else
		newValue=$(inputer).val();
	if (newValue==undefined) newValue="";
	return newValue;
}

function teditor_getOldValue(cellEditor,recordid,fieldid)
{
	var oldvalue=cellEditor.mcssData.getFieldOldValue(recordid,fieldid);
	if (oldvalue==null)
		oldvalue='';
	return oldvalue;
}

function tableeditor_dealNewData(fieldtype,dom,cellEditor,recordid,fieldid)
{	
	if(!dom)
		return;
	var oldvalue=teditor_getOldValue(cellEditor,recordid,fieldid);
	var newValue=teditor_getNewValue(dom,fieldtype);
	if (newValue!=oldvalue)
	{
		$(dom).css("background",cellEditor.editedDataColor);
		$(dom).addClass(cellEditor.editedDataClassName);
	}
	else
	{
		$(dom).css("background","");
		$(dom).css("color","black");					
		$(dom).removeClass(cellEditor.editedDataClassName);
	}	
	if(newValue.indexOf("'")>-1)
	{
		newValue = newValue.replace(/\'/g, "");//把单引号转化为空（原来是"<yh>"）,在服务器端在转化回来，否则不认得
	}
	var row=$(dom.parentNode.parentNode);
	var recordtype=row.data('mcss_recordtype');
	if (!recordtype)	recordtype="UPDATE";
	var newData={mcss_recordtype:recordtype,mcss_rowindex:row.attr('rowIndex'),recordid:recordid,fieldid:fieldid,value:newValue};
	cellEditor.params.mcssData.editNewData(newData);
}

function getTableEditor(tableid){
	var r;
	
	for(var i=0;i<TableEditors.length;i++){
		if (TableEditors[i].tableid==tableid)
		{
			r = TableEditors[i].data;
			//alert(r);
		}
	}
	return r;
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

	//从记录集合中根据id获得某字段的值
	function getFieldValueFromRows(rows,keyfield,recordid,fieldid)
	{
		for(var i=0;i<rows.length;i++)
		{
			if (rows[i][keyfield]==recordid)
			{
				return rows[i][fieldid];
			}
			
		}
		return null;
	}