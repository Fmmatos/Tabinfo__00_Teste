<?php

namespace Root\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vendor\Models\Products;

class HomeController
{

    // INDEX
        public function index(Request $request): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['products'] = Products::select(['*'])->get();

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // INDEX










    // CREATE / EDIT
        public function create_edit(Request $request, $id=0): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['products'] = Products::find_id($id);

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // CREATE / EDIT










    // STORE
        public function store(Request $request): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['products'] = Products::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // STORE










    // UPDATE
        public function update(Request $request, $id): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['products'] = Products::find_id($id)->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // UPDATE










    // DELETE
        public function delete(Request $request, $id): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['products'] = Products::find_id($id)->delete();

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // DELETE

}