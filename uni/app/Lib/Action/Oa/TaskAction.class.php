<?php
class TaskAction extends CommonAction{
	static $aa=123;
	public function table() {
		$this->assign("theme",C("mcss_theme"));
		$this->display();
	}	
	public function taskList() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	//将数据插入数据库
	public function newtask() {
		$item=M("sys_staff");
		$type=$item->field("name")->select();
		
		$this->assign('type',$type);
		$this->display();
	}
	//查询当日有无签到
	public function getAttendanceId() 
	{
		//$thisday = $_REQUEST['date'];
		$me=Organization::Me();
		$sql="select id,markdate from `oa_attendance` where staffid='$me' and markdate=curdate()";
		System::log($sql);
		$Model=new Model();
		$data=$Model->query($sql);//echo $data;
		echo $data[0]['id']."<>".$data[0]['markdate'];
	}
	//查询当日日报类型为3即工作概述的记录的id
	public function getThisDayReportId() 
	{
		$thisday = $_REQUEST['date'];
		$executerid=$_REQUEST['executerid'];
		$sql="select id from `oa_task` where executerid='$executerid' and cat='日报' and tag='3' and  begindate='$thisday' and enddate='$thisday'";
		System::log($sql);
		$Model=new Model();
		$data=$Model->query($sql);//echo $data;
		echo $data[0]['id'];
	}
	//查询当月周报类型为1即工作概述的记录的id
	public function getThisWeekReportId() 
	{
		$thisday = $_REQUEST['date'];
		$executerid=$_REQUEST['executerid'];
		$sql="select id from `oa_task` where executerid='$executerid' and cat='周报' and tag='3' and  YEARWEEK(date_format(begindate,'%Y-%m-%d')) = YEARWEEK('$thisday')";
		System::log($sql);
		$Model=new Model();
		$data=$Model->query($sql);//echo $data;
		echo $data[0]['id'];
	}	
	//查询当月月报类型为1即工作概述的记录的id
	public function getThisMonthReportId() 
	{
		$thisday = $_REQUEST['date'];
		$executerid=$_REQUEST['executerid'];
		$sql="select id from `oa_task` where executerid='$executerid' and cat='月报' and tag='3' and DATE_FORMAT(begindate, '%Y%m') = DATE_FORMAT('$thisday','%Y%m')";
		$Model=new Model();
		$data=$Model->query($sql);//echo $data;
		echo $data[0]['id'];
	}

	//查询下级员工
	public function mysubstaff()
	{
		$mydeptstaffid=Organization::MySubStaffs();
		$sql = "select id,name,username from sys_staff where id in ($mydeptstaffid)  and statue <> '0'";
		System::log("mysubstaff:".$sql);
		$Model=new Model();
		$data=$Model->query($sql);
		echo json_encode($data);
	}
	
	public function AllStaff()
	{
		$ids=Organization::AllStaff();
		$sql = "select id,name,username from sys_staff where id in ($ids) and statue <> '0'";
		System::log("mysubstaff:".$sql);
		$Model=new Model();
		$data=$Model->query($sql);
		echo json_encode($data);
	}
		
	
	//删除一条记录
	public function deleteOneTask()
	{
		$id = $_REQUEST['id'];
		$sql="delete from oa_task where id='$id'";
		$Model=new Model();
		$data=$Model->execute($sql);
		echo $data;
	}
	
	//快速修改任务状态
	public function changeTaskStatus()
	{	
		$id = $_REQUEST['id'];
		$status = $_REQUEST['status'];
		$sql ="update oa_task set finishper='$status' where id =".$id;
		$Model=new Model();
		$data=$Model->execute($sql);
		echo $data;
	}
	
	public function selecuser(){
	 	$userName=$_POST['val'];
		//echo $userName;die;
		$item=M("sys_staff");
		$zhanghao=$item->where("name='".$userName."'")->getField("username");
		echo $zhanghao;
	 }
	 
