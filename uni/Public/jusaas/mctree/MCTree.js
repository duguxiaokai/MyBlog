//alert("MCTree");

/*
瀑布树组件
样式表可以用横或竖方向展示树
对节点的单击双击鼠标移动等事件可以通过参数接收调用方的方法
params参数是一个json结构，参数包括：
click:节点单击
dblclick:节点双击
mouseover:鼠标移到节点
loadstyle:加载显示方式。'loadallshowall'表示加载并展开所有节点，'loadallshowone'表示加载所有收缩所有节点，
canedit:是否显示编辑按钮
srcData:原始数据数组（还没有组织成父子关系的，从数据库查询的记录数组）
lang:语言文件路径。例如'../lang/cn/language.js'表示中文，'../lang/cn/language.js'代表英文
params.jiaimage:加号图片地址
params.jianimage:减号图片地址
params.leafimage:叶子图片地址
params.searchTextId:搜索框id
例子：
{:clickname,dblclick:opennode,mouseover:mouseover,loadstyle:'loadallshowone123',canedit:true}
useimage:是否使用节点图片

MCTreed的常用方法：
deleteCurentNode():删除当前节点
addNode(parentNode,nodedata):增加新节点
refresh():刷新，重新加载节点
focusNode($nodeid):把光标焦点移动到指定节点
searchNode(nodeText):根据节点文本搜索节点

*/

var MCTrees=new Array;
function MCTree_getMCTree(id){
	var r;
	for(var i=0;i<MCTrees.length;i++){
		if (MCTrees[i].id==id)
		{
			r = MCTrees[i].data;
			break;
		}
	}
	return r;
}

//语言常量
var lang_childtag="_children";

