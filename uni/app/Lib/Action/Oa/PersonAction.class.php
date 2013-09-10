<?php
class PersonAction extends Action
{
	public function getDeptById()
	{
		$deptid = $_REQUEST['deptid'];
		
		$m=new Model();
		$sql="select name from sys_dept where id = ".$deptid;
		$data = $m->query($sql);
		echo $data[0]['name'];
	}
	
	public function getUserIdByName()
	{
		$name = $_REQUEST['name'];
		
		$m = new Model();
		$sql="select id from sys_user where name = '".$name."'";
		$data = $m->query($sql);
		echo $data[0]['id'];
	}
	//根据用户id得到用户的登录方式
	public function getUserLogin_option()
	{
		$id = $_REQUEST['id'];
		
		$m = new Model();
		$sql="select login_option from sys_user where id = '".$id."'";
		$data = $m->query($sql);
		echo $data[0]['login_option'];
	}
	//密码登录方式，修改密码
	public function updatePwd()
	{
		//判断密码是否重复
		
		$id= $_REQUEST['id'];
		$name= $_REQUEST['name'];
		$pwd= md5(trim($_REQUEST['pwd']));
		$m=new Model();		
		$sql="select name from sys_user where id<>'$id' and psw='$pwd' and login_option='P'";
		System::log('****:'.$sql);
		$data = $m->query($sql);
		if( $data[0]['name'])
		{
			echo 2;
		}else{		
			$sql1="update sys_user set name='$name',psw='$pwd',login_option='P' where id='$id' ";
			System::log('00000:'.$sql1);						
			$result=$m->execute($sql1);		
			System::log('****:'.$result);
			echo $result;
		}
		
	}
}
?>