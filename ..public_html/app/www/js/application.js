
        // VARIAVEIS
        var $_APP = 2;
        var TOKEN_APP = `OPKFJWEIONUIEWNBCIOMEWIOPMEOPMEDWQOMKQWIOOMXASIOXMA`;

        var $_APP_URL__LOCAL = `/Sites/Tabinfo/00_Teste/public_html`;
        var $_APP_URL__ONLINE = `https://testelef.com.br/sites/tabinfo/00_teste/public_html`;

        var $_GET = {};
        var $_GLOBAL = {};
        var $_ABERTURA_JS = 0;

        // LOCALHOST
            var LOCALHOST = 0;
            if(window.location.href.split(`http://localhost:4000`)[1] || window.location.protocol === 'file:'){
                LOCALHOST = 1;
            }
        // LOCALHOST

        // DIR_APP -> TESTAR -> DIR/public_html/app/www/index.html
            if(LOCALHOST){
                $_GLOBAL.DIR = ``;
                $_GLOBAL.DIR_API = `http://localhost:4000${$_APP_URL__LOCAL}/api`;

            } else { // NA NUVEM
                $_GLOBAL.DIR = ``;
                $_GLOBAL.DIR_API = `${$_APP_URL__ONLINE}/api`;
            }
        // DIR_APP
    // VARIAVEIS







    // INIT
        function api_app(){
            $headers = {
                method: `POST`,
                body: `{}`,
                headers: {'Content-type': 'application/json; charset=UTF-8'}
            }

            setTimeout(() => {
                let e = document.querySelector('.aaa');
                if(e){
                    e.innerHTML = `${$_GLOBAL.DIR_API}/app/init`;
                }
            }, 2000);

            fetch(`${$_GLOBAL.DIR_API}/app/init`, $headers)
            .then(($response) => fetchIni_APP($response))
            .then(($json) => {
                

                // VARS
                    for(let key in $json?.VARS){
                        let value = $json?.VARS[key];

                        let $array = JSON.parse(value);
                        for(let key_1 in $array){
                            let value_1 = $array[key_1];
    
                            // GET
                                if(key == `GET`){
                                    $_GET[key_1] = value_1;
                                }
                            // GET

                            // GLOBAL
                                if(key == `GLOBAL`){
                                    if( !(key_1 == `DIR` || key_1 == `DIR_API`) ){
                                        $_GLOBAL[key_1] = value_1;
                                    }
                                }
                            // GLOBAL
                        }
                    }
                // VARS


                // CSS
                    for(let key in $json?.CSS){
                        let value = $json?.CSS[key];

                        var link = document.createElement(`link`);
                        link.rel = `stylesheet`;
                        link.type = `text/css`;
                        link.href = value;
                        link.media = `all`;
                        link.onload = function() {
                        };
                        link.onerror = function() {
                            console.error(`Erro ao carregar ${value}`);
                        };
                        document.head.appendChild(link);
                    }
                // CSS


                // JS
                    setTimeout(function(){
                        for(let key in $json?.JS){
                            let value = $json?.JS[key];

                            var script = document.createElement(`script`);
                            script.src = value;
                            script.type = `text/javascript`;
                            script.onload = function() {
                                $_ABERTURA_JS++;
                            };
                            script.onerror = function() {
                                console.error(`Erro ao carregar ${value}`);
                            };
                            document.body.appendChild(script);
                        }

                        // JS_ROOT_APP
                            var script = document.createElement(`script`);
                            script.src = `${$_GLOBAL.DIR_API}/../assets/js_root_app.js`;
                            script.type = `text/javascript`;
                            script.onload = function() {
                            };
                            script.onerror = function() {
                                console.error(`Erro ao carregar js_root_app`);
                            };
                            document.body.appendChild(script);
                        // JS_ROOT_APP
                    }, .5);
                // JS


                // ABERTURA
                    setTimeout(function(){
                        setTimeout(function(){ ABERTURA__(); }, 1000);
                        setTimeout(function(){ ABERTURA__(); }, 2000);
                        setTimeout(function(){ ABERTURA__(); }, 3000);
                        setTimeout(function(){ ABERTURA__(); }, 4000);
                        setTimeout(function(){ document.querySelector(`.__ABERTURA__`).style.display = `none`; }, 5000);
                    }, 1000);
                // ABERTURA

            })
            .catch((error) => erros_ajax_APP(error));
        }

        // FETCHINI
            async function fetchIni_APP($response, $url=``, $data=``, $function=``, $carregando=``){
                let $text = await $response.text();
                try {
                    let $json = JSON.parse($text);
                    return $json;
                } catch(e){
                    erros_ajax_APP($text)
                }
            }
        // FETCHINI

        // ERROS AJAX
            function erros_ajax_APP(error){
                if(error == `TypeError: Failed to fetch`){
                    error_box_alert(error, 1);

                } else {
                    error_box_alert(error);
                }
            };
        // ERROS AJAX

        // ABERTURA__
            function ABERTURA__(){
                if($_ABERTURA_JS == 2){
                    document.querySelector(`.__ABERTURA__`).style.display = `none`;
                }
            }
        // ABERTURA__

        
        // ERROR_BOX_ALERT
            function error_box_alert(error, failed=0){
                let txt = `Não foi possível completar a solicitação, tente novamente mais tarde. <br>Em caso de dúvida entre em contato.`;
                if(failed == 1){
                    txt = `Não foi possível completar a solicitação, verifique a sua conexão com internet. <br>Em caso de dúvida entre em contato.`;
                }
                let msg = typeof error === 'object' ? JSON.stringify(error, Object.getOwnPropertyNames(error), 2) : error;

                let $html = ``;
                $html += `<div class="errors__"> `;
                    $html += `<div class=""> `;
                        $html += `<div> `;
                            $html += `<b>Atenção</b> `;
                            $html += `<span>${txt}</span> `;
                            $html += `<button>Tentar novamente</button> `;
                        $html += `</div> `;

                        $html += `<div> `;
                            $html += `<div class="mais_detalhes" style="padding-bottom: 5px; font-weight: 400; text-align: right; cursor: pointer; color: #006FB7;">mais detalhes</div> `;
                            $html += `<div class="mais_detalhes_box" style="max-width: ${window.innerWidth-40}px; max-height: 500px; padding: 10px; overflow: auto !important; border: 1px solid #ccc; border-radius: 5px"> `;
                                $html += error ? `<pre><code style="padding-top: 10px; white-space: pre-wrap;">${escapeHtml(msg)}</code></pre>` : ``;
                            $html += `</div> `;
                        $html += `</div> `;
                    $html += `</div> `;
                $html += `</div> `;
                document.querySelector(`.error_app`).innerHTML = $html;

                document.querySelector(`.error_app button`).addEventListener(`click`, (e) => {
                    let $e = document.querySelector(`.error_app`);
                    if($e){
                        $e.innerHTML = ``;
                    }
                    api_app();
                })

                document.querySelector(`.error_app .mais_detalhes`).addEventListener(`click`, (ev) => {
                    let $e = document.querySelector(`.error_app .mais_detalhes_box`);
                    if($e.style.display == `none`){
                        $e.style.display = `block`;
                    } else {
                        $e.style.display = `none`;
                    }
                })
            }
            function escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;");
            }
        // ERROR_BOX_ALERT


        // ERROR_CONSOLE
            // (function () {
            //     function showConsoleMessage(msg, type = 'log') {
            //         let div = document.getElementById("errorLog");
            
            //         if (!div) {
            //             div = document.createElement("div");
            //             div.id = "errorLog";
            //             div.style.position = "fixed";
            //             div.style.bottom = "0";
            //             div.style.left = "0";
            //             div.style.width = "100%";
            //             div.style.backgroundColor = "#111";
            //             div.style.color = "#eee";
            //             div.style.padding = "10px";
            //             div.style.fontSize = "14px";
            //             div.style.zIndex = "99999";
            //             div.style.overflowY = "auto";
            //             div.style.maxHeight = "200px";
            //             div.style.fontFamily = "monospace";
            //             div.style.borderTop = "2px solid red";
            //             div.innerHTML = `<strong style="color:white;">🛠 Logs:</strong><hr>`;
            //             document.body.appendChild(div);
            //         }
            
            //         const colors = {
            //             log: '#ccc',
            //             warn: 'yellow',
            //             error: 'red'
            //         };
            
            //         const messageBlock = document.createElement('pre');
            //         messageBlock.style.color = colors[type] || '#ccc';
            //         messageBlock.textContent = `[${type.toUpperCase()}] ` + msg;
            
            //         div.appendChild(messageBlock);
            //     }
            
            //     // Captura e mostra logs, warnings e erros
            //     ['log', 'warn', 'error'].forEach(type => {
            //         const original = console[type];
            //         console[type] = function (...args) {
            //             const mensagem = args.map(arg => {
            //                 try {
            //                     if (typeof arg === 'object') {
            //                         return JSON.stringify(arg, null, 2);
            //                     } else {
            //                         return String(arg);
            //                     }
            //                 } catch {
            //                     return '[Erro ao processar argumento]';
            //                 }
            //             }).join(' ');
            
            //             showConsoleMessage(mensagem, type);
            //             original.apply(console, args);
            //         };
            //     });
            
            //     // Captura erros globais
            //     window.onerror = function (message, source, lineno, colno, error) {
            //         showConsoleMessage(`Erro Global: ${message} \nArquivo: ${source} \nLinha: ${lineno}, Coluna: ${colno}`, 'error');
            //     };
            
            //     // Captura promessas não tratadas
            //     window.addEventListener("unhandledrejection", event => {
            //         showConsoleMessage(`Erro em Promise: ${event.reason}`, 'error');
            //     });
            // })();
        // ERROR_CONSOLE


        api_app();
    // INIT







// ----------------------------------------------------------------------------------------------------------------------------------------------------------







    // CORDOVA
        // DEVICEREADY
            document.addEventListener(`deviceready`, onDeviceReady, false);
            function onDeviceReady() {
                geomapeamento_mobile();

                //console.log(`Running cordova-` + cordova.platformId + `@` + cordova.version);
                //document.getElementById(`deviceready`).classList.add(`ready`);
            }
        // DEVICEREADY

        
        // GEOMAPEAMENTO
            async function geomapeamento_mobile() {
                return new Promise((resolve) => {
                    navigator.geolocation.getCurrentPosition(
                        (position) => onSuccess(position, resolve),
                        (error) => onError(error, resolve),
                        {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        }
                    );
                });
            }
            function onSuccess(position, resolve) {
                $_GLOBAL.lat = position.coords.latitude;
                $_GLOBAL.lng = position.coords.longitude;
                resolve();
            }
            function onError(error, resolve) {
                resolve();
            }
            $_GLOBAL.geomapeamento_mobile = geomapeamento_mobile;
        // GEOMAPEAMENTO
    // CORDOVA
