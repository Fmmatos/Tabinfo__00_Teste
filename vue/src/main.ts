import { createApp } from 'vue'
import router from '@/vendor/routers/router'

import { css, HEX } from '@/vendor/services/create_css';
import { href_APP, carousel } from '@/vendor/services/events';
import { PROG } from '@/vendor/services/localhost';

// import "@/vendor/assets/css/tailwind.css";
import "@/vendor/assets/css/css.css";
import "@/vendor/assets/css/resp.css";
import "@/assets/css/style.css";

import App from '@/vendor/layouts/__default.vue'

    // APP
        const app = createApp(App);

        app.use(router);


        // VERIFY ERRORS MOBILE
            // app.config.errorHandler = (err: any, instance, info) => {
            //     if(PROG()){
            //         exibirErroNaTela(`Erro Vue: ${err.message} <br> Info: ${info}`);
            //     }
            // };
        // VERIFY ERRORS MOBILE


        app.mount('#app');

        app.mixin({
            updated: function() {
                css();
                href_APP();
                setTimeout(() => { carousel(); }, .5);
            },
        })
        HEX();
    // APP









    // VERIFY ERRORS MOBILE
        // function exibirErroNaTela(mensagem: string) {
        //     let errorDiv = document.getElementById("errorLog");
        
        //     if (!errorDiv) {
        //         errorDiv = document.createElement("div");
        //         errorDiv.id = "errorLog";
        //         errorDiv.style.position = "fixed";
        //         errorDiv.style.bottom = "0";
        //         errorDiv.style.left = "0";
        //         errorDiv.style.width = "100%";
        //         errorDiv.style.backgroundColor = "rgba(255, 0, 0, 0.8)";
        //         errorDiv.style.color = "white";
        //         errorDiv.style.padding = "10px";
        //         errorDiv.style.fontSize = "14px";
        //         errorDiv.style.zIndex = "9999";
        //         errorDiv.style.overflowY = "auto";
        //         errorDiv.style.maxHeight = "150px";
        //         errorDiv.style.borderTop = "3px solid white";
        //         document.body.appendChild(errorDiv);
        //     }
        
        //     errorDiv.innerHTML += `<p>${mensagem}</p><hr>`;
        // }

        // window.onerror = function (message, source, lineno, colno, error) {
        //     if(PROG()){
        //         exibirErroNaTela(`Erro Global: ${message} <br> Arquivo: ${source} <br> Linha: ${lineno}, Coluna: ${colno}`);
        //     }
        // };

        // window.addEventListener("unhandledrejection", event => {
        //     if(PROG()){
        //         exibirErroNaTela(`Erro em Promise: ${event.reason}`);
        //     }
        // });
    // VERIFY ERRORS MOBILE