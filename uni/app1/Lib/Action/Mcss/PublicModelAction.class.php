<?php 
class PublicModelAction extends Action{
	function getOrgList()
	{
		$id_field="id";
		$field=$_REQUEST['field'];
		if ($field)
			$id_field=$field;
		$orgs=C("APP_GROUP_LIST");
		$orgArr=explode(",",$orgs);
		$sql="select $id_field,name from sys_org";
		$m=new Model();
		$rows=$m->query($sql);
		$orgArr1[]=null;
		foreach($rows as $row){
			$orgArr1[$row["id"]]=$row["name"];
		}
		$all=array_merge($orgArr,$orgArr1);
		$orgs="";
		foreach($all as $key=>$value){
			$orgs.=$key.":".$value.",";
		}
		echo $orgs;
	}
	function test1()
	{
 
	}
	function getConfig()
	{
		$cfg["mcss_needverifycode"]=C("mcss_needverifycode");
		echo json_encode($cfg);
	}
}
?>