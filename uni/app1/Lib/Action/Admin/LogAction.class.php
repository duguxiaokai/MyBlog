<?php
header("content-type:text/html; charset=UTF-8"); 
import('@.ORG.System');
class LogAction extends Action {
    public function index(){ 
		if (C("mcss_log_anyonecansee") || system::loginuser()=='admin')
		{
			$d=date("y_m_d");
			$project = substr(__ROOT__,1,strlen(__ROOT__));
			$root =$_SERVER['DOCUMENT_ROOT'].'/'.$project.'/'.APP_NAME."/Runtime/Logs/".$d.".log";
			if(file_exists($root))
			{
				echo "今日日志 <a href='Log/clearFile'>清除</a><br />";
				$file=fopen($root,"r");
				while(!feof($file))
				{
					echo fgets($file)."<br />";
				}
				fclose($file);
				echo "<a href='Log/clearFile'>清除</a><br />";

				
			}else
			{
				echo "日志不存在!";
			}
			
		}	
		else{
			echo "需要把配置文件的mcss_log_anyonecansee属性设置为true才能查看日志";
		}
	}
	
	public function clearFile()
	{	
		$d=date("y_m_d");
		$project = substr(__ROOT__,1,strlen(__ROOT__));
		$root =$_SERVER['DOCUMENT_ROOT'].'/'.$project.'/'.APP_NAME."/Runtime/Logs/".$d.".log";
		$result = unlink($root);
		if($result)
		{
			$this->success("删除成功");
		}
	}
}
?>