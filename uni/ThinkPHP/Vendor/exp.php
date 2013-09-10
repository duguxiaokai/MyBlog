<?php 
	
	require_once "PHPExcel.php";  
	require_once 'PHPExcel/IOFactory.php';  
	require_once 'PHPExcel/Writer/Excel5.php'; 
	require_once 'PHPExcel/Writer/Excel2007.php'; 
	require_once 'PHPExcel/Writer/CSV.php'; 
	/**
	 *导出excel的方法
	 *作者：独孤晓凯
	 *时间：2012-06-05 
     * 以下是使用示例，对于以 //// 开头的行是不同的可选方式，请根据实际需要 
     * 打开对应行的注释。 
     * 如果使用 Excel5 ，输出的内容应该是GBK编码。 
     */  
	function exportExcel($data="",$title="",$path="",$filetype="xls"){
		//设置PHPExcel类库的include path 
		//set_include_path('.'. PATH_SEPARATOR .'D:\workspace\biznaligy_eh\dev_src\includes\PHPExcel' . PATH_SEPARATOR . get_include_path()); 
	
		// or  
		////require_once 'PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式  
		  
		// 创建一个处理对象实例  
		$objExcel = new PHPExcel(); 
		// 创建文件格式写入对象实例, uncomment  
		if($filetype=='xls'){
			$objWriter = new PHPExcel_Writer_Excel5($objExcel);    // 用于其他版本格式 
		}else if($filetype=='xlsx'){
			$objWriter = new PHPExcel_Writer_Excel2007($objExcel);
		}else if($filetype=='csv'){
			$objWriter = new PHPExcel_Writer_CSV($objExcel);
			$objWriter->setUseBOM(true); 
		}
		// or  
		////$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // 用于 2007 格式  
		//$objWriter->setOffice2003Compatibility(true);  
		  
		//************************************* 
		//设置文档基本属性  
		$objProps = $objExcel->getProperties();  
		$objProps->setCreator('聚亿企科技公司');  
		$objProps->setLastModifiedBy('聚亿企科技公司');  
		$objProps->setTitle('导出数据文档');  
		$objProps->setSubject('导出');  
		$objProps->setDescription('技术支持phpExcel');  
		$objProps->setKeywords("数据导出Excel");  
		$objProps->setCategory($filetype);  
		//*************************************  
		//设置当前的sheet索引，用于后续的内容操作。  
		//一般只有在使用多个sheet的时候才需要显示调用。  
		//缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0  
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();  
		  
		//设置当前活动sheet的名称  
		$objActSheet->setTitle('当前页');  
		  
		//*************************************  
		//设置单元格内容  
		//  
		//由PHPExcel根据传入内容自动判断单元格内容类型 
		
		//设置Excel表格的列
			$array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T',
					   'U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK',
					   'AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA',
					   'BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ',
					   'BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG',
					   'CH','CI','CG','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW',
					   'CX','CY','CZ','DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM',
					   'DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ','EA','EB','EC',
					   'ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES',
					   'ET','EU','EV','EW','EX','EY','EZ','FA','FB','FC','FD','FE','FF','FG','FH','FI',
					   'FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY',
					   'FZ','GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO',
					   'GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ','HA','HB','HC','HD','HE',
					   'HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU',
					   'HV','HW','HX','HY','HZ','IA','IB','IC','ID','IE','IF','IG','IH','II','IJ','IK',
					   'IL','IM','IN','IO','IP','IQ','IR','IS','IT','IU','IV','IW','IX','IY','IZ','JA',
					   'JB','JC','JD','JE','JF','JG','JH','JI','JJ','JK','JL','JM','JN','JO','JP','JQ',
					   'JR','JS','JT','JU','JV','JW','JX','JY','JZ','KA','KB','KC','KD','KE','KF','KG'
					   ,'KH','KI','KJ','KK','KL','KM','KN','KO','KP','KQ','KR','KS','KT','KU','KV','KW',
					   'KX','KY','KZ','LA','LB','LC','LD','LE','LF','LG','LH','LI','LJ','LK','LL','LM',
					   'LN','LO','LP','LQ','LR','LS','LT','LU','LV','LW','LX','LY','LZ','MA','MB','MC',
					   'MD','ME','MF','MG','MH','MI','MJ','MK','ML','MM','MN','MO','MP','MQ','MR','MS',
					   'MT','MU','MV','MW','MX','MY','MZ','NA','NB','NC','ND','NE','NF','NG','NH','NI',
					   'NJ','NK','NL','NM','NN','NO','NP','NQ','NR','NS','NT','NU','NV','NW','NX','NY',
					   'NZ','OA','OB','OC','OD','OE','OF','OG','OH','OI','OJ','OK','OL','OM','ON','OO',
					   'OP','OQ','OR','OS','OT','OU','OV','OW','OX','OY','OZ'); 
		$i = 1;
		//先将数据库中的标题循环输出,置于Excel的第一列
		foreach($title as $value){
			//$objActSheet->setCellValue($array[$i-1].'1', iconv('gbk', 'utf-8', $key));
			$objActSheet->setCellValue($array[$i-1].'1', $value);
			$i++;
		} 
		//将每一行数据循环输出,置于Excel中
		$d=2;
		$arrpath = explode('/',$path);
		if(!$arrpath[3])
			$arrpath[3]=$arrpath[4];
		for($i=0;$i<count($data);$i++){
			foreach($data[$i] as $key=>$value)
			{
				$k=0;
				foreach($title as $line=>$titlevalue)
				{
					if($line==$key)
					{
						if($key=='logo'){
							$objDrawing = new PHPExcel_Worksheet_Drawing(); 
							$objDrawing->setName('Photo'); 
							$objDrawing->setDescription('Image inserted by pyxjm'); 
							$imgpath = $arrpath[0].'\\'.$arrpath[1].'\\'.$arrpath[2].'\\'.$arrpath[3].'\Public\projects\mj\images\logo\\'.$value;
							if(file_exists(iconv("utf-8","gb2312",$imgpath)))
								$objDrawing->setPath($imgpath);	
							else
								$objDrawing->setPath($arrpath[0].'\\'.$arrpath[1].'\\'.$arrpath[2].'\\'.$arrpath[3].'\Public\projects\mj\images\nologo1.png');	
							$objDrawing->setWidth('25pt');
							$objDrawing->setHeight('20pt');
							$objDrawing->setOffsetX(2);
							$objDrawing->getShadow()->setVisible(true); 
							$objDrawing->setCoordinates($array[$k].$d);
							//$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
							$objDrawing->setWorksheet($objExcel->getActiveSheet()); 
						}else{
							$objActSheet->setCellValue($array[$k].$d,$value);
						}
					}
					$k++;
				}
			}
			$d++;
		}		  
		//显式指定内容类型  
	     // $objActSheet->setCellValueExplicit('A5', '847475847857487584',   
		 //                                  PHPExcel_Cell_DataType::TYPE_STRING);  
		  
		//合并单元格  
		//$objActSheet->mergeCells('B1:C22');  
		  
		//分离单元格  
		//$objActSheet->unmergeCells('B1:C22');  
		  
		//*************************************  
		//设置单元格样式  
		//如果导出文件,此方法设置样式会降低导出的速度,因此不建议大批量导出的时候使用本方法设置样式 
		//设置宽度  
		
		// for($k=0;$k<count($data[0]);$k++){
			// $objActSheet->getColumnDimension($value)->setAutoSize(true);//将所有格设置为自动宽度
			// //$objActSheet->getColumnDimension($array[$k])->setWidth(20);//指定单元格宽度
			// for($i=1;$i<=count($data)+1;$i++){
				// if($i%2!=0 || $i==1){
					// //新版本的调用样式方法
					// $sharedStyle = new PHPExcel_Style();
					// $sharedStyle->applyFromArray(
								// array('fill'=>array(
													// 'type'=> PHPExcel_Style_Fill::FILL_SOLID,
													// 'color'=> array('argb' => 'FFEEEEEE')
												// ),
									  // 'borders'=>array(
													// //'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
													// //'right'=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
												// )
									// ));
					// $objExcel->getActiveSheet()->setSharedStyle($sharedStyle,$array[$k].$i);
				// }
			// }
		// }
		// echo 123;exit;  
		////$objStyleA5 = $objActSheet->getStyle('A5');// 旧版本设置样式示例 获取对象
		  
		//设置单元格内容的数字格式。  
		//  
		//如果使用了 PHPExcel_Writer_Excel5 来生成内容的话，  
		//这里需要注意，在 PHPExcel_Style_NumberFormat 类的 const 变量定义的  
		//各种自定义格式化方式中，其它类型都可以正常使用，但当setFormatCode  
		//为 FORMAT_NUMBER 的时候，实际出来的效果被没有把格式设置为"0"。需要  
		//修改 PHPExcel_Writer_Excel5_Format 类源代码中的 getXf($style) 方法，  
		//在 if ($this->_BIFF_version == 0x0500) { （第363行附近）前面增加一  
		//行代码:   
		//if($ifmt === '0') $ifmt = 1;  
		//  
		//设置格式为PHPExcel_Style_NumberFormat::FORMAT_NUMBER，避免某些大数字  
		//被使用科学记数方式显示，配合下面的 setAutoSize 方法可以让每一行的内容  
		//都按原始内容全部显示出来。  
		////$objStyleA5  
			////->getNumberFormat()  
			////->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);  
		  
		//设置字体  
		////$objFontA5 = $objStyleA5->getFont();  
		////$objFontA5->setName('Courier New');  
		////$objFontA5->setSize(10);  
		////$objFontA5->setBold(true);  
		////$objFontA5->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  
		////$objFontA5->getColor()->setARGB('FF999999');  
		  
		//设置对齐方式  
		////$objAlignA5 = $objStyleA5->getAlignment();  
		////$objAlignA5->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);  
		////$objAlignA5->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
		  
		//设置边框  
		////$objBorderA5 = $objStyleA5->getBorders();  
		////$objBorderA5->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		////$objBorderA5->getTop()->getColor()->setARGB('FFFF0000'); // color  
		////$objBorderA5->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		////$objBorderA5->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		////$objBorderA5->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		  
		//设置填充颜色  
		////$objFillA5 = $objStyleA5->getFill();  
		////$objFillA5->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
		////$objFillA5->getStartColor()->setARGB('FFEEEEEE');  
		  
		//从指定的单元格复制样式信息.  
		////$objActSheet->duplicateStyle($objStyleA5, 'B1:C22');   
		//添加一个新的worksheet  
		//$objExcel->createSheet();  
		//$objExcel->getSheet(1)->setTitle('测试2');  
		  
		//保护单元格  
		//$objExcel->getSheet(1)->getProtection()->setSheet(true);  
		//$objExcel->getSheet(1)->protectCells('A1:C22', 'PHPExcel');  
		  
		  
		//*************************************  
		//输出内容  
		//  
		//$outputFileName = date('Y_m_d H:i:s').'.xls';
		//$outputFileName = mb_convert_encoding($outputFileName,"gb2312", "UTF-8");
		//到文件  
		////$objWriter->save($outputFileName);  
		$rand=rand(0,100000000);
		$outputFileName = date('Y_m_d').'_'.$rand.'.'.$filetype;
        $objWriter->save($path.'/Public/temp/'.$outputFileName); 	
		return trim($outputFileName);
		//or  
		//到浏览器
		//header("Content-Type: application/force-download");  
		////header("Content-Type: application/octet-stream");  
		////header("Content-Type: application/download");  
		////header('Content-Disposition:attachment;filename="'.$outputFileName.'"');  
		////header("Content-Transfer-Encoding: binary");  
		////header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		////header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
		////header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
		////header("Pragma: no-cache");
		////$objWriter->save('php://output');  
}  
?>  