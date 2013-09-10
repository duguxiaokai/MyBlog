/*
根据MCSS模型从后台获取的记录数组的处理类
*/
g_dinamic_cssjs.push("jusaas/js/MCSSData.js");


function MCSSData(data,modeldata,params)
{
	if (!params)
		params={};
	if (!data)
		return;
	this.params=params;
	this.modeldata=modeldata;//mcss模型结构

	this.initData=data;//初始数据
	this.data=copyArray(data);//完全数据,包括初始数据和修改过、新增的数据
	this.newData=new Array();//新添或修改或删除的数据
	
	this.keyfield='id';
	if (!this.modeldata)
		return;
	if (this.modeldata.keyfield)
		this.keyfield=this.modeldata.keyfield;
	
	this.homeUrl=g_homeurl;
	this.state="";

	//recordid：记录关键字段值。如果是数字，则表示记录序号
	this.getRow=function(recordid)
	{
		if (typeof(recordid)==='number')
			return this.data[recordid];
		
		for(var i=0;i<this.data.length;i++)
		{
			if (this.data[i][this.keyfield]==recordid)
				return this.data[i];
		}
	}

	//根据记录id获得其顺序号。recordid：记录关键字段值
	this.getRowIndex=function(recordid)
	{
		for(var i=0;i<this.data.length;i++)
		{
			if (this.data[i][this.keyfield]==recordid)
				return i;
		}
	}

	//获得某记录的某字段的值
	this.getFieldValue=function(recordid,fieldid)
	{
		var row=this.getRow(recordid);
		if (row)
			return row[fieldid];
		
	}
	//编辑完全数据组this.data中的数据
	this.setFieldValue=function(recordid,fieldid,value)
	{
		var row=this.getRow(recordid);
		if (row)
		{
			row[fieldid]=value;
			this.state="CHANGED";
			if (this.params.afterDataChanged)
				this.params.afterDataChanged(this,recordid);
			
		}
		
	}
	
	this.appendRow=function(record)
	{
		this.data.push(record);
		this.state="CHANGED";		
	}

	this.currentSearchedRecordid=null;
	this.searchNext=function(text)
	{
	}
	

	//删除原有的记录，或刚刚添加的记录	
	this.deleteRow=function(record)
	{
		var found=false;
		for(var i=0;i<this.newData.length;i++)
		{
			if (this.newData[i].mcss_rowindex==record.mcss_rowindex)
			{
				this.newData[i].mcss_recordtype="DELETE";
				found=true;
				break;
			}
		}
		if (!found && record.recordid)
		{
			var data={mcss_recordtype:"DELETE",recordid:record.recordid};
			this.newData.push(data);
			this.state="CHANGED";
			if (this.params.afterDeleteRow)
				this.params.afterDeleteRow(this,record);
			if (this.params.afterDataChanged)
				this.params.afterDataChanged(this,record);
				
		}
		
	
	}

	/*更新一条记录一个字段的值
	record.recordid:记录id
	record.mcss_recordtype:更新类型，可取NEW/DELETE/UPDATE
	record.fieldid:字段id
	record.value:字段值
	*/
	this.editNewData=function(record)
	{
		var found=false;
		for(var i=0;i<this.newData.length;i++)
		{
			if (this.newData[i].recordid==record.recordid)
			{
				this.newData[i][record.fieldid]=record.value;
				this.newData[i]['mcss_recordtype']=record.mcss_recordtype;
				this.setFieldValue(record.recordid,record.fieldid,record.value);
				found=true;
				break;
			}
		}
		if (!found)
		{
			var data={mcss_recordtype:record.mcss_recordtype,mcss_rowindex:record.mcss_rowindex,recordid:record.recordid};
			if (record.fieldid)
				data[record.fieldid]=record.value;
			this.newData.push(data);
			if (this.params.afterDataChanged)
				this.params.afterDataChanged(this,record);
			
		}
		
		//上面更新了“更新数据”，下面同时要更新总数据data属性，不用更新原始数据变量initdata
		for(var i=0;i<this.data.length;i++)
		{
			if (this.data[i][this.keyfield]==record.recordid)
			{
				this.data[i][record.fieldid]=record.value;
				this.setFieldValue(record.recordid,record.fieldid,record.value);
				found=true;
				break;
			}
		}
		if (!found)
		{
			var data={mcss_recordtype:record.mcss_recordtype,mcss_rowindex:record.mcss_rowindex,recordid:record.recordid};
			if (record.fieldid)
				data[record.fieldid]=record.value;
			this.data.push(data);
			if (this.params.afterDataChanged)
				this.params.afterDataChanged(this,record);
			
		}

	}
	
	//删除新数据数组中的某个数据
	this.deleteNewData=function(record)
	{
		for(var i=0;i<this.newData.length;i++)
		{
			if (this.newData[i].mcss_rowindex==record.mcss_rowindex && this.newData[i].fieldid==record.fieldid)
			{
				this.newData[i].mcss_recordtype="";//如何移动json中某个项？
				if (this.params.afterDataChanged)
					this.params.afterDataChanged(this,record);		
			}
		}
	}
	//获得指定字段的不重复的值
	this.getFieldValueEnum=function(fieldid)
	{
		var arr=new Array();
		for(var i=0;i<this.data.length;i++)
		{
			var found=false;
			for(ii=0;ii<arr.length;ii++)
			{
				if(arr[ii] == this.data[i][fieldid])
				{
					found=true;
					break;
				}
			}
			if (!found)
				arr.push(this.data[i][fieldid]);
		}
		return arr;
	}
	
	//params.alert:false：不提醒保存结果，默认提醒
	this.save=function(afterSave,params)
	{
		var showhint=true;
		if (params && params.alert==false)
			showhint=false;
		if (!this.newData || this.newData.length==0)
		{
			mcdom.alert(lang.nonetosave,lang.save,'info','fadeout');
			if (afterSave)
				afterSave(this,this.params.refObject);			
			return;
		}
			var s="";
			for(i=0;i<this.newData.length;i++){
				if (!this.newData[i].mcss_recordtype || this.newData[i].mcss_recordtype=="DELETE" && !this.newData[i].recordid)
				{}
				else
			 	   s+=JSON.stringify(this.newData[i])+"~|~";
			}
			if (!s)
			{
				if (showhint)
				{
					mcdom.alert(lang.nonetosave,lang.save,'info','fadeout');
					if (afterSave)
						afterSave(this,this.params.refObject);					
					return;
				}
			}
			var _this=this;
			var urlpath =this.homeUrl+g_systempath+"/save_edited_data";
			$.post(
				urlpath, 
				{modelid:this.modeldata.id,tablename:this.modeldata.tablename,eidtedata:s},
				function(data){
					//alert(data);
					if (data!="ok" && data!='0') {
						if (showhint)
							mcdom.alert(data,lang.save,'info','fadeout');
					}
					else
					{
						if (showhint)
						{
							if (data=='0')
								mcdom.alert(lang.noupdates,lang.save,'info','fadeout');
							else
								mcdom.alert(lang.successfullysaved,lang.save,'info','fadeout',190,100);
						}
						_this.state="SAVED";
						_this.newData=new Array();
						if (afterSave)
							afterSave(_this,_this.params.refObject);
					}
			});
	}
}


/*
MCSS模型处理类
*/
function MCSSModel(modeldata,params)
{
	if (!params)
		params={};
	this.modeldata=modeldata;//mcss模型结构
	
	this.getFieldInfo=function(fieldid,info){
	var fieldData=this.modeldata.fields;
	for(var i=0;i<fieldData.length;i++)
	{
		if (fieldData[i].id==fieldid)
		{
			return fieldData[i][info];
		}
	}
	return null;
}
	
}

	//复制二维数组
    function copyArray(arr)
    {
    	var newArr=new Array();
    	for(var i=0;i<arr.length;i++)
    	{
	    	var one={};
	        for(var p in arr[i])
    	    {
    	    	one[p]=arr[i][p];
        	}
        	newArr.push(one);
        }
        return newArr;
    }