function MCTree(divid,data,params)
{
MCTrees.push({"id":divid,data:this});

this.divid=divid;
this.id=divid;
this.data=data;
this.params=params;
this.showStyle=params.showStyle;
this.currentNode;//当前节点对象，是一个span
this.currentNodeId=params.focusNode;//当前节点ID
this.field_id="id";
if (this.params.field_id)
	this.field_id=this.params.field_id;
this.field_pid="pid";
if (this.params.field_pid)
	this.field_pid=this.params.field_pid;

this.rootpath=getrooturl();	
this.imagepath=this.rootpath+"/Public/jusaas/mctree/img/";
this.jiaimage=this.imagepath+"jia.gif";
if (params.jiaimage)
	this.jiaimage=params.jiaimage;
this.jianimage=this.imagepath+"jian.gif";
if (params.jianimage)
	this.jianimage=params.jianimage;
this.leafimage=this.imagepath+"leaf.png";
if (params.leafimage)
	this.leafimage=params.leafimage;
this.searchList=new Array();

this.run=function(callback)
{
	$("#"+this.divid).html("");
	this.loadJS();
	//加载语言文件
	if (this.params.lang)
	{
	}
	/*
	//根节点
	var h="<img id='"+this.id+"_expandbtn' src='"+this.jiaimage+"' style='cursor:pointer;'  />";
	h+="<span id='"+this.id+"rootnode'>根</span>";
	var cid=this.id+lang_childtag;
	h+="<ul id='"+cid+"' class='line'></ul>";
	$("#"+divid).append(h);
	var _this=this;
	$("#"+this.id+"_expandbtn").click(function(){
		_this.expandNode(this);
	});
	*/


	this.createNodes(this.id,data);
	if (this.params.focusNode)
	{
		this.focusNode(this.params.focusNode);
	}
	if (callback)
		callback(this);
	if (this.params.afterCreated)
		afterCreated(this);
	
}

//上移节点,不涉及数据库
MCTree.prototype.moveup=function(nodeid)
{
	var nodeid=$("li[objectid='"+nodeid+"']").attr("id");
	var obj=$("#"+nodeid).get(0);
	var onthis = $(obj);
	var getup = $(obj).prev();
	$(getup).before(onthis);
}

//下移节点，不修改数据库
MCTree.prototype.movedown=function(nodeid)
{
	var nodeid=$("li[objectid='"+nodeid+"']").attr("id");
	var obj=$("#"+nodeid).get(0);
	var onthis = $(obj);
	var getup = $(obj).next();
	$(getup).after(onthis);
}


this.loadJS=function()
{
	mcss_importCss("jusaas/mctree/MCTree_left.css");
	mcss_importJS("jusaas/js/dom.js");
}

this.hasSubNodes=function(node)
{
	return $(node.parentNode).children('ul').size()>0;
}

//在divid指定对象内创建几个子节点
this.createNodes=function(divid,datas)
{
 
	//创建子节点容器
	var cid=divid+lang_childtag;
	var h="<ul id='"+cid+"' class='line'></ul>";
	$("#"+divid).append(h);
	if (this.params.loadstyle=='loadallshowone' && divid!=this.divid){
		$("#"+cid).hide();
		//$("#"+divid+"expandbtn").val("+");
	}

	//创建子节点	
  for(i in datas)
  { 
	if (!datas[i].name)
	{
		continue;
	}
	if (!datas[i]['class'])
		datas[i]['class']="default";
	var h="";
	h="<li id='"+this.id+'_'+datas[i].id+"' objectid='"+datas[i].id+"' class='tree_"+datas[i]['class']+"'>";

	var parentText="<img src='"+this.jianimage+"' class='imageleft'/>";
	if (datas[i].children &&  datas[i].children.length>0){
		parentText="<img src='"+this.jiaimage+"' class='imageleft'/>";
	}
	
	var checkid=this.divid+"_checkbox_"+datas[i].id;
	if (datas[i].children &&  datas[i].children.length>0 ){
		var parenttag=this.jiaimage;
		if (this.params.loadstyle=='loadallshowall')
			parenttag=this.jianimage;
		if (this.params.showCheckbox)
			h+="<input type='checkbox' id='"+checkid+"'  style='float:left' />";
		h+="<img id='"+datas[i].id+"expandbtn' src='"+parenttag+"' style='cursor:pointer;'  />";
	}
	else
	{
		if (this.params.showCheckbox)
			h+="<input type='checkbox' id='"+checkid+"'  style='float:left' />";
		var parenttag=this.leafimage;
		h+="<img id='"+datas[i].id+"expandbtn' src='"+parenttag+"' style='cursor:pointer;' />";
	}

	var nameid=this.id+"_"+datas[i].id+"_name";
	h+="<span id='"+nameid+"' style='cursor:pointer; '>"+datas[i].name+"</span>";

	h+="</li>";

	$("#"+cid).append(h);
	var _self=this;
	$("#"+checkid).data("obj_id",datas[i].id).change(function(){
		whenChecked(_self,this);
	});
	var _this=this;
	$("#"+datas[i].id+"expandbtn").click(function(){
		_this.expandNode(this);
	});
	
	
	//处理节点动作事件
	var myself=this;
	$("#"+nameid).click(function(tree){
		myself.currentNode=this;
		myself.currentNodeId=$(this.parentNode).attr("objectid");
		myself.focusNode(myself.currentNodeId);
	})
	$("#"+nameid).dblclick(function(){
		myself.currentNode=this;
		myself.currentNodeId=$(this.parentNode).attr("objectid");
		myself.focusNode(myself.currentNodeId);

	})
	$("#"+nameid).mouseover(function(){
		if (params.mouseover)
			params.mouseover(this);
	})
	 
	
	$("#"+nameid).mouseout(function(){
		if (params.mouseleave)
			params.mouseleave(this);
	})
	
	//递归创建子节点
	if (datas[i].children && datas[i].children.length>0)
	{
		this.createNodes(this.id+'_'+datas[i].id,datas[i].children);
	}
 
  }

	this.focusNode=function(nodeid){
		this.currentNodeId=nodeid;
		this.showNode(nodeid);
		if (this.params.focusNodeCss)
		{
			$("#"+this.id).find("span").removeClass(this.params.focusNodeCss);
			$("#"+this.id+"_"+nodeid+"_name").addClass(this.params.focusNodeCss);
		}
		else
		{
			$("#"+this.id).find("span").css("color","");
			$("#"+this.id+"_"+nodeid+"_name").css("color","blue");
		}

		if (params.dblclick)
			params.dblclick(this.currentNode,this.currentNodeId);	
		
		if (this.params.click)
			this.params.click(this.currentNode,this.currentNodeId);	
	}
	//刷新页面
	this.refresh =function()
	{
		this.deleteAllNode();
		this.createNodes(this.divid,this.data);
	}

}

	this.deleteCurentNode=function()
	{
		var nodeId=$("li[objectid='"+this.currentNodeId+"']").attr("id");
		var nextnodeid=$("#"+nodeId).next().attr("id");
		$("#"+nodeId).remove();
		this.focusNode(nextnodeid);
	}

	this.deleteAllNode=function(){
		//这里有错误，不应该固定为myorg
		$("#"+this.divid).html('');
	}

}

