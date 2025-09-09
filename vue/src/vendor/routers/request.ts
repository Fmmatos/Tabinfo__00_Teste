import { NEW__request } from '@/routers/NEW__request';
import api from '@/vendor/services/api';
import { load, replace } from '@/vendor/services/events';
import { rootAuth_remove, logout } from '@/vendor/storages/auth';

// BEFOREEACH
    export function beforeEach(to: any, form: any, next: (arg?: boolean | string | object) => void): void
    {
        load(1);

        response(to, form, next);
    }


    const response = async (to: any, form: any, next: (arg?: boolean | string | object) => void): Promise<void> => {
        var request: any = {};

        // GET
            $_GET = {};
            for(let key in to.query){ // ?x=1&y=2
                $_GET[key] = to.query[key];
            }

            $_GET[1] = to.params?.get_1 ? to.params.get_1 : ``;
            $_GET[2] = to.params?.get_2 ? to.params.get_2 : ``;
            $_GET[3] = to.params?.get_3 ? to.params.get_3 : ``;
            $_GET[4] = to.params?.get_4 ? to.params.get_4 : ``;
            $_GET[5] = to.params?.get_5 ? to.params.get_5 : ``;
            $_GET[6] = to.params?.get_6 ? to.params.get_6 : ``;
            $_GET[7] = to.params?.get_7 ? to.params.get_7 : ``;
            $_GET[8] = to.params?.get_8 ? to.params.get_8 : ``;
            $_GET[9] = to.params?.get_9 ? to.params.get_9 : ``;
        // GET





        // REQUEST
            let $pg = ``;

            // ?PG=
                if (to.href.split(`?pg=`)[1] != undefined){
                    let $ex = to.href.split(`?pg=`)[1];
                    let $ex_1 = $ex.split(`/`);
                    $pg = $ex_1[0];
                    to.params.get_0 = $pg;
                }
            // ?PG=


            // HOME
                else if (to.name == `home` || to.params.get_0 == `index.html`){
                    $pg = `home`;
                    to.params.get_0 = $pg;
                }
            // HOME


            // ADMIN / DASHBOARD
                else if (to.path == `/admin` || to.path == `/dashboard`){
                    $pg = to.path == `/admin` ? `admin` : `dashboard`;
                    to.params.get_0 = $pg;
                }
                else if (to.path.split(`/admin/`)[1] != undefined || to.path.split(`/dashboard/`)[1] != undefined){
                    if ($_GET['1']){
                        $pg = to.path.split(`/admin/`)[1] != undefined ? `admin/${$_GET['1']}` : `dashboard/${$_GET['1']}`;
                    } else {
                        $pg = to.path.split(`/admin/`)[1] != undefined ? `admin` : `dashboard`;
                    }
                    to.params.get_0 = $pg;
                }
            // ADMIN / DASHBOARD


            // OTHER
                else if (to.params.get_0) {
                    $pg = to.params.get_0;
                }
            // OTHER





            // PAGE
                if (!$pg){
                    next({ path: '/error404', replace: true });
                    return;

                } else {
                    $_GET['ALL'] = to.fullPath;
                    $_GET[0] = to.params.get_0.split('/')[0];

                    $_GET['PG'] = to.params.get_0;
                    if ($_GET[0] == `admin` || $_GET[0] == `dashboard`){
                        $_GET['PG'] = $_GET['1'];
                    }
                    $_GET['PG'] = $_GET['PG'] ? $_GET['PG'] : `home`;

                    if(NEW__request()) {
                        return; // STOP
                    }

                    let get_1 = ($_GET['1'] && $_GET[0] != `admin` && $_GET[0] != `dashboard`) ? `/${$_GET['1']}` : ``;
                    let get_2 = $_GET[2] ? `/${$_GET[2]}` : ``;
                    let get_3 = $_GET[3] ? `/${$_GET[3]}` : ``;
                    let get_4 = $_GET[4] ? `/${$_GET[4]}` : ``;
                    let get_5 = $_GET[5] ? `/${$_GET[5]}` : ``;
                    let get_6 = $_GET[6] ? `/${$_GET[6]}` : ``;
                    let get_7 = $_GET[7] ? `/${$_GET[7]}` : ``;
                    let get_8 = $_GET[8] ? `/${$_GET[8]}` : ``;
                    let get_9 = $_GET[9] ? `/${$_GET[9]}` : ``;

                    if ($_GET['init__']){
                        delete $_GET['init__'];
                        to.params.init__ = 1;
                    }

                    $pg = replace(`__`, `/`, $pg);

                    let url = `/${$pg}/index${get_1}${get_2}${get_3}${get_4}${get_5}${get_6}${get_7}${get_8}${get_9}`;
                    Object.assign(request, await api(url, to.params, `request_init__`));
                }
            // PAGE





            // LOGOUT
                // if(!LOCALHOST){
                    let logout__ = 0;

                    if ($_GET[0] == `login`){
                        logout__ = 1;
                    }
                    if ($_GET[0] == `admin` && $_GET[1] == `login`){
                        logout__ = 1;
                    }
                    if ($_GET[0] == `dashboard` && $_GET[1] == `login`){
                        logout__ = 1;
                    }

                    if(logout__){
                        rootAuth_remove();
                    }
                // }
            // LOGOUT
        // REQUEST





        // NEXT
            // PARAMS REQUEST
                if (request){
                    to.params['request'] = request;
                }
            // PARAMS REQUEST

            next(true);
        // NEXT

    }
// BEFOREEACH





// AFTEREACH
    export function afterEach(to: any, form: any): void
    {
    }
// AFTEREACH
