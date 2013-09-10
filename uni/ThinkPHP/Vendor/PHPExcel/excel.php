<?  
	/**
	 *����excel�ķ���
	 *���ߣ���������
	 *ʱ�䣺2012-06-05 
     * ������ʹ��ʾ���������� //// ��ͷ�����ǲ�ͬ�Ŀ�ѡ��ʽ�������ʵ����Ҫ 
     * �򿪶�Ӧ�е�ע�͡� 
     * ���ʹ�� Excel5 �����������Ӧ����GBK���롣 
     */  
	function exportExcel($data){

		//����PHPExcel����include path 
		set_include_path('.'. PATH_SEPARATOR .'D:\workspace\biznaligy_eh\dev_src\includes\PHPExcel' . PATH_SEPARATOR . get_include_path()); 
		
		require_once 'PHPExcel.php';  
		  
		// uncomment  
		require_once 'PHPExcel/Writer/Excel5.php';    // ���������Ͱ汾xls  
		// or  
		////require_once 'PHPExcel/Writer/Excel2007.php'; // ���� excel-2007 ��ʽ  
		  
		// ����һ���������ʵ��  
		$objExcel = new PHPExcel();  
		  
		// �����ļ���ʽд�����ʵ��, uncomment  
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);    // ���������汾��ʽ  
		// or  
		////$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // ���� 2007 ��ʽ  
		//$objWriter->setOffice2003Compatibility(true);  
		  
		//************************************* 
		//�����ĵ���������  
		$objProps = $objExcel->getProperties();  
		$objProps->setCreator('Creator');  
		$objProps->setLastModifiedBy('Creator');  
		$objProps->setTitle('DataBase');  
		$objProps->setSubject('DataBase');  
		$objProps->setDescription('from DataBase');  
		$objProps->setKeywords("office excel PHPExcel");  
		$objProps->setCategory("Test");  
		  
		//*************************************  
		//���õ�ǰ��sheet���������ں��������ݲ�����  
		//һ��ֻ����ʹ�ö��sheet��ʱ�����Ҫ��ʾ���á�  
		//ȱʡ����£�PHPExcel���Զ�������һ��sheet������SheetIndex=0  
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();  
		  
		//���õ�ǰ�sheet������  
		$objActSheet->setTitle('Sheet');  
		  
		//*************************************  
		//���õ�Ԫ������  
		//  
		//��PHPExcel���ݴ��������Զ��жϵ�Ԫ���������� 
		
		//����Excel������
		$array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T',
					   'U','V','W','X','Y','Z',);
		//���ݿ����������,��Ϊ��������
		//$data = array(
		//	array('ID'=>1,'�û���'=>'admin','����'=>'123'),
		//	array('ID'=>2,'�û���'=>'��������','����'=>'123'),
		//	array('ID'=>3,'�û���'=>'������ʿ','����'=>'123')
		//	); 
		$i = 1;
		//�Ƚ����ݿ��еı���ѭ�����,����Excel�ĵ�һ��
		foreach($data[0] as $key=>$value){
			$objActSheet->setCellValue($array[$i-1].'1', iconv('gbk', 'utf-8', $key));
			$i++;
		}
		//��ÿһ������ѭ�����,����Excel��
		$d=2;
		for($i=0;$i<count($data);$i++){
			$k=0;
			foreach($data[$i] as $value){
				$objActSheet->setCellValue($array[$k].$d, iconv('gbk', 'utf-8', $value));
				$k++;
			}
		$d++;
		}
		  
		//��ʽָ����������  
	   // $objActSheet->setCellValueExplicit('A5', '847475847857487584',   
		 //                                  PHPExcel_Cell_DataType::TYPE_STRING);  
		  
		//�ϲ���Ԫ��  
		//$objActSheet->mergeCells('B1:C22');  
		  
		//���뵥Ԫ��  
		//$objActSheet->unmergeCells('B1:C22');  
		  
		//*************************************  
		//���õ�Ԫ����ʽ  
		//  
		  
		//���ÿ��  
		foreach($array as $value){
			$objActSheet->getColumnDimension($value)->setAutoSize(true);  
		}
		  
		$objStyleA5 = $objActSheet->getStyle('A5');  
		  
		//���õ�Ԫ�����ݵ����ָ�ʽ��  
		//  
		//���ʹ���� PHPExcel_Writer_Excel5 ���������ݵĻ���  
		//������Ҫע�⣬�� PHPExcel_Style_NumberFormat ��� const ���������  
		//�����Զ����ʽ����ʽ�У��������Ͷ���������ʹ�ã�����setFormatCode  
		//Ϊ FORMAT_NUMBER ��ʱ��ʵ�ʳ�����Ч����û�аѸ�ʽ����Ϊ"0"����Ҫ  
		//�޸� PHPExcel_Writer_Excel5_Format ��Դ�����е� getXf($style) ������  
		//�� if ($this->_BIFF_version == 0x0500) { ����363�и�����ǰ������һ  
		//�д���:   
		//if($ifmt === '0') $ifmt = 1;  
		//  
		//���ø�ʽΪPHPExcel_Style_NumberFormat::FORMAT_NUMBER������ĳЩ������  
		//��ʹ�ÿ�ѧ������ʽ��ʾ���������� setAutoSize ����������ÿһ�е�����  
		//����ԭʼ����ȫ����ʾ������  
		$objStyleA5  
			->getNumberFormat()  
			->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);  
		  
		//��������  
		$objFontA5 = $objStyleA5->getFont();  
		$objFontA5->setName('Courier New');  
		$objFontA5->setSize(10);  
		$objFontA5->setBold(true);  
		$objFontA5->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);  
		$objFontA5->getColor()->setARGB('FF999999');  
		  
		//���ö��뷽ʽ  
		$objAlignA5 = $objStyleA5->getAlignment();  
		$objAlignA5->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);  
		$objAlignA5->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
		  
		//���ñ߿�  
		$objBorderA5 = $objStyleA5->getBorders();  
		$objBorderA5->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objBorderA5->getTop()->getColor()->setARGB('FFFF0000'); // color  
		$objBorderA5->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objBorderA5->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		$objBorderA5->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
		  
		//���������ɫ  
		$objFillA5 = $objStyleA5->getFill();  
		$objFillA5->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
		$objFillA5->getStartColor()->setARGB('FFEEEEEE');  
		  
		//��ָ���ĵ�Ԫ������ʽ��Ϣ.  
		$objActSheet->duplicateStyle($objStyleA5, 'B1:C22');  
		  
		  
		//*************************************  
		//���ͼƬ  
		//$objDrawing = new PHPExcel_Worksheet_Drawing();  
		//$objDrawing->setName('ZealImg');  
		//$objDrawing->setDescription('Image inserted by Zeal');  
		//$objDrawing->setPath('./zeali.net.logo.gif');  
		//$objDrawing->setHeight(36);  
		//$objDrawing->setCoordinates('C23');  
		//$objDrawing->setOffsetX(10);  
		//$objDrawing->setRotation(15);  
		//$objDrawing->getShadow()->setVisible(true);  
		//$objDrawing->getShadow()->setDirection(36);  
		//$objDrawing->setWorksheet($objActSheet);  
		  
		 
		//���һ���µ�worksheet  
		$objExcel->createSheet();  
		$objExcel->getSheet(1)->setTitle('����2');  
		  
		//������Ԫ��  
		$objExcel->getSheet(1)->getProtection()->setSheet(true);  
		$objExcel->getSheet(1)->protectCells('A1:C22', 'PHPExcel');  
		  
		  
		//*************************************  
		//�������  
		//  
		$outputFileName = date('Y_m_d').'.xls';
		//$outputFileName = mb_convert_encoding($outputFileName,"gb2312", "UTF-8");
		//���ļ�  
		////$objWriter->save($outputFileName);  
		//or  
		//�������  
		header("Content-Type: application/force-download");  
		header("Content-Type: application/octet-stream");  
		header("Content-Type: application/download");  
		header('Content-Disposition:inline;filename="'.$outputFileName.'"');  
		header("Content-Transfer-Encoding: binary");  
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
		header("Pragma: no-cache");  
		$objWriter->save('php://output');  
}      
exportExcel(123);
?>  