MCTree.prototype.changeShowStyle=function(style)
{
	if (style=="waterpool")
	{
		$("#"+this.divid).css("float","left");
		$("#"+this.divid).css("text-align","center");

	}
	else{
		$("#"+this.divid+" div div").css("float","");
		$("#"+this.divid+" div div").css("text-align","left");

		
	}
}

//增加新节点
MCTree.prototype.addNode=function(parentNodeId,nodedata)
{
	var datas=[];
	datas.push(nodedata);
	this.createNodes(parentNodeId,datas);
}


//搜索节点
MCTree.prototype.searchNode=function(nodeText)
{
	if (nodeText==this.lastSearchText)
	{
		this.searchNextNode(nodeText);
		return;
	}

	this.searchIndex=0;//满足搜索条件的，第几个
	this.lastSearchText=nodeText;//搜索词
	if (!this.params.srcData)
	{
		alert('缺少params.srcData参数，无法搜索。');return;
	}
	var nodes=this.params.srcData;
 	for(i in nodes){
		if (nodes[i].name.indexOf(nodeText)>-1){
			this.searchIndex=0;//满足搜索条件的，第几个
			var nodeid=nodes[i][this.field_id];
			this.addToSearchList(nodeText);
			this.focusNode(nodeid);
			
			return;
		}
	}
	mcdom.alert("没有找到'"+nodeText+"'。","查找",'info','fadeout');
}
//搜索下一个
MCTree.prototype.searchNextNode=function(nodeText)
{
	var nodes=this.params.srcData;
	var count=0;
 	for(i in nodes){
		if (nodes[i].name.indexOf(nodeText)>-1){
			if (count>this.searchIndex)
			{
				this.searchIndex++;//又找到一个了，加1
				var nodeid=nodes[i][this.field_id];
				this.addToSearchList(nodeText);
				this.focusNode(nodeid);
				
				return;
			}
			count++;
		}
	}
	mcdom.alert("没有找到下一个'"+nodeText+"'。","查找",'info','fadeout');
	this.searchIndex=-1;
}

//处理搜索历史
MCTree.prototype.addToSearchList=function(nodeText)
{
	this.searchList.push(nodeText);
	this.searchDataListId="searchdatalist_"+this.id;		
	var dom="<datalist id='"+this.searchDataListId+"'>";
	var arr=this.searchList;
	for(var j=0;j<arr.length;j++)
	{
		dom+="<option value='"+arr[j]+"' />";
	}
	dom+="</datalist>";
	$("#"+this.searchDataListId).remove();
	$("#"+this.searchDataListId).after(dom);
	$("#"+this.params.searchTextId).attr('list',this.searchDataListId);							
}

//根据节点ID获得数据源ID
MCTree.prototype.getObjIdByNodeId=function(NodeId)
{	
	var nodes=this.params.srcData;
 	for(i in nodes){
		if (nodes[i][this.field_id]==NodeId){
			return nodes[i]["objid"];
		}
	}
	return null;
}	 

