<?php
class SystemAction extends Action{

    public function loginuser(){
		echo $_SESSION['loginuser'];
    }
	/*
	根据类别type和单号的长度length获得一个新的id
	*/
    public function newid($type,$length){
		if ($type==null)
		$type=$_REQUEST["type"];
		$length=$_REQUEST["length"];
		import("@.ORG.System");
		echo System::newid($type,$length);
    }
	
	//根据前台传来的sql返回sql执行结果的第一个记录的第一字段的值
	function get1bysql()
	{
		$sql=$_REQUEST['sql'];
		$sql=$this->dealSpecialChar($sql);
		$sql=Expression::parseExpression($sql);
		echo Tool::get1bysql($sql);
	}
	
		
	//根据前台传来的sql返回sql执行结果
	function getJSONbysql()
	{
		$sql=$_REQUEST['sql'];
		$sql=$this->dealSpecialChar($sql);
		if(get_magic_quotes_gpc())//如果get_magic_quotes_gpc()是打开的
		{
			$sql=stripslashes($sql);//将字符串进行处理
		}
		$sql=Expression::parseExpression($sql);
		System::log('getJSONbysql:'.$sql);
		echo Tool::getDataJSON($sql);
	}	
	//保存查询条件
	public function saveSeachValue()
	{
		$modelid=$_REQUEST['modelid'];
		$query=addslashes($_REQUEST['query']);
		$valuename=$_REQUEST['valuename'];
		$name=System::loginuser();
		$sql = "select query from sys_myquery where valuename='".$valuename."' and user='".$name."'";
		$m = new Model();
		$result = $m->query($sql);
		if($result[0]['query'])
		{
			echo 1;
		}else
		{
			$sql1 = "insert into sys_myquery (modelid,user,valuename,query) values ('".$modelid."','".$name."','".$valuename."','".$query."')";
			$m=new Model();
			$rows=$m->query($sql1);
		}
		
		
	}
	 //获取保存标题
	public function getSeachValue()
	{
		$modelid = $_REQUEST['modelid'];
		$user = System::loginuser();
		$sql = "select valuename from sys_myquery where modelid='".$modelid."' and user='".$user."'";
		$m = new Model();
		$result = $m->query($sql);
		$count = count($result);
		$s="";
		for($i=0;$i<$count;$i++)
		{
			if ($result[$i]['valuename'])
			{	
				if ($s)
				{
					$s.=',';
					
				}
				$s.=$result[$i]['valuename'];
			}
		}
		echo $s;
	}
	//根据标题执行查询语句
	public function getSeachquery()
	{
		$valuename = $_REQUEST['title'];
		$user = System::loginuser();
		$sql = "select query from sys_myquery where valuename='".$valuename."' and user='".$user."'";
		$m = new Model();
		$result = $m->query($sql);
		echo $result[0]['query'];
	}
	//删除查询标题
	public function clearValue()
	{
		$clearvalue = $_REQUEST['clearvalue'];
		$user = System::loginuser();
		$arr = explode(',',$clearvalue);
		$m = new Model();
		for($i=0;$i<count($arr);$i++)
		{
			$sql = "delete from sys_myquery where valuename='".$arr[$i]."' and user='".$user."'";
			$rows=$m->query($sql);
		}
	}

	public function getFuncUrl()
	{
		$func_code=$_REQUEST["func_code"];
		$m=new Model();
		$rows=$m->query("select * from sys_function where no='$func_code'");
		if (count($rows)>0)
		{
			$row=$rows[0];
			echo $url=System::getFuncRunningUrl($row);
		}
	}

	public function getmenuUrl()
	{
		$func_code=$_REQUEST["func_code"];
		$m=new Model();
		$rows=$m->query("select * from ems_menu where no='$func_code'");
		if (count($rows)>0)
		{
			$row=$rows[0];
			echo $url=System::getFuncRunningUrl($row);
		}
	}
	
