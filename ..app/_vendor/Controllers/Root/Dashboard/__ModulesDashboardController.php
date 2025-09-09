<?php

namespace Vendor\Controllers\Root\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vendor\Services\__ActionsService;
use Vendor\Controllers\Admin\__AdminController_Admin;

class __ModulesDashboardController
{
    protected string $pg;
    protected int $modulo;

    // __CONSTRUCT
        public function __construct(string $pg, int $modulo)
        {
            $this->pg = $pg;
            $this->modulo = $modulo;
        }
    // __CONSTRUCT





    // INDEX
        public function index(Request $request, int $id = 0): JsonResponse
        {
            $_GET['__NO_QUERY__DASHBOARD__'] = 1;
            $arr = __AdminController_Admin::index($request, $this->modulo, 2, true);
            $arr['OBJ']['menus'] = $_GET['menus__']??[];
            unset($_GET['__NO_QUERY__DASHBOARD__']);

            $arr['dashboard_modules'] = 'table';
            return json_encode__($arr, $request);
        }
    // INDEX










    // CREATE_EDIT
        public function create_edit(Request $request, int $id = 0): JsonResponse
        {
            $_GET['__NO_QUERY__DASHBOARD__'] = 1;
            $arr = __AdminController_Admin::create_edit($request, $this->modulo, $id, 2, true);
            unset($_GET['__NO_QUERY__DASHBOARD__']);

            $arr['dashboard_modules'] = 'edit';
            return json_encode__($arr, $request);
        }
    // CREATE_EDIT










    // STORE
        public function store(Request $request, int $id = 0): JsonResponse
        {
            $_GET['__NO_QUERY__DASHBOARD__'] = 1;
            $arr = __AdminController_Admin::store($request, $this->modulo, 2, true);
            unset($_GET['__NO_QUERY__DASHBOARD__']);
            return json_encode__($arr, $request);
        }
    // STORE










    // UPDATE
        public function update(Request $request, int $id): JsonResponse
        {
            $_GET['__NO_QUERY__DASHBOARD__'] = 1;
            $arr = __AdminController_Admin::update($request, $this->modulo, $id, 2, true);
            unset($_GET['__NO_QUERY__DASHBOARD__']);
            return json_encode__($arr, $request);
        }
    // UPDATE










    // DELETE
        public function delete(Request $request, int $id = 0): JsonResponse
        {
            $_GET['__NO_QUERY__DASHBOARD__'] = 1;
            $arr = __AdminController_Admin::delete($request, $this->modulo, $id, 2, true);
            unset($_GET['__NO_QUERY__DASHBOARD__']);
            return json_encode__($arr, $request);
        }
    // DELETE










    // ACTIONS
        public function actions(Request $request, string $type): JsonResponse
        {
            $_GET['__NO_QUERY__DASHBOARD__'] = 1;
            $arr = [];
            if ($type == 'order') {
                $arr = __ActionsService::order($request, $this->modulo);

            } else if($type == 'items_page_update') {
                // $arr = __ActionsService::items_page_update($request, $this->modulo);

            } else {
                $arr = __ActionsService::actions($request, $this->modulo, $type, __AdminController_Admin::class);
            }
            unset($_GET['__NO_QUERY__DASHBOARD__']);

            return json_encode__($arr, $request);
        }
    // ACTIONS

}