    public function insertrecord() {
    	//var_dump($_POST);die;
    	//$T = M('oa_task');
        $project=$_POST['project'];
        $name=$_POST['name'];
        $cat=$_POST['cat'];
        $desp=$_POST['desp'];
        $begindate=$_POST['begindate'];
        $enddate=$_POST['enddate'];
        $loginuser=$_POST['loginuser'];
        $executer=$_POST['executer'];
        $status=$_POST['status'];
		$executerid=$_POST['executerid'];
		$loginuserid=$_POST['loginuserid'];
        $sql="insert into `oa_task` (`project`,`name`,`cat`,`desp`,`begindate`,`enddate`,`loginuser`,`executer`,`status`,`executerid`,`loginuserid`) values ('".$project."','".$name."','".$cat."','".$desp."','".$begindate."','".$enddate."','".$loginuser."','".$executer."','".$status."','".$executerid."','".$loginuserid."')";
		$Model=new Model();
		$data=$Model->execute($sql);
		if($data>0){
                echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
		else{
                echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
    	/*$data = $T->create();
    	if(!empty($data)){
			if(false !== $T->add()){
                echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
            }else{
                echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
            }
		}else {
			 echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}*/
		
    }

    //显示所有任务信息
    public function list_task() {
        $this->redirect('../List/List/list2?param:table=list_task',array(),0,"");
    }
    public function list_task_pending() {
        $this->redirect('../List/List/list2?param:table=list_task_pending',array(),0,"");
    }
    public function list_project() {
        $this->redirect('../List/List/list2?param:table=list_project',array(),0,"");
    }
    public function list_topic() {
        $this->redirect('../List/List/list2?param:table=list_topic',array(),0,"");
    }

    //根据id显示某一个发票信息
    function showonefapiao() {
    	$id=$_GET["id"];
    	$cus = M('crm_fapiao');
		$data=$cus->getByid($id);
		$this->assign('data',$data);
		$this->display();
    }

    //根据id编辑任务信息
    function edittask() {

		
    	$id = $_GET["id"];
    	if(!empty($id)){
			$T = M('oa_task');
			$data=$T->getByid($id);
			
			$item=M("sys_staff");
			$type=$item->field("name")->select();
				
			if(!empty($data)) {
			    $this->assign('type',$type);
				$this->assign('data',$data);
				$this->display('edittask');
			}else {
			    echo  "<script>alert('没有此记录');history.back();</script>";
			}
		}else {
			echo "<script>alert('编辑的内容不存在');history.back();</script>";
		}
		
		
    }

    //保存编辑的发任务信息
	function updaterecord() {
		$id = $_POST['id'];
    	$T=M('oa_task');
//
    	$data = $T->create();
	if(!empty($data)){
	  $T->save();
	}
	
	echo "<script>window.parent.updateOneRow(".$id."); </script>";
	//echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
    }
	
	public function getexecuterbyexecuterid()
	{
		$executerid = $_REQUEST['executerid'];
		$sql = "select name from sys_staff where id='".$executerid."'";
		$m = new Model();
		$rows = $m->query($sql);
		echo $rows[0]['name'];
	}
	public function note() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	public function addnote() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}
	
	public function deletenote()
	{
		$id = $_REQUEST['id'];
		$sql = "delete from oa_note where id ='".$id."'";
		$Model=new Model();
		$data=$Model->execute($sql);
		echo $data;
	}
	public function note_task()
	{
		$id = $_REQUEST['id'];
		$sql = "select * from oa_note where id = '".$id."'";
		$m = new Model();
		$rows = $m ->query($sql);
		$sql1 = "insert into oa_task (name,executerid,begindate,enddate,cat,tag) values ('".$rows[0]['name']."','Me()','".$rows[0]['SYS_ADDTIME']."','".$rows[0]['SYS_ADDTIME']."','日报','2')";
		$sql1 = Expression::parseExpression($sql1);
		$data=$m->execute($sql1);
		echo $data;
	}
	
	public function getloginuserBYstaffid()
	{
		$staffid = $_REQUEST['staffid'];
		$sql = "select username from sys_staff where id=$staffid";
		$username = Data::sql1($sql);
		echo $username;
	}
	
	public function insertday()
	{
		$begindate = $_REQUEST['begindate'];
		$enddate = $_REQUEST['enddate'];
		$finishper = $_REQUEST['finishper'];
		$name = $_REQUEST['name'];
		$executerid = $_REQUEST['executerid'];
		$notes = $_REQUEST['notes'];
		$ADDUSER = System::loginuser();
		$ADDTIME = $_REQUEST['ADDTIME'];
		$EDITUSER = $_REQUEST['EDITUSER'];
		$EDITTIME = $_REQUEST['EDITTIME'];
		$orgid=System::orgid();
		$sql = "insert into oa_task (name,executerid,begindate,enddate,cat,tag,SYS_ADDUSER,SYS_ADDTIME,SYS_EDITUSER,SYS_EDITTIME,SYS_ORGID) values ('".$name."','$executerid','".$begindate."','".$enddate."','日报','2','".$ADDUSER."','".$ADDTIME."','".$EDITUSER."','".$EDITTIME."','$orgid')";
		System::log('abc'.$sql);
		$m = new Model();
		$m->execute($sql);
		
	}
	
	function workStat()
	{
		$begindate = $_REQUEST['beginweek'];
		$endweek = $_REQUEST['endweek'];
		$ADDUSER = System::loginuser();
		$m = new Model();
		$orgid=System::orgid();
		$data=array();
		for($i=0;$i<7;$i++)
		{	
			$sql = "select count(*) as total from oa_task where begindate = (SELECT subdate( '$begindate' , date_format( '$begindate' , '%w' )-$i  ) ) AND  ENDdate = (SELECT subdate( '$endweek' , date_format( '$endweek' , '%w' )-$i  ) )  and  	SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' and cat='日报' ";
			$total=$m->query($sql);
			//System::log("=================周$i的+===========".$sql);
			$sql1 = "select count(*) as done from oa_task where begindate = (SELECT subdate( '$begindate' , date_format( '$begindate' , '%w' )-$i  ) ) AND  ENDdate = (SELECT subdate( '$endweek' , date_format('$endweek' , '%w' )-$i  ) ) and finishper=1  and  SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' and cat='日报'  ";
			$finishper =$m->query($sql1);
			$data[]["total"]=$total[0]['total'];
			$data[]["done"]=$finishper[0]['done'];
		}
		//var_dump($data);
		echo json_encode($data);
	}
	
	function weekStat()
	{
		$begindate = $_REQUEST['beginweek'];
		$endweek = $_REQUEST['endweek'];
		$ADDUSER = System::loginuser();
		$m = new Model();
		$orgid=System::orgid();
		$data=array();
		$sql = "select count(*) as total from oa_task where begindate>= '$begindate'  AND  ENDdate <=  '$endweek'   and  	SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' and (cat ='周报' or cat ='项目') ";
		$total=$m->query($sql);
		System::log("周工作总条数".$sql);
		$sql1 = "select count(*) as done from oa_task where begindate>= '$begindate'  AND  ENDdate <=  '$endweek'   and  	SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' and (cat ='周报' or cat ='项目') and  finishper=1 ";
		
		System::log("周工作完成数".$sql1);
		$finishper =$m->query($sql1);
		$data[]["total"]=$total[0]['total'];
		$data[]["done"]=$finishper[0]['done'];
		echo json_encode($data);
	}
	function monthStat()
	{
		$begindate = $_REQUEST['beginmonth'];
		$enddate = $_REQUEST['endmonth'];
		$ADDUSER = System::loginuser();
		$orgid=System::orgid();
		$m = new Model();
		$data=array();
		//查出所有的当月的月工作
		$sql = "select count(*) as total from oa_task where year(begindate) = year('$begindate')  and month(begindate) = month('$begindate') and year(enddate) = year('$enddate') and month(enddate) = month('$enddate')  and  	SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' ";
		$total=$m->query($sql);
		//System::log("所有的当月的月工作:".$sql);
		//查出完成的
		$sql1 = "select count(*) as done from oa_task where year(begindate) = year('$begindate')  and month(begindate) = month('$begindate') and year(enddate) = year('$enddate') and month(enddate) = month('$enddate') and finishper=1  and  SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' ";
		$finishper =$m->query($sql1);
		//查出未完成的
		$sql2 = "select count(*) as nodone from oa_task where year(begindate) = year('$begindate')  and month(begindate) = month('$begindate') and year(enddate) = year('$enddate') and month(enddate) = month('$enddate') and finishper=0  and  SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid' ";
		$nodone =$m->query($sql2);
		//System::log("未完成的:".$sql2);
		//查出放弃的
		$sql3 = "select count(*) as fangqi from oa_task where year(begindate) = year('$begindate')  and month(begindate) = month('$begindate') and year(enddate) = year('$enddate') and month(enddate) = month('$enddate') and finishper=2  and  SYS_ADDUSER='$ADDUSER '  and sys_orgid='$orgid'  ";
		$fangqi =$m->query($sql3);
		//System::log("放弃的:".$sql3);
		$data[]["total"]=$total[0]['total'];
		$data[]["done"]=$finishper[0]['done'];
		$data[]["nodone"]=$nodone[0]['nodone'];
		$data[]["fangqi"]=$fangqi[0]['fangqi'];
		
		echo json_encode($data);
	}
		//根据媒体id得到相应的媒体信息
		
	//移动任务后更新日期
	public function updatemovedate(){
		$id = $_REQUEST["id"];
		$begindate = $_REQUEST["begindate"];
		$enddate = $_REQUEST["enddate"];
		
		$sql = "update oa_task set begindate = '$begindate',enddate = '$enddate' where id = '$id'";
		echo Data::sql($sql);
	}
	//修改任务的状态
	public function updateTaskStateStatus()
	{
		$id = $_REQUEST["id"];
		$status = $_REQUEST["status"];
		$sql = "update oa_task set status = '$status' where id = '$id'";
		if($status=="done")
		{
			$sql = "update oa_task set status = '$status',finishper='1'  where id = '$id'";
		}
		//System::log("修改任务的状态:".$sql);
		echo Data::sql($sql);
	}
	//获取到公司的所有员工
	public function getAllStaff(){
		$m = new Model();
		$orgid = System::orgid();
		$sql = "select id,name from sys_staff where SYS_ORGID = $orgid and statue = 1";
		$result = $m->query($sql);
		echo json_encode($result);
	}
	//改变获取员工的出勤记录
	public function getStaffRecord(){
		$m = new Model();
		$starttime = $_REQUEST['starttime'];
		$endtime = $_REQUEST['endtime'];
		$staffid = $_REQUEST['staffid'];
		$dt_start = strtotime($starttime);
		$dt_end   = strtotime($endtime);
		$orgid = System::orgid();
		$sql = "select id from sys_staff where SYS_ORGID = $orgid and statue = 1";
		$staffids = $m->query($sql);
		$data = array();
		while($dt_start <= $dt_end){    
			$witch_day = date('w',$dt_start);
			if($witch_day=='6' || $witch_day=='0'){//如果该日是周末，跳过所有检查
				$dt_start += 86400;// 加一天日期
				continue;
			}
			$sqldate = date('Y-m-d',$dt_start);
			if($staffid){//查看每个员工的缺勤记录
				$data = $this->checkStaff($sqldate,$staffid,$m,$data);
			}else{
				for($k = 0;$k < count($staffids);$k++){
					$staffid = $staffids[$k]['id'];
					$data = $this->checkStaff($sqldate,$staffid,$m,$data);
				}
				$staffid = "";
			}
			$dt_start += 86400;// 重复 Timestamp + 1 天(86400), 直至大于结束日期中
		}
		echo json_encode($data);
	}
	//检查员工是否打卡请假
	public function checkStaff($sqldate,$staffid,$m,$data){
		$sql = "select count(id) from oa_attendance where staffid = $staffid and markdate = '$sqldate'";
		$result = Tool::get1bysql($sql); //检查该员工是否在这一天打卡
		if($result == 0){
			$sql = "select name from sys_staff where id = $staffid";
			$name = Tool::get1bysql($sql);//获取员工的名称
			$sql = "select count(id) from oa_qingjia where staffid = $staffid and ((begindate < '$sqldate' and enddate > '$sqldate') or begindate='$sqldate' or enddate='$sqldate') and status = 'valid'";
			$result = Tool::get1bysql($sql);//员工未打卡时，检查是否请假
			if($result == 0){
				$type = "旷工";
			}else{
				$sql = "select type from oa_qingjia where staffid = $staffid and ((begindate < '$sqldate' and enddate > '$sqldate') or begindate='$sqldate' or enddate='$sqldate') and status = 'valid'";
				$result = $m->query($sql);//获得员工请假的类型
				$type = $result[0]['type'];
			}
			$i = count($data);
			$data[$i]['name'] = $name;
			$data[$i]['date'] = $sqldate;
			$data[$i]['type'] = $type;
		}
		return $data;
	}
	//获取任务的最新评论信息
	public function getNewDiscuss(){
		$m = new Model();
		$id = $_REQUEST['id'];
		$sql = "select content from oa_discussion where task_id = $id order by SYS_ADDTIME desc";
		$data = $m->query($sql);
		echo $data[0]['content'];
	}
}
?>