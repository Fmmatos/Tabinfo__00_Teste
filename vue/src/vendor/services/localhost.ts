import { adminAuth, adminAuth_master } from '@/vendor/storages/auth';

    // LOCALHOST
        export function LOCALHOST(): number
        {
            if (window.location.href.split('http://localhost:')[1]){
                return 1;
            }
            return 0;
        }
    // LOCALHOST





    // PROG
        export function PROG(): number
        {
            if (LOCALHOST() || (adminAuth() && adminAuth_master())){
                return 1;
            }
            return 0;
        }
    // PROG





    // APP
        export function APP(): number
        {
            return $_APP;
        }
    // APP


    // DIR
        export function DIR(): string // HTTPS:DOMINIO/DIR
        {
            if (window.location.origin == `file://`){
                return `file:///D:/wamp64/www`;
            }
            return `${window.location.origin}${$_GLOBAL.DIR}`;
        }
    // DIR


    // DIR_P
        export function DIR_P(): string // PUBLIC_HTML
        {
            return `${$_GLOBAL.DIR_API}/..`;
        }
    // DIR_P


    // DIR_LINK
        export function DIR_LINK(): string // URL
        {
            if (window.location.origin == `file://`){
                return `file:///D:/wamp64/www`;
            }
            if(LOCALHOST()){
                return `${window.location.origin}`;
            }
            return DIR();
        }
    // DIR_LINK


    // DIR_API
        export function DIR_API(): string // PUBLIC_HTML/API
        {
            return $_GLOBAL.DIR_API;
        }
    // DIR_API


    // DIR_APP
        export function DIR_APP(): string // .APP
        {
            return `${$_GLOBAL.DIR_API}/../../..app`;
        }
    // DIR_APP

    
    // DIR_DATA
        export function DIR_DATA(): string // .DATA
        {
            return `${$_GLOBAL.DIR_API}/../../..data`;
        }
    // DIR_DATA
