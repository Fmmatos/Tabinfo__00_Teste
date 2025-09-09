import { load, load_close, cod, FormData__, json_encode, split, request_obj, alerts, not } from '@/vendor/services/events';
import { DIR, DIR_API, LOCALHOST, PROG } from '@/vendor/services/localhost';
import { rootAuth, logout, adminAuth } from '@/vendor/storages/auth';


    // API
        // $errors_show -> Mostrar erros
        // $timeout -> abortar apos x segs
        // $run_again -> quandas vezes ira rodar a api
        // controller -> controlar a api para abortar (class AbortController default, mais pode criar outras)
        const api = async (url: string, data: Record<string, any> = {}, $function: Function | string | null = null, $load: number = 1, $errors_show: number = 1, $timeout: number = 0, $run_again: number = 0, controller: AbortController = new AbortController()): Promise<any> => {
            data = {...data};
            $load ? load($load) : ``;

            const { signal } = controller;

            const api_old = () => api(url, data, $function, $load, $errors_show, $timeout, $run_again-1, controller);
            let fechar = 1;

            // AUTH
                let header: Record<string, string> = { 'Content-type': `application/json`, 'Accept': `application/json` };

                // ROOT
                    let token = rootAuth();
                    if (token){
                        header.Authorization = `Bearer ${token}`;
                    }
                // ROOT

                // ADMIN
                    let token_admin = adminAuth();
                    if (token_admin){
                        header[`Authorization-admin`] = token_admin;
                    }
                // ADMIN
            // AUTH

            // HEADER
                if (data.header){
                    header = data.header;
                    data.header = null;
                }

                // GET
                    let headers: RequestInit = {
                        method: "GET",
                        headers: header,
                        credentials: "include",
                        signal: signal
                    }
                    let headers_teste = headers;
                // GET


                if (data){
                    // ZIP
                        if (data?.application_zip){
                            header = { 'Content-Type': `application/json`, 'Accept': `application/zip` }
                            if (token){
                                header.Authorization = `Bearer ${token}`
                            }
                            if (token_admin){
                                header.admin = token_admin
                            }

                            headers = {
                                method: 'POST',
                                body: JSON.stringify(data),
                                headers: header,
                                credentials: "include",
                                signal: signal,
                            }
                        }
                    // ZIP

                    // FORMDATA
                        else if (data.FORM != null || data.v != null){
                            let data_new = data.FORM != null ? {...data.FORM.v} : {...data.v};
                            data_new = treatment_ini(data_new, url)
                            let $FormData = FormData__(data_new);

                            header = { 'Accept': `application/json` }
                            if (token){
                                header.Authorization = `Bearer ${token}`
                            }
                            if (token_admin){
                                header.admin = token_admin
                            }

                            headers = {
                                method: 'POST',
                                body: $FormData,
                                headers: header,
                                // headers: token ? { "Authorization": `Bearer ${token}`, 'Accept': `application/json` } : { 'Accept': `application/json` },
                                credentials: "include",
                                signal: signal,
                            }
                        }
                    // FORMDATA

                    // POST / PUT / DELETE
                        else {
                            data = treatment_ini(data, url)
                            headers = {
                                method: 'POST',
                                body: JSON.stringify(data),
                                headers: header,
                                credentials: "include",
                                signal: signal,
                            }
                        }
                    // POST / PUT / DELETE

                    // HEADERS_teste
                        let data_teste = treatment_ini(data, url);
                        if (data.FORM != null){
                            data_teste = data_teste.FORM.v;
                        } else if (data.v != null){
                            data_teste = data_teste.v;
                        }
                        headers_teste = {
                            method: 'POST',
                            body: JSON.stringify(data_teste),
                            headers: header,
                            credentials: "include",
                            signal: signal,
                        }
                    // HEADERS_teste
                }
            // HEADER

            let req__: Response | undefined;
            let json__: any;
            let text__: string | undefined;
            let fetchPromise: Promise<Response>;
            let timeoutPromise: Promise<void> | undefined;

            try {
                // console.log(url); console.log(headers);
                
                fetchPromise = fetch(treatment_url(url), headers);

                timeoutPromise = new Promise((_, reject) => {
                    if ($timeout>0){
                        setTimeout(() => {
                            controller.abort();
                            reject(new Error(`Request timed out: ${url}`));
                        }, $timeout)
                    }
                });

                req__ = await Promise.race([fetchPromise, timeoutPromise]) as Response;

                // ZIP
                    if (data.application_zip) {
                        // ERROR
                            if (!req__.ok) {
                                if ($load) load_close();
                                
                                // 500
                                if (req__.status === 500) {
                                    alerts(0, 'Erro interno do servidor ao gerar o ZIP');
                                    return false;
                                }
                                // 500

                                // ERROR
                                    let errJson: any;
                                    try {
                                        errJson = await req__.json();
                                    } catch {
                                        alerts(0, 'Erro desconhecido ao baixar arquivos');
                                        return false;
                                    }
                                    alerts(0, errJson.error || 'Erro ao baixar arquivos');
                                
                                    return false;
                                // ERROR
                            }
                        // ERROR

                        // 200
                            const zipBlob = await req__.blob();
                            if ($load) load_close();
                            if (typeof $function === 'function') {
                                ($function as Function)(zipBlob);
                            }
                            return zipBlob;
                        // 200
                    }
                // ZIP

                // CLONE TEXT
                    const req__clone = req__.clone();
                    text__ = await req__clone.text();
                // CLONE TEXT

                json__ = await req__.json();
                // console.log(json__);

                if ($load){
                    load_close();
                }
                return computed(req__, json__, api_old, url, headers, headers_teste, fechar, $function, $errors_show, $run_again);

            } catch(e){
                // if (req__ != null){
                //     (req__ as any).text__ = text__;
                // }
                // const callableFunction = typeof $function === 'function' ? $function : null;
                // error_log(req__, json__, e, api_old, url, headers, headers_teste, fechar, callableFunction, $errors_show);
                load_close();
            }
        }

        const treatment_ini = (data: Record<string, any>, url: string): Record<string, any> => {
            data.GET = $_GET;

            // ARRAY | CHECK
                for (const [key, value] of Object.entries(data)) {
                    if (Array.isArray(value)) {
                        data[key] = json_encode(value, 'object');
                    }
                }
            // ARRAY | CHECK

            return data;
        }

        const treatment_url = (url: string): string => {
            if (!split(`http`, url)){
                url = `${DIR_API()}${url}`;
            }

            return url;
        }
    // API










    // COMMPUTED
        const computed = (req: Response, json: any, api_old: Function, url: string, headers: RequestInit, headers_teste: RequestInit, fechar: number, $function: Function | string | null, $errors_show: number, $run_again: number): any => {
            // RESET
                if ($function == `request_init__`){
                    $_GLOBAL.OBJ.menu_admin = {};
                    $_GLOBAL.OBJ.DATATABLE = {};
                    $_GLOBAL.OBJ.PAGG = {};
                }
            // RESET


            // EVENTS
                if (req.status != 200){
                    if ($run_again>0){ setTimeout(() => { api_old(); }, 50); }
                }
            // EVENTS


            // 200
                if (req.status == 200){
                    // CLOSE __ERRORS__
                        let e = document.querySelector(`.__EVENTS__ .__ERRORS__`) as HTMLElement;
                        if (e) {
                            const child = e.firstElementChild as HTMLElement | null;
                            if (child) {
                              const term = not('accents_all', url);
                              const cls = child.className.trim();
                          
                              if (cls === term) {
                                e.innerHTML = '';
                              }
                            }
                          }
                    // CLOSE __ERRORS__

                    // if (json?.logout){
                    //     logout();
                    //     return false;

                    // } else {
                        treatment(req, json, api_old, url, headers, headers_teste, fechar, $function as ((json: any) => void) | string | null, $errors_show);
                        return json;
                    // }
                }
            // 200

            // 400 (Bad Request)
                else if (req.status == 400){
                    alerts(0, `O servidor está com instabilidade. Tente acessar novamente em alguns segundos! Caso não consigo entre em contato como o suporte!`);
                }
            // 400 (Bad Request)

            // 401 (Unauthorized)
                else if (req.status == 401){
                    logout();
                    return false;
                }
            // 401 (Unauthorized)

            // 403 (Forbidden)
                else if (req.status == 403){
                    logout();
                    return false;
                }
            // 403 (Forbidden)

            // 404 (Not Found)
                else if (req.status == 404){
                    //alerts(0, `Página não encontrada!`);
                    $_GET['PG'] = 'zzz';
                }
            // 404 (Not Found)

            // 405 (Method Not Allowed)
                else if (req.status == 405){
                    alerts(0, `Método não permitido!`);
                }
            // 405 (Method Not Allowed)

            // 422 (Unprocessable Content)
                else if (req.status == 422){
                    treatment(req, json, api_old, url, headers, headers_teste, fechar, null, 0);
                }
            // 422 (Unprocessable Content)

            // 429 Too Many Requests
                else if (req.status == 429){
                    if ($errors_show){
                        alert(`Você fez muitas requisições em um espaço muito curto de tempo! Por segurança o servidor está limitando o número de requisições. Tente acessar novamente mais tarde!`);
                    }
                }
            // 429 Too Many Requests

            // 500 (Internal Server Error)
                // else if (req.status == 500 || req.status == 502 || req.status == 503 || req.status == 504){
                //     alerts(0, `O servidor está com instabilidade. Tente acessar novamente em alguns segundos! Código: ${req.status}.`);
                // }
            // 500 (Internal Server Error)

            // ELSE
                else {
                    const callableFunction = typeof $function === 'function' ? $function : null;
                    error_log(req, json, ``, api_old, url, headers, headers_teste, fechar, callableFunction, $errors_show);
                    return false;
                }
            // ELSE
        }
    // COMMPUTED










    // TREATMENT
        const treatment = (req: Response, json: any, api_old: Function, url: string, headers: RequestInit, headers_teste: RequestInit, fechar: number, $function: ((json: any) => void) | string | null = null, $errors_show: number): void => {
            // ERRORS
                if ((json.error && json.error != null) && !(json.errors && json.errors != null)){
                    json.errors = json.error;
                }
                if ((json.errors && json.errors != null) || (json.message && json.message != null && json.alert == null)){

                    // ERRORS
                        if (Array.isArray(json.errors) ||  json.message && json.message != null){
                            if (json.errors == null){
                                json.errors = [`${json.message}`];
                            }
                            document.querySelectorAll('.__EVENTS__ .alerts').forEach(item => { item.innerHTML = '' });
                            if (json?.errors != null){
                                for(let $key in json.errors){
                                    let item = json.errors[$key];
                                    if (Array.isArray(item)){
                                        for(let $value_1 of item){
                                            alerts(0, $value_1, 1);
                                        }
                                    } else {
                                        alerts(0, item, 1);
                                    }
                                }
                            }
                        }
                    // ERRORS

                    // ERROR_LOG
                        else {
                            const callableFunction = typeof $function === 'function' ? $function : null;
                            error_log(req, json, json.errors, api_old, url, headers, headers_teste, fechar, callableFunction, $errors_show);
                            return;
                        }
                    // ERROR_LOG
                }
            // ERRORS

            // LOCATION
                else if (json.location != null && json.location){
                    //$_GLOBAL.ROUTER.push(json.location);
                    window.location.href = `${DIR()}${json.location}`;
                }
            // LOCATION

            // VERIFICAR STATUS DO REQUEST
                else if (json?.status != 200){
                    alerts(0, `Ocoreu algum erro na resposta da requisição!`);
                }
            // VERIFICAR STATUS DO REQUEST

            // ELSE
                else if ($function != `request_init__`){
                    // OBJ
                        request_obj(json);
                    // OBJ

                    // FUNCTION
                        if (typeof $function === 'function') {
                            $function(json);
                        }
                    // FUNCTION
                }
                else if ($function == `request_init__`){
                    $_GET['dashboard_modules'] = json.dashboard_modules ? json.dashboard_modules : 0;
                }

            // ELSE


            // ALERTS
                if ((json.alert != null || json.alerts != null) && json.errors == null){
                    if (json.alerts){
                        json.alert = json.alerts;
                    }
                    if (json.msg){
                        alerts(json.alert, json.msg, 0, json.delay);
                    } else if (json.message){
                        alerts(json.alert, json.message, 0, json.delay);
                    } else if (json.alert){
                        alerts(1, (json.alert==1 ? '': json.alert), 0, json.delay);
                    } else {
                        alerts(0, '', 0, json.delay);
                    }
                }
            // ALERTS
        }
    // TREATMENT










    // ERROR_LOG
        const error_log = (req: Response | undefined, json: any, e: any, api_old: Function, url: string, headers: RequestInit, headers_teste: RequestInit, fechar: number, $function: Function | null, $errors_show: number): void => {
            if ($errors_show){
                error_box_alert(req, json, e, api_old, url, headers, headers_teste, fechar, $function);
                //e ? console.log(`API =>`) : ``;
                //e ? console.log(e) : ``;
            }
        }
    // ERROR_LOG










    // ERROR_BOX_ALERT
        const error_box_alert = async (req: Response | undefined, json: any, e: any, api_old: Function, url: string, headers: RequestInit, headers_teste: RequestInit, fechar: number, $function: Function | null): Promise<void> => {

            e = cod(`asc`, e.toString());

            let txt = 'Não foi possível completar a solicitação, tente novamente mais tarde. <br>Em caso de dúvida entre em contato.';
            if (req?.status == 500 || req?.status == undefined){
                txt = 'Não foi possível completar a solicitação, verifique a sua conexão com internet. <br>Em caso de dúvida entre em contato.';
            }

            let $html = ``;
            $html += `<div clas="${not(`accents_all`, url)}"> `;
                $html += fechar ? `<a class="fechar"><img src="${require('@/vendor/assets/img/svg/default/close.svg')}" class="h20" /></a>` : ``;
                $html += `<div> `;
                    $html += `<b>Atenção</b> `;
                    $html += `<span>${txt}</span> `;
                    $html += `<button>Tentar novamente</button> `;
                $html += `</div> `;

                $html += `<div> `;
                    $html += `<div class="pb5 fwb4 flexx flex_j flex_ac"> `;
                        $html += `<div class=""> `;
                            if (LOCALHOST() || PROG()){
                                $html += `<form method="POST" action="${DIR_API()}/curl_error" target="_blank" > `;
                                    $html += `<input name="url" value='${DIR_API()}${url}' class="dni" /> `;
                                    $html += `<input name="json" value='${json_encode(headers_teste)}' class="dni" /> `;
                                    $html += `<button class="p0 m0 bd0i bg0i">Ver Erro</button> `;
                                $html += `</form> `;
                            }
                        $html += `</div> `;
                        $html += `<div class="c-p c_blue_2 link mais_detalhes">mais detalhes</div> `;
                    $html += `</div> `;
                    $html += `<div class="p10 o-a mais_detalhes_box ${(LOCALHOST() || PROG()) ? '' : 'dnx'}" style="max-width: ${window.innerWidth-40}px; max-height: 500px; border: 1px solid #ccc; border-radius: 5px"> `;
                        $html += (req?.status!=null || req?.statusText!=null) ? `<div class="">${req.status} - ${req?.statusText}</div> ` : `<div class="">500 - Internal Server Error!</div> `;
                        $html += e ? `<code class="pt10">${e}</code> ` : ``;
                        if (e && req) {
                            try {
                                const responseText = await req.text();
                                $html += `<code class="pt10">${responseText}</code>`;
                            } catch {
                                $html += `<code class="pt10">Erro ao obter o texto da resposta.</code>`;
                            }
                        }
                        $html += `<div class="pt10"> `;
                            $html += json?.file ? `<div class="">File: ${json.file}</div> ` : ``;
                            $html += json?.line ? `<div class="pt2">Line: ${json.line}</div> ` : ``;
                            $html += json?.message ? `<div class="pt2">Message: ${json.message}</div> ` : ``;
                        $html += `</div> `;
                    $html += `</div> `;
                $html += `</div> `;

            $html += `</div> `;

            const $e = document.querySelector(`.__EVENTS__ .__ERRORS__`) as HTMLElement;
            if ($e) {
                $e.innerHTML = $html;

                const button = $e.querySelector('button') as HTMLButtonElement;
                if (button) {
                    button.addEventListener('click', (e: MouseEvent) => {
                        const $e = document.querySelector(`.__EVENTS__ .__ERRORS__`) as HTMLElement;
                        if ($e) {
                            $e.innerHTML = '';
                        }
                        api_old();
                    });
                }
            }

            const closeLink = $e.querySelector('a.fechar') as HTMLAnchorElement;
            if (closeLink) {
                closeLink.addEventListener('click', (e: MouseEvent) => {
                    const $e = document.querySelector(`.__EVENTS__ .__ERRORS__`) as HTMLElement;
                    if ($e) {
                        $e.innerHTML = '';
                    }
                });
            }

            const moreDetailsButton = $e.querySelector('.mais_detalhes') as HTMLElement;
            if (moreDetailsButton) {
                moreDetailsButton.addEventListener('click', (e: MouseEvent) => {
                    const detailsBox = document.querySelector(`.__EVENTS__ .__ERRORS__ .mais_detalhes_box`) as HTMLElement;
                    if (detailsBox) {
                        if (detailsBox.classList.contains('dn')) {
                            detailsBox.classList.remove('dn');
                        } else {
                            detailsBox.classList.add('dn');
                        }
                    }
                });
            }
        }
    // ERROR_BOX_ALERT



export default api;