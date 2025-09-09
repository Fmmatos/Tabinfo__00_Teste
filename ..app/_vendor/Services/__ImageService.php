<?php

namespace Vendor\Services;


class __ImageService
{

    // CONFIGURACOES
        private $QUALITY__JPG = 90;
        private $QUALITY__PNG = 9;
        private $QUALITY__WEBP = 100;
    // CONFIGURACOES





    // THUMBS
        public static function thumbs(string $img, array $value, string $column): object
        {
            $width = $_GET['__GLOBAL__']['__THUMBS__']['default']['width'];
            $height = $_GET['__GLOBAL__']['__THUMBS__']['default']['height'];

            foreach ($_GET['__GLOBAL__']['__THUMBS__'] as $key_1 => $value_1) {
                // TABLE
                    $table__ = $value['table__']??'';
                    if (isset($value['table__type']) && !is_number($value['table__type'])) {
                        $table__ = $value['table__type'];
                    }

                    if ($key_1 == $table__) {
                        if (!isset($value_1['width'])) {
                            foreach ($value_1 as $key_2 => $value_2) {

                                // FIELD
                                    if (isset($value_2['width'])) {
                                        if ($key_2 == $column) {
                                            $width = $value_2['width'];
                                            $height = $value_2['height'];
                                        }

                                    } else {
                                        foreach ($value_2 as $key_3 => $value_3) {

                                            // TYPE
                                                if (isset($value_3['width'])) {
                                                    if (isset($value['type']) && $key_3 == $value['type']) {
                                                        $width = $value_3['width'];
                                                        $height = $value_3['height'];
                                                    }
                    
                                                } else {
                                                    foreach ($value_3 as $key_4 => $value_4) {

                                                        // PLACE
                                                            if (isset($value_4['width'])) {
                                                                if (isset($value['place']) && $key_4 == $value['place']) {
                                                                    $width = $value_4['width'];
                                                                    $height = $value_4['height'];
                                                                }                                
                                                            }
                                                        // PLACE
                                                    }
                                                }
                                            // TYPE
                                        }                
                                    }
                                // FIELD
                            }
                        }
                    }
                // TABLE
            }

            return (object)['width' => $width, 'height' => $height];
        }
    // THUMBS










    // IMG
        public static function img(string $img, array | int $value, string | int $column): string
        {
            // CONFIG
                if (is_array($value)) {
                    $thumbs = self::thumbs($img, $value, $column);
                    $width = $thumbs->width;
                    $height = $thumbs->height;
                }

                $width_2 = $width; //$width*2;
                $height_2 = $height; //$height*2;

                $DATA_IMG = self::data_img($img);
            // CONFIG





            // VERIFICANDO SE VAI CRIAR THUMBS
                $CREATE_THUMBS = 0;

                // VERIFICAR
                    // IMGS
                        $jpeg = compare__('jpeg', strtolower($DATA_IMG['ext']));
                        $jpg = compare__('jpg', strtolower($DATA_IMG['ext']));
                        if (($jpeg || $jpg) && isset($_GET['__GLOBAL__']['__THUMBS_FORMATS__']) && ($_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_jpg_to_jpg || $_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_jpg_to_webp)) {
                            $CREATE_THUMBS = 1;
                        }
                    // IMGS
                    // PNG
                        $png = compare__('png', strtolower($DATA_IMG['ext']));
                        if ($png && isset($_GET['__GLOBAL__']['__THUMBS_FORMATS__']) && ($_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_png_to_jpg || $_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_png_to_web)) {
                            $CREATE_THUMBS = 1;
                        }
                    // PNG
                    // WEBP
                        $webp = compare__('webp', strtolower($DATA_IMG['ext']));
                        if ($webp && isset($_GET['__GLOBAL__']['__THUMBS_FORMATS__']) && ($_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_webp_to_webp)) {
                            $CREATE_THUMBS = 1;
                        }
                    // WEBP

                    $ext_valid = ['.jpeg', '.jpg', '.png', '.webp'];
                    if (!in_array('.'.strtolower($DATA_IMG['ext']), $ext_valid)) {
                        $CREATE_THUMBS = 0;
                    }
                // VERIFICAR
            // VERIFICANDO SE VAI CRIAR THUMBS





            // CRIAR THUMBS
                $IMG_FINAL = DIR.PHOTOS.'/'.$img;
                if ($CREATE_THUMBS) {
                    if (file_exists(DIR_P.PHOTOS.'/'.$img)) {
                        $thumbs_file = $DATA_IMG['name'].'_'.$width_2.'x'.$height_2;
                        if (LUGAR_ADMIN()) {
                            $thumbs = THUMBS.'/admin_'.$thumbs_file;
                        } else {
                            $thumbs = THUMBS.'/'.$thumbs_file;
                        }

                        if (file_exists(DIR_P.$thumbs.'.webp')) {
                            $IMG_FINAL = DIR.$thumbs.'.webp';
                        }
                        else if(file_exists(DIR_P.$thumbs.'.jpg')) {
                            $IMG_FINAL = DIR.$thumbs.'.jpg';
                        }
                        else {
                            $IMG_FINAL = DIR.self::create_thumbs($DATA_IMG, $img, $width_2, $height_2);
                        }
                    }
                }

                if (!file_exists(DIR_P.PHOTOS.'/'.$img)) {
                    $IMG_FINAL = '';
                }
            // CRIAR THUMBS










            // MARCA DAGUA
            // MARCA DAGUA





            return $IMG_FINAL;
        }
    // IMG










