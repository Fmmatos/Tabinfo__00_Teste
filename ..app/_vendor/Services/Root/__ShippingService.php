<?php

namespace Vendor\Services\Root;

use Vendor\Models\XSettings;
use Vendor\Services\Root\Api\Shipping\__CorreiosService;
use Vendor\Services\Root\Api\Shipping\__MelhorEnvioService;
use Vendor\Services\Root\Api\Shipping\__StoreService;

class __ShippingService
{

    // INDEX
        public static function index(object $array): array
        {
            $return = [];
            $XSettings = XSettings::get__();


            // CORREIOS
                if ($XSettings->shippings_correios) {
                    $return = __CorreiosService::index($array, $return);
                }
            // CORREIOS


            // MELHOR_ENVIO
                if ($XSettings->shippings_melhor_envio) {
                    $return = __MelhorEnvioService::index($array, $return);
                }
            // MELHOR_ENVIO


            // RETIRAR_NA_LOJA
                if ($XSettings->shippings_retirar_na_loja) {
                    $return = __StoreService::index($array, $return);
                }
            // RETIRAR_NA_LOJA


            return $return;
        }
    // INDEX

}