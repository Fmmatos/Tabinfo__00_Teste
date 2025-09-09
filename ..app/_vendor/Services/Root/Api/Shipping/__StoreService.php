<?php

namespace Vendor\Services\Root\Api\Shipping;

use Vendor\Models\XSettings;

class __StoreService
{

    // INDEX
        public static function index(object $array, array $return): array
        {
            $return[] = (object)[
                'id' => 'store',
                'name' => 'Retirar na Loja',
                'price' => price(0),
                'price__' => 0,
                'api' => 'store',
            ];

            return $return;
        }
    // INDEX

}
