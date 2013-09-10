<?php
class CommonAction extends Action{	
	//判断是否登录
	public function islogin(){
		if($_SESSION['loginuser']==null)
			echo '0';
		else
			echo $_SESSION['loginuser'];
	}
	//获取登录用户信息
	public function getUserID(){
		$sql = "select * from sys_user where name='".$_SESSION['loginuser']."'";
		$userid  = Tool::get1bysql($sql);
		$s   = "select id from recruitment_user where userid='".$userid."'";
		$id  = Tool::get1bysql($s);
		echo $id;
	}
}
?>