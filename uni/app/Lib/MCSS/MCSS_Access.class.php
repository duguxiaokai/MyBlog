<?php
/*
权限检查类
*/
import("@.MCSS.MCSSModel");
class MCSS_Access extends Model
{
	function getRoleAccess()
	{
		$role_access=$_SESSION['role_access'];
		if (C('mcss_checkaccess_everytime'))
			$role_access=null;
		if (!isset($role_access))
		{
			$access_file=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.APP_NAME."/Lib/Model/Access/role_model.php";
			$role_access=require($access_file);
			$_SESSION['role_access']=$role_access;
		}
		return $role_access;		
	}
	
	public function getLoginUserRole($modelid)
	{
		return System::loginuserrole();
	}
 
	//获得登录人的功能菜单对应的模型id列表
	static function getModelsFromFunction()
	{
		$func_ids=$_SESSION['loginuser_func_ids'];
		$sql="select models from sys_function where id in (".$func_ids.")";
		$m=new Model();
		$rows=$m->query($sql);
		$models="";
		foreach($rows as $row)
		{	
			if ($models)
				$models.=",";
			$models.=$row['models'];
		}
		return $models;
		
	}	
	public function checkRoleModelAccess($modelid)
	{
		//return true;
		$userrole=$this->getLoginUserRole();
		$loginuser_role_access=$_SESSION['loginuser_role_access'];
		$role_access=$this->getRoleAccess();
		if (!isset($loginuser_role_access) || C('mcss_checkaccess_everytime'))
		{
			$models=$role_access[$userrole];
			if (!$models)//如果权限文件中没有定义该角色的权限，就从数据库中查找
			{
				$sql="select models from sys_role_model where roleid in (select id from sys_role where code='$userrole')";
				$models=Data::get1bysql($sql);
				if ($models!="*"){
					$models.=",".self::getModelsFromFunction();//加上登录人的功能菜单模型
				}
				 
				$_SESSION['loginuser_role_access']=$models;
			}
			$loginuser_role_access=$models;
		}	
		$public_models=$role_access["*"];
		$models=$loginuser_role_access;

		//分几种情况检查权限，符合任何一种就算权限检查通过
		$pass=false;
		$arr=array($userrole=>$modelid);
		
		if ((C("mcss_adminuserallaccess")) && System::loginuser()=='admin')
			$pass=true;
		
		
		if ($models=='*')
			$pass=true;
		if (!$pass && strpos($models.",",$modelid.",")>-1)
		{
			$pass=true;
		}
		if (!$pass)
		{
			$pass=$this->hasModelStartsWith($models,$modelid);
		}
		if (!$pass && strpos($public_models .",",$modelid.",")>-1)
		{
			$pass=true;
		}
		if (!$pass)
		{
			$key= array_search($modelid,$role_access);
			if ($key=='*')
				$pass=true;
		}
		if (!$pass)
		{
			//看看是否完全公开的模型
			$model=MCSSModel::getModel($modelid);
			if (strpos($model['more'],"[PUBLIC]")>-1)
				$pass=true;
		}
		if (!$pass)
		{
//			if ($this->isShareToMe(""))
//				$pass=true;
		}			
		return $pass;
	}
	static function isShareToMe($str)
	{
		//return true;
		if(!$str)
			$str=$_SERVER['HTTP_REFERER'];
		else
		{
			$h='http';
			if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
				$h="https";
			$str=$h."://".$str;
		}
		$url=substr($str,0,stripos($str,"/sharekey"));
		$str=explode("/",$str);
		
		if(in_array("sharekey",$str))
		{
			$str1=array_flip($str);
			$sharekey=$str[$str1["sharekey"]+1];
		}
		$sql="select shareto from sys_share where sharekey<>'' and sharekey='$sharekey' and url='$url'";
		$m=new Model();
		$data=$m->query($sql);
		System::log($sql.'ssss');
		if (count($data)>0)
		{
			if($data[0]["shareto"]=="PUBLIC")
				return true;
			else if(System::loginuser() && strstr(strtolower(System::loginuser()),strtolower($data[0]["shareto"])))
				return true;
			else
				return false;
		}	
		else
			return false;
	}

	//判断某个共享可以是否存
	static function sharekeyIsRight()
	{
		$sharekey=self::getSharekey();
		if (!$sharekey)
			return false;
		$rows=Data::getRows("select shareto,SYS_ORGID from sys_share where sharekey='$sharekey'");
		if(count($rows)>0)
			return true;
		return false;
		
	}
	
	//获得url中sharekey的值
	static function getSharekey()
	{
		$str=$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$str=explode("/",$str);
		if(in_array("sharekey",$str))
		{
			$str1=array_flip($str);
			return $sharekey=$str[$str1["sharekey"]+1];
		}
		return "";
	}
	
	//根据共享key获得其组织id
	static function getShareKeyOrgid($str)
	{
		if(!$str)
			$str=$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$str=explode("/",$str);
		if(in_array("sharekey",$str))
		{
			$str1=array_flip($str);
			$sharekey=$str[$str1["sharekey"]+1];
		}
		if (!$sharekey)
			return false;
		$sql="select shareto,SYS_ORGID from sys_share where sharekey='$sharekey'";
		$m=new Model();
		$data=$m->query($sql);
		if(count($data)>0)
		{
			if($data[0]["shareto"]=="PUBLIC")
				return $data[0]["SYS_ORGID"];
			else if(strstr(strtolower(System::loginuser()),strtolower($data[0]["shareto"])))
				return $data[0]["SYS_ORGID"];//true;
		}
		else
			return '';
	}
	//角色权限配置中用*模型匹配的情况处理。例如'visitor'=>'oa_*'表示visitor角色拥有所有名称以“oa_”作为前缀的模型
	function hasModelStartsWith($models,$modelid)
	{
		$modelarr=explode(",",$models);
		foreach($modelarr as $value)
		{
			if (substr($value,strlen($value)-1,1)==='*')//用*模糊匹配
			{
				$start=substr($value,0,strlen($value)-1);
				if (stripos($modelid,$start)===0)
				{
					return true;
				}
			}

		}
		return false;
	}
	
	//权限检查，检查未通过则返回错误信息，否则返回null
	static function checkAccess($modelid,$returntype)
	{ 
		if (C("mcss_access_disable")==true)
			return null;//如果执行这句，那就不检查权限了！
				
		if (self::isShareToMe(""))//如果是共享页面
			return null;
		
		//看看是否完全公开的模型
		$model=MCSSModel::getModel($modelid);
		if (strpos($model['more'],"[PUBLIC]")>-1)
			return null;
				
		$access=new MCSS_Access();
		if (!$_SESSION['loginuser'])
		{
			$errmsg='请登录！';
			$err=array(array('err'=>$errmsg,'errcode'=>'loginexpire'));
			//System::log($logstr);
			if ($returntype=='string')
				return $errmsg;
			else
				return json_encode($err);
		}
		else
		if (!$access->checkRoleModelAccess($modelid))
		{

			$errmsg='unaccessible:权限不足！';
			$err=array(array('err'=>$errmsg,'errcode'=>'unaccessible'));
			$logstr='unaccessible:权限不足！详细信息：模型ID:'.$modelid;
			$logstr.=',用户名:'.System::loginuser().',角色:'.System::loginuserrole();
			System::log($logstr);
			if ($returntype=='string')
				return $errmsg;
			else
				return json_encode($err);
		}


		return null;
	}
}
?>