    // RESIZE_IMAGE
        public static function resize_image(string $img_current): bool|string
        {
            // WIDTH / HEIGHT
                $getimagesize = getimagesize(DIR.PHOTOS.'/'.$img_current);
                $image = self::verify_format($getimagesize['mime'], DIR_P.PHOTOS.'/'.$img_current);
                if ($image) {
                    $width = imagesx($image);
                    $height = imagesy($image);

                    $width_final = $width;
                    $height_final = $height;

                    $limit = ALL__MAX_UPLOAD_IMAGE_LIMIT_WIDHT();
                    if ($width > $limit) {
                        $width_final = $limit;

                        $perc = $limit*100/$width;
                        $height_final = $perc*$height/100;
                    }
                }
            // WIDTH / HEIGHT


            // CREATE IMAGE
                if (isset($width_final) && isset($height_final)) {
                    $ext = pathinfo($img_current, PATHINFO_EXTENSION);
                    $img_new = replace('.'.$ext, '__.'.$ext, $img_current);

                    if ($getimagesize['mime'] == 'image/jpeg') {
                        $reponse = self::convert_to_jpg($img_current, $img_new, $width_final, $height_final, PHOTOS.'/', $getimagesize, 90);
                    }
                    else if($getimagesize['mime'] == 'image/png') {
                        $reponse = self::convert_to_png($img_current, $img_new, $width_final, $height_final, PHOTOS.'/', $getimagesize, 9);
                    }
                    else {
                        $reponse = self::convert_to_webp($img_current, $img_new, $width_final, $height_final, PHOTOS.'/', $getimagesize, 90);
                    }

                    if ($reponse) {
                        return $reponse;
                    }
                }
            // CREATE IMAGE

            return false;
        }
    // RESIZE_IMAGE










    // CREATE_THUMBS
        private static function create_thumbs(array $DATA_IMG, string $img_current, int $width_default, int $height_default): string
        {
            $width_new = (int)$width_default;
            $height_new = (int)$height_default;

            $return = PHOTOS.'/'.$img_current;
            try {
                
                $getimagesize = getimagesize(DIR_P.PHOTOS.'/'.$img_current);
				if ($getimagesize) {
					$image = self::verify_format($getimagesize['mime'], DIR_P.PHOTOS.'/'.$img_current);
					if ($image) {
						$width = imagesx($image);
						$height = imagesy($image);

						// VERIFICAR SE WIDTH E HEIGHT NEW É MENOR QUE WIDTH E HEIGHT DA IMG
							if ($width_new > $width) {
								$width_new = $width;
							}
							if ($height_new > $height) {
								$height_new = $height;
							}
						// VERIFICAR SE WIDTH E HEIGHT NEW É MENOR QUE WIDTH E HEIGHT DA IMG

						// PROPORCIONAL
							if ($width_new != 0 && $height_new != 0) {
								$ratioWidth = $width/$width_new;
								$ratioHeight = $height/$height_new;
								if ($ratioWidth < $ratioHeight) {
									$width_final = $width/$ratioHeight;
									$height_final = $height_new;
								} else {
									$width_final = $width_new;
									$height_final = $height/$ratioWidth;
								}
							} else {
								if ($width_new != 0) {
									$ratioWidth = $width/$width_new;
									$width_final = $width_new;
									$height_final = $height/$ratioWidth;
								} else if ($height_new != 0) {
									$ratioHeight = $height/$height_new;
									$height_final = $height_new;
									$width_final = $width/$ratioHeight;
								} else {
									$width_final = $width;
									$height_final = $height;
								}
							}

							$width_final = round($width_final);
							$height_final = round($height_final);
							if ($width_final < 1) $width_final = 1;
							if ($height_final < 1) $height_final = 1;
				        // PROPORCIONAL

				        // CREATE THUMBS
                            return self::created_thumbs($img_current, $DATA_IMG, $width_default, $height_default, $width_final, $height_final, $getimagesize);
				        // CREATE THUMBS
                    }
                }

            } catch (\Throwable $th) { }

            return $return;
        }

