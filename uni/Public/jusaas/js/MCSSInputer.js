g_dinamic_cssjs.push("jusaas/js/MCSSInputer.js");

function createMcssSelect(modelid,field,container_id,fieldvalue,width,params)
{
	    var d="";
	    var rowIndex='';
	    if (params && params.rowIndex)
	    	rowIndex=params.rowIndex;	  
	    var dom_id=container_id+"_"+ field.id+"_"+rowIndex;
	    	
	    d="<select name='"+modelid+"["+field.id+"]' id='" +dom_id + "' style='border-style:none;height:100%;width:"+width;
	    if (params && params.height)
	    	d+=";height:"+params.height;
	    d+="'";	
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
						var name=enum_arr_con[1];
					}
					d += "<option ";
					if(enum_arr_con[0]===fieldvalue  || field.defaultdata && (enum_arr_con[0]===field.defaultdata || enum_arr_con[1]===field.defaultdata))
					{
						d +=" selected='true' ";
					}
					if (lang[name])
						name=lang[name];
					d +=" value='"+enum_arr_con[0]+"'>"+name+"</option>";
				}
			}
		    d += "</select>";
		}
		if (params.parentNode)
		{
			$(params.parentNode).append(d);
			//$("#"+container_id+"_"+field.id).focus();
			d=$("#"+dom_id).get(0);
		}
		return d;

}

function createMcssCheckbox(modelid,field,container_id,fieldvalue,width,params)
{
	    var d="";
	    var rowIndex="";
	    if (params && params.rowIndex)
	    	rowIndex=params.rowIndex;
	    var dom_id=container_id+"_"+ field.id+"_"+rowIndex;
        d="<input type='checkbox' name='"+modelid+"["+field.id+"]' id='"+dom_id +"' style='border-style:none;width:"+width+";height:100%;' ";		

		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}
		
		if (fieldvalue==true || fieldvalue=='on')
			d +=" checked='checked'";
		else
		if (field.defaultdata)
			d+=" value='"+field.defaultdata+"'";
				
		d+=" />";	
		
		if (params.parentNode)
		{
			$(params.parentNode).append(d);
			d=$("#"+dom_id).get(0);
		}
		
		return d;

}


function createMcssText(modelid,field,container_id,fieldvalue,width,params)
{
	    var d="";
	    var rowIndex="";
	    if (params && params.rowIndex)
	    	rowIndex=params.rowIndex;
	    var dom_id=container_id+"_"+ field.id+"_"+rowIndex;
        d="<input type='text' name='"+modelid+"["+field.id+"]' fieldid='"+field.id+"' id='"+dom_id +"' list='datalist_"+modelid+"_"+field.id+"' style='border-style:none;background:none;width:"+width+";height:100%;' ";		
		if (field.type=="date")	
		{  
		    if(field.readonly !== "true")
			    d+="class='autoform_inputtime'  onclick=\"autoform_selectDate('yyyy-MM-dd')\"";
		}
		if (field.type=="datetime")	
		{
		    if(field.readonly !== "true")
			    d+="class='autoform_inputtime'  onclick=\"autoform_selectDate('yyyy-MM-dd HH:mm:ss')\"";
		}
		if (field.readonly == "true")
		{
			d+=" readonly='true'";
		}		
		if (fieldvalue)
		{
			if (field.type=="date" && fieldvalue=='0000-00-00')
				fieldvalue="";
			d+=" value='"+fieldvalue+"'";
		}
		else
		if (field.defaultdata)
			d+=" value='"+field.defaultdata+"'";		
		
		d+=">";	
		if (params.parentNode)
		{
			$(params.parentNode).append(d);
			//$("#"+container_id+"_"+field.id).focus();
			d=$("#"+dom_id).get(0);
		}
		
		return d;

}

function createMcssRichtext(modelid,field,container_id,fieldvalue,width,params)
{
	    var d="";
	    var rowIndex="";
	    if (params && params.rowIndex)
	    	rowIndex=params.rowIndex;
	    var dom_id=container_id+"_"+ field.id+"_"+rowIndex;
        d="<input type='text' name='"+modelid+"["+field.id+"]' id='"+dom_id +"' style='border-style:none;width:"+width+";height:100%;' ";		
        d="<div name='"+modelid+"["+field.id+"]' id='"+dom_id +"' style='border-style:none;width:"+width+";height:100%;' ";		

		
		if (field.defaultdata!=undefined)//现在不用了，因为默认值在其它方法单独处理了
		{
			//d += " value='"+field.defaultdata+"'";
		}
		if (field.readonly != "true")
		{
 			d+=" contentEditable='true'";
		}
		d+=">";	
		
		if (fieldvalue)
			d+=fieldvalue;
		else
		if (field.defaultdata)
			d+=field.defaultdata;
		
		d+="</div>";	
		
		if (params.parentNode)
		{
			$(params.parentNode).append(d);
			d=$("#"+dom_id).get(0);
		}
		return d;
}