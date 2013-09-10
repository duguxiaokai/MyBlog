<?php
/*
这个类是组织机构方法处理类
作者：陈坤极
*/
Class Organization extends Model{
	
	//static loginuser="AA";//$_SESSION["loginuser"];
	//获得登录者的角色
	static function MyRole(){
		return 0;
	}
	
	//获得登录帐号对应的员工ID
	static function MyStaffID(){
		return self::StaffID($_SESSION["loginuser"]);
	}
	//获得登录帐号对应的员工Name
	static function MyStaffName(){
		return self::StaffName($_SESSION["loginuser"]);
	}
	//获得登录帐号对应的员工ID
	static function Me(){
		if (!$_SESSION['loginstaffid'])
		{
			$_SESSION['loginstaffid']=self::StaffID($_SESSION["loginuser"]);
		}
		if($_SESSION['loginstaffid']!='')
			return $_SESSION['loginstaffid'];
		else
			return -1;
	}	

	//获得登录帐号对应的员工所在部门ID
	static function MyDeptId_df(){
		$deptid=$_SESSION["mydeptid"];
		if (!$deptid)
		{
			$sql="select deptid from sys_staff where username='".$_SESSION["loginuser"]."' and SYS_ORGID=".System::orgid();
			$deptid=Data::get1bysql($sql);	
			$_SESSION["mydeptid"]=$deptid;
		}
		return $deptid;
	}		
	//获得登录帐号对应的员工所在部门名称
	static function MyDeptName_df(){
		$deptName=$_SESSION["mydeptname"];
		if(!$deptName){
			$sql="select deptid from sys_staff where username='".$_SESSION["loginuser"]."' and SYS_ORGID=".System::orgid();
			$deptid=Data::get1bysql($sql);	
			$sql="select name from sys_dept where id = $deptid";
			$deptName=Data::get1bysql($sql);	
			$_SESSION["mydeptname"]=$deptName;
		}
		return $deptName;
	}		
	//获得登录者对应员工的岗位id列表（多个id间用逗号分割）
	static function MyStaffPositions(){
		
	}


	/*
	获得登录员工的所有下属员工的某个信息（姓名或者编号或者ID）列表（多项间用逗号分割）
	param field:string，信息类别。有三个选项：NAME:员工姓名,CODE:员工编码,ID:员工ID
	return string：返回信息列表，例如：”张三,李四” 或”NO1,NO2” 或”1,2”
	*/
	static public function MyDeptStaffs($field)
	{
		$staffid=$_SESSION['loginuserstaffid'];//self::getMyStaffID();
		return self::DeptStaffIDs($staffid);
	}
	//指定员工是否是我的直属下属员工
	static public function IsMySubStaff($staffid)
	{
		$ids=self::MySubStaffs().',';
		$ids=str_replace(Organization::Me().",","",$ids);
		if (strpos($ids,$staffid.','))
		{
			echo 1;
			return true;
		}
		else
			return false;		
	}	
	
	//我的直属下属员工
	static public function MySubStaffs()
	{
		$s=$_SESSION['loginuser_substaffid'];
		if (!$s)
		{
			$sql='select staffid from sys_deptpositionstaff where deptid in (select distinct(deptid) from sys_deptpositionstaff where staffid='.Organization::Me().') and deptid in (select deptid FROM sys_deptpositionstaff where positionid IN (SELECT id from sys_position where isleader=1))';
			System::log("MySubStaffs:".$sql);

			$rows=Data::getRows($sql);
			$s="";
			foreach($rows as $row)
			{
				if ($s!="")
					$s.=",";
				$s.=$row['staffid'];
			
			}
			$_SESSION['loginuser_substaffid']=$s;
			
		}
		if ($s=="")	$s="-1";			
		return $s;
	}
	
	//我的下属员工
	static public function MySubAndSubStaffs()
	{
	}
	/*
	获得登录员工的所有下属员工的ID列表（多项间用逗号分割）.
	适合场景：一个员工只能有隶属一个部门和岗位。员工表记录直接用deptid记录所在部分，用positionid记录所在岗位
	return string：返回信息列表，例如：”1,2”
	*/
	static public function MyDeptStaffs_df()
	{
		//return $staffid=$_SESSION['loginuserstaffid'];exit;
		$mydeptid=self::MyDeptId_df();
		$deptAndSubDeptIDs=self::DeptAndSubDeptIDs_df($mydeptid);
		$sql="select id from sys_staff where deptid in ($deptAndSubDeptIDs)";
		$m=new Model();
		$rows=$m->query($sql);
		$s=$staffid;
		for($i=0;$i<count($rows);$i++)
		{
			if ($s!="")
				$s.=",";
			$s.=$rows[$i]['id'];
		}
		if (!$s) $s="-1";
		return $s;
	}
	
	//获得指定登录帐号的角色
	static function UserRole($username){
	}
	
	//获得指定登录帐号对应的员工ID
	static function StaffID($username){
		$s=Data::get1bysql("select id from sys_staff where username='$username' and SYS_ORGID=".System::orgid());	
		if (!$s)
			$s="-1";	
		return $s;
	}

	//获得指定登录帐号对应的员工NAME
	static function StaffName($username){
		return Data::get1bysql("select name from sys_staff where username='$username' and SYS_ORGID=".System::orgid());	
	}
	
	//获得指定登录帐号对应员工的岗位id列表（多个id间用逗号分割）
	static function StaffPositions($username){
	}
	
	//获得指定登录帐号的员工的所有下属员工id列表（多个id间用逗号分割）
	static function DeptStaffIDs($staffid){
		return self::DeptStaffs($staffid,"staffid");
	}
	
	//获得指定部门及其所有下属部门的员工的id列表（多个id间用逗号分割）
	static function DeptAllStaffIDs_df($deptid){
		$alldeptid="select id from sys_staff where deptid=$deptid";
	}	
	//x（多个id间用逗号分割）
	static function MyDeptAndSubDeptIDs_df(){
		$mydeptid=self::MyDeptId_df();
		return self::DeptAndSubDeptIDs_df($mydeptid);
	}
	//获得指定部门及其所有下属部门的id列表（多个id间用逗号分割）
	static function DeptAndSubDeptIDs_df($deptid){
		if(!$deptid)
			return $deptid;
		$sql="select id from sys_dept where parentid=$deptid";
		$m=new Model();
		$rows=$m->query($sql);

		$s=$deptid;
		for($i=0;$i<count($rows);$i++)
		{
			$did=$rows[$i]['id'];
			if ($s!="")
				$s.=",";
			$s.=$did;
			$sql="select id from sys_dept where parentid=$did";
			$m=new Model();
			$rows1=$m->query($sql);	
			if (count($rows1)>0)
			{
				if ($s!="")
					$s.=",";
				$s.=self::DeptAndSubDeptIDs_df($did);
			}
		}
		if (!$s) $s="-1";	
		return $s;
	}		
	

	/*
	获得指定登录帐号的员工的所有下属员工的某个信息（姓名或者编号或者ID）列表（多项间用逗号分割）
	param type:string，信息类别。有三个选项：NAME:员工姓名,CODE:员工编码,ID:员工ID
	return string：返回信息列表，例如：”张三,李四” 或”NO1,NO2” 或”1,2”
	*/
	static function DeptStaffs($staffid,$field)
	{
		if (!isset($field))	$field="staffid";
		$sql="select $field from sys_deptpositionstaff where deptid in (select distinct deptid from sys_deptpositionstaff where staffid=$staffid)";
		$m=new Model();
		$rows=$m->query($sql);
		$r="";
		foreach($rows as $row){
			if ($r!="") $r.=",";
			$r.=$row[$field];
		}
		if (!$r)	$r="-1";			
		return $r;
	}	
	
	//获得本组织所有员工的id
	static function AllStaff(){
			$sql="select id from sys_staff where SYS_ORGID=".System::orgid();			
			System::log("AllStaff:".$sql);

			$rows=Data::getRows($sql);
			$s="";
			foreach($rows as $row)
			{
				if ($s!="")
					$s.=",";
				$s.=$row['id'];
			
			}	
		if ($s=="")	$s="-1";	
		return $s;

	}	


}
?>