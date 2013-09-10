<?php
class InfoAction extends CommonAction{
	public function index() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	public function tree() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	public function updateMessageStatue(){
		$id = Organization::MyStaffID();
		$m = new Model();
		$sql = "update sys_message set statue = 1 where reciever_id = $id and statue = 0";
		$m->execute($sql);
	}
}

?>