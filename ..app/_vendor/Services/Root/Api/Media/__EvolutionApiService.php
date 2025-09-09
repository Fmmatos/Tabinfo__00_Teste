<?php

namespace Vendor\Services\Root\Api\Media;

use Illuminate\Http\Request;
use Vendor\Traits\Root\Media\__EvolutionApiTrait;

class __EvolutionApiService
{
    use __EvolutionApiTrait;


    // MESSAGES
        // SEND
            public static function messages_send(Request $request, array $data): array|object
            {
                $data = [
                    'number' => '+55'.phone_complete($data['number']),
                    'text' => $data['text'],
                ];

                // ENDPOINT
                    return self::endpoint($data, '/message/sendText');
                // ENDPOINT
            }
        // SEND
    // MESSAGES

}
