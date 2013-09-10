<?php
class OtherAction extends CommonAction{
	public function index() {
		$this->assign("mcss_theme",C("mcss_theme"));
		$this->display();
	}	
	
}

?>