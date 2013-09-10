<?php
header("content-type:text/html; charset=UTF-8"); 
import('@.ORG.System');
import('@.ORG.Tool');
class IndexAction extends Action{
    public function index(){ 
		$appname=$_SESSION["ORGCODE"];
		$this->redirect('../'.$appname.'/Admin/index',array(),0,'');
	}	
    public function log() 
	{
		import("@.Action.Admin.LogAction");
		$log=new LogAction;
		$log->index();
	
	}
	public function activation(){	
		$m = new Model();
		$id = $_GET['id'];
		$usertype = $_GET['usertype'];
		$sysid = $_GET['sysid'];
		$sql="update sys_user set status='1' where id ='$sysid'";
		$result = $m->execute($sql);
		if ($result>0){
			$this->assign("jumpUrl",__APP__."/Fco/Index/register4/usertype/$usertype/uid/$id");
			$this->success('激活成功');	
		}else{
			$this->assign("jumpUrl",__APP__."/Fco/Index/register4/usertype/$usertype/uid/$id");
			$this->success('不要重复激活');	
		}
	}
	
}

?>