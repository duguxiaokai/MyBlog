//document.write("<span style='color:red'>调试中...</span>");
//alert("看到这行表明autoform.js文件被加载了。");

/*
Autoform:自动表单类
自动根据对象模型生成各种字段、默认值、保存操作
创建参数jason的选项说明如下：
modelid：模型id
recordid:记录id
defaultvalue：设置打开表单的默认值,格式:pid:'123',name:'jack'
keyfield
hidetitle：true表示隐藏不创建字段名称
imagepath:自定义的图片目录
type："showdata"表示使用该类的目的只是显示数据，不编辑，直接把字段值显示在td内，无法各种input
showFormButton:是否创建保存按钮
afterGetData:取到数据后执行的方法
常用方法：
setFieldValue(fieldid):设置字段值
setTdValue(fieldid):设置字段值(showdata类型)
getField(fieldid)：得到字段id返回字段对象
getFieldID(fieldid):根据字段id返回字段输入框对象的id
getTdID(fieldid):根据字段id返回字段输入框对象的id(showdata类型)
getFieldValue(fieldid):根据字段id返回字段值
getTdValue(fieldid):根据字段id返回字段值(showdata类型)
SetReadonly():把整个表单各种字段设置为只读
afterSave():在保存完表单后执行的方法
params.afterGetModel:获得模型后的事件，可用语动态修改模型的属性
params.afterPopupSelectOne:弹出单选后的方法，代码在mcsstable
*/
var editor;
function Autoform(formid,jason)
{
	//alert("看到这行表明对象被创建了。");
	Autoforms.push({"formid":formid,data:this});
	//alert(Autoforms.length)
	//设置默认参数
	jason.homeUrl=getHomeUrl();
	if (!jason.saveUrl)
		jason.saveUrl=jason.homeUrl+"/List/List/saveRecord";
	if (!jason.getRecordUrl)
		jason.getRecordUrl=jason.homeUrl+"/List/List/getOneRecord";
	if (!jason.getModelUrl)
		jason.getModelUrl=jason.homeUrl+"/List/List/getModelData";
	if (jason.formobjid)  //<form>的id
		this.formobjid=jason.formobjid;

	this.params=jason;//params是参数的标准参数，应该用params代替jason

	this.imagepath=jason.imagepath;
	this.formid=formid;
	this.id=formid;
	this.arr_json=null;//字段数组，字段包含了常用的属性。重要！不过取名不恰当
	this.initData=null;//第一次取到的表单记录的数据，用来比较更新过的数据
	this.modelid=jason.modelid;
	this.checkuniquebeforesave=jason.checkuniquebeforesave;
	this.hidetitle=jason.hidetitle;
	this.type=jason.type;
	this.modeldata=null;
	this.tablename=null;
	//一些常量申明
 	var  const_urlid_tag='url:';//从url中获得id的值所需要的url参数标记
	
	this.recordid=jason.recordid;
	this.homeUrl=jason.homeUrl;
	
	this.saveUrl=jason.saveUrl;
	this.getRecordUrl=jason.getRecordUrl;
	this.getModelUrl=jason.getModelUrl;
    this.editcondition=null;//jason.modeldata.editcondition;
	this.cols=null;
	this.IsNewRecord=false;
	this.valuechanged=false;//字段值是否修改过
	this.richeditorid=null;
	this.richeditorreadonly=false;
	if (this.recordid==null || this.recordid=="" || this.recordid==undefined)
	{
		this.IsNewRecord=true;
	}
	this.keyfield='id';//keyfield如果在后面取到模型数据后模型中有keyfield，则会用模型中keyfield代替id。
	if (jason.lang)
		this.lang=jason.lang; 
	else
	 	this.lang='cn';
	
	this.funcAfterRun=null;	
	this.canClickSave=true;//是否可以忘后天发送保存命令。防止多次点击保存
	var _this = this;
	
	//run方式是核心
	this.run = function(func) {	
		if ($("#"+this.formid).size()==0)
		{
			alert("id为'"+this.formid+"'的对象不存在!无法创建表单");return;
		}
		$("#"+this.formid).get(0).innerHTML="";
		$("#"+this.formid).attr("modelid",this.modelid)
		mcss_importJS("jusaas/js/regex.js");
		mcss_importJS("jusaas/js/MCSSData.js");
		mcss_importJS("lang/"+this.lang+"/language.js");
		mcss_importJS("js/popup.js");	
		mcss_importJS("jusaas/js/dom.js");
		//mcss_importJS("plugins/jqtransform/jquery.jqtransform.js");//加载表单样式的JS
		//mcss_importCss("plugins/jqtransform/jqtransform.css");//加载表单样式的CSS
		mcss_importCss("jusaas/css/validstyle.css");//加载表单验证的CSS
	    this.setStyle(); 
		this.funcAfterRun=func;
		
		//alert("控制器方法路径是："+this.getModelUrl);
		$.getJSON(this.getModelUrl, 
			{modelid : jason.modelid}, 
			function(data){
				//alert("控制器方法返回的模型数据是："+data);

				if (!data)
				{
					$("#"+_this.id).html("<span style='color:red'>ERROR:模型结构错误("+_this.modelid+")</span>");
					return;
				}
				_this.modeldata=data;
				//_this.mcssmodel=new MCSSModel(data);

				_this.loadJS();//根据需要加载其它文件
				_this.getKeyFieldIDByUrl();
				//autoform_parseModel(data,formid);
				_this.parseModel(data,formid);
				if (_this.params.afterGetModel)
				{
					_this.params.afterGetModel(_this.modeldata,_this);
				}				
				_this.reload(func);
			}			
		);
	}
	
	//创建字段，执行加载后方法
	this.reload=function()
	{	
		var s="<img id='loading' src='"+getrooturl()+"/Public/Images/loading.gif'/>";
		$("#"+this.id).before(s);
		autoform_createAutoForm(this.id);
		
		//空记录时这里执行外部方法,如果有记录则在记录记载完后执行(在this.SetFormData中)
		if (!this.recordid && this.funcAfterRun) 
		{   
			this.funcAfterRun(this);
		}
		$.each($("#"+this.formid+" table tr"),function(i,item){
			if($(item).css("display")!="none" && $(item).find("input,textarea,select").attr("readonly")!=true)
			{
				if($(item).find("input,textarea,select").size()>0)
				{
					if($(item).find("table").size()<1)
					{
						$(item).find("input,textarea,select").focus();
						return false;
					}
				}
			}
		});
	
	}
	////根据需要加载其它js文件
	this.loadJS=function()
	{
		
		var hasimage=false;
		var hasdate=false;
		var haspopselect=false;//是否有弹出单选字段
		if (this.modeldata.fields)
		{
			for(var i=0;i<this.modeldata.fields.length;i++)		
			{
				if (this.modeldata.fields[i].type=='image')
					hasimage=true;
				if (this.modeldata.fields[i].type=='date' || this.modeldata.fields[i].type=='datetime')
					hasdate=true;
				if (this.modeldata.fields[i].type=='popupselectone')
					haspopselect=true;
			}
			if (hasimage) 
				mcss_importJS("jusaas/js/viewImage.js");
			if (haspopselect) 
				mcss_importJS("jusaas/js/MCSSTable.js");
			if (hasdate)
				mcss_importJS("js/DatePicker/WdatePicker.js");
		}

	} 
	this.parseModel=function()
	{
		//form1.modeldata=data;
		var fields=this.modeldata.fields;
		if (this.params.defaultValue)
		{
			var defaultValue=this.params.defaultValue;
			//defaultValue:pid:'123',name:'jack'
			var arr=defaultValue.split(",");
			for(var i=0;i<arr.length;i++)
			{
				var arr1=arr[i].split(":");
				if (arr1.length==2)
				{
					fields=autoform_setArrPropValue(fields,arr1[0],'defaultdata',arr1[1]);
				}
			}
	 
		}
		this.modeldata.fields=fields;
	
	}
	//从url中获得记录ID的值
	this.getKeyFieldIDByUrl=function()
	{
		if (jason.recordid && jason.recordid!='undefined' && jason.recordid.toString().indexOf(const_urlid_tag)>-1)
		{
			var id_name=jason.recordid.substring(jason.recordid.indexOf(const_urlid_tag)+const_urlid_tag.length);
			if (id_name=="KEYFIELD")
				id_name=this.modeldata.keyfield;
			if (!id_name)
				id_name="id";
			var s=location.href;
			var id=getParamValue(s,id_name);//获得记录的id
			jason.recordid=trim(id);
			this.recordid=jason.recordid;
 		}	
	}
	this.setStyle=function()
	{
		if ($("#"+this.formid).attr('className')=='')
		{
			$("#"+this.formid).attr('className','mcssform');

		}
		//alert($("#"+this.formid).c('border-width','1px');
	
	}
	this.setValueFromParam=function(jason)
	{
		if (jason.fields)
		{
			this.setFields(jason.fields);
		}
		if (jason.modeldata)
		{
			this.modeldata=jason.modeldata;
			this.tablename=jason.modeldata.tablename;
			this.editcondition=jason.modeldata.editcondition;
			this.cols=jason.modeldata.cols;
		}
		
	}
	this.SetInitData=function(data)
	{
		if (data && data.length>0)
			this.initData=data[0];
		
	}
	this.setFields=function(Fields)
	{
		var arr_json=new Array;
		for(var i=0;i<Fields.length;i++){
			arr_json.push(this.createField(Fields[i]));
        };
		this.arr_json=arr_json;
	}
	
	this.createButtons=function()
	{
		var h="";
		if (this.params.showFormButton.indexOf("SAVE")>-1)
			h+="<input type='button' id='"+this.formid+"_savebutton' value='保存' >";
		$("#"+this.formid).append(h);
		var afterSave=this.params.afterSave;
		$("#"+this.formid+"_savebutton").click(function(){
			_this.save(false,afterSave,false);
		});
		
		

	},
	this.createRicheditor=function(id)
	{
		var self=this;
		$(function() 
		{	
			if(self.richeditorreadonly && self.recordid)
			{
				self.editor = KindEditor.create('textarea[id="'+id+'"]',{
				themeType : 'simple',
				readonlyMode : true,
				});
			}
			else
			{
				self.editor = KindEditor.create('textarea[id="'+id+'"]',{
				themeType : 'simple',
				items : ['fontname', 'fontsize','bold','forecolor', 'hilitecolor',  'insertorderedlist', 'insertunorderedlist','justifyleft', 'justifycenter', 'justifyright','hr','link', 'unlink','image','insertfile','emoticons','source','fullscreen'
                        ],				
				});
			}
			
		});
	}
	this.createFields=function(FieldList)
	{  
		if (this.arr_json==undefined)
			this.setValueFromParam({fields:FieldList});
		var s=this.getFieldsHTML(this.arr_json);
		
		//$('input.autoform_datetime').datetimepicker();
		//$('input.autoform_date').datepicker();
		
		$("#"+this.formid).append(s);
		

		//自动填充字段.html5
		/*暂时不用
		var fields=this.arr_json;
		for(var i=0;i<fields.length;i++)
		{
			if ((!fields[i].type || fields[i].type=="text") && fields[i].data)
			{
				var id="datalist_"+this.modelid+"_"+fields[i].id;
				var dom="<datalist id='"+id+"'>";
				var arr=fields[i].data.split(",");
				for(var j=0;j<arr.length;j++)
				{
					dom+="<option value='"+arr[j]+"' />";
				}
				dom+="</datalist>";
				$("#"+this.formid).after(dom);
			}
		}
		*/
		
		
		if(this.richeditorid)
		{
			var self=this;
			var id=this.richeditorid;
			setTimeout(function(){self.createRicheditor(id);},500)
		}
		var w=parseInt($("#"+this.id+"_table1").width())+20;
		var h=parseInt($("#"+this.id+"_table1").height())+20;
 		
 		//陈坤极 如果表单含有文件上传，且这个Autoform的dom对象不是form，则给创建一个form，然后把原来的dom对象移动到这个form中。
		//if (this.hasfile) //不管是否有附件，都创建form，这样结构一致.
		{
			var divobj=$("#"+this.formid).get(0);
			if (!this.formobjid && $("#formobj"+this.formid).size()==0)
			{
				this.formobjid="formobj"+this.formid;
				var html="<form id='"+this.formobjid+"'></form>";
				$("#"+this.formid).before(html);
				var formobj=$("#"+this.formobjid).get(0);
				formobj.appendChild(divobj);
			}
 		}
		if(arr_json_hide.length>0){
			//如果有隐藏字段就加载值
			this.SetDefaultValue2Input(arr_json_hide);
		}
		if(arr_json_show>0){
			this.SetDefaultValue2Input(arr_json_show);//如果有显示字段就加载值
		}
		this.SetDefaultValue2Input();
		$("#"+this.formid).find(":input").change(function(){
			Autoform_afterEdited(this,formid);
		});
		
		$("#"+this.formid).find("input[type='text']").keyup(function(event){
			if(event.keyCode==13){
				if(_this.params.saveButton)
					$(_this.params.saveButton).click();
			}
		});
	}
	

	this.afterEdited=function(obj)//编辑完毕离开时要处理的
	{
		this.verify(obj);
		if (this.params.afterEditField)
		{
			this.params.afterEditField(this,obj);
		}
	}
	
	this.verify=function(obj,when)
	{   
		$("#verifalert_"+obj.id).remove();       
		if(when=='save')
		{	
			this.verifynull(obj);
		}
		
		if(this.verifylength(obj))
		{   
			if(this.verifyformate(obj))
			{  
				this.verifyfieldtype(obj)
				{
					if(when!='save')
						this.verify_unique(obj);
				}
			}
		}
	}
	this.getValueOfObj=function(obj)
	{
		var r;
		if (obj.value!=undefined)
			r= obj.value;
		else
			r=obj.innerHTML;
		return r;
	}
	this.verifyformate=function(obj)
	{   
		var value=this.getValueOfObj(obj);
		var f=this.getField(obj.id);
		var reg=/^\d+\.\d+$/;

		if(reg.test(f.formate))
		{   
		    var formate = f.formate;
			if(formate!=null)
			{
			    var numformate = formate.split(".");
		        var beforlength = numformate[0].length-1;
		        var afterlength = numformate[1].length;
			}
		    var parttern = new RegExp('^[1-9][0-9]{'+beforlength+'}\.[0-9]{'+afterlength+'}$');
		    if (!parttern.test(value) && value.length!==0)
		    {   
			   var err="'"+f.name+"'"+lang.formatfor+f.formate;
			   this.showErr(obj,err);
		    }
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		else
		{
		  
		    if(f.formate == "int")
		    {   
		        if (!IsInt(value) && value.length!==0)
		        {  
			       var err="'"+f.name+"'"+lang.onlyinteger;
			       this.showErr(obj,err);
		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
		    else
		    if(f.formate == "+int")
		    {		         	
		        if ( value.length!==0 && !IsPosInt(value))
		        {  
			       var err="'"+f.name+"'"+lang.onlypositiveinteger;
			       this.showErr(obj,err);
		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
			else
		    if(f.formate == "float")
		    {	    	
		        if (!IsFloat(value) && obj.value.length!==0)
		        {  
			       var err="'"+f.name+"'"+lang.onlydecimal;
			       this.showErr(obj,err);
		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
		    else
		    if(f.formate == "email")
		    {
	    	    if (value.length!==0 && !IsEmail(value))
		        {    
			        var err="'"+f.name+"'"+lang.messageformat;
			        this.showErr(obj,err);

		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
			else
		    if(f.formate == "phone")
		    {
	    	    if (value.length!==0 && !IsPhone(value))
		        {    
			        var err="'"+f.name+"'"+lang.telemobilenumber;
			        this.showErr(obj,err);

		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
			else
		    if(f.formate == "tel")
		    {
	    	    if (value.length!==0 && !IsTel(value))
		        {    
			        var err="'"+f.name+"'"+lang.telephonenumber;
			        this.showErr(obj,err);

		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
			else
		    if(f.formate == "identity")
		    {
	    	    if (value.length!==0 && !IsIdentity(value))
		        {    
			        var err="'"+f.name+"'"+lang.identificationnumber;
			        this.showErr(obj,err);

		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }else
		    if(f.formate == "post")
		    {
	    	    if (value.length!==0 && !IsPost(value))
		        {    
			        var err="'"+f.name+"'"+lang.postnumber;
			        this.showErr(obj,err);

		        }
				return ($("#"+this.formid).find(".errcss").length==0);
		    }
		    else
		    {   
				if (f.formate)
				{
					var a = f.formate;
					var parttern = new RegExp(a);
					if (value && value.length!==0 && !parttern.test(value))
					{   
					   var err="'"+f.name+"'"+lang.formaterror;
					   this.showErr(obj,err);
					
					}
				}
			 	return ($("#"+this.formid).find(".errcss").length==0);		

		    }
		}
		
	}
	this.verifyfieldtype=function(obj)
	{   
	    var f=this.getField(obj.id);
		var value=this.getValueOfObj(obj);
		if(f.fieldtype == "int")
		{  	    
		    if (value.length!==0 && !IsInt(value))
		    {  
			    var err="'"+f.name+"'"+lang.onlyinteger;
			    this.showErr(obj,err);
				
		    }
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		else
		if(f.fieldtype == "+int")
		{	    	
		    if (value.length!==0 && !IsPosInt(value))
		    {  
			    var err="'"+f.name+"'"+lang.onlypositiveinteger;
			    this.showErr(obj,err);
				
		    }
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		else
		if(f.fieldtype == "float" || f.fieldtype == "real")
		{    
		    if (value.length!==0 && !IsFloat(value))
		    {  
			    var err="'"+f.name+"'"+lang.onlydecimal;
			    this.showErr(obj,err);
				
		    }
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		else
		if(f.fieldtype == "time")
		{
		    if (value.length!==0 && !IsDatetime(value))
		    {  
			    var err="'"+f.name+"'"+lang.formatfor+"2011-11-03 11:20:21";
			    this.showErr(obj,err);
				
		    }
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		else
		if(f.fieldtype == "date")
		{
		    if (value.length!==0 && !IsDate(value))
		    {  
			    var err="'"+f.name+"'"+lang.formatfor+"2011-11-03";
			    this.showErr(obj,err);
				
		    }
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		return ($("#"+this.formid).find(".errcss").length==0);

	}
		
	this.verify_unique=function(obj)//字段唯一值校验
	{  	

	    var f=this.getField(obj.id);
		var value=this.getValueOfObj(obj);	
		if (f.unique)
		{
			autoform_check_unique(this.formid,obj,this.modelid,f.id,value);			
			return ($("#"+this.formid).find(".errcss").length==0);		
		}
	}
	
	this.verifylength=function(obj)//长度校验
	{ 
		if(obj)
		{
		    var fieldModel=this.getField(obj.id);
			if(fieldModel)
			{
			    if(fieldModel.type=='text' || fieldModel.type==undefined || fieldModel.type=="textarea")
				{  
					if (fieldModel.length!=null &&  fieldModel.length!= undefined && obj.value.length > fieldModel.length)
					{   
					  var err=fieldModel.name+":"+lang.maxcharlength+fieldModel.length;
					  this.showErr(obj,err);
					 
					}
				}
				return ($("#"+this.formid).find(".errcss").length==0);	
			}
			
		}
	}
	
	this.verifynull=function(obj)//空值校验
	{
		
		var f=this.getField(obj.id);
		if(f)
		{ 
		    var v=this.getValue(obj);
			if(v)
				v=v.replace(/\s/g,"");
			
			if (f.nullable == "false" &&  f.nullable!== undefined && v == '')
			{ 
 				var err="'"+f.name+"'"+lang.cannotnull;
				this.showErr(obj,err);
				
			}
			return ($("#"+this.formid).find(".errcss").length==0);
		}
		
	}
	this.showErr=function(obj,err)
	{   
		var errcssLeft=0;
		var errcssTop=0;
		try
		{
			errcssLeft=$("#"+obj.id).position().left+$("#"+obj.id).width()-20;
			errcssTop=$("#"+obj.id).position().top-35;
		}
		catch(ex){
		}
		
		
		var errinfo="<div class='err_info errcss' id='verifalert_"+obj.id+"' style='left:"+errcssLeft+"px;top:"+errcssTop+"px;display:block'><span class='Validform_checktip Validform_wrong'>"+err+"</span><span class='dec'><s class='dec1'>◆</s><s class='dec2'>◆</s></span></div>"
		//$("#"+obj.id).after("<span style='color:red;' class='errcss' id='verifalert_"+obj.id+"'>"+"<br />"+err+"</span>");
		$("#"+obj.id).addClass('Validform_error').after(errinfo);
	}
	this.getField=function(fieldid)
	{ 
		for (var i=0;i<this.arr_json.length;i++)
		{     
			var id=this.formid+"_"+this.arr_json[i].id;			
			if(id==fieldid){ 
				return this.arr_json[i];
		    }	
		}	
	}
	//veryify_before_update是错别字，但被引用了，为了兼容
	this.veryify_before_update=function()
	{
		this.verify_before_update();
	}
	this.verify_before_update=function()
	{
		if (!this.arr_json) return false;
		for (var i=0;i<this.arr_json.length;i++)
		{
			var f=this.arr_json[i];
			id=this.formid+"_"+f.id;
			this.verify(document.getElementById(id),'save');
		}
		var err='';
		$("#"+this.formid).find(".errcss").each(function(i,item){
			err+=item.innerHTML+";";
		})
		mcdom.popup(err,{position:'middle',fadeOut:true});
		return ($("#"+this.formid).find(".errcss").length==0);	
	}
	this.creatFieldsHTML=function(obj,width)
	{
	    if (obj.type=="textarea")
		{
			d=this.get_textarea(obj,width);
		}
		else if (obj.type=="password" || (obj.type=="hidden" && getCookie("mcss_loginuserrole")!='admin'))
		{
			d=this.get_password(obj,width);
		}
		else if (obj.type=="div")
		{
			d=this.get_div(obj,width);
		}
		else if (obj.type=="dropdown")
		{
			d=this.get_dropdown(obj,width);
		}else if(obj.type=="smartselect"){
			d=this.get_smartselect(obj,width);
		}
		else if (obj.type=="checkbox")
		{
			d=this.get_checkbox(obj,width);					
		}
		else if (obj.type=="radio")
		{
			d=this.get_radio(obj,width);
		}
		else if (obj.type=="checkboxlist")
		{  
			d=this.get_checkboxlist(obj,width);
		}				
		else if (obj.type=="richeditor")
		{  
			d=this.get_richeditor(obj,width);
		}
		else if (obj.type=="selectbutton")
		{  
			d=this.get_selectbutton(obj,width);
		}								
		else if (obj.type=="file" || obj.type=="image")
		{  
			d=this.get_uploadfile(obj,width);
			this.hasfile=true;
		}			
		else if (obj.type=="popupselectone")
		{  
			d=this.get_popupselectone(obj,width);
		}		
		//else if (obj.type=="date")
		//{  
		//	d=this.get_date(obj,width);
		//}		
		else
		{
			d=this.get_others(obj,width);
		}
		
		return d;
	}
	//根据模型的字段定义创建各个字段在Web页面上的各类输入框
	this.getFieldsHTML=function(arr_json)
	{
		arr_json_show = new Array();
		arr_json_hide = new Array();
		for(var i=0;i<arr_json.length;i++){
			if(arr_json[i].visible=='false'){
				arr_json_hide.push(arr_json[i]);
			}else{
				arr_json_show.push(arr_json[i]);
			}
		}
		var length=arr_json_show.length;
		if(this.cols==2)
		{
	        var nceil = Math.ceil(length/2);
			var s="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			for (var i=0;i<nceil;i++)
			{ 
				s=this.creatTable(s,arr_json_show[i]);					
			}
			s +="</table></td>";
			
			var s2="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			for (var i=nceil;i<length;i++)
			{   
				s2=this.creatTable(s2,arr_json_show[i]);
			}
			s2 +="</table></td>";
			
			var s3="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			for (var i=0;i<arr_json_hide.length;i++)
			{   
				s3=this.creatTable(s3,arr_json_hide[i]);
			}
			s3 +="</table></td>";
			s="<table id='"+this.formid+"_table1'><tr>"+s+s2+s3+"</tr></table>";
		}
		else if(this.cols==3)
		{
			var nceil = Math.ceil(length/3);
			var s="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			for (var i=0;i<nceil;i++)
			{
				s=this.creatTable(s,arr_json_show[i]);
			}
			s+="</table></td>";
			
			var s2="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			var middle=nceil;
			for(var i=nceil;i<(middle+nceil);i++)
			{
				s2=this.creatTable(s2,arr_json_show[i]);
			}
			s2+="</table></td>";
			
			var s3="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			for(var i=(middle+nceil);i<arr_json_show.length;i++){
				s3=this.creatTable(s3,arr_json_show[i]);
			}
			s3+="</table></td>";
			
			var s4="<td style='vertical-align:top;padding: 10px;'><table style='float:left'>";
			for (var i=0;i<arr_json_hide.length;i++)
			{   
				s4=this.creatTable(s4,arr_json_hide[i]);
			}
			s4 +="</table></td>";
			
			s="<table id='"+this.formid+"_table1'><tr>"+s+s2+s3+s4+"</tr></table>";
		}
		else
		{   
			var s1="";
			for (var i=0;i<arr_json.length;i++)
			{   
				s1=this.creatTable(s1,arr_json[i]);
			}
			
			s="<table id='"+this.formid+"_table1'>"+s1+"</table>";
		}
		return s;
	}
	this.creatTable=function(s,field)
	{    
	    var width="242px";
		if (field.width != undefined && field.width !=null)
			width=field.width;
		s+="<tr id='tr_"+this.formid+"_"+field.id+"'";
		if ( field.visible=="false")
		{
			s+=" style='display:none'";
		}
		s+=">";
		if(!this.hidetitle)
		{
			if(field.nullable=="false")
			{
				s+="<td class='lefttitle'><span style='color:red'> * </span>"+field.name+"&nbsp;&nbsp;</td>";
			}
			else
			{
				s+="<td class='lefttitle'>"+field.name+"&nbsp;&nbsp;</td>";
			}
		}
		if(this.type=="showdata")
		{
			s+="<td id='" +this.formid+"_"+field.id + "'></td>";
		}
		else
		{
			s+="<td>";
			d=this.creatFieldsHTML(field,width);
			s+=d+"</td>";
		}
		
		s+="</tr>";	
		return s;
	}
    this.get_dropdown=function(field,width)
	{   
	    var d="";
		//var selectWidth=parseInt(width)-31;
		var selectWidth=parseInt(width)+5;
	    d="<select name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='" +this.formid+"_"+field.id + "' style='width:"+selectWidth+"px;'";
		if (field.readonly == "true")
		{
			d+="disabled='disabled'";
		}
		d+=">";
		if (field.data !=null)
        {
		    var enum_arr = field.data.split(",");
			for(j=0;j<enum_arr.length;j++)
			{   
			    if(enum_arr[j]!=null)
				{
				    var enum_arr_con = enum_arr[j].split(":");
					var name=enum_arr_con[0];
					if (enum_arr_con.length==2)
					{
						name=enum_arr_con[1];
					}
					d += "<option ";
					if(field.defaultdata && (enum_arr_con[0]===field.defaultdata || enum_arr_con[1]===field.defaultdata))
					{
						d +=" selected='true' ";
					}
					d +=" value='"+enum_arr_con[0]+"'>"+name+"</option>";
				}
			}
		    d += "</select>";
		}
		return d;
	}
	this.get_smartselect=function(field,width){
		var fieldValue="",fieldText="",d="",par1="",par2="",enum_arr="";
		if (field.data!=null)
        {
		    enum_arr = field.data.split(",");
			for(j=0;j<enum_arr.length;j++)
			{   
			    if(enum_arr[j]!=null)
				{
				    var enum_arr_con = enum_arr[j].split(":");
					if(par1)
						par1+=',';
					if(enum_arr_con.length==2)
						par1+=enum_arr_con[1];
					else
						par1+=enum_arr_con[0];
					
					if(par2)
						par2+=',';
					par2+=enum_arr_con[0];
					if(field.defaultdata && (enum_arr_con[0]===field.defaultdata || enum_arr_con[1]===field.defaultdata))
					{
						fieldText=enum_arr_con[0];
						if (enum_arr_con.length==2)
						{
							fieldText=enum_arr_con[1];
						}
						fieldValue = field.defaultdata;
					}
				}
			}
		}
		var inputWidth=parseInt(width);
		var data_init = "";
		if(field.data_init)
			data_init = field.data_init;
		else
			data_init = field.data;
		data_init=data_init.replace(/'/gi,"");	
		if(enum_arr.length<1001){
			d="<div class='zhineng_select'><input fieldid='"+field.id+"' id='" +this.formid+"_"+field.id + "_display' value='"+fieldText+"' ";
			if (field.readonly == "true"){
				d+=" readOnly='true' style='width:"+inputWidth+"px;'";
			}else{
				d+=" style='width:"+(inputWidth-50)+"px;'";
				d+=" onFocus='mcssform_smartselect_focus(event,this,"+inputWidth+",\""+par1+"\",\""+par2+"\",\""+data_init+"\")'";
				d+=" onKeyUp='mcssform_smartselect_keyUp(event,this,"+inputWidth+",\""+par1+"\",\""+par2+"\",\""+data_init+"\")'";
				d+=" placeholder='请输入或选择内容'";
				d+="  onblur='mcform_closeView(this,\""+par1+"\",\""+par2+"\")' type='text' class='select_txt_input' /><input type='button' name='"+this.formid+"["+field.id+"_btn]' id='" +this.formid+"_"+field.id + "_btn' value='选择' onclick='mcform_showselectview(this,"+inputWidth+",\""+par1+"\",\""+par2+"\",\""+data_init+"\")' class='selectbutton'  ";
			}
			d+=" />";
			d+="<input type='hidden' name='"+this.formid+"["+field.id+"]' id='" +this.formid+"_"+field.id + "' value='"+fieldValue+"'></div>";
		}else{
			var url = getrooturl();
				d="<div class='zhineng_select'><input fieldid='"+field.id+"' id='" +this.formid+"_"+field.id + "_display' value='"+fieldText+"' ";
			if (field.readonly == "true"){
				d+=" readOnly='true' style='width:"+inputWidth+"px;'";
			}else{
				d+=" style='width:"+(inputWidth-50)+"px;'";
				d+=" onFocus='mcssform_smartsqlselect_focus(event,this,"+inputWidth+",\""+data_init+"\")'";
				d+=" onKeyUp='mcssform_smartsqlselect_keyUp(event,this,"+inputWidth+",\""+data_init+"\")'";
				d+=" placeholder='请输入或选择内容'";
				d+="  onblur='mcform_closeSqlView(this,\""+data_init+"\")' type='text' class='select_txt_input' /><input type='button' name='"+this.formid+"["+field.id+"_btn]' id='" +this.formid+"_"+field.id + "_btn' value='选择' onclick='mcform_showsqlselectview(this,"+inputWidth+",\""+data_init+"\")' class='selectbutton'  ";
			}
			d+=" />";
			d+="<input type='hidden' name='"+this.formid+"["+field.id+"]' id='" +this.formid+"_"+field.id + "' value='"+fieldValue+"'></div>";
		}
		return d;
	}
	this.get_div=function(field,width)
	{  
	    var d="";
	    d="<div name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='" +this.formid+"_"+field.id + "' class='div' style='width:"+width+";height:100px;'";
		if (field.readonly != "true")
		{
			d+=" contentEditable='true'";
		}
		d+=" >";
		if (field.defaultdata!=undefined )
			d +=field.defaultdata;
		d +="</div>";
		return d;
	}
	this.get_checkbox=function(field,width)
	{  
	    var d="";
	    d="<input  type='checkbox' name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_"+ field.id +"' ";
		if (field.readonly == "true")
		{
			d+="disabled='disabled'";
		}
		if (field.defaultdata=="true")
		{
			d +=" checked='checked'";
		}
		d += "/>";
		return d;			
	}
	this.get_radio=function(field,width)
	{   
	    var d="";
	    d="<div id='"+this.formid+"_"+ field.id+"' class='div_radio'>";
		if (field.data !=null)
        {  
		    var enum_arr = field.data.split(",");
			for(j=0;j<enum_arr.length;j++)
			{   
			    if(enum_arr[j]!=null)
				{
				    var enum_arr_con = enum_arr[j].split(":");
					var name=enum_arr_con[0];
					if (enum_arr_con.length==2)
					{
						var name=enum_arr_con[1];
					}
					d+="<input type='radio'  fieldid='"+field.id+"' id='"+this.formid+"_"+field.id+"_"+j+"'  name='"+this.formid+"["+field.id+"]'  style='float:none' value='"+enum_arr_con[0]+"'";
					if (field.readonly == "true")
					{
						d+="disabled='disabled'";
					}
					if (field.defaultdata==enum_arr_con[0] ||field.defaultdata==enum_arr_con[1]) 
					{
						d +=" checked='checked'";
					}
					if(j>0)
					{	
						d+="style='margin-left:20px;'"
					}
					d+= " /><label for='"+this.formid+"_"+field.id+"_"+j+"' style='margin:0 10px 0 3px;'>"+name+"</label>";
				}
			}
			
		    d+="</div>";
		    return d;
		}  			
	}
	this.get_checkboxlist=function(field,width)
	{   
	    var d="";
	    d="<div  id='"+this.formid+"_"+ field.id+"' class='div_checkbox'>";
	    if (field.data !=null)
        {
		    var enum_arr = field.data.split(",");
			for(j=0;j<enum_arr.length;j++)
			{   				    
				if(enum_arr[j]!=null)
				{
					var enum_arr_con = enum_arr[j].split(":");
					var name=enum_arr_con[0];
					if (enum_arr_con.length==2)
					{
						var name=enum_arr_con[1];
					}
					d+="<input type='checkbox' id='"+this.formid+"_"+field.id+"_"+j+"' name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' value='"+enum_arr_con[0]+"'";
					if (field.readonly == "true")
					{
						d+="disabled='disabled'";
					}
					d+= "/><label for='"+this.formid+"_"+field.id+"_"+j+"'>"+name+"</label>";	
				}	
			}
			d+="</div>";
			return d;
		}
	}
	this.get_textarea=function(field,width)
	{
		var d="";
		var height="70px";
		if (field.height)
			height=field.height;
		if(field.unique)
			d="<textarea name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_" + field.id + "' class='unique' style='width:"+width+";height:"+height+"'";
		else
			d="<textarea name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_" + field.id + "' style='width:"+width+";height:"+height+"'";
		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}
		d+=">";
		if (field.defaultdata!=undefined )
			d +=field.defaultdata;
		d +="</textarea>";
		return d;
	}
	this.get_password=function(field,width)
	{
		var d="";
	    d="<input  type='password' name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_"+ field.id + "' style='width:"+width+";'";
		if (field.type=='password')
			d+=" onchange='autoform_md5(this)' ";
		
		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}
		d += "/>";
		return d;

	}
	this.get_richeditor=function(field)
	{
		var d="";
		
		//增加宽度高度处理。by ckj
		var height='100px';
		var width='250px';
		//alert(field.height.length);
		if (field.height)
 			height=field.height;
 		
		if (field.width)
			width=field.width;	

		d="<div style='width:"+width+";'>";
 		if (field.readonly == "true")
		{
			// d+="<div style='text-align:right;' id='"+this.formid+"_" + field.id + "_bar"+"'><span class='labellink' onclick='openRichEditor(this,\""+this.formid+"_"+ field.id+"\",\""+this.homeUrl+"\");'>"+lang.senioreditor+"</span></div>";
			this.richeditorreadonly=true;
		}
		mcss_importJS("plugins/editor/kindeditor-min.js");
		d+="<textarea id='"+this.formid+"_" + field.id+"'  fieldid='"+field.id+"' class='"+this.formid+"_richeditor' name='"+this.formid+"["+field.id+"]' style='width:"+width+";height:"+height+";'></textarea>";
		this.richeditorid=this.formid+"_"+ field.id;
		return d;
	}
	this.get_selectbutton=function(field,width)
	{
		var d="";
		d="<textarea name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_" + field.id + "' style='width:"+width+";height:50px'";
		
		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}
		d+=">";
		if (field.defaultdata!=undefined )
			d +=field.defaultdata;
		if (field.data !=null)
		{
		    var enum_arr = field.data.split(":");
			for(x in enum_arr)
			{
				//alert(enum_arr[x]);
			}
			d +="</textarea><a id='sel' href='#' onClick=\"autoform_selectItems('"+this.formid+"_" + field.id +"','list_agent_For_Select_Item','','"+this.homeUrl+"')\" title="+lang.choice+">…</a>";	
			return d;
		}		
	}
	//文件上传
	this.get_uploadfile=function(field,width)
	{
		var d="";
		d+="<input type='hidden' name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_"+ field.id +"' style='width:"+width+"' />";	
		var u=location.href;
		var idx=u.indexOf("/index.php");
		var u=u.substr(0,idx);
		if (field.readonly!="true")
			d+="<iframe class='"+this.formid+"_iframe' id='"+this.formid+"_iframe_"+field.id+"' name='"+this.formid+"_iframe_"+field.id+"' frameborder='0' marginheight='0' marginwidth='0' border='0' src='"+u+"/Public/jusaas/htm/upload.htm?formid/"+this.formid+"/fieldid/"+field.id+"/modelid/"+this.modelid+"/recordid/"+this.recordid+"' style='width:"+width+";height:28px;border:none;vertical-align:middle;' scrolling='no'></iframe>";
 		return d;
	}	
	this.get_popupselectone=function(field,width)
	{
	    var d="";

        d="<div class='select_down' style='white-space: nowrap;'><input type='text' name='"+this.formid+"["+field.id+"]'  fieldid='"+field.id+"' id='"+this.formid+"_"+ field.id +"' class='select_txt_input' style='width:"+(parseFloat(width)-40)+"px'";		
		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}
		d+=">";	
		popupModelid='';
		
		if (field.data && field.data.indexOf("model:")>-1)
		{
			popupModelid=field.data.substr(6);
			if (popupModelid.indexOf("(")>0)  //弹出单选的模型设置可能像model:sys_selectuser，也可能带字段对应表model:sys_selectuser(name:username,email:mymail)		
			{
				popupModelid=popupModelid.substr(0,popupModelid.indexOf("("));
			}
			d+="<input type='button' value='选择' id='"+this.formid+"_"+ field.id+"_selectbutton' class='selectbutton' onclick='autoform_popupselectone(\""+popupModelid+"\",\""+this.formid+"\",\""+field.id+"\",this);'></div>";
		}
		return d;
	}
	
	this.get_others=function(field,width)
	{  
	    var d="";
		d="<input type='text' name='"+this.formid+"["+field.id+"]' fieldid='"+field.id+"' id='"+this.formid+"_"+ field.id +"' list='datalist_"+this.modelid+"_"+field.id+"' style='width:"+width+"' ";		
		if(field.unique)
			d+=" class='unique' ";



		if (field.type=="date")	
		{  
		    if(field.readonly !== "true")
			   // d+="class='autoform_inputtime autoform_date' ";
			  d+="class='autoform_inputtime autoform_date'  onclick=\"autoform_selectDate('yyyy-MM-dd')\"";
		}
		if (field.type=="datetime")	
		{
		    if(field.readonly !== "true")
			    //d+="class='autoform_inputtime autoform_datetime' ";
			   d+="class='autoform_inputtime autoform_datetime'  onclick=\"autoform_selectDate('yyyy-MM-dd HH:mm:ss')\"";
		}

		
		if (field.defaultdata!=undefined)//现在不用了，因为默认值在其它方法单独处理了
		{
			//d += " value='"+field.defaultdata+"'";
		}
		if (field.readonly == "true")
		{
			d+=" readonly='true' UNSELECTABLE='true' class='readonly_bg'";
		}
		d+=">";	
		return d;
	}

	this.get_date=function(field,width)
	{  
	    var d="";
		d="<input type='"+field.type+"' name='"+this.formid+"["+field.id+"]' id='"+this.formid+"_"+ field.id +"' style='width:"+width+"' ";		

		
		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}
		d+=">";	
		return d;
	}
	
	//暂时不用这个函数。因为原来设计这个函数是为了解析字段默认值，但现在字段默认值在服务器端解析了。	
	this.expParser=function (str)
	{
		var p="today()";
		if (str.indexOf(p) > -1){
			var now= new Date();
			
			var year=now.getFullYear();
			
			var month=now.getMonth()+1;
			if (month<10)
				month="0"+month;
			var day=now.getDate();
			if (day<10)
				day="0"+day;
			
			var hour=now.getHours();
			var minute=now.getMinutes();
			var second=now.getSeconds();
			var now = year+"-"+month+"-"+day;
			
			str = str.replace(p,now);
		}
		var p="now()";
		if (str.indexOf(p) > -1){
			var now= new Date();
			
			var year=now.getFullYear();
			
			var month=now.getMonth()+1;
			if (month<10)
				month="0"+month;
			var day=now.getDate();
			if (day<10)
				day="0"+day;
			
			var hour=now.getHours();
			var minute=now.getMinutes();
			var second=now.getSeconds();
			var now = year+"-"+month+"-"+day+" "+hour+":"+minute+":"+second;
			
			str = str.replace(p,now);
		}
		return str;
	}
	this.createField=function(v)
	{
		return  {id:v.id,name:v.name,type:v.type,readonly:v.readonly,visible:v.visibleWhenAdd,defaultdata:v.defaultdata,data:v.data,width:v.width,height:v.height,virtualfield:v.virtualfield,length:v.length,nullable:v.nullable,fieldtype:v.fieldtype,formate:v.formate,unique:v.unique,data_init:v.data_init};
	}
	
	this.parseEditCondition=function()
	{
		if (!this.editcondition)
			return true;
		
 		var FieldArr=this.arr_json;
		for (var i=0;i<FieldArr.length;i++)
		{ 			
 			this.editcondition=this.editcondition.replace(FieldArr[i].id,"'"+this.initData[FieldArr[i].id]+"'");
 		}	
		try{
			if (this.editcondition!==null && eval(this.editcondition)==false)
				return false;
		}
		catch(e){
			alert(lang.analyticexpression+"("+this.editcondition+")"+lang.error+"。"+lang.originalinformation+"："+e.message);
		}
		
		return true;
 	
	}
	this.setCheckboxlistValue=function(field,fid,fieldvalue)
	{   
	    var fieldid=field.id;
	    if (field.data !=null)
		{
			var enum_arr=field.data.split(",");	

			for(m=0;m<enum_arr.length;m++)
			{   
				if(enum_arr[m]!=null)
				{
					var enum_arr_con = enum_arr[m].split(":");
					var checkboxval=enum_arr_con[0];
					
					if(enum_arr_con.length==2)	
						checkboxval=enum_arr_con[1];
						
					var initcheckboxlist=fieldvalue.split(",");
					
					for(p=0;p<initcheckboxlist.length;p++)
					{   		
						$("#"+fid).find(":checkbox").eq(m).attr("checked","");
					}
					for(p=0;p<initcheckboxlist.length;p++)
					{   		
						if(checkboxval==initcheckboxlist[p]|| enum_arr_con[0]==initcheckboxlist[p])
						    $("#"+fid).find(":checkbox").eq(m).attr("checked","checked");
					}
				}	
			} 
		}
	}
	this.setDropdownValue=function(field,fid,fieldvalue)
	{   	
	    var fieldid=field.id;
		
		//如果模型中下拉列表选项没有，或者在autoform页面中临时用代码修改了下拉选项，则从页面下拉中获得数据
		var f=document.getElementById(this.getFieldID(fieldid));
		if (f.options.length>0 && !field.data)
		{
			var data="";
			for(var i=0;i<f.options.length;i++)
			{
				if (data)
					data+=",";
				data+=f.options[i].value+":"+f.options[i].innerHTML;
			}
			field.data=data;
		}
		
		if (field.data!=null)
		{
			var enum_arr=field.data.split(",");
			for(m=0;m<enum_arr.length;m++)
			{   
				if(enum_arr[m]!=null)
				{
					var enum_arr_con = enum_arr[m].split(":");
					if(fieldvalue!=null)
					{   
						if(enum_arr_con[0]==fieldvalue)
							$("#"+fid).find("option").eq(m).attr("selected","selected");
					}	
					
				}
			} 
		}
	}
	this.setSmartselectValue=function(field,fid,fieldvalue)
	{   	
	    var fieldid=field.id;
		var fieldValue,fieldid = "";
		if (field.data)
		{
			var enum_arr=field.data.split(",");
			for(m=0;m<enum_arr.length;m++)
			{   
				if(enum_arr[m]!=null)
				{
					var enum_arr_con = enum_arr[m].split(":");
					if(fieldvalue!=null)
					{      
						var dropdownval=enum_arr_con[0];
						if(enum_arr_con.length==2)	
							dropdownval=enum_arr_con[1];
						if(enum_arr_con[0]==fieldvalue){
							$("#"+fid+"_display").val(dropdownval);
							$("#"+fid).val(fieldvalue);
						}
					}	
				}
			} 
		}
	}
	this.setRadioValue=function(field,fid,fieldvalue)
	{
	    var fieldid=field.id;
		if (field.data !=null)
		{
			var enum_arr=field.data.split(",");
			for(m=0;m<enum_arr.length;m++)
			{   
				if(enum_arr[m]!=null)
				{
					var enum_arr_con = enum_arr[m].split(":");
					var radioval=enum_arr_con[0];
					
					if(enum_arr_con.length==2)	
						radioval=enum_arr_con[1];
					if(fieldvalue!=null)
					{
						var initradio=fieldvalue.split(",");
						for(p=0;p<initradio.length;p++)
						{   
							if(radioval==initradio[p]|| enum_arr_con[0]==initradio[p])
							   $("#"+fid).find("input").eq(m).attr("checked","checked");
						}
					}	
					
				}
				
			} 
		}     
	}
	this.getModelFieldById=function(fieldid)
	{   
		var fields=this.arr_json;
		if(fields){
			for(var i=0;i<fields.length;i++)
			{   
				if (fields[i].id==fieldid)
					return fields[i];
			}
		}
		return null;
	}
	this.setTdValue=function(field,fieldvalue)
	{	
		var fieldid;
		if (typeof(field)=='string')
			fieldid=field;
		else
			fieldid=field.id;
		var fid=this.formid+"_"+fieldid;
		getE(fid).innerHTML=fieldvalue;
	}
	this.setFieldValue=function(field,fieldvalue)
	{	
		if (typeof(field)=='string')
			field=this.getModelFieldById(field);
		if (!field)
			return;
				
		var fieldid=field.id;
		var fid=this.formid+"_"+fieldid;
		//处理具有value属性的输入框的值
		if (field.type=='' || field.type=='text'  || field.type=='popupselectone' || field.type==undefined || field.type=='SYS_ADDTIME' || field.type=='SYS_ADDUSER' || field.type=='SYS_EDITTIME' || field.type=='SYS_EDITUSER'  || field.type=='SYS_ORGID')
		{
			var default_value=fieldvalue;
			if (default_value!=undefined)  
				getE(fid).value=fieldvalue;
			
		}
		else if(field.type=='password' || field.type=='hidden')
		{     
			getE(fid).value=fieldvalue;
		}
		else if((field.type=='file' || field.type=='image' ))
		{  
			var rooturl=getrooturl();
			var filepath=rooturl+"/Public/uploadfile/";
			if(this.imagepath)//如果图片根目录不是公共的，可以通过参数传进来，那就用这个参数的路径作为图片的路径
			{
				filepath=this.imagepath;
			}
			var html="<div id='"+this.formid+"file"+field.id+"'>";
			var fileorimage="";
			
			//解析文件显示名与存储名
			var filename=fieldvalue;
			var savename=fieldvalue;
			var arr=fieldvalue.split('~');
			if (arr.length>1)
			{
				filename=arr[0];
				savename=arr[1];
			}

			
			if (field.type=='file' )
			{
				fileorimage="<a href=\"javascript:window.open('"+filepath+savename+"')\">"+filename+"</a>";
			}
			else if (field.type=='image')
			{
				var width="150px";
				if (field.width != undefined && field.width !=null)
					width=field.width;
				var height="100px";
				if (field.height != undefined && field.height !=null)
					height=field.height;				
				var image ="<img src='"+filepath+savename+"' style='width:"+width+";height:"+height+";' onclick='ImgShow(this)' onerror='errorimg(this)'/>";
				
				fileorimage+="<span id='wenchuan'>"+image+"</span>";
			}
 			if (field.readonly!="true")
			{
				var del = "&nbsp;<span id='"+this.formid+"_"+field.id+"_deletefile' class='labellink' onclick='autoform_deleteFileField(\""+fid+"\",this);' >"+lang.del+"</span>";
				fileorimage+=del;
			}
			
			if (!fieldvalue)
				fileorimage="";
			if ($("#"+this.formid+"file"+field.id).length>0)
			{	
				$("#"+this.formid+"file"+field.id).html(fileorimage);
			}
			else
			{

				html+=fileorimage+"</div>";
				$("#"+fid).before(html);
			}
			getE(fid).value=fieldvalue;
		}			
		else if(field.type=='textarea')
		{  
			getE(fid).value=fieldvalue;
			
		}
		else if(field.type=='date')
		{     
			if (fieldvalue=='0000-00-00')
				getE(fid).value="";
			else
				getE(fid).value=fieldvalue;
		}
		else if(field.type=='datetime')
		{  
			if (fieldvalue=='0000-00-00 00:00:00')
				getE(fid).value="";
			else
				getE(fid).value=fieldvalue;
		}
		else if(field.type=='richeditor')
		{   
			//$("#"+fid).prev().find("iframe").contents().find("body").html();
			if($("#"+fid).prev().find("iframe").contents().find("body"))
			{
				$("#"+fid).prev().find("iframe").contents().find("body").html(fieldvalue);
				getE(fid).innerHTML=fieldvalue;
			}
			else
			{
				getE(fid).innerHTML=fieldvalue;
			}

		}
		else if(field.type=='checkbox')
		{    
			if(fieldvalue=='0')
			{   
				 $("#"+fid).attr("checked","");
			}
			if(fieldvalue=='1')
			{   
				 $("#"+fid).attr("checked","checked");
			}
		}
		else if(field.type=='dropdown')
		{ 
			this.setDropdownValue(field,fid,fieldvalue);
		}
		else if(field.type=='smartselect'){
			this.setSmartselectValue(field,fid,fieldvalue);
		}
		else if(field.type=='radio')
		{  	
			this.setRadioValue(field,fid,fieldvalue); 
		}
		else if(field.type=='checkboxlist')//支持2种格式,"1：选项"
		{
			this.setCheckboxlistValue(field,fid,fieldvalue); 
		}
	}
	
	//把一条记录数据赋值给个字段，并设置整个表单是否可编辑
	this.SetFormData=function(data)
	{
		if (data.length==0)
			return;
		this.datarecord=data[0];//表单的记录数据（只有一条）
		this.SetValue2Form(data);
		this.canedit=this.parseEditCondition();
		if(!this.canedit)
		{ 
			this.SetReadonly();
		}
 		if (this.funcAfterRun) 
			this.funcAfterRun(this);
		$("#loading").remove();
	}
	
	//把整个表单各种字段设置为只读
	this.setReadOnly=function()
	{		
		$(".selectbutton").attr("disabled",true);
 		var FieldArr=this.arr_json;
		for (var i=0;i<FieldArr.length;i++)
		{ 
			this.setFieldReadonly(FieldArr[i].id);
		}	
	}
	this.setReadonly=function()
	{
		this.setReadOnly();
	}
	this.SetReadonly=function()
	{
		this.setReadonly();
	}
	//把从后台获取的一条记录数据赋值到各个输入框
	this.SetValue2Form=function(data)
	{
		this.SetInitData(data);
		var FieldArr=this.arr_json;
		
		if (this.initData)
		{
			for (var i=0;i<FieldArr.length;i++)
			{ 
				var field=FieldArr[i];
				var fid=this.formid+"_"+field.id;
				var v=this.initData[field.id];
				if (v==undefined)
					v='';
				
				if(this.type=="showdata")
					this.setTdValue(field,v);
				else
					this.setFieldValue(field,v);			
			}
		}
		else
			alert(lang.getfailed+"\r\n模型名称:"+this.modeldata.title+"模型ID:"+this.modelid);

	}
	
	
	//把对象模型中的字段默认值赋给各个编辑框
	this.SetDefaultValue2Input=function(data)
	{
		var FieldArr=this.arr_json;
		if(data)
			FieldArr=data;
		for (var i=0;i<FieldArr.length;i++)
		{   
		    
			if (FieldArr[i].defaultdata!=undefined)
			{  
				//处理具有value属性的输入框的值
				if (FieldArr[i].type=='' || FieldArr[i].type=='text' || FieldArr[i].type==undefined  || FieldArr[i].type=='date'  || FieldArr[i].type=='datetime' || FieldArr[i].type=='textarea'|| FieldArr[i].type=='password')
				{   
					getE(this.formid+"_"+FieldArr[i].id).value=FieldArr[i].defaultdata;
				}
				//处理不具有value属性的输入框的值
				else
				{
				   
				}
			}
		}
	}
	
	//收集字段的值	
	this.getValue=function(item)
	{
		var newValue='';
			if (item.type=='checkbox' )
			{	
				newValue='0';
				if (item.checked)
					newValue='1';
			}
			else
			if (item.className=='div_checkbox')
			{	
				newValue=this.getcheckboxlistvalue(item);//getcheckboxlistvalue这个方法还没有写，因此先注释掉。by 陈坤极	
			}
			else
		    if (item.className=='div_radio')
			{   
				newValue=this.getradiovalue(item);	
			}
            else
		    if (item.className==this.formid+"_richeditor")
			{   
				//newValue=item.innerHTML;
				newValue=$(item).prev().find("iframe").contents().find("body").html();
			}				
			else
		    
			if (item.type=='checkbox' && item.value=='on')
			{   
			   newValue="1";
			}
			else
			{  
				newValue=item.value;
			}
		return newValue;
	}
    //收集字段的值	
	this.getValues=function()
	{   
		var FieldArr=this.arr_json;
		var modelid=this.modelid;
		var formid=this.formid;
		var values="";

		for(y in FieldArr)
		{
			//alert(FieldArr[y].id);
			var list=document.getElementsByName(formid+"["+FieldArr[y].id+"]");//alert(list[0].name);
 			for(var i=0;i<list.length;i++)
			{
				var item=list[i];
				if(!item.name && item.className)
				{   
				    var fid=item.id;
					if(fid!=null)
					{
					    fid=fid.split("_");
						fid=fid[1];
					}
					
					var InitValue=null;
					if (!this.IsNewRecord && this.initData!=null)
					{   
						InitValue = this.initData[fid];
					}

				    continue;
				}   

				var fieldname=item.name;
				var len=fieldname.length;
				fieldname=fieldname.substr(formid.length+1,len);
				len=fieldname.length;
				fieldname=fieldname.substr(0,len-1);
				var InitValue=null;
			    if (!this.IsNewRecord && this.initData!=null)
				{   
					InitValue = this.initData[fieldname];
				}

				if (item.type=='radio')
				{
					newValue=this.getFieldValue(FieldArr[y].id);
					if (values.indexOf(fieldname+"<=>")==-1 && InitValue !== newValue )  
						values +=this.getRadios(item,InitValue,fieldname);	
				}
				else
				if (item.type=='file' || item.type=='image')
				{
					//附件的名称不用再这里传递，会单独处理的
					newValue=item.type				
				}
				else
				if (item.type=='checkbox')
				{	
					newValue='';
					var checkboxs=document.getElementsByName(item.name);
					if(checkboxs.length==1)
					{   
					    for(var m=0;m<checkboxs.length;m++)
					    {
							if (checkboxs[m].checked)
							{	
								var newValue='1';
								if (InitValue != newValue)
								{
									if(FieldArr[y].virtualfield !=="true")
									{
										values +="~|~"+fieldname+"<=>"+newValue;
										
									}
								}
							}
							else
							{
							    var newValue='0';
								if (InitValue != newValue)
								{
									if(FieldArr[y].virtualfield !=="true")
									{
										values +="~|~"+fieldname+"<=>"+newValue;
										
									}
								}
							}
					    }
					}
					else
					{
					    for(var m=0;m<checkboxs.length;m++)
					    {
							if (checkboxs[m].checked)
							{
								if (newValue=="")
									newValue=checkboxs[m].value;
								else newValue=newValue+","+checkboxs[m].value;

							}
					    }
						if (values.indexOf(fieldname+"<=>")==-1 && InitValue !== newValue )
						{   
							if(FieldArr[y].virtualfield !=="true")
							{
								values +="~|~"+fieldname+"<=>"+newValue;
								
							}
						}		
					}
									
				}				
				else
				if (item.type=='textarea' && FieldArr[y].type!="richeditor")
				{ 
					var newValue=item.value;
					if(InitValue != newValue)
					{
					    if(FieldArr[y].virtualfield !=="true")
						{
							values +="~|~"+fieldname+"<=>"+newValue;
							
						}
					}
				}
				else
				if (item.type=='password')
				{   
					var newValue=item.value;
					if(InitValue != newValue)
					{
					   if(FieldArr[y].virtualfield !=="true")
						{
							values +="~|~"+fieldname+"<=>"+newValue;
						}
					}
				}			
				else
				if (item.type=='dropdown')
				{    
					var newValue=item.value;
					if(InitValue != newValue)
					{
					   if(FieldArr[y].virtualfield !=="true")
						{
							values +="~|~"+fieldname+"<=>"+newValue;
							
						}
					}
				}
				else if (FieldArr[y].type=="richeditor")
				{
					var newValue=$(item).prev().find("iframe").contents().find("body").html();
					if(InitValue != newValue)
					{
						values +="~|~"+FieldArr[y].id+"<=>"+newValue;
					}
				}				
				else if (FieldArr[y].type=="smartselect")
				{
					newValue=$(item).val();
					if(InitValue != newValue)
					{
						values +="~|~"+FieldArr[y].id+"<=>"+newValue;
					}
				}
				else
				{  
					var newValue=item.value;
					if((InitValue !== newValue) ) //|| (fieldname=="id") 
					{  							
						if(FieldArr[y].virtualfield !=="true")
						{
							values +="~|~"+fieldname+"<=>"+newValue;
							
						}
					}
				}
				
			}
			
		}
		return values;	
	}
    //判断附件上传是否更改了	
	this.uploadfile_haschanged_old=function()
	{  
		var changed=false;
		var FieldArr=this.arr_json;
		for(y in FieldArr)
		{
			if (FieldArr[y].type=='file' || FieldArr[y].type=='image') 
			{
				var InitValue;
				if (this.initData)
					InitValue = this.initData[FieldArr[y].id];
				var newvalue=$("#"+this.getFieldID(FieldArr[y].id)+"_select").val();
				var newvalue1=$("#"+this.getFieldID(FieldArr[y].id)).val();
 				if (newvalue || newvalue1 )
				{
					changed=true;				
				}
			}
		}
  		return changed;	
	}	

    //判断附件上传是否更改了	
	this.uploadfile_haschanged=function()
	{
		var iframes=$("[id ^="+this.formid+"_iframe_]");
		for(var i=0;i<iframes.length;i++)
		{
			var mcssuploadfile=iframes[i].contentWindow.document.getElementById('mcssuploadfile');	
			if (mcssuploadfile && mcssuploadfile.value)
			{
				return true;								
			}					
		}
		return false;	
	}
				
	this.getcheckboxlistvalue=function(item)//获取checkboxlist的值
	{ 
		var v="";
		var checkboxs=$(":checkbox");
		for(var i=0;i<checkboxs.length;i++)
		{
			if (checkboxs[i].parentNode.id==item.id)
			{
				if (checkboxs[i].checked)
				{
					if (v=="")
						v=checkboxs[i].value;
					else 	v+=','+checkboxs[i].value;
				}
			}
		}
		return v;
	}
   //获取radio的值
	this.getradiovalue=function(item)
	{   
		var r="";
		var radios=$(":radio");
		for(var k=0;k<radios.length;k++)
		{
			if (radios[k].parentNode.id==item.id)
			{
				if (radios[k].checked)
				{
					if (r=="")
						r=radios[k].value;
					else 	r+=','+radios[k].value;
					
				}
			}
		}
		return r;
	}
	this.getRadios=function(item,InitValue,fieldname)
	{
	    var values='';
		var newValue="";
		var radios=document.getElementsByName(item.name);
		for(var m=0;m<radios.length;m++)
		{
			if (radios[m].checked)
			{    
				var newValue=radios[m].value;
				if (values.indexOf(fieldname+"<=>")==-1 && InitValue !== newValue)
				{   
					values +="~|~"+fieldname+"<=>"+newValue;
					
				}
			}
		}
	    return values;
    }
	this.getCheckboxValue=function(item,InitValue,fieldname)
	{   
	    var values='';
	    var newValue='';
		var checkboxs=document.getElementsByName(item.name);
		if(checkboxs.length==1)
		{   
			for(var m=0;m<checkboxs.length;m++)
			{
				if (checkboxs[m].checked)
				{	
					var newValue='1';
					if (InitValue != newValue)
					{
						values +="~|~"+fieldname+"<=>"+newValue;
					}
				}
				else
				{
					var newValue='0';
					if (InitValue != newValue)
					{
						values +="~|~"+fieldname+"<=>"+newValue;
					}
				}
			}
		}
		else
		{
			for(var m=0;m<checkboxs.length;m++)
			{
				if (checkboxs[m].checked)
				{
					if (newValue=="")
						newValue=checkboxs[m].value;
					else newValue=newValue+","+checkboxs[m].value;
				}
			}
			if (values.indexOf(fieldname+"<=>")==-1 && InitValue !== newValue )
			{   
				values +="~|~"+fieldname+"<=>"+newValue;	
			}		
		}
		return values;
	}
	this.show=function(str)
	{
		alert(str);
	}
	this.judgevaluechanged=function()
	{
		this.fieldvalues=this.getValues();  
		if (this.fieldvalues=='')
			return false;		
  		var arr1=this.fieldvalues.split("~|~");
		for(var i=0;i<arr1.length;i++)
		{
			var arr2=arr1[i].split('<=>');
			if (arr2.length>1)
			{
				this.valuechanged=true;
			}
		}
		return this.valuechanged;
	}	
	this.getParamDataForSave=function()
	{   
		//本类不再用这个方法，不知道其它地方是否用到，因此暂时保留
		var values=this.getValues();  
		return {"id":this.recordid,"tablename":this.tablename,"fieldvalues":values};
	}


	//refresh:保存后是否刷新页面；func：保存后要执行的方法；silent:true表示不提示保存结果，false或空表示要提示
	this.save=function(refresh,func,silent,params)
	{
		if (!this.verify_before_update())
			return;
		if(this.checkuniquebeforesave)
		{
			var unique=$("#"+this.formid).find(".unique");
			if (unique.length>0)//检查table中是否有唯一的字段--刘兆菊
			{
				var field=unique.eq(0).attr("name");
				var mode=/\[(.*)\]/;
				field=field.match(mode);
				field=field[1];
				var value=unique.eq(0).val();
				var _this=this;
				$.post(this.homeUrl+"/List/List/checkUnique",{modelid:this.modelid,field:field,value:value,recordid:this.recordid},function(isunique)
				{
					$("#verifalert_"+unique.eq(0).attr("id")).remove();       
					if (isunique.indexOf('0')>-1)
					{
						var err=lang.cannotrepeat;
						_this.showErr(unique.eq(0).get(0),err);
						$("#verifalert_"+unique.eq(0).attr("id")).addClass("unique_check");
					}
					else
					{
						unique.eq(0).removeClass("unique");
						_this.save(refresh,func,silent,params)
					}					
				})
			}
			else
			{
				this.saveupload(refresh,func,silent,params);
			}
		}
		else
		{
			this.saveupload(refresh,func,silent,params);
		}
		
	}
	this.saveupload=function(refresh,func,silent,params)
	{
		//检查是否有文件上传，如果有先上传文件
		var needupload=this.uploadfile_haschanged();
 		if (needupload)
		{  
			uploadDoneArr[this.formid]=false;
			if(func)
				setInterval("mcssform_checkifuploaded('"+this.formid+"',"+refresh+","+func+","+silent+")",500);//监听文件上传是否完成；一旦完成，就自动保存保单的方法
			else
				setInterval("mcssform_checkifuploaded('"+this.formid+"',"+refresh+",'',"+silent+")",500);//监听文件上传是否完成；一旦完成，就自动保存保单的方法

			var iframes=$("[id ^="+this.formid+"_iframe_]");
			for(var i=0;i<iframes.length;i++)
			{
				var filename=iframes[i].contentWindow.document.getElementById('mcssuploadfile');
				if (filename.value!=''){
					var beginUpload=iframes[i].contentWindow.document.getElementById('uploadbutton');
					beginUpload.click();
				}
			}
		}
		else{
			this.saveForm(refresh,func,silent,params);
		}
	}
	this.saveForm=function(refresh,func,silent,params)
	{	
		//refresh:保存后是否刷新页面；func：保存后要执行的方法；silent:true表示不提示保存结果，false或空表示要提示
		if (!this.canClickSave)
		{
			//alert(lang.savingnow);//到底要不要提示？纠结。如果提示了，可能有点烦，因为客户可能只是快速地点了两次按钮，提示多余；需要提示的理由是单击了保存，没即使看到反馈结果又单击了，这时需要提示
			return;
		}
		this.canClickSave=false;
 		this.fieldvalues=this.getValues();
		var _this=this;
 		if (this.judgevaluechanged())
		{  
 			var hasfile=this.hasfile;
			var formid=this.formid;
			//先保存除附件外的数据
			var needid=false;
			if (!this.recordid)
			{
				needid='true';				
			}
				
			var recordid=this.recordid;	
			if (this.modeldata.editurl=="default") 
				this.modeldata.editurl = "/List/List/addRecord/model/"+this.modelid+"/tablename/"+this.tablename;

			var editurl=this.modeldata.editurl;
			var keyfield=this.keyfield;
			var homeUrl=this.homeUrl;
			var temp_formid=this.formid;
			var beginTran="";
			var commitTran="";
			if (params)
			{
				//这两个事务处理参数还没有完全做好
				beginTran=params.beginTransaction;//是否启用mysql的事务处理
				commitTran=params.commitTransaction;//是否提交事务
			}
			var param ={"id":this.recordid,"need_return_newid":needid,"tablename":this.tablename,"fieldvalues":this.fieldvalues,'modelid':this.modelid,beginTransaction:beginTran,commitTransaction:commitTran};
			//保存表单数据时，如果有附件，为了不让附件影响表单的异步保存效果，处理方法是：先保存表单的非附件数据，然后再调用form的方法上传功能。陈坤极	
			$.post(this.saveUrl,param, function(data)
			{
				mcssform_setSaveStatus(temp_formid,true);//把保存状态改为允许再次保存

				//alert(data);
				data=trim(data);
				var arr=data.split(":");
				if (arr.length==2)
				{
					recordid=arr[1];
					_this.recordid=arr[1];				
				}
				if (data=="" || arr.length==2)
				{
					if (!silent){
						//mcdom.minialert(lang.successfullysaved);
						//alert(lang.successfullysaved);
						MCDom.prototype.alert(lang.successfullysaved,lang.hint,"info","fadeout");
					}
					_this.updateFormToData();
					if (func)
						func(recordid,lang.successfullysaved,_this);	
					if (refresh)
					{
						$("#"+formid).append("<div id='hintmsg' style='font-size:20px;color:red'>"+lang.successfullysaved+"</button>");
						$("#hintmsg").toggle(1000,closeAndReload(needid),true);			
					}						
				}
				else//后端返回错误
				{
					if (!silent)
						alert(data);
					else
					if (func)
						func(-1,data,_this);						
				}
				return data;
			});	

			
			
		}
		else {
			if (!silent)
			{	
				//mcdom.minialert(lang.noupdates);
				//alert(lang.noupdates);
				MCDom.prototype.alert(lang.noupdates,lang.hint,"info","fadeout");
			}
			if (func)
				func(recordid,lang.noupdates,_this);	
		};		
	}
	//把表单上的最新值更新到this.datarecord已有记录data
	this.updateFormToData=function()
	{
		if (!this.datarecord)
			return;
		//"~|~enddate<=>2013-05-16"
		var arr1=this.fieldvalues.split("~|~");
		var arr2;
		for(var i=0;i<arr1.length;i++)
		{
			arr2=arr1[i].split("<=>");
			this.datarecord[arr2[0]]=arr2[1];
		}
	}
 
	this.fetchData=function(func,mcform)
	{	
		var _this=this;
		$.getJSON(
		  this.getRecordUrl,
		  {keyfield:this.keyfield,id:this.recordid,table:this.tablename,modelid:this.modelid},
		  function(data1) { 
			//alert("控制器方法返回的记录数据是："+data1);
			var err=common_getAccessError(data1);
			if (err)
			{
				if($("."+_this.modelid+"_errorinfo").html())
					$("."+_this.modelid+"_errorinfo").remove();
				$("#"+mcform.id).before("<span style='color:red' class='"+_this.modelid+"_errorinfo'>"+err+"("+_this.modelid+")</span>");
				return;
			}
			mcform.data=data1;
			if (func)	
				func(data1,formid); 
			if (mcform && mcform.params.afterGetData)
			{
				mcform.params.afterGetData(mcform);
			}
		  });	
	}
    this.getFieldID=function(fieldid)
	{
		var dom=document.getElementById(this.formid+"_"+fieldid);
		if (dom)
			return dom.id;
		return null;
	}
	this.getTdValue=function(fieldid)
	{
		//还需要判断不同类型的字段
		var id=this.getFieldID(fieldid);
		if (id)
		{
			var v=$("#"+id).html();
			return v;
		}
	}
    this.getFieldValue=function(fieldid)
	{
		//还需要判断不同类型的字段
		var id=this.getFieldID(fieldid);
		if (id)
		{
			var v=$("#"+id).val();
			if (!v)
			{
				v=this.getValue(document.getElementById(id));
			}
			return v;
		}
		else
		{
			if(this.initData)
				return this.initData[fieldid];
			return null;
		}
		
	}
	//获得指定字段的显示文本
    this.getFieldText=function(fieldid)
	{
		r=this.getFieldValue(fieldid);
		var f=this.getFieldModel(fieldid);
		if (f.type=="dropdown"){
			
			//var objid=this.getfieldID(fieldid);
			var objid=document.getElementById(this.formid+"_"+fieldid).id;
			var o=document.getElementById(objid);
			r=getSelectedText(o);
		}
		return r;
	}	
    this.field=function(fieldid)
	{
		return document.getElementById(this.formid+"_"+fieldid);
	}	
	this.addText=function(fieldid,text)
	{
		//alert(fieldid);
		$("#"+this.getFieldID(fieldid)).after(text);
	}
	this.getFieldModel=function(fieldid)
	{
		for(var i=0;i<this.arr_json.length;i++)
		{
			if (this.arr_json[i].id==fieldid)
				return this.arr_json[i];
		}
		return null;
	}
	this.getFieldType=function(fieldid)
	{
		var f=this.getFieldModel(fieldid);
		if (f)
			return f.type;
		return null;
	}
	this.setFieldVisible=function(fieldid,visible)
	{
		var f=document.getElementById(this.getFieldID(fieldid)).parentNode.parentNode;
		if (this.getField(this.getFieldID(fieldid)).type=="richeditor")
			f=f.parentNode;
		if (visible)
			$("#"+f.id).show();	
		else
			$("#"+f.id).hide();		
	}
	this.setFieldReadonly=function(fieldid,readonly)
	{
		var disabled="disabled";
		var read=true;
		if (readonly==false || readonly=='false')
		{
			disabled="";
			read=false;
		}
		var field=null;
 		var FieldArr=this.arr_json;
		for (var i=0;i<FieldArr.length;i++)
		{ 
			if (FieldArr[i].id==fieldid)
			{
				field=FieldArr[i];
				break;
			}
		}
		if (!field) return;
		
		var fid=this.formid+"_"+field.id;
		var fname=this.formid+"["+field.id+"]";
		if(field.type=='richeditor')
			$("#"+fid).attr("contentEditable",!read);
		else if(field.type=="date" || field.type=="datetime")
		{
			$("#"+fid).attr("onClick","");
			$("#"+fid).attr("disabled",disabled);
		}
		else if(field.type=="dropdown" || field.type=="radio" || field.type=="checkbox" || field.type=="checkboxlist")
			$("[name='"+fname+"']").attr("disabled",disabled);		
		else if(field.type=="image" || field.type=="file")
		{
				if (readonly==true || readonly==undefined)
				{
					$("#"+this.formid+"_"+field.id+"_deletefile").hide();
					$("#"+this.formid+"_iframe_"+field.id).hide();					
				}
				else
				{
					$("#"+this.formid+"_"+field.id+"_deletefile").show();
					$("#"+this.formid+"_iframe_"+field.id).show();
				}
		}
		else
			$("[name='"+fname+"']").attr("readonly",read);
		
	}
}
function getE(id)
{
return document.getElementById(id);
}

function Autoform_afterEdited(obj,formid)
{
	var form=getAutoform(formid);
	form.afterEdited(obj);
}

function getParamValue(url,param_name)
{
	if(url!=null)
	{
	    var params=url.split("/");
		for(var i=0;i<params.length;i++)
		{
			if (params[i]==param_name && i<params.length-1){
				return trim(params[i+1]);
			}
		}
		return "";
	}
}

//打开富文本编辑器
function openRichEditor(field,id,homeUrl)
{ 
	if ($("#"+id).attr("contentEditable")!="true")
	{
		alert(lang.cannotedit);return;
	}
    if(field.title!=null)
	{
	    var paramvalue=field.title.split(':');
		currentRichEdotor=getE(id);
		var RichEdotorvalue=currentRichEdotor.innerHTML;
		setCookie('baidueditor',RichEdotorvalue);
		var url=homeUrl+"/List/List/RichEditor?srcobj="+id+"&&readvalue="+paramvalue[1];
		window.open(url);
    }
     
}

//修改字段属性值
function autoform_setArrPropValue(arr,field,prop,value)
{
	for(var i=0;i<arr.length;i++)
	{
		if (arr[i]['id']==field)
		{
			arr[i][prop]=value;
			break;
		}
	}
	return arr;
}
function autoform_parseModel(data,formid)
{
	var form1=getAutoform(formid);
	form1.modeldata=data;
	var fields=form1.modeldata.fields;
	if (form1.params.defaultValue)
	{
		var defaultValue=form1.params.defaultValue;
		//defaultValue:pid:'123',name:'jack'
		var arr=defaultValue.split(",");
		for(var i=0;i<arr.length;i++)
		{
			var arr1=arr[i].split(":");
			if (arr1.length==2)
			{
				fields=autoform_setArrPropValue(fields,arr1[0],'defaultdata',arr1[1]);
			}
		}
 
	}
	form1.modeldata.fields=fields;
}
//20111210ckj
function autoform_createAutoForm(formid)
{
	var form1=getAutoform(formid);
	var data=form1.modeldata;
	if (!data) return;
	document.getElementById(formid).innerHTML='';

	var err=common_getAccessError(data);
	if (err)
	{
		$("#"+formid).html(err+"("+form1.modelid+")");
		return;
	}		
	if (data.keyfield)
		form1.keyfield = data.keyfield;	
	
	form1.setValueFromParam({"modeldata":data,fields:data.fields});
	form1.createFields(data.fields);
	if (form1.params.showFormButton)
	{
		form1.createButtons(); 
	}
	form1.recordid=form1.params.recordid;
	if (form1.recordid && form1.recordid!="")//说明不是新增记录，而是显示一条已存在的记录
	{   
		//var s="<img id='loading' src='"+getrooturl()+"/Public/Images/loading.gif'/>";
		//$("#"+form1.id).before(s);		
		////<img src='"+mcsstable.rooturl+"/Public/Images/loading.gif'/>
		form1.fetchData(autoform_initData,form1);		
	}
	else //新记录，处理通过url传递的字符默认值
	{
		var p=u=decodeURI(document.location.href);
		var defaultv=getParamValue(p,"defaultValue");
		var arr=defaultv.split(",");
		if(arr.length!=0)
		{
			for(var i=0;i<arr.length;i++)
			{
				var arr1=arr[i].split(":");
				if (arr1.length==2)
				{
					form1.setFieldValue(arr1[0],arr1[1]);
					if(i==(arr.length-1))
						$("#loading").remove();
				}else{
					$("#loading").remove();
				}
			}
		}else{
			$("#loading").remove();
		}		
	}
	//加载完表单后，需要定义表单的样式
	//$('form').jqTransform();//问题已解决。还有其它情况没解决
}

function getParamValue(url,param_name)
{   
	if (url)
	{
		var params=url.split("/");
		for(var i=0;i<params.length;i++)
		{   
			if (params[i]==param_name && i<params.length)
				{return params[i+1];}
		}
	}
	return "";

}

function autoform_initData(data,formid)
{
	var err=common_getAccessError(data);
	if (err)
	{
		$("#"+formid).html(err);
		return;
	}	
    getAutoform(formid).SetFormData(data);	
}

function autoform_selectItems(fieldname,listname,title,homeurl)
{
	currentField=fieldname;
	url=homeurl+"/List/List/list2/param:table/"+listname;
	ShowIframe(url,400,500,title,'1');
}
function inputfromPopup(a) 
{
  document.getElementById(currentField).value = getTDtextByID(a.parentNode,"name");
  popp.close();
}

function closeAndReload(needid)
{
	window.parent.document.location.reload();
	return;//下面代码本来可以，现在无法执行window.parent.mcsstable_loaddatarows(tableid);暂时不用 //ckj
	if (navigator.appName.indexOf("Microsoft")>-1)
	{
		window.parent.document.location.reload();
	}
	else
	{
		var tabaleid=getParamValue(location.href,'mcsstableid');
		if (needid)
			window.parent.mcsstable_loadfirstrow(tableid);
		else{	
			window.parent.mcsstable_loaddatarows(tableid); //加载记录数据
		}
		window.parent.ClosePop();
	}

}


function autoform_setFieldValue(fieldid,value)
{
	if (value==undefined)
		$("#"+fieldid).val('');
	else
		$("#"+fieldid).val(value);
}
function autoform_deleteFileField(fieldid,obj)
{
	$("#"+fieldid).val('');
	$("#"+obj.parentNode.id).remove();
}

function autoform_uploadfile(formid,recordid)
{
	var f=getAutoform(formid);
 	var formobj=$("#"+f.formobjid)[0];
	var fieldvalues='';
	var act=f.saveUrl+"/modelid/"+f.modelid+"/useajax/false";
	if (recordid && recordid!="")
		act+="/id/"+recordid;
	formobj.action=act;
	formobj.method="POST";
	formobj.encoding="multipart/form-data";//IE下写在这里无效，只能写在html中
	formobj.submit();	
}

//权限检查
function checkaccess(data)
{
	if (data)
	{
		if (data.length>0 && data[0]['err'])
		{	
			var err=data[0]['err'];
			//if (err.indexOf('unaccessible:')>-1)
			{
				return err;
			}
		}
	}
	return '';
}

function autoform_check_unique(formid,obj,modelid,field,value)
{
	var f=getAutoform(formid);
	var id=f.getFieldValue(f.keyfield);
	f.showErr(obj,'唯一性校验中...');
	$("#verifalert_"+obj.id).addClass("unique_check");
	$.post(f.homeUrl+"/List/List/checkUnique",{modelid:modelid,field:field,value:value,recordid:id},function(isunique){
		$("#verifalert_"+obj.id).remove();$("#"+obj.id).removeClass('Validform_error');       
		if (isunique.indexOf('0')>-1)
		{
			var err=lang.cannotrepeat;
			f.showErr(obj,err);
			$("#verifalert_"+obj.id).addClass("unique_check");
		}
		
		
		
	})
}

function autoform_selectDate(timeformdate)
{
	WdatePicker({skin:'default',dateFmt:timeformdate,alwaysUseStartDate: true});
}

//获取下拉列表选中项的文本   
function getSelectedText(obj){ 
	for(i=0;i<obj.options.length;i++){   
	   if(obj.options[i].selected==true){   
		return obj.options[i].innerHTML; 
	   }   
	}   
}   

function autoform_popupselectone(modelid,formid,fieldid,obj)
{
	var f=getAutoform(formid);
	var filter="";
	if (f.params.popupSelectOne_filter)
	{
		//popupSelectOne_filter例子：{customerid:"type=1",order_no:"customer='mj'"}
		filter=f.params.popupSelectOne_filter[fieldid];
		if (!filter)
			filter="";
	}
	var selectType="radio"//可以选择radio或checkbox，即单选或复选
	var h = "<div style='overflow:scroll'><table id='"+formid+"_mcsstable'></table></div>";
	
	mcdom.showPopup(obj,h,'middle',null,null,400,600,"选择");
	mcssTable=new MCSSTable({tableid:formid+"_mcsstable",modelid:modelid,showfirst:true,first_td_name:'',selectType:selectType,pageposition:'rightdown',showRecordCount:true,afterLoadRows:clickRadioButton,showRecordActionAtLast:false,showtitle:true,popupFormID:formid,popupFieldID:fieldid,filter:filter});
	mcssTable.run();
}
function clickRadioButton(mcsstable){
	$("#"+mcsstable.params.tableid+" input[type='radio']").click(function(){
		try{
			returnSelectedRows(mcsstable.id);
			$("#"+mcsstable.id).parent().parent().parent().remove();
		}catch(e){
		}
	})
}
Autoform.prototype.deleteRecord=function(){
	$.post(this.homeUrl+"/List/List/deleteRecord",{recordid:this.recordid,modelid:this.modelid},function(result){
		//alert(result);
		if (!result)
			alert(result);
		
	})
}
//点击放大图片
function getEvent(){
	if(document.all){
	return window.event;//如果是ie
	}
	func=getEvent.caller;
	while(func!=null){
		var arg0=func.arguments[0];
		if(arg0){
			if((arg0.constructor==Event || arg0.constructor ==MouseEvent)||(typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation)){
			return arg0;
			}
		}
		func=func.caller;
	}
	return null;
}
//点击放大图片
function ImgShow(evt){
	viewImage(evt);//这个放大定义文件是viewImage.js
}
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
//智能下拉选项框
function mcssform_smartselectdiv(obj,width,FieldText,FieldValue,sql){
	$("#"+obj.id+"_ajax").remove();
	var textArr = "",valueArr = "";
	if(FieldText)
		textArr = FieldText.split(',');
	if(FieldValue)
		valueArr = FieldValue.split(',');
	
	var width=width+10;
	var h = "<div id='"+obj.id+"_ajax' style='width:"+(width-50)+"px;height:auto;' class='ul_selectList'><ul>";
	var rowCount = 0;
	var textConditionArr = new Array();
	var valueConditionArr = new Array();
	var objvalue = $(obj).val();
	if(objvalue)
		objvalue = objvalue.replace(/[ ]/g,"");
	for(var i = 0;i < textArr.length;i++){
		if(rowCount==10)
			break;
		if(textArr[i].indexOf(objvalue) != -1){
			textConditionArr[rowCount] = textArr[i];
			valueConditionArr[rowCount] = valueArr[i];
			rowCount++;
		}
	}
	for(var i = 0;i < textConditionArr.length;i++){
		if(textConditionArr[i].indexOf(objvalue) != -1){
			h+="<li fieldValue='"+valueConditionArr[i]+"' onmouseover='mcfrom_smartNodeMouseover(this)' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+textConditionArr[i]+"</li>";
		}
	}
	h+="</ul></div>";
	$(obj).next().after(h);
	if($("#"+obj.id+"_ajax").children("ul").eq(0).children('li').length == 0)
		$("#"+obj.id+"_ajax").remove();
}
//智能下拉从数据库中检索出数据 
function mcssform_smartsqlselectdiv(obj,width,sql){
	var objvalue = $(obj).val();
	if(objvalue)
		objvalue = objvalue.replace(/[ ]/g,"");
	var width=width+10;
	$.getJSON(getrooturl()+"/index.php/Mcss/Model/getSmartSelectList",{sql:sql,page:0,condition:objvalue},function(data){
		$("#"+obj.id+"_ajax").remove();
		var h = "<div id='"+obj.id+"_ajax' style='width:"+(width-50)+"px;height:auto;' class='ul_selectList'><ul>";
		var firstfield = "",secondfield = "";
		if(data.length>0){
			firstfield = data[0]['firstField'];
			if(data[0]['secondField'])
				secondfield = data[0]['secondField'];
			else
				secondfield = data[0]['firstField'];;
		}
		for(var i = 0;i <data.length;i++){
			h+="<li fieldValue='"+data[i][firstfield]+"' onmouseover='mcfrom_smartNodeMouseover(this)' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+data[i][secondfield]+"</li>";
		}
		h+="</ul></div>";
		$(obj).next().after(h);
		if($("#"+obj.id+"_ajax").children("ul").eq(0).children('li').length == 0)
			$("#"+obj.id+"_ajax").remove();
	})
}
function mcfrom_smartNodeMouseover(obj){
	$(obj).parent().parent().prev().prev().val($(obj).html());
	$(obj).parent().parent().next($(obj).attr("fieldValue"));
}

//当光标消失时执行方法
function mcform_closeView(obj,FieldText,FieldValue){
	isclose = false;
	$(obj).next().next("div").remove();
	if(FieldText)
		textArr = FieldText.split(",")
	if(FieldValue)
		valueArr = FieldValue.split(",")
	var condition = false;
	for(var i =0;i < textArr.length;i++){
		if($(obj).val()==textArr[i]){
			condition = true;
			value = valueArr[i];
			break;
		}
	}
	if(!condition){
		$(obj).val("");
		$(obj).next().next().val("");
	}else{
		$(obj).next().next().val(value);
	}
}	
//当光标消失时执行的方法
function mcform_closeSqlView(obj,sql){
	isclose = false;
	$(obj).next().next("div").remove();
	var condition = $(obj).val();
	$.post(getrooturl()+"/index.php/Mcss/Model/accessIsHave",{sql:sql,condition:condition},function(data){
		if(data==0){
			$(obj).val("");
			$(obj).next().next().val("");
		}else{
			$(obj).next().next().val(data);
		}
	})
}     		
//弹出的选项框
var isclose = false;
function mcform_showselectview(obj,width,FieldText,FieldValue,sql){
	$(document).attr("onclick",null);
	$("#"+$(obj).prev().attr("id")+"_ajax").remove();
	if(isclose){
		isclose=false;return;
	}else{
		isclose=true;
	}
	var textArr = "",valueArr = "",pageTotal=1;
	if(FieldText)
		textArr = FieldText.split(',');
	if(FieldValue)
		valueArr = FieldValue.split(',');
	var width=width+53;
	var did=$(obj).prev().attr("id")+"_ajax";
	var h = "<div id='"+did+"' style='width:"+(width-50)+"px;height:auto;' class='div_selectList'>";
	h+="<div class='div_selectList_shaixuan'><input  placeholder='输入部分字符，回车搜索'  onKeyDown='mcform_viewSearchEnter(this,event)' class='search_text_style' style='width:"+(width-96)+"px;'/> <input type='button' value=' ' onclick='mcform_viewSearch(this,\""+FieldText+"\",\""+FieldValue+"\",\""+sql+"\")' class='search_btn_style'/></div>";
	h+="<p class='select_zuijin' style='height:auto;'></p><ul>";
	var rowcount = 0;
	for(var i = 0;i < textArr.length;i++){
		if(rowcount==10)
			break;
		h+="<li fieldvalue='"+valueArr[i]+"' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+textArr[i]+"</li>";
		rowcount++;
	}
	h+="</ul>";
	if(textArr.length%10)
		pageTotal = parseInt(textArr.length/10)+1
	else
		pageTotal = parseInt(textArr.length/10);
	h+="<div class='right_select_page' style='text-align:right'><a href='javascript:void(0)' onclick='mcform_viewPage(this,\"prev\",\""+FieldText+"\",\""+FieldValue+"\",\""+sql+"\")'>上页</a> <a href='javascript:void(0)' onclick='mcform_viewPage(this,\"next\",\""+FieldText+"\",\""+FieldValue+"\",\""+sql+"\")'>下页</a> <input value='1' style='width:15px; height:15px;' onKeyDown='mcform_viewPageEnter(this,event,\""+FieldText+"\",\""+FieldValue+"\",\""+sql+"\")' type='text'/> / <a href='javascript:void(0)' onclick='mcform_viewPage(this,\"total\",\""+FieldText+"\",\""+FieldValue+"\",\""+sql+"\")'>"+pageTotal+"</a></div></div>";
	$(obj).after(h);
	$(".search_text_style").focus();
	 //当点击不是导出按钮的时候把下拉菜单移除
	 $(document).click(function(event){
		//if (event.target!=$("did").get(0))
		//	$("#"+did).remove();
	 })
	
	
	if(sql){
		var loginuser = getCookie("mcss_loginuser");
		var completekey = "mcformsmart_"+loginuser+"_"+sql;
		var cookiekey = completekey.substr(0,60);
		if(getCookie(cookiekey)){
			var listStr = getCookie(cookiekey);
			var h = mcform_appendList(listStr);
			$("#"+$(obj).prev().attr("id")+"_ajax").children("p.select_zuijin").eq(0).html("<em>最近:</em>"+h);
		}else{
			$.post(getrooturl()+"/index.php/List/List/getValueByKey",{cookiekey:cookiekey},function(data){
				if(data){
					setCookie(cookiekey,data);
					var listStr = getCookie(cookiekey);
					var h = mcform_appendList(listStr);
					$("#"+$(obj).prev().attr("id")+"_ajax").children("p.select_zuijin").eq(0).html("<em>最近</em>:"+h);
				}else{
					$("#"+$(obj).prev().attr("id")+"_ajax").children("p.select_zuijin").eq(0).html("<em>最近:无</em>");
				}
			})
		}
	}
	if($("#"+$(obj).prev().attr("id")+"_ajax").children("ul").eq(0).children('li').length == 0)
		$("#"+$(obj).prev().attr("id")+"_ajax").children("ul").eq(0).append("无数据");
}
//弹出的从数据库拿到的列表框
function mcform_showsqlselectview(obj,width,sql){
	$(document).attr("onclick",null);
	$("#"+$(obj).prev().attr("id")+"_ajax").remove();
	if(isclose){
		isclose=false;return;
	}else{
		isclose=true;
	}
	var width=width+53;
	var firstfield="",secondfield="",totalpage=0;
	var did=$(obj).prev().attr("id")+"_ajax";
	$.getJSON(getrooturl()+"/index.php/Mcss/Model/getSmartSelectList",{sql:sql,page:0},function(data){
		var h = "<div id='"+did+"' style='width:"+(width-50)+"px;height:auto;' class='div_selectList'>";
		h+="<div class='div_selectList_shaixuan'><input  placeholder='输入部分字符，回车搜索'  onKeyDown='mcform_viewSearchEnter(this,event)' class='search_text_style' style='width:"+(width-96)+"px;'/> <input type='button' value=' ' onclick='mcform_sqlViewSearch(this,"+"\""+sql+"\")' class='search_btn_style'/></div>";
		h+="<p class='select_zuijin' style='height:auto;'></p><ul>";
		if(data.length>0){
			firstfield = data[0]['firstField'];
			if(data[0]['secondField'])
				secondfield = data[0]['secondField'];
			else
				secondfield = data[0]['firstField'];;
			totalpage = data[0]['totalpage'];
		}
		for(var i = 0;i < data.length;i++){
			h+="<li fieldvalue='"+data[i][firstfield]+"' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+
			data[i][secondfield]+"</li>";
		}
		h+="</ul>";
		h+="<div class='right_select_page' style='text-align:right'><a href='javascript:void(0)' onclick='mcform_sqlviewPage(this,\"prev\",\""+sql+"\")'>上页</a> <a href='javascript:void(0)' onclick='mcform_sqlviewPage(this,\"next\","+"\""+sql+"\")'>下页</a> <input value='1' style='width:15px; height:15px;' onKeyDown='mcform_sqlviewPageEnter(this,event,"+"\""+sql+"\")' type='text'/> / <a href='javascript:void(0)' onclick='mcform_sqlviewPage(this,\"total\","+"\""+sql+"\")'>"+totalpage+"</a></div></div>";
		$(obj).after(h);
		$(".search_text_style").focus();
		 //当点击不是导出按钮的时候把下拉菜单移除
		 $(document).click(function(event){
			//if (event.target!=$("did").get(0))
			//	$("#"+did).remove();
		 })
		if(sql){
			var loginuser = getCookie("mcss_loginuser");
			var completekey = "mcformsmart_"+loginuser+"_"+sql;
			var cookiekey = completekey.substr(0,60);
			if(getCookie(cookiekey)){
				var listStr = getCookie(cookiekey);
				var h = mcform_appendList(listStr);
				$("#"+$(obj).prev().attr("id")+"_ajax").children("p.select_zuijin").eq(0).html("<em>最近:</em>"+h);
			}else{
				$.post(getrooturl()+"/index.php/List/List/getValueByKey",{cookiekey:cookiekey},function(data){
					if(data){
						setCookie(cookiekey,data);
						var listStr = getCookie(cookiekey);
						var h = mcform_appendList(listStr);
						$("#"+$(obj).prev().attr("id")+"_ajax").children("p.select_zuijin").eq(0).html("<em>最近</em>:"+h);
					}else{
						$("#"+$(obj).prev().attr("id")+"_ajax").children("p.select_zuijin").eq(0).html("<em>最近:无</em>");
					}
				})
			}
		}
	})
}
 
//加载最近选择的列表
function mcform_appendList(listStr){
	var strArr = listStr.split(',');
	var h = "";
	for(var i = 0;i < strArr.length;i++){
		var objArr = strArr[i].split(':');
		h+=" <a href='javascript:void(0)' onclick='mcform_clickNodeList(this)' fieldvalue='"+objArr[0]+"'>"+objArr[1]+"</a>"
	}
	return h;
}
//点击最近列表触发事件
function mcform_clickNodeList(obj){
	$(obj).parent().parent().prev().prev().val($(obj).html());
	$(obj).parent().parent().next().val($(obj).attr("fieldvalue"));
	$(obj).parent().parent().remove();
	isclose = false;
}
//选项点击事件
function mcform_clickSmartNode(obj,sql){
	var user = getCookie("mcss_loginuser");
	var completekey = "mcformsmart_"+user+"_"+sql;
	var cookiekey = completekey.substr(0,60);//.replace("("/g,"");
	var strList = getCookie(cookiekey);
	if(strList){
		var strArr = strList.split(',');
		if(strArr.length == 5){
			var values = $(obj).attr("fieldvalue")+":"+$(obj).html();
			if(strList.indexOf(values)==-1){
				strList = "";
				for(var i = 0;i < strArr.length-1;i++){
					if(strList)
						strList+=',';
					strList+=strArr[i];
				}
				if(strList.indexOf(values)==-1)
					strList=values+","+strList;
			}
		}else{
			var values = $(obj).attr("fieldvalue")+":"+$(obj).html();
			if(strList.indexOf(values)==-1)
				strList=values+','+strList;
		}
	}else{
		strList = "";
		var values = $(obj).attr("fieldvalue")+":"+$(obj).html();
		strList+=values;
	}
	setCookie(cookiekey,strList);
	$(obj).parent().parent().prev().prev().val($(obj).html());
	$(obj).parent().parent().next().val($(obj).attr("fieldvalue"));
	$(obj).parent().parent().remove();
	isclose = false;
}

//弹出的列表的翻页
function mcform_viewPage(obj,type,FieldText,FieldValue,sql){
	var inputObj = "",currentPage = "",condition = "",h = "";
	if(type=='prev'){
		inputObj = $(obj).next().next();
		inputObj.val(inputObj.val()-1);
	}else if(type=='next'){
		inputObj = $(obj).next();
		inputObj.val(parseInt(inputObj.val())+1);
	}else if(type=='current'){
		inputObj = $(obj);
	}else if(type=='total'){
		inputObj = $(obj).prev();
		inputObj.val($(obj).html());
	}
	if(inputObj.next().html()=='0'){
		alert("无数据!");inputObj.val(0);return;
	}
	if(isNaN(inputObj.val())){
		alert("请确认翻页输入框是数字!");inputObj.val(1);
	}else if(parseInt(inputObj.val())<=0){
		alert('已经是第一页了!');inputObj.val(1);
	}else if(parseInt(inputObj.val())>parseInt(inputObj.next().html())){
		alert('已经是最后一页了!');inputObj.val(inputObj.next().html());
	}
	if(!inputObj.val())
		inputObj.val("1");
	currentPage = parseInt(inputObj.val());
	if(FieldText)
		textArr = FieldText.split(',');
	if(FieldValue)
		valueArr = FieldValue.split(',');
	if(inputObj.parent().prev().prev().prev().children("input").eq(0).val())
		condition = inputObj.parent().prev().prev().prev().children("input").eq(0).val().replace(/[ ]/g,"");;
	var textPageArr = new Array();
	var valuePageArr = new Array();
	var rowCount = 0;
	for(var i = 0;i < textArr.length;i++){
		if(textArr[i].indexOf(condition) != -1 || !condition){
			textPageArr[rowCount] = textArr[i];
			valuePageArr[rowCount] = valueArr[i];
			rowCount++;
		}		
	}
	inputObj.parent().prev().html('');
	for(var i = (currentPage*10-10);i < currentPage*10;i++){
		if(i>=textPageArr.length)
			break;
		if(textPageArr[i].indexOf(condition) != -1 || !condition)
			h+="<li fieldvalue='"+valuePageArr[i]+"' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+textPageArr[i]+"</li>";
	}
	inputObj.parent().prev().html(h);
}
//从数据库取出来的数据翻页
function mcform_sqlviewPage(obj,type,sql){
	var inputObj = "",currentPage = "",condition = "",h = "",firstfield = "",secondfield = "";
	if(type=='prev'){
		inputObj = $(obj).next().next();
		inputObj.val(inputObj.val()-1);
	}else if(type=='next'){
		inputObj = $(obj).next();
		inputObj.val(parseInt(inputObj.val())+1);
	}else if(type=='current'){
		inputObj = $(obj);
	}else if(type=='total'){
		inputObj = $(obj).prev();
		inputObj.val($(obj).html());
	}
	if(inputObj.next().html()=='0'){
		alert("无数据!");inputObj.val(0);return;
	}
	if(isNaN(inputObj.val())){
		alert("请确认翻页输入框是数字!");inputObj.val(1);
	}else if(parseInt(inputObj.val())<=0){
		alert('已经是第一页了!');inputObj.val(1);
	}else if(parseInt(inputObj.val())>parseInt(inputObj.next().html())){
		alert('已经是最后一页了!');inputObj.val(inputObj.next().html());
	}
	if(!inputObj.val())
		inputObj.val("1");
	currentPage = parseInt(inputObj.val());
	if(inputObj.parent().prev().prev().prev().children("input").eq(0).val())
		condition = inputObj.parent().prev().prev().prev().children("input").eq(0).val().replace(/[ ]/g,"");
	$.getJSON(getrooturl()+"/index.php/Mcss/Model/getSmartSelectList",{sql:sql,page:currentPage,condition:condition},function(data){
		inputObj.parent().prev().html('');
		if(data.length>0){
			firstfield = data[0]['firstField'];
			if(data[0]['secondField'])
				secondfield = data[0]['secondField'];
			else
				secondfield = data[0]['firstField'];;
		}
		for(var i = 0;i < data.length;i++){
			h+="<li fieldvalue='"+data[i][firstfield]+"' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+
			data[i][secondfield]+"</li>";
		}
		inputObj.parent().prev().html(h);
	})
}
//弹出的翻页文本框回车触发的事件
function mcform_viewPageEnter(obj,event,FieldText,FieldValue,sql){
	if(event.keyCode=='13'){
		if(isNaN($(obj).val())){
			alert("请输入一个数字!");return;
		}
		mcform_viewPage(obj,"current",FieldText,FieldValue,sql);
	}
}
//弹出的数据库翻页文本框回车触发的事件
function mcform_sqlviewPageEnter(obj,event,sql){
	if(event.keyCode=='13'){
		if(isNaN($(obj).val())){
			alert("请输入一个数字!");return;
		}
		mcform_sqlviewPage(obj,"current",sql);
	}
}
//弹出的选项框搜索的方法
function mcform_viewSearch(obj,FieldText,FieldValue,sql){
	var condition = $(obj).prev().val();
	if(condition)
		condition = condition.replace(/[ ]/g,"");
	var textArr = "",valueArr = "",h = "";
	if(FieldText)
		textArr = FieldText.split(',');
	if(FieldValue)
		valueArr = FieldValue.split(',');
	$(obj).parent().next().next().html('');
	var rowCount = 0;
	var textConditionArr = new Array();
	var valueConditionArr = new Array();
	for(var i = 0;i < textArr.length;i++){
		if(textArr[i].indexOf(condition) != -1 || !condition){
			textConditionArr[rowCount] = textArr[i];
			valueConditionArr[rowCount] = valueArr[i];
			rowCount++;
		}
	}
	for(var i = 0;i < 10;i++){
		if(!textConditionArr[i])
			break;
		if(textConditionArr[i].indexOf(condition) != -1 || !condition){
			h+="<li fieldValue='"+valueConditionArr[i]+"' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+textConditionArr[i]+"</li>";
		}
	}
	if(rowCount%10)
		rowCount = parseInt(rowCount/10)+1;
	else
		rowCount = parseInt(rowCount/10);
	if(!rowCount){
		rowCount = "0";
		$(obj).parent().next().next().next().children("input").val("0");
	}else{
		$(obj).parent().next().next().next().children("input").val("1");
	}	
	$(obj).parent().next().next().next().children("a").eq(2).html(rowCount);
	$(obj).parent().next().next().html(h);
	if($(obj).parent().next().next().children('li').length == 0)
		$(obj).parent().next().next().append("无数据");
}
//从数据库搜索后加载的数据
function mcform_sqlViewSearch(obj,sql){
	var condition = $(obj).prev().val();
	if(condition)
		condition = condition.replace(/[ ]/g,"");
	var firstfield="",secondfield="",totalpage=0;
	$.getJSON(getrooturl()+"/index.php/Mcss/Model/getSmartSelectList",{sql:sql,condition:condition,page:0},function(data){
		$(obj).parent().next().next().html('');
		var h = "";
		if(data.length>0){
			firstfield = data[0]['firstField'];
			if(data[0]['secondField'])
				secondfield = data[0]['secondField'];
			else
				secondfield = data[0]['firstField'];
			totalpage = data[0]['totalpage'];
		}
		for(var i = 0;i < data.length;i++){
			h+="<li fieldValue='"+data[i][firstfield]+"' onclick='mcform_clickSmartNode(this,\""+sql+"\")'>"+
			data[i][secondfield]+"</li>";
		}
		if(totalpage == 0)
			$(obj).parent().next().next().next().children("input").val("0");
		else
			$(obj).parent().next().next().next().children("input").val("1")
		$(obj).parent().next().next().next().children("a").eq(2).html(totalpage);
		$(obj).parent().next().next().html(h);
		if($(obj).parent().next().next().children('li').length == 0)
			$(obj).parent().next().next().append("无数据");
	})
}
//弹出的搜索文本框回车触发的事件
function mcform_viewSearchEnter(obj,event){
	if(event.keyCode=='13'){
		$(obj).next().click();
	}
}

//输入展示下拉键盘上弹
function mcssform_smartselect_focus(event,obj,inputWidth,par1,par2,sql)
{
	mcssform_smartselect_keyUp(event,obj,inputWidth,par1,par2,sql);
}
//输入展示下拉键盘上弹
function mcssform_smartsqlselect_focus(event,obj,inputWidth,sql)
{
	mcssform_smartsqlselect_keyUp(event,obj,inputWidth,sql);
}

//输入展示下拉键盘上弹
function mcssform_smartselect_keyUp(event,obj,inputWidth,par1,par2,sql){
	if(event.keyCode=='38' || event.keyCode=='40'){
		if($(obj).next().next().children("ul").eq(0).children('li').length==0)
			return;
		var liobj = $(obj).next().next().children("ul").eq(0).find(".smartnodeselect");
		var selectliobj = "";
		if(liobj.html()){
			liobj.removeClass("smartnodeselect");
			liobj.removeClass("li_hover");
			if(event.keyCode=="40"){
				if(liobj.next().html())
					selectliobj = liobj.next();
				else
					selectliobj = $(obj).next().next().children("ul").eq(0).children('li').eq(0);
			}else{
				if(liobj.prev().html())
					selectliobj = liobj.prev();
				else
					selectliobj = $(obj).next().next().children("ul").eq(0).children('li').eq($(obj).next().next().children("ul").eq(0).children('li').length-1);
			}
		}else{
			selectliobj = $(obj).next().next().children("ul").eq(0).children('li').eq(0);
		}
		$(obj).val(selectliobj.html());
		$(obj).next().next().next().val(selectliobj.attr("fieldvalue"));
		selectliobj.addClass("smartnodeselect");
		selectliobj.addClass("li_hover");
	}else{
		mcssform_smartselectdiv(obj,inputWidth,par1,par2,sql);
	}
}
//从数据库中检索出的数据
function mcssform_smartsqlselect_keyUp(event,obj,inputWidth,sql){
	if(event.keyCode=='38' || event.keyCode=='40'){
		if($(obj).next().next().children("ul").eq(0).children('li').length==0)
			return;
		var liobj = $(obj).next().next().children("ul").eq(0).find(".smartnodeselect");
		var selectliobj = "";
		if(liobj.html()){
			liobj.removeClass("smartnodeselect");
			liobj.removeClass("li_hover");
			if(event.keyCode=="40"){
				if(liobj.next().html())
					selectliobj = liobj.next();
				else
					selectliobj = $(obj).next().next().children("ul").eq(0).children('li').eq(0);
			}else{
				if(liobj.prev().html())
					selectliobj = liobj.prev();
				else
					selectliobj = $(obj).next().next().children("ul").eq(0).children('li').eq($(obj).next().next().children("ul").eq(0).children('li').length-1);
			}
		}else{
			selectliobj = $(obj).next().next().children("ul").eq(0).children('li').eq(0);
		}
		$(obj).val(selectliobj.html());
		$(obj).next().next().next().val(selectliobj.attr("fieldvalue"));
		selectliobj.addClass("smartnodeselect");
		selectliobj.addClass("li_hover");
	}else{
		mcssform_smartsqlselectdiv(obj,inputWidth,sql);
	}
}
function mcssform_setSaveStatus(formid,can)
{
	var f=getAutoform(formid);
	f.canClickSave=can;
}	
	
//下面代码处理附件异步处理，其实是放在iframe中上传，这样不会导致父页面刷新
var uploadDoneArr=new Array();//记录本表单所有文件iframe中的文件是否已经上传完毕
var uploadedCountArr=new Array();//记录本表单所有文件iframe中的文件已经上传完毕的文件数量
function mcssform_checkifuploaded(formid,refresh,func,silent)
{
	if (uploadDoneArr[formid])	return;
	
 	var iframes=$("."+formid+"_iframe");
	
	var form1=getAutoform(formid);
 	for(var i=0;i<iframes.length;i++)
	{	
		var filename=iframes[i].contentWindow.document.getElementById('mcssuploadfile');
		
		if (filename && !filename.value)//没有文件的情形
		{
			uploadedCountArr[iframes[i].id]=true
		}
		else
		{
			//检查有文件的，是否返回上传文件的信息
			var upls=$(iframes[i]).contents().find("#uploadresult");
			if (upls.length>0 && upls[0].value)
			{
				var arr=upls[0].value.split(":");  //例子：value='ok:imange1<=>abc.jpg'
				if (arr[0]=='ok')
				{
					uploadedCountArr[iframes[i].id]=true;
					var arr2=arr[1].split("<=>");
					if (arr2.length==2){
						form1.setFieldValue(arr2[0],arr2[1]);
					}
				}
			}
		}

	}
	
	var done=true;
 	for(var i=0;i<iframes.length;i++)
	{	
		if (!uploadedCountArr[iframes[i].id])
		{
			done=false;
		}	
	}
	if (done)
	{
		uploadDoneArr[formid]=true;
		clearInterval("mcssform_checkifuploaded");
		form1.saveForm(refresh,func,silent);	
	}
	

}
//获得网站跟路径,不包括域名
function getHomeUrl()
{
	var strFullPath=window.document.location.href;
	var strPath=window.document.location.pathname;
	var pos=strFullPath.indexOf(strPath);
	return postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1)+"/index.php";
}

//获得模型字段的属性值
function mcss_getFieldProp(model,fieldid,prop)
{
	for(var i=0;i<model.fields.length;i++)
	{
		if (model.fields[i].id==fieldid)
		{
			return model.fields[i][prop];
		}
	}
}
var autoform_js_hasload=true;//判断本文件是否已加载
//if (!Autoforms)
//var Autoforms=new Array;//定义在common.js中
var currentRichEdotor;
var richid;
function getAutoform(formid){
	var r;
	for(var i=0;i<Autoforms.length;i++){
		if (Autoforms[i].formid==formid)
		{    
			r = Autoforms[i].data;
		}
	}
	return r;

}


//创建Autoform对象的另一种方式
function newMCSSForm(id,modelid,params) 
{
	if (!params)
		params={};
	params.modelid=modelid;
	return new Autoform(id,params);
}
