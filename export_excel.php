<?php

/**
 * 导出 excel 表格
 *  
 * @param array $data 要导出的数据
 * @param array $title excel表格的表头
 * @param string $filename 文件名
 */
function export_excel($data=array(),$title=array(),$filename='报表',$catalog='./名单/'){
    $html = "
    <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
    <html xmlns='http://www.w3.org/1999/xhtml'>
    <meta http-equiv='Content-type' content='text/html;charset=UTF-8' />
    <head>
        <title>".$filename."</title>
        <style>
        td{
            text-align:center;
            font-size:12px;
            font-family:Arial, Helvetica, sans-serif;
            border:rgb(200,200,200) 1px solid;
            color:#152122;
            width:auto;
        }
        table,tr{
            border-style:none;
        }
        .title{
            background:rgb(127,150,152);
            color:#FFFFFF;
            font-weight:bold;
        }
        </style>
    </head>
    <body>
    <table width='100%' border='1'>
    <tr>
    ";
    foreach($title as $k=>$v){
        $html .= " <td class='title' style='text-align:center;'>".$v."</td>";
    }
    $html .= "</tr>";
    foreach ($data as $key => $value) {
        $html .= "<tr>";
        foreach($value as $aa){
            $html .= "<td>".$aa."</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table></body></html>";

    // 判断表格目录是否存在
    if(!is_dir($catalog)) {
        // 创建目录
        $res = mkdir(iconv("UTF-8", "GBK", $catalog),0777,true); 
        if (!$res){
            return -1;
        }
    }
    
    // 将表格写入本地
    $file_name = $filename.'.xls';   // 保存的文件名称
    $myfile = fopen($catalog.$file_name, "w");
    fwrite($myfile, $html);
    fclose($myfile);
    
    return 1;
}