<?php

namespace Vendor\Services\Root\Api\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vendor\Models\Customers;

class __FacebookService
{
    private static $url = "https://graph.facebook.com/v22.0";

    // AUTH
        // FACEBOOK_ID
            public static function auth_id(Request $request): array|object
            {
                COOKIES_CREATE('FACEBOOK_ID', $request->user()->id);
                COOKIES_CREATE('FACEBOOK_URL', DIR.$request['GET']['ALL']);

                $arr['url'] = 'https://www.facebook.com/v22.0/dialog/oauth?'.http_build_query([
                    'client_id' => '1364604197899421',
                    'redirect_uri' => 'https://club.futury.com.br/api/facebook/auth',
                    'scope' => 'instagram_basic,pages_show_list',
                ]);

                $arr['status'] = 200;
                return json_encode__($arr);
            }
        // FACEBOOK_ID


        // AUTH
            public static function auth(Request $request): void
            {
                $code = $request->get('code');
                if (!$code) {
                    echo 'Código de autorização não encontrado.';
                    exit();
                }

                $tokenResponse = Http::get(self::$url.'/oauth/access_token', [
                    'client_id'     => env('FACEBOOK_CLIENT_ID'),
                    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                    'redirect_uri'  => env('FACEBOOK_REDIRECT_URI'),
                    'code'          => $code
                ]);
                if ($tokenResponse->failed()) {
                    echo 'Erro ao obter token de acesso.';
                    exit();
                }

                Customers::find_id(COOKIES('FACEBOOK_ID'))->update([
                    'facebook_login_token' => $tokenResponse->json('access_token') ?? ''
                ]);

                echo '<script>window.location.href = `'.COOKIES('FACEBOOK_URL').'?instagram_accounts=1`;</script>';
            }
        // AUTH
    // AUTH





    // INSTAGRAM
        // ACCOUNTS
            public static function instagram_accounts(Request $request): array|object
            {
                $arr['OBJ']['pages'] = [];
                $endpoint = self::$url.'/me/accounts?limit=100&access_token='.(Customers::select(['facebook_login_token'])->find(40)->facebook_login_token ?? '');

                do {
                    $response = json_decode(file_get_contents($endpoint));
                    
                    if (isset($response->data)) {
                        foreach ($response->data as $page) {
                            $pictureEndpoint = self::$url . '/' . $page->id . '?fields=picture&access_token=' . $page->access_token;
                            $pictureData = json_decode(@file_get_contents($pictureEndpoint));
                            $page->picture = $pictureData->picture->data->url ?? null;
            
                            $arr['OBJ']['pages'][] = $page;
                        }
                    }
            
                    $endpoint = $response->paging->next ?? null;
                } while ($endpoint);

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // ACCOUNTS


        // BUSINESS
            public static function instagram_business(Request $request): array|object
            {
                $page = '1507902006107752';

                $response = Http::get(self::$url.'/'.$page, [
                    'fields' => 'instagram_business_account',
                    'access_token' => Customers::select(['facebook_login_token'])->find(40)->facebook_login_token ?? ''
                ]);
                $array = json_decode($response->body());

                if ($array) {
                    $arr['array'] = $array;
                    $arr['status'] = 200;

                } else {
                    $arr['error'][]  = 'Conta Instagram Business não encontrada!';
                }

                return json_encode__($arr, $request);
            }
        // BUSINESS


        // FOLLOWERS
            public static function instagram_followers(Request $request): array|object
            {
                $business = '1507902006107752';

                $response = Http::get(self::$url.'/'.$business, [
                   'fields' => 'username,followers_count',
                   'access_token' => Customers::select(['facebook_login_token'])->find(40)->facebook_login_token ?? ''
                ]);
                $array = json_decode($response->body());

                if ($array) {
                    $arr['array'] = $array;
                    $arr['status'] = 200;

                } else {
                    $arr['error'][]  = 'Conta não encontrada!';
                }

                return json_encode__($arr, $request);
            }
        // FOLLOWERS
    // INSTAGRAM

}
