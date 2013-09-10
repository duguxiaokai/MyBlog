<?  
	/**
	 *导出excel的方法
	 *作者：独孤晓凯
	 *时间：2012-06-05 
     * 以下是使用示例，对于以 //// 开头的行是不同的可选方式，请根据实际需要 
     * 打开对应行的注释。 
     * 如果使用 Excel5 ，输出的内容应该是GBK编码。 
     */ 
function uploadFile($file,$filetempname) 
{
    //自己设置的上传文件存放路径
    $filePath = 'upFile/';
    $str = "";   
    //下面的路径按照你PHPExcel的路径来修改
    require_once 'PHPExcel.php';
    require_once 'PHPExcel/PHPExcel/IOFactory.php';
    require_once 'PHPExcel/PHPExcel/Reader/Excel5.php';

    //注意设置时区
    $time=date("y-m-d-H-i-s");//去当前上传的时间 
    //获取上传文件的扩展名
    $extend=strrchr ($file,'.');
    //上传后的文件名
    $name=$time.$extend;
    $uploadfile=$filePath.$name;//上传后的文件名地址 
    //move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
    $result=move_uploaded_file($filetempname,$uploadfile);//假如上传到当前目录下
    //echo $result;
    if($result) //如果上传文件成功，就执行导入excel操作
    {
        include "conn.php";
        $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
        $objPHPExcel = $objReader->load($uploadfile); 
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow();           //取得总行数 
        $highestColumn = $sheet->getHighestColumn(); //取得总列数
        
        /* 第一种方法

        //循环读取excel文件,读取一条,插入一条
        for($j=1;$j<=$highestRow;$j++)                        //从第一行开始读取数据
        { 
            for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
            { 
                //
                这种方法简单，但有不妥，以'\\'合并为数组，再分割\\为字段值插入到数据库
                实测在excel中，如果某单元格的值包含了\\导入的数据会为空        
                //
                $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
            } 
            //echo $str; die();
            //explode:函数把字符串分割为数组。
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
                echo 'sql语句有误';
            }
            $str = "";
        }  
        unlink($uploadfile); //删除上传的excel文件
        $msg = "导入成功！";
        */

        /* 第二种方法*/
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); 
        echo 'highestRow='.$highestRow;
        echo "<br>";
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
        echo 'highestColumnIndex='.$highestColumnIndex;
        echo "<br>";
        $headtitle=array(); 
        for ($row = 1;$row <= $highestRow;$row++) 
        {
            $strs=array();
            //注意highestColumnIndex的列数索引从0开始
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
                echo 'sql语句有误';
            }
        }
    }
    else
    {
       $msg = "导入失败！";
    } 
    return $msg;
}
?>