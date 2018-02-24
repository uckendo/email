<?php
set_time_limit(0);
include('connect.php');
include('function/phpexcel/PHPExcel.php');
include('function/phpexcel/PHPExcel/IOFactory.php');
$inputFileName = $path; //'example2.xls';
$objPHPExcel = new PHPExcel();

extract($_POST);
if($DataType == 'User'){    //用户数据
    $objPHPExcel->getActiveSheet()->setCellValue('A1','邮箱');
    $objPHPExcel->getActiveSheet()->setCellValue('B1','用户名');  
    $objPHPExcel->getActiveSheet()->setCellValue('C1','性别');  
    $objPHPExcel->getActiveSheet()->setCellValue('D1','公司'); 
    if($Nums == 'All'){     //全部数据
        $sql = "select * from `user` order by `UserID` $Order" ;    
        
    }else{
        $sql = "select * from `user` order by `UserID` $Order limit $Nums" ;      
    }
}else{      //模板数据
    $objPHPExcel->getActiveSheet()->setCellValue('A1','模板名称');
    $objPHPExcel->getActiveSheet()->setCellValue('B1','模板内容');  
    if($Nums == 'All'){     //全部数据
        $sql = "select * from `template` order by `TemplateID` $Order" ;    
        
    }else{
        $sql = "select * from `user` order by `TemplateID` $Order limit $Nums" ;      
    }
}
$query = mysql_query($sql);
$line = 2;
while($info = mysql_fetch_assoc($query)){
    if($DataType == 'User'){
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$line,$info['Email']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$line,$info['Username']);  
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$line,$info['Sex']);  
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$line,$info['Company']);     
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$line,$info['TemplateName']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$line,$info['TemplateContent']);  
    }
   $line++;     
}




 
$filename = $DataType.'.xls';
$obj_Writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Content-Disposition:inline;filename="'.$filename.'"');
header("Content-Transfer-Encoding: binary");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
$obj_Writer->save('php://output'); 
?>