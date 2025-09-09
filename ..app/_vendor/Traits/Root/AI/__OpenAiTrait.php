<?php

namespace Vendor\Traits\Root\AI;

use Illuminate\Support\Facades\Http;
use Root\Models\MessagesIaLog;

trait __OpenAiTrait
{
    private static $token = '';

    // ENDPOINT
        protected static function endpoint(array $data, string $endpoint): mixed
        {
            if (!self::$token) { (new static)::$token = env('OPENAI_API_KEY'); }

            // HTTP
                // V
                    $v = 'v1';
                // V


                // HEADERS
                    $headers = [
                        'Authorization' => "Bearer ".self::$token,
                        'Content-Type' => 'application/json',
                    ];

                    if (compare__('/threads', $endpoint) || compare__('/assistants', $endpoint) || compare__('/vector_stores', $endpoint) || compare__('/embeddings', $endpoint)) {
                        $headers['OpenAI-Beta'] = 'assistants=v2';
                    }
                    // if (compare__('/assistants', $endpoint) && compare__('/files', $endpoint)) {
                    //     $headers['OpenAI-Beta'] = 'assistants=v1';
                    // }
                // HEADERS


                // ENDPOINT
                    // GET
                        if (isset($data[0]) && $data[0] == 'get') {
                            $response = Http::withHeaders($headers)->get('https://api.openai.com/'.$v.ltrim($endpoint));
                        }
                    // GET


                    // DELETE
                        else if(isset($data[0]) && $data[0] == 'delete') {
                            $response = Http::withHeaders($headers)->delete('https://api.openai.com/'.$v.ltrim($endpoint));
                        }
                    // DELETE

                    
                    // FILES
                        else if ($endpoint == '/files') {
                            unset($headers['Content-Type']);
                            unset($headers['OpenAI-Beta']);
                            $response = Http::withHeaders($headers)->attach('file', file_get_contents($data['file']->getFilename()), $data['file']->getPostFilename())->post('https://api.openai.com/'.$v.ltrim($endpoint), ['purpose' => $data['purpose']]);
                        }
                    // FILES

                    
                    // VECTOR_STORES
                        // else if (compare__('/vector_stores', $endpoint) && compare__('/files', $endpoint)) {
                        //     unset($headers['Content-Type']);
                        //     $response = Http::withHeaders($headers)->attach('file', file_get_contents($data['file']->getFilename()), $data['file']->getPostFilename())->post('https://api.openai.com/'.$v.ltrim($endpoint));
                        // }
                    // VECTOR_STORES

                    // POST
                        else {
                            $response = Http::withHeaders($headers)->post('https://api.openai.com/'.$v.ltrim($endpoint), $data);
                        }
                    // POST

                    $array = json_decode($response->body());
                // ENDPOINT
            // HTTP





            // INFO
                if (LOCALHOST || PROG) {
                    if(!isset($_GET['openai__'])){
                        $_GET['openai__'] = 0;
                    }

                    unset($headers['Authorization']);
                    GET_pre_fixed_set([
                        'endpoint' => $endpoint,
                        'headers' => $headers,
                        'data' => $data,
                        'response' => $array,
                        'text' => $array->choices[0]->message->content ?? '',
                        'error' => $array->last_error ?? '',
                    ], 'openai__'.$_GET['openai__'].'__'.ltrim($endpoint));
                    $_GET['openai__']++;
                }

                // MessagesIaLog::create([
                //     'endpoint' => $endpoint,
                //     'data' => json_encode($data),
                //     'array' => json_encode($array),
                //     'text' => $array->choices[0]->message->content ?? '',
                //     'error' => isset($array->last_error) ? json_encode($array->last_error) : null,
                // ]);
            // INFO





            // JSON
                // $file = fopen(DIR_D.'/json/openai/'.date('Y-m-d H.i.s').'.json', 'w');
                // fwrite($file, json_encode([ 'endpoint' => $endpoint, 'data' => $data, 'array' => $array, 'array' => $array->choices[0]->message->content ?? '', 'error' => $array->last_error ?? '' ]));
                // fclose($file);
            // JSON





            // RESPONSE
                // OK
                    if (isset($array->choices[0]->message->content)) {
                        $return = $array->choices[0]->message->content;
                    }

                    else if(isset($array->choices[0]->text)) {
                        $return = $array->choices[0]->text;
                    }

                    else if(isset($array->id) || isset($array->data)) {
                        $return = $array;
                    }
                // OK


                // ERROR
                    else {
                        GET_pre_fixed_set($array, 'openai_error', 1);
                        $return = $array;
                    }
                // ERROR
            // RESPONSE

            return $return;
        }
    // ENDPOINT

}
