<?php 
class OrgAction extends CommonAction{
//组织结构添加
public function addorg(){
	$data=array(
		'name'=>$_POST["name"],
		'adminuser'=>$_POST["adminuser"]
	);
	$add=M("sys_org")->add($data);
	if($add>0){
		echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
	}
	$this->display();
}

//组织结构查询单个编辑的数据
public function savorg(){
	$id=$_GET["id"];
	$sav=M("sys_org")->find($id);
	$this->assign("data",$sav);
	$this->display();
}
//组织结构的更新
function savorgs(){
	$org=M("sys_org");
	$data=array(
		'id'=>$_POST["orgid"],
		'name'=>$_POST["name"],
		'adminuser'=>$_POST["adminuser"]
	);
	$savorgs=$org->save($data);
	if($savorgs>0)
	{
	echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
	}
}
//组织结构删除
//public function delorg(){
	//$id=$_REQUEST["orgid"];
	//$del=M("sys_org")->delete($id);
	//if($del>0){
				 //echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
			//}
//}
//组织结构查看
public function selorg(){
	$this->redirect('../List/List/list2?param:table=sys_org_list',array(),0,"");
}


function getDeptIdByPositionId()
{
	$positionid=$_REQUEST["positionid"];
	$sql="select deptid from sys_position where id=$positionid";
	echo Tool::get1bysql($sql);
}

function index()
{
	$this->assign("mcss_theme",C("mcss_theme"));
	$this->assign("mcss_lang",C("mcss_lang"));
	$this->display();
}

function index1()
{
	$this->assign("mcss_theme",C("mcss_theme"));
	$this->assign("mcss_lang",C("mcss_lang"));
	$this->display();
}

function addStaaffsToPosition()
{
	$staffs=$_REQUEST["staffs"];
	$deptid=$_REQUEST["deptid"];
	$positionid=$_REQUEST["positionid"];
	$arr=explode(",",$staffs);
	$sql="";
	$n=0;
	for($i=0;$i<count($arr);$i++)
	{
		$staffid=$arr[$i];
		$sql="insert into sys_deptpositionstaff(deptid,positionid,staffid) values($deptid,$positionid,$staffid)";
		$n+=Tool::sql($sql);
	}
	echo $n;
	
	
	
}
	function MyDeptName_df(){
		$deptName=$_SESSION["mydeptname"];
		if(!$deptName){
			$sql="select deptid from sys_staff where username='".$_SESSION["loginuser"]."' and SYS_ORGID=".System::orgid();
			$deptid=Tool::get1bysql($sql);	
			$sql="select name from sys_dept where id = $deptid";
			$deptName=Tool::get1bysql($sql);	
			$_SESSION["mydeptname"]=$deptName;
		}
		echo $deptName;
	}		
	
	//echo Orgazationm::MyDeptName_df();


}
?>