//根据节点ID获得数据源某字段的值
MCTree.prototype.getFieldValueByNodeId=function(NodeId,fieldid)
{	
	var nodes=this.params.srcData;
 	for(var i=0;i<nodes.length;i++){
		if (nodes[i][this.field_id]==NodeId){
			return nodes[i][fieldid];
		}
	}
	return null;
}	 
	


var circusCount=0;//循环次数过多表示可能是死循环
//获得指定节点的顶级节点的id
MCTree.prototype.getTopNodeId=function(nodeId)
{
	if (circusCount>100)
	{
		//alert("循环次数过多，程序终止。");
		return;
	}
	circusCount++;
	var nodes=this.params.srcData;
	for(i in nodes){
		if (nodes[i][this.field_id]==nodeId)
		{
			if (nodes[i][this.field_pid])
					return this.getTopNodeId(nodes[i][this.field_pid]);
			else{
				return nodes[i][this.field_id];
			}
		}
	}
}
//搜索节点
MCTree.prototype.showNode=function(objectid)
{
	var nodeId=$("li[objectid='"+objectid+"']").attr("id");
	if ($("#"+nodeId).length>0){
		if ($("#"+nodeId).find('li').size()>0)
			$("#"+nodeId).children("img").eq(0).attr("src",this.jianimage);
		if ($("#"+nodeId)[0].parentNode.id!=this.divid)	
		{
			$("#"+$("#"+nodeId)[0].parentNode.id).css("display",'');
			this.showNode($("#"+nodeId).parent().parent().attr('objectid'));
		}
		var cid=nodeId+lang_childtag;
		$("#"+cid).css("display",'');		
	}
	 
}

MCTree.prototype.hasParent=function(nodeId)
{
	var data=this.params.srcData;
	for(var i=0;i<data.length;i++)
	{
		if (data[i][this.field_id]==nodeId)
		{
			var pid=data[i][this.params.field_pid];
			for(var j=0;j<data.length;j++)
			{	
				if (data[j][this.field_id]==pid)
				{
					return true;
				}
			}
		}
	}
	return false;
}

//获得完全路径，例如，""
MCTree.prototype.getFullPath=function(nodeId)
{
	var r="";
	var data=this.params.srcData;
	for(var i=0;i<data.length;i++)
	{
		if (data[i].id==nodeId)
		{
			r=data[i].name;
			if (this.hasParent(nodeId))
			{
				var pid=data[i][this.params.field_pid];
				r=this.getFullPath(pid)+"/"+r;
			}
		}
	}
	return r;
}

//展开收缩节点
MCTree.prototype.expandNode=function(obj)
{
	if ($("#"+obj.parentNode.id+lang_childtag).length==0)
		return;
	var id=obj.parentNode.id+lang_childtag;
 	if ($("#"+id).css("display")=='none'){
		$("#"+id).show();
		obj.src=this.jianimage;
	}
	else{
		$("#"+id).hide();
		obj.src=this.jiaimage;
	}
}


var getTreeJSON_nodecount=0;
var maxCount=2000;
var hasShowedErr=true;//是否已显示错误
function getTreeJSON(jsonArray,nodeid,idfield,parent_field_id)
{
	getTreeJSON_nodecount++;
	if (getTreeJSON_nodecount>maxCount)
	{
		if (!hasShowedErr){
			alert("节点数量已经超过"+maxCount+"，或者因为死循环，节点加载已终止！");
			hasShowedErr=true;
		}
		return;
	}
	var nodes=new Array;
	for(i in jsonArray)
	{
		if (nodeid){
			if (jsonArray[i][parent_field_id]==nodeid)
				nodes.push({"id":jsonArray[i][idfield],"pId":jsonArray[i][parent_field_id],"name":jsonArray[i].name,"class":jsonArray[i].type,"children":getTreeJSON(jsonArray,jsonArray[i][idfield],idfield,parent_field_id)});
		}
		else{
			//顶级节点
//			if (tree_isTopNode(jsonArray,jsonArray[i][idfield],idfield,parent_field_id))
 			if (!jsonArray[i][parent_field_id] || jsonArray[i][parent_field_id]=='0' || jsonArray[i][parent_field_id]=='DEPT_0' )//'DEPT_0'个性化代码写在这里不合理
 			{
				nodes.push({"id":jsonArray[i][idfield],"pId":jsonArray[i][parent_field_id],"name":jsonArray[i].name,"class":jsonArray[i].type,"children":getTreeJSON(jsonArray,jsonArray[i][idfield] ,idfield,parent_field_id)});
			}
		}
	}
	
	return nodes;
}

