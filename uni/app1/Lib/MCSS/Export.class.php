<?php
/*
数据导入到出类，主要用于配合MCSSTable的导出导入功能
*/
header("content-type:text/html; charset=UTF-8"); 
class Export extends Action
{
	static function exportToCSV($params)
	{
		//陈坤极
		$sql=$params['sql'];
		$sql=system::parseExpresion($sql);
		$fields=$params['fields'];
		$fieldnames=$params['fieldnames'];
 		$rows=$params['rows'];
		$str="\xEF\xBB\xBF";
		$fieldArr=explode(',',$fields);
		if (!isset($rows))
		{
			$Model = new Model();
			$rows = $Model->query($sql);
		}
				
		$fieldnameArr=$fieldArr;
		if (isset($fieldnames))
		{
			$fieldnameArr=explode(',',$fieldnames);
		}
		
		$line1='';
		foreach($fieldnameArr as $field){
			if ($line1!='') $line1.=",";
			$line1.='"'.$field.'"';
		}
		$str.=$line1."\r\n";
		
		for($i=0;$i<count($rows);$i++)
		{
			$line='';
			foreach($fieldArr as $v)
			{
				if ($line!='') $line.=",";
				
				$line.='"'.$rows[$i][$v].'"';
			}
			$str.=$line."\r\n";
		}
		$filename=date('y-m-d-h-m-s').'.csv';
		 
		$filename=$params['filename'].$filename;
		$savepath='./Public/temp/'.$filename;
		$file = fopen($savepath,"w+");
		fwrite($file,$str);
		fclose($file);

		//echo $savepath='/Public/temp/'.$filename;
		echo $filename;
	}
	static function exportToXls($params){
		Vendor("PHPExcel.expExcel");//导入phpexcel
	}

}
?>