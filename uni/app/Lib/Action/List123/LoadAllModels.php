<?php
/*
加载所有数据模型。该类通常也用不到，因为大部份情况下，模型是单个加载，不需要全部加载。今后可能为了提高性能，可以全部记载到内存中
作者：陈坤极
*/
class MCSSModel{
	public static function getAll()
	{
		//if (C("mcss_UseModel"))
			return MCSSModel::getAllModels();
		//else
		//	return MCSSModel::getAllInOne();
	}

	public static function getAllModels()
	{
		$root=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.APP_NAME."/Lib/Model/Models/";
		$files = scandir($root);
		$ModelList=array();
		$keys=array();
		$n=0;
		foreach( $files as $file ) {
 			if($file != '.' && $file != '..' && is_file($root .$file)) {
				try
				{
					if (strpos($file,'.php')>-1)
					{
						$i=strripos($file,'.');
						$modelid=substr($file,0,$i);
						array_push($keys,$modelid);
						$modelid=include($root . $file);
						//if (MCSSModel::isUTF8($modelid))
						{
							array_push($ModelList,$modelid);
						}
					}
				}
				catch (Exception $e) {
					Log::write($e->getMessage());
					//print_r($e->getMessage());
				}
 			}
		}
 	
 		$ms=array_combine($keys,$ModelList);
 		return $ms;
	}
	public static function getAllInOne()
	{
		//每添加一行include_once的文件，就要再array_merge里面条件include_once进来的文件中的模型名称
		include("SystemModels.php");
		include("CRMModels.php");
		include("OAModels.php");
		include("yogaModels.php");
		include("tdModels.php");
		include("TD_Member_Models.php");
		include("TD_Admin_Models.php");
		include("TD_Jiaoyi_Models.php");
		include("TD_BankAcount_Models.php");

		
		$ModelList= array_merge(
		$SystemModels, 
		$CRMModels, 
		$OAModels,
		$yogaModels,
		$tdModels,
		$TD_Member_Models,
		$TD_Admin_Models,
		$TD_Jiaoyi_Models,
		$TD_BankAcount_Models
		);
		
		return $ModelList;
	}
	static function isUTF8($str)
	{
		if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
}

?>