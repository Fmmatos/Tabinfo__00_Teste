<?php

namespace Root\Services;

use Illuminate\Http\Request;

class NEW__ExportService
{

    // INDEX
        public static function index(Request $request, string $data): string
        {

            if(!empty($request['type'])){
                $ex = explode('_', $request['type']??'');

                // HTML__
                    if(in_array('html', $ex)){
                        $request['html__'] = 1;
                    }
                // HTML__





                // FILE
                    // if(in_array('file', $ex) && is_image_files($request['image'])){
                    // }
                // FILE
            }

            return $data;
        }
    // STATUS

}