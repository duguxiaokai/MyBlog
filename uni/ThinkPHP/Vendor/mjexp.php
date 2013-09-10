<?php 
	
	require_once "PHPExcel.php";  
	require_once 'PHPExcel/IOFactory.php';  
	require_once 'PHPExcel/Writer/Excel5.php'; 
	require_once 'PHPExcel/Writer/Excel2007.php'; 
	require_once 'PHPExcel/Writer/CSV.php'; 
 
	function exportExcel($data="",$title="",$path="",$status="",$filetype="xls")
	{
 
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
		for($i=0;$i<count($data);$i++)
		{
			foreach($data[$i] as $key=>$value)
			{
				$k=0;
				foreach($title as $line=>$titlevalue)
				{
					if($line==$key)
					{
						if($status == 'monitorurl')
						{
							if($key == 'valid')
							{
								if($value == 0)
									$objActSheet->setCellValue($array[$k].$d,'禁用');
								else
									$objActSheet->setCellValue($array[$k].$d,'启用');
							}
							else if($key == 'type')
							{
								if($value == 'N')
									$objActSheet->setCellValue($array[$k].$d,'新闻');
								else
									$objActSheet->setCellValue($array[$k].$d,'微博');
							}
							else{
								$objActSheet->setCellValue($array[$k].$d,$value);
							}
							
						}else if($status == 'monitorproject')
						{
							if($key == 'sentiment')
							{
								if($value == 0)
									$objActSheet->setCellValue($array[$k].$d,'中性');
								else if($value == 1)
								{
									$objActSheet->setCellValue($array[$k].$d,'正面');
								}else
									$objActSheet->setCellValue($array[$k].$d,'负面');
							}else{

								$objActSheet->setCellValue($array[$k].$d,$value);
							}
							
						}else{
							$objActSheet->setCellValue($array[$k].$d,$value);
						}
					}
					$k++;
				}
			}
			$d++;
		} 
	  
	$rand=rand(0,100000000);
	$outputFileName = date('Y_m_d').'_'.$rand.'.'.$filetype;
	$objWriter->save($path.'/Public/temp/'.$outputFileName); 	
	System::log($path.'/Public/temp/'.$outputFileName);
	return trim($outputFileName);
 }
?>  