<?php
//OpenFunctionAction
class OpenurlAction extends Action{
	public function _initialize(){
	        header("Content-Type:text/html; charset=utf-8");
	}

	public function openurl() {
		$str = $_REQUEST[openurl];
		$str_right=System::get_loginuser_right();
		$index=strpos($str_right,$str."|");
		if (index==-1)
		{
			echo '权限不足!';exit; 
		}
		else {
			$obj = M('sys_function');
			$m=new Model();
			$rows=$m->query("select no,name,type,modelid,url,groupno from sys_function where no='$str'");
			if (count($rows)>0)
			{
				$row=$rows[0];
				$url=System::getFuncRunningUrl($row);
				
				if ($url)
					$this->redirect($url, array(),0,'页面跳转中~');
				else if (!$rows[0]['groupno'])
				{
					echo "请选择下级菜单。";
				}
				else
					echo "该菜单无对应页面。";
				
			}
			
		}
	}
}
?>