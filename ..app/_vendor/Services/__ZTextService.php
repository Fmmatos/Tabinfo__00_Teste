<?php

namespace Vendor\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vendor\Models\ZText;
use Vendor\Services\__UploadService;

class __ZTextService
{

    // GET
        public static function get(array $data, mixed $model, int $id=0): array
        {
            $table__ = MODEL__ROOT__OR__ALL($model);
            if ($id) {
                $ZText = ZText::where('table__', $table__)->where('value', '<>', '')->where('id__', $id)->get(['id', 'id__', 'fields', 'value']);
            } else {
                $ZText = ZText::where('table__', $table__)->where('value', '<>', '')->get(['id', 'id__', 'fields', 'value']);
            }

            $return = [];
            foreach ($data as $key => $value) {
                foreach ($ZText as $key_1 => $value_1) {
                    if ($value['id'] == $value_1->id__) {

                        $value__ = base64_decode($value_1->value);
                        $value__ = replace(PHOTOS.'/', DIR.PHOTOS.'/', $value__);

                        $value[$value_1->fields] = $value__;
                    }
                }
                $return[$key] = $value;
            }

            return $return;
        }
    // GET





    // CLONE
        public static function clone(object $menu_admin, int $id, int $id_new): void
        {
            $get = ZText::where('table__', MODEL__ROOT__OR__ALL($menu_admin->table__))->where('id__', $id)->get();

            foreach ($get as $key => $value) {
                $array = [
                    'table__' => MODEL__ROOT__OR__ALL($menu_admin->table__),
                    'id__' => $id_new,
                    'fields' => $value->fields,
                    'value' => $value->value,
                ];
                ZText::create($array);
            }
        }
    // CLONE





    // CKEDITOR_UPLOAD
        public function ckeditor_upload(Request $request): JsonResponse
        {
            $arr = [];

            if ($request->hasFile('file')) {
                // TREATMENT
                    $size = __UploadService::treatment_size($request->file('file'));
                    $type = __UploadService::treatment_type($request->file('file'), 'image');
                // TREATMENT

                if ($size) {
                    $arr["erro"][] = $size;

                } else if($type) {
                    $arr["erro"][] = $type;

                } else {
                    $file = __UploadService::save('z_text', $request->file('file'), (object)['name' => 'cke']);
                    $arr["url"]['default'] = DIR.PHOTOS.'/'.$file['file'];
                }

	        } else {
				$arr["erro"][] = 'Não foi possivel subir esta imagem. Verifique se a imagem é menor que '.price_number(ALL__MAX_UPLOAD_IMAGE()/1000000).'MB e se a extensão é .jpg, .jpeg ou .png!';
	        }

            return response()->json($arr);
        }
    // CKEDITOR_UPLOAD

}