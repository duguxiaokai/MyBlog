<?  
	/**
	 *����excel�ķ���
	 *���ߣ���������
	 *ʱ�䣺2012-06-05 
     * ������ʹ��ʾ���������� //// ��ͷ�����ǲ�ͬ�Ŀ�ѡ��ʽ�������ʵ����Ҫ 
     * �򿪶�Ӧ�е�ע�͡� 
     * ���ʹ�� Excel5 �����������Ӧ����GBK���롣 
     */ 
function uploadFile($file,$filetempname) 
{
    //�Լ����õ��ϴ��ļ����·��
    $filePath = 'upFile/';
    $str = "";   
    //�����·��������PHPExcel��·�����޸�
    require_once 'PHPExcel.php';
    require_once 'PHPExcel/PHPExcel/IOFactory.php';
    require_once 'PHPExcel/PHPExcel/Reader/Excel5.php';

    //ע������ʱ��
    $time=date("y-m-d-H-i-s");//ȥ��ǰ�ϴ���ʱ�� 
    //��ȡ�ϴ��ļ�����չ��
    $extend=strrchr ($file,'.');
    //�ϴ�����ļ���
    $name=$time.$extend;
    $uploadfile=$filePath.$name;//�ϴ�����ļ�����ַ 
    //move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��
    $result=move_uploaded_file($filetempname,$uploadfile);//�����ϴ�����ǰĿ¼��
    //echo $result;
    if($result) //����ϴ��ļ��ɹ�����ִ�е���excel����
    {
        include "conn.php";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
        $objPHPExcel = $objReader->load($uploadfile); 
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow();           //ȡ�������� 
        $highestColumn = $sheet->getHighestColumn(); //ȡ��������
        
        /* ��һ�ַ���

        //ѭ����ȡexcel�ļ�,��ȡһ��,����һ��
        for($j=1;$j<=$highestRow;$j++)                        //�ӵ�һ�п�ʼ��ȡ����
        { 
            for($k='A';$k<=$highestColumn;$k++)            //��A�ж�ȡ����
            { 
                //
                ���ַ����򵥣����в��ף���'\\'�ϲ�Ϊ���飬�ٷָ�\\Ϊ�ֶ�ֵ���뵽���ݿ�
                ʵ����excel�У����ĳ��Ԫ���ֵ������\\��������ݻ�Ϊ��        
                //
                $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//��ȡ��Ԫ��
            } 
            //echo $str; die();
            //explode:�������ַ����ָ�Ϊ���顣
            $strs = explode("\\",$str);
            $sql = "INSERT INTO te(`1`, `2`, `3`, `4`, `5`) VALUES (
            '{$strs[0]}',
            '{$strs[1]}',
            '{$strs[2]}',
            '{$strs[3]}',
            '{$strs[4]}')";
            //die($sql);
            if(!mysql_query($sql))
            {
                return false;
                echo 'sql�������';
            }
            $str = "";
        }  
        unlink($uploadfile); //ɾ���ϴ���excel�ļ�
        $msg = "����ɹ���";
        */

        /* �ڶ��ַ���*/
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); 
        echo 'highestRow='.$highestRow;
        echo "<br>";
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//������
        echo 'highestColumnIndex='.$highestColumnIndex;
        echo "<br>";
        $headtitle=array(); 
        for ($row = 1;$row <= $highestRow;$row++) 
        {
            $strs=array();
            //ע��highestColumnIndex������������0��ʼ
            for ($col = 0;$col < $highestColumnIndex;$col++)
            {
                $strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }    
            $sql = "INSERT INTO te(`1`, `2`, `3`, `4`, `5`) VALUES (
            '{$strs[0]}',
            '{$strs[1]}',
            '{$strs[2]}',
            '{$strs[3]}',
            '{$strs[4]}')";
            //die($sql);
            if(!mysql_query($sql))
            {
                return false;
                echo 'sql�������';
            }
        }
    }
    else
    {
       $msg = "����ʧ�ܣ�";
    } 
    return $msg;
}
?>