        private static function created_thumbs(string $img_current, array $DATA_IMG, int $width_default, int $height_default, int $width_final, int $height_final, array $getimagesize): string 
        {
            $ext = pathinfo($img_current, PATHINFO_EXTENSION);
            $admin = LUGAR_ADMIN() ? 'admin_' : '';
            $img_new = $admin.replace('.'.$ext, '_'.$width_default.'x'.$height_default.'.'.$ext, $img_current);
            $thumbs_format = self::thumbs_format($getimagesize['mime']);

            $return = $img_current;
            if (($thumbs_format == 'jpg' || $thumbs_format == 'jpeg') && $getimagesize['mime'] != 'image/png') {
                $return = self::convert_to_jpg($img_current, $img_new, $width_final, $height_final, THUMBS.'/', $getimagesize);
            }
            else {
                $return = self::convert_to_webp($img_current, $img_new, $width_final, $height_final, THUMBS.'/', $getimagesize);
            }

            return THUMBS.'/'.$return;
        }
    // CREATE_THUMBS










    // CONFIG / VERIFY
        public static function data_img(string $img): array
        {
            $ext = pathinfo($img, PATHINFO_EXTENSION);
            $return = [
                'name' => replace('.'.$ext, '', $img),
                'ext' => $ext,
            ];
            return $return;
        }

        private static function verify_format(string $ext, string $img): mixed
        {
            $return = '';
            if ($ext == 'image/jpeg') {
                $return = @imagecreatefromjpeg($img);

            } else if($ext == 'image/png') {
                $return = @imagecreatefrompng($img);

            } else if($ext == 'image/webp') {
                $return = @imagecreatefromwebp($img);
            }
            return $return;
        }

        private static function thumbs_format(string $ext): string
        {
            $return = 'webp';
            if ($ext == 'image/jpeg') {
                if ($_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_jpg_to_jpg) {
                    $return = 'jpg';
                } else {
                    $return = 'webp';
                }

            } else if($ext == 'image/png') {
                if ($_GET['__GLOBAL__']['__THUMBS_FORMATS__']->thumbs_png_to_jpg) {
                    $return = 'jpg';
                } else {
                    $return = 'webp';
                }
            }
            return $return;
        }
    // CONFIG / VERIFY










    // CONVERT
        // CONFIG
            private static function convert_config($img_current, $width_final, $height_final, $dir, $getimagesize='')
            {
                $getimagesize = $getimagesize ?: getimagesize(DIR.PHOTOS.'/'.$img_current);
                $ext = pathinfo(DIR.PHOTOS.'/'.$img_current, PATHINFO_EXTENSION);

                // TREATMENT
                    $image = self::verify_format($getimagesize['mime'], DIR_P.PHOTOS.'/'.$img_current);
                    $width = imagesx($image);
                    $height = imagesy($image);

                    $canvas = imagecreatetruecolor($width_final, $height_final);

                    // TRANSPARENT
                        imagealphablending($canvas, false);
                        imagesavealpha($canvas, true);
                        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                        imagefill($canvas, 0, 0, $transparent);    
                    // TRANSPARENT

                    imagecopyresampled($canvas, $image, 0, 0, 0, 0, $width_final, $height_final, $width, $height);
                    imagedestroy($image);
                // TREATMENT

                return $canvas;
            }
        // CONFIG

