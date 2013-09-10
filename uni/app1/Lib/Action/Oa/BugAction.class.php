<?php
class BugAction extends Action{
    function index()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}
	
	public function addBug()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}
	public function savebug()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}
	public function addreply()
	{
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}
	//根据ID搜索出记录信息
	public function selectSYS_EDITUSER(){
		$id = $_REQUEST['id'];
		$m = new Model();
		$sql = 'select status,assign_to,begindate,SYS_EDITUSER from oa_bug where id='.$id;
		$result = $m->query($sql);
		echo json_encode($result);
	}
	public function getdealline()
	{
		$pid = $_REQUEST['pid'];
		$sql = "select dealline from oa_bug where id='".$pid."'";
		$m=new Model();
		$result = $m->query($sql);
		echo $result[0]['dealline'];
	}
	public function editdealline()
	{
		$pid = $_REQUEST['pid'];
		$sql = "update oa_bug set dealline=curdate() where id='".$pid."'";
		$m = new Model();
		$m->query($sql);
	}
}

?>