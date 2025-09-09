<?php

namespace Vendor\Controllers\Root\App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class __AppController
{

    // INIT
        public function init(Request $request): JsonResponse
        {
            $arr = [];

            ob_start();
                require_once DIR_PP.'/.dist/index.html';
                $html = ob_get_contents();
            ob_end_clean();


            // CSS
                $arr['CSS'] = [];
                $ex_css = explode('link href="', $html);
                foreach ($ex_css as $key => $value) {
                    if ($key) {
                        $ex_1 = explode('"', $value);

                        if (strpos($ex_1[0], '.dist')) {
                            $arr['CSS'][] =  HOST.$ex_1[0];
                        }
                    }
                }
            // CSS


            // JS
                $arr['JS'] = [];
                $ex_js = explode('script defer="defer" src="', $html);
                foreach ($ex_js as $key => $value) {
                    if ($key) {
                        $ex_1 = explode('"', $value);

                        if (strpos($ex_1[0], '.dist')) {
                            $arr['JS'][] =  HOST.$ex_1[0].'?v='.rand();
                        }
                    }
                }
            // JS


            // VARS
                $arr['VARS'] = [];

                // GET
                    $HTML = '';
                    $ARRAY = (object)[];
                    $ex_js = explode('_GET = {', $html);
                    foreach ($ex_js as $key => $value) {
                        if ($key) {
                            $ex_1 = explode('}', $value);

                            $HTML = trim($ex_1[0]);
                        }
                    }
                    $HTML = explode("\n", $HTML);
                    foreach ($HTML as $key => $value) {
                        $val = trim($value);

                        $ex_1 = explode(':', $val);
                        $ex_2 = explode("'", $val);
                        if (isset($ex_1[0])  AND isset($ex_2[1])) {
                            $v = $ex_1[0];
                            $ARRAY->$v = $ex_2[1];
                        }
                    }
                    $arr['VARS']['GET'] = json_encode($ARRAY);
                // GET


                // GLOBAL
                    $HTML = '';
                    $ARRAY = (object)[];
                    $ex_js = explode('_GLOBAL = {', $html);
                    foreach ($ex_js as $key => $value) {
                        if ($key) {
                            $ex_1 = explode('}', $value);

                            $HTML = trim($ex_1[0]);
                        }
                    }
                    $HTML = explode("\n", $HTML);
                    foreach ($HTML as $key => $value) {
                        $val = trim($value);

                        $ex_1 = explode(':', $val);
                        $ex_2 = explode("'", $val);
                        if (!isset($ex_2[1]) AND isset($ex_1[1])) {
                            $ex_2[1] = replace(',', '', $ex_1[1]);
                            if (isset($ex_2[1]) AND $ex_2[1]) {
                                if (compare__('(', $ex_2[1]) AND compare__(')', $ex_2[1])) { // Se tiver entre ()
                                    eval('$ex_2[1] = '.$ex_2[1].';');
                                }
                            }
                        }
                        if (isset($ex_1[0])  AND isset($ex_2[1])) {
                            $v = $ex_1[0];
                            if ( !($v == 'DIR' OR $v == 'DIR_API') ) {
                                $ARRAY->$v = $ex_2[1];
                            }
                        }
                    }
                    $arr['VARS']['GLOBAL'] = json_encode($ARRAY);
                // GLOBAL
            // VARS

            return json_encode__($arr, $request);

        }
    // INIT

}