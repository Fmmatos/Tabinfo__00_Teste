<?php

namespace Vendor\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Root\Services\NEW__UploadService;

class __UploadService
{


    // SAVE
        public static function save(Model|string $model, UploadedFile $file, ?object $value = null): array
        {
            $return = [];

            $size = $file->getSize();
            $ext = self::treatment_type_ext($file);

            // NAME FILE
                $table = not('accents_all', is_string($model) ? $model : $model->table());
                $table = not('s_end', $table);
                $table = replace('x-', '', $table).'-';

                $id = (isset($value->id) && $value->id) ? $value->id.'_' : '';
                $name = (isset($value->name) && $value->name) ? not('accents_all', limit1($value->name, 50)).'_' : '';

                $file_patch = $table.$id.$name.rand().'.'.$ext;
            // NAME FILE


            // SAVE
                $response = $file->move(DIR_P.PHOTOS.'/', $file_patch);
                if ($response) {
                    $return = [
                        'file' => $file_patch,
                        'size' => $size ?? '',
                        'name' => $file->getClientOriginalName() ?? '',
                    ];
                }
            // SAVE


            // CREATE_THUMBS_TO_IMAGE_BIG
                if ($size > ALL__MAX_UPLOAD_IMAGE__MAX()) {
                    return self::create_thumbs_to_image_big($file_patch, $ext);
                }
            // CREATE_THUMBS_TO_IMAGE_BIG

            return $return;
        }





        // CREATE_THUMBS_TO_IMAGE_BIG
            public static function create_thumbs_to_image_big(string $file_patch, string $ext): array
            {
                if (self::treatment_type_image(null, $ext)) {
                        try {
                        $response = __ImageService::resize_image($file_patch);
                        if ($response) {
                            $response = replace(PHOTOS.'/', '', $response);

                            $return = [
                                'file' => $file_patch,
                                'size' => ``,
                                'name' => '',
                            ];
        
                            return $response;

                            $file__current = DIR_P.PHOTOS.'/'.$file_patch;
                            $file__new = DIR_P.PHOTOS.'/'.$response;

                            if (file_exists($file__current) && file_exists($file__new)) {
                                unlink($file__current);
                            }
                        }

                    } catch (\Throwable $th) { }
                }
                return [];
            }
        // CREATE_THUMBS_TO_IMAGE_BIG
    // SAVE










    // TREATMENT
        public static function treatment_size(UploadedFile $file): string|false
        {
            if (!$file->isValid()) {
                return 'O arquivo '.$file->getClientOriginalName().' é inválido!';
            }

            // IMAGE
                else if(self::treatment_type_image($file)) {
                    if ($file->getSize() > ALL__MAX_UPLOAD_IMAGE()) {
                        return 'A imagem '.$file->getClientOriginalName().' contém '.price_number((int)$file->getSize()/1000000).'MB, deve ser menor que '.price_number(ALL__MAX_UPLOAD_IMAGE()/1000000).'MB!';
                    }    
                }
            // IMAGE

            // FILE
                else {
                    if ($file->getSize() > ALL__MAX_UPLOAD_FILES()) {
                        return 'O Arquivo '.$file->getClientOriginalName().' contém '.price_number((int)$file->getSize()/1000000).'MB, deve ser menor que '.price_number(ALL__MAX_UPLOAD_FILES()/1000000).'MB!';
                    }
                }
            // FILE
            
            return false;
        }

        public static function treatment_type_image(?UploadedFile $file, string $ext = '', ?object $menu_admin = null, string $key = ''): bool
        {
            $ext = $ext ? $ext : self::treatment_type_ext($file);

            $passar = false;
            if (lower($ext) == 'jpg') $passar = true;
            if (lower($ext) == 'jpeg') $passar = true;
            if (lower($ext) == 'png') $passar = true;
            if (lower($ext) == 'webp') $passar = true;
            if (lower($ext) == 'gif') $passar = true;
            if (lower($ext) == 'svg') $passar = true;
            if (lower($ext) == 'ico') $passar = true;
            if (lower($ext) == 'heic') $passar = true;
    
            $passar = NEW__UploadService::treatment_type_image($file, $ext, $menu_admin, $key, $passar);

            return $passar;
        }

