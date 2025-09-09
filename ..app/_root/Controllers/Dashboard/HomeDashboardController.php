<?php

namespace Root\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Root\Models\Campaigns;
use Root\Models\CampaignsCustomers;
use Vendor\Models\Products;
use Vendor\Models\ProductsBrands;
use Vendor\Models\ProductsCategories;

class HomeDashboardController
{

    // INDEX
        public function index(Request $request): JsonResponse
        {
            $arr = [];

            // $arr['OBJ']['DIR'] = DIR_LINK;

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // INDEX

}