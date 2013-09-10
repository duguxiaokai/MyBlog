<?php
class FileAction extends CommonAction{
	public function file() {
		$this->assign("theme",C("mcss_theme"));
		$this->display();
	}	
	public function dir() {
		$this->assign("theme",C("mcss_theme"));
		$this->display();
	}
}

?>