<?php 
	require_once 'PHPExcel.php';
	require_once 'PHPExcel/IOFactory.php';
	require_once 'PHPExcel/Reader/Excel5.php';
	require_once 'PHPExcel/Reader/Excel2007.php';
	/**
	 *导入excel的方法
	 *作者：独孤晓凯
	 *时间：2012-06-09 
     *此页面编码为UTF-8
     */  
	 //生成读取类
	 function createReader($filename){
		$filetype = explode('.',$filename);
		if($filetype[1]=='xls')
			$objReader = PHPExcel_IOFactory::createReader('Excel5');//加载xls读取类
		else
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');//加载xlsx读取类
		return $objReader;
	 }
	 //导入excel数据方法
	 function impExcel($filename,$sheetname){
		$objReader = createReader($filename);
		try{
			 $objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/uploadfile/'.$filename); //读取文件
		}catch(Exception $e){
			 $objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].'/Public/uploadfile/'.$filename); //读取文件
		}
		if($sheetname)//根据工作表名称读取工作表数据
			$sheet=$objPHPExcel->getSheetByName($sheetname);
        else
			$sheet = $objPHPExcel->getSheet(0); //获得当前活动页
        $highestRow = $sheet->getHighestRow();           //取得总行数 
		$d=2;//此方法仅为为个人方法，按需求可进行修改
		for($i=0;$i<$highestRow-1;$i++){
			for($k=0;$k<100;$k++){
				if($sheet->getCellByColumnAndRow($k,1)->getValue()==null){
					break;
				}else{
					 $cell =$sheet->getCellByColumnAndRow($k,$d);  
					 $value=$cell->getValue();  
					if($cell->getDataType()==PHPExcel_Cell_DataType::TYPE_NUMERIC){
						$cellstyleformat=$cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat();  
					    $formatcode=$cellstyleformat->getFormatCode();  
					    if(preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i',$formatcode)){  
							 $value=gmdate("Y-m-d",PHPExcel_Shared_Date::ExcelToPHP($value));  
					    }else{  
							 $value=PHPExcel_Style_NumberFormat::toFormattedString($value,$formatcode);  
					    }  
						$data[$i][$sheet->getCellByColumnAndRow($k,1)->getValue()]=$value;
					}else{
						$data[$i][$sheet->getCellByColumnAndRow($k,1)->getValue()]=$cell->getValue();
					}
				}
			}
			$d++;
		}
		return $data;
	 }
	 //获取excel的工作表名称
	 function getExcelSheetNames($filename){
		$objReader = createReader($filename);
       	try{
			 $objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/uploadfile/'.$filename); //读取文件
		}catch(Exception $e){
			 $objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].'/Public/uploadfile/'.$filename); //读取文件
		}
		$names = $objPHPExcel->getSheetNames();
		return $names;
	 }
	 //获取excel的前6行数据
	 function getsixdata($filename,$sheetname){
		$objReader = createReader($filename);
       	try{
			 $objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/uploadfile/'.$filename); //读取文件
		}catch(Exception $e){
			 $objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].'/Public/uploadfile/'.$filename); //读取文件
		}
		if($sheetname)//根据工作表名称读取工作表数据
			$sheet=$objPHPExcel->getSheetByName($sheetname);
        else
			$sheet = $objPHPExcel->getSheet(0); //获得当前活动页
 
		$d=2;//此方法仅为为个人方法，按需求可进行修改
		for($i=0;$i<6;$i++){
			for($k=0;$k<100;$k++){
				if($sheet->getCellByColumnAndRow($k,1)->getValue()==null){
					break;
				}else{
					 $cell =$sheet->getCellByColumnAndRow($k,$d);  
					 $value=$cell->getValue();  
					if($cell->getDataType()==PHPExcel_Cell_DataType::TYPE_NUMERIC){
						$cellstyleformat=$cell->getParent()->getStyle($cell->getCoordinate())->getNumberFormat();  
					    $formatcode=$cellstyleformat->getFormatCode();  
					    if(preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i',$formatcode)){  
							 $value=gmdate("Y-m-d",PHPExcel_Shared_Date::ExcelToPHP($value));  
					    }else{  
							 $value=PHPExcel_Style_NumberFormat::toFormattedString($value,$formatcode);  
					    }  
						$data[$i][$sheet->getCellByColumnAndRow($k,1)->getValue()]=$value;
					}else{
						$data[$i][$sheet->getCellByColumnAndRow($k,1)->getValue()]=$cell->getValue();
					}
				}
			}
			$d++;
		}
		return $data;
	 }
?>  