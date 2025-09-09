<?php
    use Illuminate\Support\Facades\DB;


    // META
        require_once __DIR__.'/../../..app/laravel/vendor/autoload.php';
        $app = require_once __DIR__.'/../../..app/laravel/bootstrap/app.php';

        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        $x_settings = DB::table('x_settings')
        ->where('fields', 'name_site')
        ->orWhere('fields', 'meta_title')
        ->orWhere('fields', 'meta_keywords')
        ->orWhere('fields', 'meta_description')
        ->orWhere('fields', 'image_favicon')
        ->orWhere('fields', 'image_sharing')
        ->orWhere('fields', 'image_sharing_mime')
        ->orWhere('fields', 'image_sharing_width')
        ->orWhere('fields', 'image_sharing_height')
        ->get();

        foreach ($x_settings as $key => $value) {
            if($value->fields == 'name_site' AND $value->value){
                $name_site = $value->value;
            }
            elseif($value->fields == 'meta_title' AND $value->value){
                $meta_title = $value->value;
            }
            elseif($value->fields == 'meta_keywords' AND $value->value){
                $meta_keywords = $value->value;
            }
            elseif($value->fields == 'meta_description' AND $value->value){
                $meta_description = $value->value;
            }
            elseif($value->fields == 'image_favicon' AND $value->value){
                $array = json_decode($value->value);
                if(isset($array[0]->file) AND $array[0]->file){
                    $icon = DIR.PHOTOS.'/'.$array[0]->file;
                }
            }
            elseif($value->fields == 'image_sharing' AND $value->value){
                $array = json_decode($value->value);
                if(isset($array[0]->file) AND $array[0]->file){
                    $image_sharing = DIR.PHOTOS.'/'.$array[0]->file;
                }
            }
            elseif($value->fields == 'image_sharing_mime' AND $value->value){
                $image_mime = $value->value;
            }
            elseif($value->fields == 'image_sharing_width' AND $value->value){
                $image_sharing_width = $value->value;
            }
            elseif($value->fields == 'image_sharing_height' AND $value->value){
                $image_sharing_height = $value->value;
            }
        }

        $__META__ .= '<title>'.(isset($meta_title) ? limit($meta_title, 70) : '').'</title>';
        $__META__ .= '<meta name="description" content="'.(isset($meta_description) ? limit($meta_description, 160) : '').'" /> ';
        $__META__ .= '<meta name="keywords" content="'.(isset($meta_keywords) ? $meta_keywords : '').'" /> ';
        $__META__ .= '<meta name="robots" content="index, follow" /> ';
        $__META__ .= '<link rel="canonical" href="'.DIR.'"> ';
        $__META__ .= '<meta name="robots" content="index, follow" />';
        //$__META__ .= '<<link rel="sitemap" href="sitemap.xml"> ';

        $__META__ .= '<meta itemprop="name" content="'.(isset($meta_title) ? limit($meta_title, 70) : '').'"> ';
        $__META__ .= '<meta itemprop="description" content="'.(isset($meta_description) ? limit($meta_description, 160) : '').'"> ';
        $__META__ .= '<meta itemprop="url" content="'.DIR.'"> ';

        $__META__ .= '<meta property="og:locale" content="pt_BR" /> ';
        $__META__ .= '<meta property="og:type" content="article" /> ';
        $__META__ .= '<meta property="og:site_name" content="'.(isset($name_site) ? limit($name_site, 70) : '').'" /> ';
        $__META__ .= '<meta property="og:title" content="'.(isset($meta_title) ? limit($meta_title, 70) : '').'" /> ';
        $__META__ .= '<meta property="og:description" content="'.(isset($meta_description) ? limit($meta_description, 70) : '').'" /> ';
        $__META__ .= '<meta property="og:url" content="'.DIR.'" /> ';

        if(isset($icon) AND $icon){
            $__META__ .= '<link rel="shortcut icon" href="'.$icon.'" type="image/x-icon" /> ';
        } else {
            $__META__ .= '<link rel="shortcut icon" href="'.DIR.'/img/ico.ico" type="image/x-icon" /> ';
        }

        if(isset($image_sharing) AND $image_sharing AND isset($image_mime) AND $image_mime AND isset($image_sharing_width) AND $image_sharing_width AND isset($image_sharing_height) AND $image_sharing_height){
            $__META__ .= '<meta property="og:image" content="'.$image_sharing.'" /> ';
            $__META__ .= '<meta property="og:image:secure_url" content="'.$image_sharing.'" /> ';
            $__META__ .= '<meta property="og:image:alt" content="'.(isset($meta_title) ? $meta_title : '').'" /> ';
            $__META__ .= '<meta property="og:image:type" content="'.$image_mime.'" /> ';
            $__META__ .= '<meta property="og:image:width" content="'.$image_sharing_width.'" /> ';
            $__META__ .= '<meta property="og:image:height" content="'.$image_sharing_height.'" /> ';
        }
    // META

?>