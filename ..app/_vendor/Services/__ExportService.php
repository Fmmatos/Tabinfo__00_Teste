<?php

namespace Vendor\Services;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Root\Services\NEW__ExportService;

class __ExportService
{

    // EXCEL
        public static $xls_break = "\n";
        public static $xls_separator = "\t";
        // public static $xls_break = "<br>";
        // public static $xls_separator = "----|----";

        public static function excel(Request $request): void
        {
            ini_set("default_charset", "iso-8859-1");

            $array = self::treatment($request);

            // DATA
                $data  = '';

                // THEAD
                    foreach($array['thead'] as $key => $value) {
                        $data .= $value . self::$xls_separator;
                    }
                    $data .= self::$xls_break;
                // THEAD

                // TBODY
                    foreach($array['tbody'] as $k => $v) {
                        foreach($v as $key => $value) {
                            $data .= $value . self::$xls_separator;
                        }
                        $data .= self::$xls_break;
                    }
                // TBODY
            // DATA


            // echo $data; exit();

            $file = $request['name'].'_'.date('d_m_Y').'.xls';
            header("Content-type: application/x-msdownload");
            header("Content-Disposition: attachment; filename=$file");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo not('tags', self::excel__utf8_decode($data));
            exit();
        }

        public static function excel__utf8_decode($data)
        {
            //$data = utf8_decode($data);
            $data = mb_convert_encoding($data, 'ISO-8859-1', 'UTF-8');
            return $data;
        }
    // EXCEL










    // PDF
        public static function pdf(Request $request): void
        {
            $array = self::treatment($request);

            // DATA
                $data  = '';

                // TABLE
                    if (!empty($array['thead'])) {
                        $data .= '<table style="width:100%;" border="0" cellspacing="3" cellpadding="3"> ';

                            // THEAD
                                $data .= '<tr> ';
                                    foreach($array['thead'] as $key => $value) {
                                        $align = 'center';
                                        if ($key == 0 || $key == 1) {
                                            $align = 'left';
                                        }

                                        $data .= '<td align="'.$align.'" style="border:#aaa 1px solid; background:#ddd; padding:5px; font-size:14px"><div style="padding:2px 5px;">'.$value.'</div></td> ';
                                    }
                                $data .= '</tr> ';
                            // THEAD


                            // TBODY
                                foreach($array['tbody'] as $k => $v) {
                                    $data .= '<tr> ';
                                        foreach($v as $key => $value) {
                                            $align = 'center';
                                            if ($key == 0 || $key == 1) {
                                                $align = 'left';
                                            }

                                            $data .= '<td align="'.$align.'" style="border:#ccc 1px solid; padding:5px; font-size:14px"><div style="padding:2px 5px;">'.$value.'</div></td> ';
                                        }
                                    $data .= '</tr> ';
                                }
                            // TBODY

                        $data .= '</table>';
                    }
                // TABLE


                // HTML
                    else {
                        $data .= $array['html'];
                    }
                // HTML


                // NEW
                    $data = NEW__ExportService::index($request, $data);
                // NEW
            // DATA

            // echo $data; exit();
            if (!empty($request['html__'])) {
                echo $data; exit();
            }


            $_PAPER = 'A4'; //'letter';
            $_ORIENTATION = 'landscape'; //'portrait';
            $_DATA = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Dados Exportados em PDF</title></head>
                            <body>'.$data.'</body>
                        </html>';

            //$dompdf = new DOMPDF();
            $dompdf = new DOMPDF(["enable_remote" =>true]);

            $dompdf->getOptions()->setChroot(DIR_F);
            $dompdf->loadHtml(stripslashes($_DATA));
            $dompdf->setPaper($_PAPER, $_ORIENTATION);
            $dompdf->render();
    
            $file = $request['name'].'_'.date('d_m_Y').'.pdf';

            if (!empty($request['open'])) {
                $dompdf->stream($file, ["Attachment" => false]); // ver na pagina

            } else {
                $dompdf->stream($file);
            }
            exit();

        }
    // PDF










    // TREATMENT
        public static function treatment(Request $request): array
        {
            // STRING
                if (compare__('z|z', (string)$request['thead'])) {
                    $thead = explode('z|z', $request['thead']??'');
                    $count = count($thead) - 1;
                    unset($thead[$count]);

                    $tbody_temp = explode('z-z', $request['tbody']??'');
                    $count = count($tbody_temp) - 1;
                    unset($tbody_temp[$count]);

                    $tbody = [];
                    foreach($tbody_temp as $key => $value) {
                        $tbody[$key] = explode('z|z', $value);

                        $count = count($tbody[$key]) - 1;
                        unset($tbody[$key][$count]);
                    }
                }
            // STRING


            // ARRAY
                else {
                    $thead = !empty($_POST['thead']) ? json_decode($_POST['thead'], true) : [];
                    $tbody = !empty($_POST['tbody']) ? json_decode($_POST['tbody'], true) : [];
                    $html = !empty($_POST['html']) ? $_POST['html'] : '';

                    $request['image'] = !empty($_POST['image']) ? json_decode($_POST['image'], true) : [];

                }
            // ARRAY

            return [ 'thead' => $thead, 'tbody' => $tbody, 'html' => $html ];
        }
    // TREATMENT

}