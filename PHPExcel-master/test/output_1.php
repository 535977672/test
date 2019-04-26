<?php
//导出

$strTable = '<table width="500" border="1">';
            $strTable .= '<tr>';
            $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">第三方</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="100">发给</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">发呆</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">改的</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">发送到</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">给对方</td>';
            $strTable .= '<td style="text-align:center;font-size:12px;" width="*">发的</td>';
            $strTable .= '</tr>';
            
$strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">34324324</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">3423</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">trret</td>';
                    $strTable .= '<td style="vnd.ms-excel.numberformat:@">覆盖</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">二恶而2</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">王夫人的股份</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">热二</td>';
                    $strTable .= '</tr>'; 
            
$strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">34324324</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">3423</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">trret</td>';
                    $strTable .= '<td style="vnd.ms-excel.numberformat:@">覆盖</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">二恶而2</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">王夫人的股份</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">热二</td>';
                    $strTable .= '</tr>'; 
            
$strTable .= '<tr>';
                    $strTable .= '<td style="text-align:center;font-size:12px;">34324324</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">3423</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">trret</td>';
                    $strTable .= '<td style="vnd.ms-excel.numberformat:@">覆盖</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">二恶而2</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">王夫人的股份</td>';
                    $strTable .= '<td style="text-align:left;font-size:12px;">热二</td>';
                    $strTable .= '</tr>'; 
$strTable .= '</table>'; 


header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=" . wqewewqe . "_" . date('Y-m-d') . ".xlsx");
header('Expires:0');
header('Pragma:public');
echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . $strTable . '</html>';
exit;