	//处理引号
	function dealSpecialChar($str)
	{
	    $repl = array("<yh>"=>"'",'\"'=>"'","\'"=>"'");
        return $str=strtr($str,$repl);
	}	
	//实现文件下载
	public function download(){
		$filename=$_REQUEST["filename"];
        $file =$final_file_path=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/uploadfile/'.$filename; // 要下载的文件	
        ob_clean();
        header('Pragma: public');
        header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control:no-store, no-cache, must-revalidate');
        header('Cache-Control:pre-check=0, post-check=0, max-age=0');
        header('Content-Transfer-Encoding:binary'); 
        header('Content-Encoding:none');
        header('Content-type:multipart/form-data');
        header('Content-Disposition:attachment; filename="'.basename($file).'"'); //设置下载的默认文件名
        header('Content-length:'. filesize($file));
        $fp = fopen($file, 'r'); //读取数据，开始下载
        while(connection_status() == 0 && $buf = @fread($fp, 8192)){
            echo $buf;
        }
        fclose($fp);
        @flush();
        @ob_flush();
        exit();
    }	
	//是否指定员工是否我的下属
	function isMySubStaff()
	{
		$staffid = $_REQUEST["staffid"];
		echo Organization::IsMySubStaff($staffid);
	}


	function test()
	{
		echo System::randstr();
	}	
	
	//获得随机字符串
	function getRandStr()
	{
		//echo String::uuid();
		Load('extend');
		echo rand_string(8,"","");
	}
	
	//保存共享信息
	function saveShareInfo()
	{		
		$m = new Model();
		$url=str_replace("*xiegang*","/",$_REQUEST["url"]);
		$cancel = $_REQUEST["cancel"];
		$shareto = $_REQUEST["shareto"];
		$sharekey = $_REQUEST["sharekey"];
		$recordid = $_REQUEST["recordid"];
		if ($recordid=='undefined')
			$recordid='';
		$objectid = $_REQUEST["objectid"];
		if ($_REQUEST["canedit"]=='true')
			$canedit='[canedit]';
		else
			$canedit='';

		if($shareto!="PUBLIC" && $shareto!="cancel" && $shareto!='')
		{
			$shareArray=explode(",",$shareto);
			foreach($shareArray as $k=>$v)
			{
				$sql="SELECT id FROM `sys_user` WHERE name='$v'";
				if($m->execute($sql)==0)
				{
					echo $v;
					return;
				}
			}
		}
		//是否存在共享记录
		if ($recordid)
		{
			if ($cancel=='true')
				if ($recordid!='undefined')
				{
					$sql="delete from sys_share where id=$recordid";			
				}
				else
					echo 1;
			else
				$sql="UPDATE `sys_share` SET `shareto`='$shareto',options='$canedit' WHERE id='$recordid'";
			echo Data::sql($sql);
		}
		else
		{
			$orgid=System::orgid();
			$sql="INSERT INTO sys_share (`shareto`, `url`, `sharekey`,share_object_id,options,SYS_ORGID) VALUES ('$shareto','$url','$sharekey','$objectid ','$canedit',$orgid)";
			$n=Data::sql($sql);
			if ($n==1)
				echo mysql_insert_id();
			else
				echo $n;
		}
		System::log('saveShareInfo:'.$sql);		
		
		//echo Data::sql($sql);
		//echo mysql_insert_id;
	}

	//检查共享信息	
	function checkShareUrl(){
		$orgid=System::orgid();
		$url=str_replace("*xiegang*","/",$_REQUEST["url"]);
		$sql="select * from  sys_share where url='$url'";
		//System::log($sql);
		$m = new Model();
		$data=$m->query($sql);
		if($data)
			echo json_encode($data);
		else
			echo 0;		
	}
	
	function updateFileOwnerId()
	{
		$old_ownerid=$_REQUEST['old_ownerid'];
		$new_ownerid=$_REQUEST['new_ownerid'];
		$sql="update sys_formfile set ownerid='$new_ownerid' where ownerid='$old_ownerid'";
		System::log($sql);
		echo Data::sql($sql);
    }
    function test1()
    {
    	echo System::orgid();
    }

}
?>