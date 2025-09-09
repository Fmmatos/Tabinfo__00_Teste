import router from '@/vendor/routers/router'

import { APP, DIR } from '@/vendor/services/localhost';
import { COOKIES, isset_COOKIES } from '../services/events';


    // ROOT
        export function rootAuth(token: string = ``): any
        {
            // ADMIN
                if ($_GET[0] == `admin`){
                    if (token){
                        localStorage.setItem(`adminAuth_${DIR()}`, token);
                        return token;
    
                    } else {
                        return localStorage.getItem(`adminAuth_${DIR()}`);
                    }    
                }
            // ADMIN


            // DASHBOARD
                if ($_GET[0] == `dashboard`){
                    if (token){
                        localStorage.setItem(`dashboardAuth_${DIR()}`, token);
                        return token;
    
                    } else {
                        return localStorage.getItem(`dashboardAuth_${DIR()}`);
                    }    
                }
            // DASHBOARD


            // ROOT
                else {
                    if (token){
                        localStorage.setItem(`rootAuth_${DIR()}`, token);
                        return token;
    
                    } else {
                        return localStorage.getItem(`rootAuth_${DIR()}`);
                    }    
                }
            // ROOT
        }

        export function rootAuth_remove(): void
        {
            // ADMIN
                if ($_GET[0] == `admin`){
                    localStorage.removeItem(`adminAuth_${DIR()}`);
                }
            // ADMIN


            // DASHBOARD
                if ($_GET[0] == `dashboard`){
                    localStorage.removeItem(`dashboardAuth_${DIR()}`);
                }
            // DASHBOARD


            // ROOT
                else {
                    localStorage.removeItem(`rootAuth_${DIR()}`);
                }
            // ROOT
        }
    // ROOT





    // ADMIN
        export function adminAuth(): any
        {
            if (localStorage.getItem(`adminAuth_${DIR()}`)){
                return `Bearer ${localStorage.getItem(`adminAuth_${DIR()}`)}`;
            }
            return 0;
        }
        export function adminAuth_master(): any
        {
            if (isset_COOKIES(`adminAuth`)){
                return String(COOKIES(`adminAuth`)) == `1`;
            }
            return 0;
        }
    // ADMIN





    // LOGOUT
        export function logout(): void
        {
            let url = `/`;

            // ADMIN
                if ($_GET[0] == `admin`){
                    if ($_GET[2] == `logout`){
                        url = `/admin/login/`;
                    } else {
                        url = `/admin/login/logout/`;
                    }
                }
            // ADMIN


            // DASHBOARD
                else if ($_GET[0] == `dashboard`){
                    if ($_GET[2] == `logout`){
                        url = `/dashboard/login/`;
                    } else {
                        url = `/dashboard/login/logout/`;
                    }
                }
            // DASHBOARD


            // CART
                else if ($_GET[0] == `cart`){
                    url = `/cart/login/`;
                }
            // CART


            // ROOT
                else {
                    if ($_GET[1] == `logout`){
                        url = `/login/`;
                    } else {
                        url = `/login/logout/`;
                    }
                }
            // ROOT


            if (APP()){
                router.push({ path: url, query: { v: new Date().getTime() } });
            } else {
                // console.log(`${DIR()}${url}`);
                window.location.href = `${DIR()}${url}`;
            }
        }
    // LOGOUT