        // TO JPG
            private static function convert_to_jpg($img_current, $img_new, $width_final, $height_final, $dir, $getimagesize='', $QUALITY__JPG=0)
            {
                try {
                    $QUALITY__JPG = $QUALITY__JPG ?: (new static)->QUALITY__JPG;
                    $ext = pathinfo(DIR.PHOTOS.'/'.$img_current, PATHINFO_EXTENSION);
                    $img_new = replace('.'.$ext, '.jpg', $img_new);

                    // PNG
                        if (lower($ext) == 'png') {
                            $img_1 = imagecreatetruecolor($getimagesize[0], $getimagesize[1]);
                            $img_2 = @imagecreatefrompng(DIR_P.PHOTOS.'/'.$img_current);
            
                            $white = imagecolorallocate($img_1, 255, 255, 255);
                            imagefill($img_1, 0, 0, $white);
            
                            imageCopy($img_1, $img_2, 0, 0, 0, 0, $getimagesize[0], $getimagesize[1]);
                            imagejpeg($img_1, DIR_P.$dir.$img_new, (new static)->QUALITY__WEBP);
            
                            imageDestroy($img_1);
                            imageDestroy($img_2);
                        }
                    // PNG

                    // JPG
                        else {
                            $canvas = self::convert_config($img_current, $width_final, $height_final, $getimagesize);
                            imagejpeg($canvas, DIR_P.$dir.$img_new, $QUALITY__JPG);
                            imagedestroy($canvas);
                        }
                    // JPG

                    return $img_new;

                } catch (\Throwable $th) {}

                return false;
            }
        // TO JPG


        // TO PNG
            private static function convert_to_png($img_current, $img_new, $width_final, $height_final, $dir, $getimagesize='', $QUALITY__PNG=0)
            {
                try {
                    $QUALITY__PNG = $QUALITY__PNG ?: (new static)->QUALITY__PNG;
                    $ext = pathinfo(DIR.PHOTOS.'/'.$img_current, PATHINFO_EXTENSION);
                    $img_new = replace('.'.$ext, '.png', $img_new);

                    $canvas = self::convert_config($img_current, $width_final, $height_final, $getimagesize);

                    imagepng($canvas, DIR_P.$dir.$img_new, $QUALITY__PNG);
                    imagedestroy($canvas);

                    return $img_new;

                } catch (\Throwable $th) {}

                return false;                
            }
        // TO PNG


        // TO WEBP
            private static function convert_to_webp($img_current, $img_new, $width_final, $height_final, $dir, $getimagesize='', $QUALITY__WEBP=0)
            {
                try {
                    $QUALITY__WEBP = $QUALITY__WEBP ?: (new static)->QUALITY__WEBP;
                    $ext = pathinfo(DIR.PHOTOS.'/'.$img_current, PATHINFO_EXTENSION);
                    $img_new = replace('.'.$ext, '.webp', $img_new);

                    $canvas = self::convert_config($img_current, $width_final, $height_final, $getimagesize);

                    imagepalettetotruecolor($canvas);
                    imagealphablending($canvas, true);
                    imagesavealpha($canvas, true);

                    $img_new = replace('.'.$ext, '.webp', $img_new);
                    imagewebp($canvas, DIR_P.$dir.$img_new, $QUALITY__WEBP);
                    imagedestroy($canvas);

                    return $img_new;

                } catch (\Throwable $th) {}

                return false;
            }
        // TO WEBP


        // BASE64
            private static function convertar_img_to_base64($foto)
            {
                $type = pathinfo($foto, PATHINFO_EXTENSION);
                $return = 'data:image/'.$type.';base64,'.base64_encode(file_get_contents(DIR_P.PHOTOS.'/'.$foto));
                return $return;
            }

            private static function convertar_base64_to_img($foto, $table, $nome='')
            {
                $ex = explode(';base64,', $foto);
                $return = not('accents_all', $table).'_'.($nome ? $nome : time()).rand().'.jpg';
                $file = fopen(DIR_P.PHOTOS.'/'.$return, 'w');
                fwrite($file, base64_decode($ex[1]));
                fclose($file);
                return $return;
            }
            private static function convertar_base64_to_img1($base64, $nome='')
            {
                $ex = explode(';base64,', $base64);
                $return = $nome;
                if (isset($ex[1])) {
                    $file = fopen(DIR_P.PHOTOS.'/'.$return, 'w');
                    fwrite($file, base64_decode($ex[1]));
                    fclose($file);
                }
                return $return;
            }
        // BASE64
    // CONVERT

}