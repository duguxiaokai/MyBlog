<?php 
/*
模型操作类
作者：陈坤极
*/ 
class MCSSModel extends Model
{
	//得到某个模型，没有解析
	public static function getModel($modelid) 
	{
		if (C("mcss_loadmodel_everytime")) //每次都重新加载模型文件
    	{
    		return self::loadModel($modelid);
    	}
    	else //加载过的模型放到session中，比直接读取文件提高速度20倍
    	{
    		$ModelList=$_SESSION['MCSSModelList'];
    		if ($ModelList[$modelid])
    		{
	    		$m=$ModelList[$modelid];
    		}
    		else
    		{
    			$m=self::loadModel($modelid);
    			$ModelList[$modelid]=$m;
    			$_SESSION['MCSSModelList']=$ModelList;
    		}
        }		
		return $m; 
	}
	static function loadModel($modelid)
	{
			$approot=__ROOT__;
			$approot=substr($approot,1);
			$doc_root=$_SERVER['DOCUMENT_ROOT'];
			if (substr($doc_root,strlen($doc_root)-1)=="/")
				$doc_root=substr($doc_root,0,strlen($doc_root)-1);
			$modelroot=$doc_root.'/'.$approot.'/'.APP_NAME."/Lib/Model/";
			

			//为了兼容旧的路径的情况
			$usenewway=true;//使用原来的方式
			if (!$usenewway){
				$root=$modelroot."Models/";
				$m=include($root . $modelid.'.php');
			}
			else
			{
				$arr=explode("_",$modelid);
				$orgcode="default";
				if (count($arr)>1)
				{
					$orgcode=$arr[0];
				}
			
				$file=$modelroot . $orgcode."/Models/".$modelid.'.php';
				if (file_exists($file)){
					$m=include($file); 
				}
				else{
					//为了兼容旧的路径的情况
					$root=$modelroot."/Models/";
					$m=include($root . $modelid.'.php');
				}
			}
			return $m;
	}

}
 
?>