        public static function treatment_type_file(?UploadedFile $file, string $ext = '', ?object $menu_admin = null, string $key = ''): bool
        {
            $ext = $ext ? $ext : self::treatment_type_ext($file);

            $passar = false;
            if (lower($ext) == 'jpg') $passar = true;
            if (lower($ext) == 'jpeg') $passar = true;
            if (lower($ext) == 'png') $passar = true;
            if (lower($ext) == 'webp') $passar = true;
            if (lower($ext) == 'gif') $passar = true;
            if (lower($ext) == 'svg') $passar = true;
            if (lower($ext) == 'ico') $passar = true;
            if (lower($ext) == 'heic') $passar = true;

            if (lower($ext) == 'pdf') $passar = true;

            if (lower($ext) == 'doc') $passar = true;
            if (lower($ext) == 'docx') $passar = true;
            if (lower($ext) == 'txt') $passar = true;

            if (lower($ext) == 'ppt') $passar = true;
            if (lower($ext) == 'pptx') $passar = true;
            if (lower($ext) == 'pps') $passar = true;
            if (lower($ext) == 'ppsx') $passar = true;
            if (lower($ext) == 'odp') $passar = true;
            if (lower($ext) == 'pot') $passar = true;
            if (lower($ext) == 'potx') $passar = true;

            if (lower($ext) == 'csv') $passar = true;
            if (lower($ext) == 'xls') $passar = true;
            if (lower($ext) == 'xlsx') $passar = true;

            if (lower($ext) == 'mp3') $passar = true;
            if (lower($ext) == 'wav') $passar = true;
            if (lower($ext) == 'ogg') $passar = true;
            if (lower($ext) == 'm4a') $passar = true;
            if (lower($ext) == 'flac') $passar = true;
            if (lower($ext) == 'wma') $passar = true;
            if (lower($ext) == 'aac') $passar = true;

            if (lower($ext) == 'flv') $passar = true;
            if (lower($ext) == 'swf') $passar = true;

            if (lower($ext) == 'mp4') $passar = true;
            if (lower($ext) == 'm4v') $passar = true;
            if (lower($ext) == 'mov') $passar = true;
            if (lower($ext) == 'mkv') $passar = true;
            if (lower($ext) == 'webm') $passar = true;
            if (lower($ext) == 'avi') $passar = true;
            if (lower($ext) == 'wmv') $passar = true;
            if (lower($ext) == '3gp') $passar = true;

            $passar = NEW__UploadService::treatment_type_file($file, $ext, $menu_admin, $key, $passar);

            if(self::treatment_type_input_tags($file, $menu_admin, $key, $ext)){
                $passar = true;
            }

            return $passar;
        }

        public static function treatment_type_input_tags(?UploadedFile $file, ?object $menu_admin = null, string $key = '', string $ext = ''): bool
        {
            if(isset($menu_admin->input)){
                foreach($menu_admin->input as $key_1 => $value_1) {
                    if(isset($value_1->name) && $value_1->name == $key) {
                        if(isset($value_1->tags)){
                            $format = entre('formart="', '"', $value_1->tags);
                            if(isset($format)){
                                $ex = explode(',', $format);
                                foreach($ex as $key_2 => $value_2) {
                                    if(lower(trim($value_2)) == lower(trim($ext))) {
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return false;
        }

        public static function treatment_type(UploadedFile $file, string $type = 'file', ?object $menu_admin = null, string $key = ''): string|false
        {
            $ext = self::treatment_type_ext($file);

            // IMAGE
                if ($type == 'image') {
                    if (!self::treatment_type_image($file, $ext, $menu_admin, $key)) {
                        return 'O formtado do seu arquivo é .'.$ext.', não é um formtado válido para upload!';
                    }
                    return false; 
                }
            // IMAGE

            // FILE
                if ($type == 'file') {
                    if (!self::treatment_type_file($file, $ext, $menu_admin, $key)) {
                        return 'O formtado do seu arquivo é .'.$ext.', não é um formtado válido para upload!';
                    }
                    return false; 
                }
            // FILE

            // ELSE
                else {
                    return 'O formtado do seu arquivo é .'.$ext.', não é um formtado válido para upload!';
                    return false; 
                }
            // ELSE

            return false; 
        }

        public static function treatment_type_ext(?UploadedFile $file): string
        {
            $ext = $file->extension();
            if (!$ext) {
                $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            }
            $ext = $ext ? $ext : '';

            return $ext;
        }

    // TREATMENT










    // UPLOAD CAPTURE
        public static function upload_capture(Request $request): string|object
        {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image'); // Obtém o arquivo de imagem

                $imagePath = $file->getRealPath(); // Obtém o caminho temporário do arquivo
                $image = imagecreatefrompng($imagePath); // Carrega a imagem a partir do arquivo PNG

                if ($image === false) {
                    return response()->json(['error' => 'Falha ao carregar a imagem.'], 400);
                }

                $filename = MOBILE() ? '__metrix__mobile.jpg' : '__metrix__desktop.jpg';
                $path = '../../../../../public_html/web/photos/'.$filename;

                $jpgPath = storage_path('app/public/' . $path); // Converte e salva a imagem como JPG
                imagejpeg($image, $jpgPath, 90);
                imagedestroy($image);

                return response()->json(['message' => 'Arquivo enviado com sucesso.', 'path' => $path]);
            } else {
                return response()->json(['error' => 'Nenhuma imagem foi enviada ou ocorreu um erro no upload.'], 400);
            }
        }
    // UPLOAD CAPTURE

}