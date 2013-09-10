<?php
class StatAction extends Action{
	
	//获得最近一次的统计信息
	public function getLastStatInfo(){
		$stat_code=$_REQUEST["stat_code"];
		$stat_time=$_REQUEST["stat_time"];
		$sql="select * from sys_statdata where stat_code='$stat_code' and stat_time='$stat_time' order by id desc limit 0,1";
		//Log::write($sql);
		echo Tool::getDataJSON($sql);
	}
 	public function viewStatData(){
		$statid=$_GET["id"];
		$stat=M("sys_statdata");
		$statinfo=$stat->find($statid);
		$mcss_app=$_SESSION["ORGCODE"];
		$mcss_theme=C("mcss_theme");
		$this->assign('mcss_app',$mcss_app);
		$this->assign('mcss_theme',$mcss_theme);
		$this->assign('stat',$statinfo);
		
		$this->display();
	}
	public function newStat(){
		$mcss_app=$_SESSION["ORGCODE"];
		$mcss_theme=C("mcss_theme");
		$this->assign('mcss_app',$mcss_app);
		$this->assign('mcss_theme',$mcss_theme);
		$this->display();	
	}
	public function printhtml(){
		$statid=$_REQUEST["statid"];
		$sql="select html from `sys_statdata` where id=".$statid;
		$M=M();
		$statlist=$M->query($sql);
		foreach ($statlist as $show){
			echo $show['html'];
		}
	}	
	
	//执行统计，把统计结果写入sys_statdata表，并把新id返回给前台页面
	function doStat()
	{
		$stat_code=$_REQUEST['stat_code'];
		$stat_name=$_REQUEST['stat_name'];
		$stat_time=$_REQUEST['stat_time'];
		$startdate=$_REQUEST['startdate'];
		$enddate=$_REQUEST['enddate'];
		$chartfield=$_REQUEST['chartfield'];
		$stat_notes=$_REQUEST['stat_notes'];
		//System::log($stat_notes);
 
		$timespan=$this->getTimeSpan($stat_time,$startdate,$enddate);
		$startdate=$timespan['startdate'];
		$enddate=$timespan['enddate'];		

		$time_from=strtotime("now");

		import("@.Action.System.ModelAction");
		$dataDeal=new ListAction();
		$model=$dataDeal->getGoodModel($stat_code);
		$sql=$model['sql']; 
		$sql = str_replace("{begin_date}",$startdate, $sql);
		$sql = str_replace("{end_date}",$enddate, $sql);
		System::log($sql);
		$m=new Model();
		$rows=$m->query($sql);

		$data_arr=serialize($rows);
		//System::log('data_arr:'.$data_arr);
		$time_to=strtotime("now");
		$cost_time=$time_to-$time_from;//统计所耗时间

		$data["stat_code"]=$stat_code;
		$data["stat_name"]=$stat_name;
		$data["stat_time"]=$stat_time;//统计的时间段
		$data["SYS_ADDTIME"]=date("Y-m-d H:i:s");//统计的时间点
		$data["owner"]=$_SESSION['loginuser'];//统计者
		$data["chart"]=$chartfield;
        $data["cost_time"]=$cost_time;
        $data["stat_notes"]=$stat_notes;
		
		//$data["datatype"]="JSON";//不知为何更新不上，只好用下面的Tool::sql
		$data["html"]=$data_arr;
 		$T=M("sys_statdata");
		$T->data($data)->add();
		$id=mysql_insert_id();
		Tool::sql("update `sys_statdata` set `datatype`='JSON',stat_function='"."Sys/".MODULE_NAME."/".ACTION_NAME."' where id=$id");
		echo $id;			
 
	}
	
	function getDataJSON()
	{
		$statid=$_REQUEST["id"];
		//$statid='2792';
		$data=Tool::get1bysql("select html from sys_statdata where id=$statid");
		$rows=unserialize($data);
		//print_r($rows);
		echo json_encode($rows);
	}
	//计算开始与结束日期
	function getTimeSpan($stat_time,$startdate,$enddate)
	{
		if($stat_time=="所有时间")
		{
			$startdate="1900-01-01";
			$enddate="2049-12-31";						
		} else 
		if ($startdate!="" || $enddate!="") 
		{
			if (!$startdate)
				$startdate="1900-01-01";
			if (!$enddate)
				$enddate="2049-12-31";						
		}
		else //$stat_time是年份
		{
			$startdate=$stat_time."-01-01";
			$enddate=$stat_time."-12-31";
		}
		$time['startdate']=$startdate;
		$time['enddate']=$enddate;
		
		return 	$time;
	}	
	function test4()
	{
		import("@.Action.System.ModelAction");
		$dataDeal=new ListAction();
		$model=$dataDeal->getGoodModel('2');
		echo $sql=$model['sql']; 
		//exit;
		//$sql = str_replace("{begin_date}",'2010-01-01', $sql);
		//$sql = str_replace("{end_date}",'2010-12-31', $sql);
		//System::log($sql);
		$m=new Model();
		$rows=$m->query($sql);

		echo $data_arr=serialize($rows);
		//System::log('data_arr:'.$data_arr);	
	}
}
?>
















