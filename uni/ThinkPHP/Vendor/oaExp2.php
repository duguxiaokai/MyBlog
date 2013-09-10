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
	function exportExcel($data="",$title="",$style="",$display="",$seloption="",$begindate,$enddate,$path="",$filetype="xls"){
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
		expGanTeTu($objExcel,$array,$title,$data,$style,$display,$seloption,$begindate,$enddate);
		$rand=rand(0,100000000);
		$outputFileName = date('Y_m_d').'_'.$rand.'.'.$filetype;
        $objWriter->save($path.'/Public/temp/'.$outputFileName); 	
		return trim($outputFileName);
}
function expGanTeTu($objExcel,$array,$title,$data,$style,$display,$seloption,$begindate,$enddate){
	//*************************************  
		//设置当前的sheet索引，用于后续的内容操作。  
		//一般只有在使用多个sheet的时候才需要显示调用。  
		//缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0  
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();  
		  
		//设置当前活动sheet的名称  
		$objActSheet->setTitle('任务甘特图');  
		  
		$i = 1;
		//先将数据库中的标题循环输出,置于Excel的第一列
		foreach($title as $value){
			//填充背景色
			$objFill = $objActSheet->getStyle($array[$i-1].'1')->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
			$objFill->getStartColor()->setARGB($style['header_bgd_color']);	
			//Set column borders 设置列边框 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			//Set border colors 设置边框颜色 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getLeft()->getColor()->setARGB($style['header_border_color']); 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getTop()->getColor()->setARGB($style['header_border_color']); 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getRight()->getColor()->setARGB($style['header_border_color']); 
			$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getBottom()->getColor()->setARGB($style['header_border_color']); 
			//设置垂直居中对齐
			$objActSheet->getStyle($array[$i-1].'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle($array[$i-1].'1')->getFont()->setBold(true);//加粗字体
			//$objActSheet->getColumnDimension($array[$i-1].'1')->setAutoSize(true); //设置宽度
			$objActSheet->getColumnDimension($array[$i-1])->setWidth(10);//设置每列日期宽度为10
			if(!$display[$i-1])
				$objActSheet->getColumnDimension($array[$i-1])->setVisible(false);//隐藏单元格
			$objActSheet->setCellValue($array[$i-1].'1', $value);  //填充单元格内容
			$i++;
		} 
		//填充中间头部格子背景色
		$objFill = $objActSheet->getStyle($array[$i-1].'1')->getFill();
		$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
		$objFill->getStartColor()->setARGB($style['header_bgd_color']);	
		//Set column borders 设置列边框 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
		//Set border colors 设置边框颜色 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getLeft()->getColor()->setARGB($style['header_border_color']); 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getTop()->getColor()->setARGB($style['header_border_color']); 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getRight()->getColor()->setARGB($style['header_border_color']); 
		$objActSheet->getStyle($array[$i-1].'1')->getBorders()->getBottom()->getColor()->setARGB($style['header_border_color']); 
		$datecell = $i;//记录日期开始的键值
		$dateendcell;//记录日期结束的键值
		$dt_start = strtotime($begindate);
		$dt_end = strtotime($enddate);
		$week = array(0=>'一',1=>'二',2=>'三',3=>'四',4=>'五',5=>'六',6=>'日');
		while ($dt_start<=$dt_end){//循环甘特图日期
			$w = date('w',$dt_start);
			if($dt_start==$dt_end)
				$dateendcell = $i;
			$w = date('w',$dt_start);
			$objActSheet->getStyle($array[$i].'1')->getAlignment()->setWrapText(true);
			$objActSheet->getStyle($array[$i].'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//设置垂直居中对齐
			$objActSheet->getStyle($array[$i].'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置水平居中对齐
			if($dt_start == strtotime($begindate) || $dt_start == strtotime($enddate)){
				$objActSheet->getColumnDimension($array[$i])->setWidth(12);//设置每列日期宽度为12
				$objActSheet->setCellValue($array[$i].'1',date('Y-m-d',$dt_start)."\n".$week[$w]);
			}else{
				$day = date("d",$dt_start);
				if($day == 1){
					$objActSheet->getColumnDimension($array[$i])->setWidth(12);//设置每列日期宽度为12
					$objActSheet->setCellValue($array[$i].'1',date('Y-m-d',$dt_start)."\n".$week[$w]);
				}else{
					//$objActSheet->getColumnDimension($array[$i-1])->setAutoSize(true); 
					$objActSheet->getColumnDimension($array[$i])->setWidth(3);//设置每列日期宽度为3
					$objActSheet->setCellValue($array[$i].'1',$day."\n".$week[$w]);
				}
			}
			//星期六日的单元格填充为灰色
			$objFill = $objActSheet->getStyle($array[$i].'1')->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			if($w == 5 || $w == 6)
				$objFill->getStartColor()->setARGB('FFEEEEEE');
			else
				$objFill->getStartColor()->setARGB($style['header_bgd_color']);	//填充背景色
			//Set column borders 设置列边框 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
			//Set border colors 设置边框颜色 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getLeft()->getColor()->setARGB('000000'); 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getTop()->getColor()->setARGB('000000'); 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getRight()->getColor()->setARGB('000000'); 
			$objActSheet->getStyle($array[$i].'1')->getBorders()->getBottom()->getColor()->setARGB('000000'); 
			$dt_start = strtotime('+1 day',$dt_start);
			$i++;
		}
		//将每一行数据循环输出,置于Excel中
		$d=2;
		$start = 0;
		//任务分组合并
		foreach($title as $value){//第一个显示的列的键值
			if($display[$start])
				break;
			$start++;
		}
		$end = count($title)-1;
		for($end;$end >= 0;$end--){//最后一个显示的列的键值
			if($display[$end])
				break;
		}
		for($i=0;$i<count($data);$i++){
			if($data[$i]['tasktype']!=$lasttype){//工作类别分组
				$objActSheet->getStyle($array[$start].$d)->getFont()->setBold(true);//设置字体样式
				$objActSheet->getStyle($array[$start].$d)->getFont()->setSize(16); //设置字体大小
				$cell = $array[$start].$d.":".$array[$dateendcell].$d;
				$objActSheet->mergeCells($cell);//合并单元格
				$objActSheet->setCellValue($array[$start].$d,$data[$i]['tasktype']);
				$lasttype = $data[$i]['tasktype'];
				$d++;
				$i--;
				continue;
			}
			//填充甘特图中间格
			$objFill = $objActSheet->getStyle($array[$datecell-1].$d)->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
			if($data[$i]['finishper']==0)//根据完成率填充不同颜色
				$objFill->getStartColor()->setARGB('00CCCCCC');
			else if($data[$i]['finishper']==1)
				$objFill->getStartColor()->setARGB('0033FF00');
			else
				$objFill->getStartColor()->setARGB('003333CC');
			//填充甘特图图表
			$dt_start = strtotime($begindate);
			$dt_end = strtotime($enddate);
			$gantedate = $datecell;
			$fillword = 1;
			while ($dt_start<=$dt_end){
				$w = date('w',$dt_start);
				if($w == 5 || $w == 6){//星期六日的单元格填充为灰色
					$objFill = $objActSheet->getStyle($array[$gantedate].$d)->getFill();
					$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
					$objFill->getStartColor()->setARGB('FFEEEEEE');
				}
				if($dt_start>=strtotime($data[$i]['begindate']) && $dt_start<=strtotime($data[$i]['enddate'])){
					$objFill = $objActSheet->getStyle($array[$gantedate].$d)->getFill();
					$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
					if($data[$i]['finishper']==0)//根据完成率填充不同颜色
						$objFill->getStartColor()->setARGB('00CCCCCC');
					else if($data[$i]['finishper']==1)
						$objFill->getStartColor()->setARGB('0033FF00');
					else
						$objFill->getStartColor()->setARGB('003333CC');
					if($fillword==1){
						$fillcontent = '';
						if(stristr($seloption,'worktype'))
							$fillcontent.='['.$data[$i]['tasktype'].']';
						if(stristr($seloption,'taskname'))
							$fillcontent.=' '.$data[$i]['name'];
						if(stristr($seloption,'executer'))
							$fillcontent.=' '.$data[$i]['executer'];
						$objActSheet->setCellValue($array[$gantedate].$d,$fillcontent);
						$fillword = 2;
					}
				}
				$dt_start = strtotime('+1 day',$dt_start);
				$gantedate++;
			}
			//填充每列内容
			foreach($data[$i] as $key=>$value)
			{
				$k=0;
				foreach($title as $line=>$titlevalue)
				{
					if($line==$key)
					{
						$objActSheet->getStyle($array[$k].$d)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
						$objActSheet->setCellValue($array[$k].$d,$value);
					}
					$k++;
				 }
			}
			$d++;
		}	
		//最后一行处理
		$cell = $array[$start].$d.":".$array[$dateendcell].$d;
		$objActSheet->mergeCells($cell);//合并单元格
		$objActSheet->setCellValue($array[$start].$d,"(完)");
}  
?>  