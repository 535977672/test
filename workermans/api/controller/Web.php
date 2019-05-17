<?php

/**
 * Description of Web
 *
 * @author Administrator
 */
class WebController {
    
    public function index($arg) {
        $arg['time'] = time();
        $file = $arg['params2'];
        if($file){
            $mime = substr($file, strpos($file, ':')+1, strpos($file, ';')-strpos($file, ':')-1);
            if($mime == 'text/plain'){//txt
                $ext = 'txt';
            }else if($mime == 'image/jpeg'){//jpg
                $ext = 'jpeg';
            }else if($mime == 'image/png'){//png
                $ext = 'png';
            }else if($mime == 'application/vnd.ms-excel'){//xls
                $ext = 'xls';
            }else if($mime == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){//xlsx
                $ext = 'xlsx';
            }
            $name = '11.' . $ext;
            file_put_contents('./resource/' . $name, base64_decode(str_replace('data:' . $mime . ';base64,', '', $file)));
        }
        return $arg['mime'] = $mime;
    }   
}