function getTreeData(jsonArray,idfield,parent_field_id)
{
	getTreeJSON_nodecount++;
	if (getTreeJSON_nodecount>maxCount)
	{
		if (!hasShowedErr){
			alert("节点数量已经超过"+maxCount+"，或者因为死循环，节点加载已终止！");
			hasShowedErr=true;
		}
		return;
	}
	var nodes=new Array;
	for(i in jsonArray)
	{
		if (tree_isTopNode(jsonArray,jsonArray[i],idfield,parent_field_id))
				nodes.push({"id":jsonArray[i][idfield],"pId":jsonArray[i][parent_field_id],"name":jsonArray[i].name,"class":jsonArray[i].type,"children":getTreeJSON(jsonArray,jsonArray[i][idfield] ,idfield,parent_field_id)});
	}
	
	return nodes;
}

//判断某节是否顶级节点
function tree_isTopNode(jsonArray,node,idField_id,parentField_id)
{
	if (!node[idField_id])	
		return;
	var pid=node[parentField_id];	
	for(var i=0;i<jsonArray.length;i++)
	{
		if (jsonArray[i][idField_id]==pid)
		{
			return false;
		}
	}
	return true;
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

/*
从所有平行的节点记录（直接来自sql查询结果）中搜索指定节点ID(nodeid)的子节点的id。
*/
MCTree.prototype.getChiledrenIds=function(nodeid)
{
	var ids=[];
	var nodes=this.params.srcData;
	for(i in nodes){
		if (nodes[i][this.field_pid]==nodeid)
		{
			ids.push(nodes[i].no);
		}
	}
	return ids;
}

/*
这个选中所有下级节点的算法比较快，但没弄好，暂时不用，就用性能较差的tree.getChiledrenIds代替了。killer
data：整个包含了节点及子节点的节点树
pid：当前节点id
*/
function getChiledren(data,pid)
{
	if (!data)
		return null;
	var nodes=[];
	for(var i=0;i<data.length;i++)
	{
		if (data[i].id==pid)
		{
			nodes=data[i].children;
			break;
		}
		else
		{
			if (data[i].children && data[i].children.length>0)
				nodes= getChiledren(data[i].children,pid);
			if (nodes)
				break;
		}
	}
	return nodes;
}


/*
这个选中所有下级节点的算法不是很好，可以优化
tree：MCTree树对象
nodeid：当前节点的id
checked：选中还是不选中
*/
function setChecked(tree,nodeid,checked)
{
	//选中该节点，也要处理子节点
	var nodes=tree.getChiledrenIds(nodeid);
	if (!nodes || nodes.length==0)
		return;
	for(var i=0;i<nodes.length;i++)
	{
		document.getElementById(tree.divid+"_checkbox_"+nodes[i]).checked=checked;
		setChecked(tree,nodes[i],checked)
	}
}

//选中该节点，也要处理父节点
function setCheckedForParentNodes(tree,nodeid,checked)
{
	//选中该节点，也要处理父节点
	if (checked)
	{
		if (tree.hasParent(nodeid))
		{
			var id=document.getElementById(nodeid).parentNode.parentNode.id;
			document.getElementById(tree.divid+"_checkbox_"+id).checked=checked;
			setCheckedForParentNodes(tree,id,checked);
		}
	}
}

function whenChecked(tree,checkbox,nodeid)
{
	var nodeid=$("#"+checkbox.id).data("obj_id");
	setCheckedForParentNodes(tree,nodeid,checkbox.checked);//选中本节点，父亲节点也应该选中
	setChecked(tree,nodeid,checkbox.checked);
}