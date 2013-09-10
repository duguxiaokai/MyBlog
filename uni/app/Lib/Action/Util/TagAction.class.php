<?php
header("content-type:text/html; charset=UTF-8"); 

import('@.ORG.System');
class TagAction extends Action{
    function index() {
		if ($_SESSION['loginuser']==null)
		{
			$url=__APP__.'/Home/Index/login';
			if (C("mcss_lang") && C("mcss_lang")!='cn')
				$url .="_".C("mcss_lang");
			redirect($url,'1','请登录！');
		}
		
		$a=$_SESSION["loginuser_funcs_arr"];
		$this->assign('dataname',$a);
		$appname=$_SESSION['ORGCODE'];
		$this->assign('mcss_app',$appname);
    	$this->display();
    }
	function getoftenusetag(){
		$user = System::loginuser();
		$sql="select distinct(name),id from utils_tag ";
		System::Log("查询便签".$sql);
		echo Data::json($sql);
	}
	function addcommonlyusedtags(){
		$user = System::loginuser();
		$time = Expression::now();
		$name = $_REQUEST['name'];
		$repnamesql="select * from `utils_tag` where name='$name'";
		$m = new Model();
		$data = $m->query($repnamesql);
		if($data)
		{
			echo "3";
		}else{
			$sql="INSERT INTO `utils_tag` ( `name` ,`SYS_ADDUSER` ,`SYS_ADDTIME` )VALUES ( '$name', '$user', '$time')";
			$m = new Model();
			$data = $m->query($sql);
			if(count($data)>0)
			{
				echo "0";
			}else{
				echo "1";
			}
		}
		
		System::Log("查询便签".$repnamesql);
	}
	function delcommonlyusedtags(){
		$user = System::loginuser();
		$time = Expression::now();
		$id = $_REQUEST['id'];
		$sql="DELETE FROM `utils_tag` WHERE  `id` = $id";
		$m = new Model();
		$data = $m->query($sql);
		if(count($data)>0)
		{
			echo "0";
		}else{
			echo "1";
		}
		System::Log("查询便签".$sql);
	}
	function updatetag(){
		$user = System::loginuser();
		$time = Expression::now();
		$id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		
		$repnamesql="select * from `utils_tag` where name='$name'";
		System::Log("查询便签".$repnamesql);
		$m = new Model();
		$data = $m->query($repnamesql);
		if($data){
			echo "3";
		}else{
			$sql="UPDATE `utils_tag` SET `name`='$name',`SYS_EDITUSER`='$user',`SYS_EDITIME`='$time' WHERE  id=$id";
			$m = new Model();
			$data = $m->query($sql);
			if(count($data)>0)
			{
				echo "0";
			}else{
				echo "1";
			}
		}
		System::Log("更新系统便签".$sql);
	}
	//获取系统的标签
	public function getSystemTags(){
		$m = new Model();
		$type = $_REQUEST['type'];
		$sql = "select name from utils_tag where type = '$type'";
		$result = $m->query($sql);
		echo json_encode($result);
	}
	//获取用户自定义的标签
	public function getRecordTags(){
		$m = new Model();
		$recordid = $_REQUEST['recordid'];
		$type = $_REQUEST['type'];
		$sql = "select name from utils_saved_tag where recordid = $recordid and type = '$type'";
		$result = $m->query($sql);
		echo json_encode($result);
	}
	//保存记录标签
	public function saveRecordTag(){
		$m = new Model();
		$recordid = $_REQUEST['recordid'];
		$type = $_REQUEST['type'];
		$tags = $_REQUEST['tags'];
		$user = System::loginuser();
		$time = Expression::now();
		$tagsArr = explode(',',$tags);
		
		$sql = "delete from utils_saved_tag where recordid = $recordid and type = '$type'";
		$errors=0;
		$m->execute($sql);
		for($i=0;$i<count($tagsArr);$i++){
			$name = $tagsArr[$i];
			$sql = "insert into utils_saved_tag(name,type,recordid,SYS_ADDUSER,SYS_ADDTIME,SYS_EDITUSER,SYS_EDITIME)
			values('$name','$type','$recordid','$user','$time','$user','$time')";
			$result = $m->execute($sql);
			if($result == 0)
				$errors++;
		}
		return $errors;
	}
	//获取系统标签
	public function getSystemTagByType(){
		$m = new Model();
		$type = $_REQUEST["type"];
		$orgid = System::orgid();
		$sql = "select * from utils_tag where type = '$type' and SYS_ORGID = $orgid";
		echo json_encode($m->query($sql));
	}
	//获取自定义标签
	public function getRecordTagByType(){
		$m = new Model();
		$type = $_REQUEST["type"];
		$user = System::loginuser();
		$sql = "select * from utils_saved_tag where type = '$type' and SYS_ADDUSER = '$user'";
		echo json_encode($m->query($sql));
	}
	//获取热门标签
	public function getHotTagByType(){
		$m = new Model();
		$type = $_REQUEST["type"];
		$user = System::loginuser();
		$sql = "SELECT name,count(id) AS count FROM utils_saved_tag GROUP BY name ORDER BY count DESC LIMIT 6";
		echo json_encode($m->query($sql));
	}
	//搜索记录id
	public function getRecordidByTypeAndName(){
		$m = new Model();
		$name = $_REQUEST['name'];
		$type = $_REQUEST['type'];
		$sql = "select recordid from utils_saved_tag where type = '$type' and name ='$name'";
		$data = $m->query($sql);
		for($i = 0;$i < count($data);$i++){
			if($ids)
				$ids.=',';
			$ids.=$data[$i]['recordid'];
		}
		echo $ids;
	}
	//根据标签id删除记录
	public function delTagById(){
		$m = new Model();
		$id = $_REQUEST['id'];
		$sql = "delete from utils_saved_tag where id = $id";
		echo $m->execute($sql);
	}
	
}
?>