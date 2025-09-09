<?php

namespace Root\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class NEW__UploadService
{


        public static function treatment_type_image(?UploadedFile $file, string $ext = '', ?object $menu_admin = null, string $key, bool $passar): bool
        {
            // if (lower($ext) == 'xxxxxxxxxxxxxxx') $passar = 1;

            return $passar;
        }

        public static function treatment_type_file(?UploadedFile $file, string $ext = '', ?object $menu_admin = null, string $key, bool $passar): bool
        {
            if (lower($ext) == 'ai') $passar = 1;
            if (lower($ext) == 'cdr') $passar = 1;

            return $passar;
        }

}