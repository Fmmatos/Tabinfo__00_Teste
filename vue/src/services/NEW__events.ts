import { icons__ } from "@/services/NEW__icons";
import api from "@/vendor/services/api";
import { sql_json_list, clone, extra__, in_array, is_string, json_decode, replace, compare__, open__ } from "@/vendor/services/events";


    // NEW

        // INTERVAL
            export function INTERVAL_INI(INTERVAL: any): void
            {
            }

            export function INTERVAL_FIM(INTERVAL: any): void
            {
            }
        // INTERVAL

    // NEW




















// ----------------------------------------------------------------------------------------------------------------------------------------------------------




















    // NEW FUNCTIONS
        // OPEN__
            export function NEW__open__pg(queryParams: any, pg: string, value: any = null, dashboard: number = 0): any
            {
                return pg;
            }

            export function NEW__open__queryParams(queryParams: any, pg: string, value: any = null, dashboard: number = 0): any
            {
                return queryParams;
            }
        // OPEN__


        // URL_ADMIN_DASHBOARD
            export function NEW__url_admin_dashboard(pg: string): string
            {
                // DASHBOARD
                    if($_GET[0] == `dashboard`){
                    }
                // DASHBOARD
                
                return pg;
            }
        // URL_ADMIN_DASHBOARD


        // SELECT NAME OPTIONS
            export function NEW__select_name(value: any): string
            {

                // CLIENTES
                    // if(value.table__ == 'clients'){
                    //     if(value?.cnpj_cpf != null && value?.uf_text != null){
                    //         return `${value.name} (${value?.uf_text}) ${value?.cnpj_cpf}`;
                    //     }
                    // }
                // CLIENTES

                // CUSTOMERS_CATEGORIES
                    if(value.table__ == 'items' && value?.weight){
                        return `${value.name} (${value?.limit ? `Acima de` : `Até`} ${value?.weight} kg)`;
                    }
                // CUSTOMERS_CATEGORIES

                return value.name;
            }
        // SELECT NAME OPTIONS


        // REQUEST OBJ
            export function NEW__request_obj(request: any): void
            {

                // if($_GLOBAL.OBJ?.menu_admin?.id == '1042'){
                //     $_GLOBAL.OBJ.menu_admin.BUTTON_EDIT = (id: number, value: any) => {
                //         open__(`/${$_GET[1]}/${$_GET[2]}/${id}`, { table: value?.table_orders ? value.table_orders : value?.table__ }, 1);
                //     };
                // }

            }
        // REQUEST OBJ


        // HTML_ARRAY_CLICK
            export function NEW__html_array_click(json: any): void
            {
                json = json_decode(json);

                // // STOCK_MARK_DELIVERED
                //     if(json?.function == `stock_mark_delivered`){
                //         $_GLOBAL.SHOW.stock_mark_delivered = json?.id;
                //         $_GLOBAL.SHOW.BOXS = `STOCK_MARK_DELIVERED`;
                //         setTimeout(() => {
                //             $_GLOBAL.FORM.v.qty_delivered = json?.qty;
                //         }, .5);
                //     }
                // // STOCK_MARK_DELIVERED

            }
        // HTML_ARRAY_CLICK


        // NEW__modules__create_edit
            export function NEW__modules__create_edit(id: number = 0, value: any = null): boolean
            {

                // if ($_GLOBAL.OBJ.menu_admin.table == 'accounts_payable'){
                //     if(value.accounts_receivable){
                //         return true;
                //     }
                // }

                return false;
            }
        // NEW__modules__create_edit

    // NEW FUNCTIONS




















// ----------------------------------------------------------------------------------------------------------------------------------------------------------




















    // LAYOUT INDEX
        // ONBEFOREMOUNT
            export function index__layout(): void
            {
            }
        // ONBEFOREMOUNT
    // LAYOUT INDEX










    // DASHBOARD
        // MENU_SIDE
            export function dashboard__menu_side(): void
            {
                let $menu: any = [];

                if(0){}

                // CUSTOMERS
                    else if($_GLOBAL.OBJ.user?.type == 'customers'){
                        $menu = [
                            {
                                name: `Cadastro`,
                                svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M4.5 4.5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"></path><path d="M2.516 12.227a6.273 6.273 0 0 1 10.968 0l.437.786a1.338 1.338 0 0 1-1.17 1.987h-9.502a1.338 1.338 0 0 1-1.17-1.987z"></path></svg>`,
                                svg_ative: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7m-2 3.5a2 2 0 1 1 3.999-.001 2 2 0 0 1-3.999.001"></path><path fill-rule="evenodd" d="M13.484 12.227a6.274 6.274 0 0 0-10.968 0l-.437.786a1.338 1.338 0 0 0 1.17 1.987h9.502a1.338 1.338 0 0 0 1.17-1.987zm-9.657.728a4.775 4.775 0 0 1 8.346 0l.302.545h-8.95z"></path></svg>`,
                                url: `/customers`,
                                pages: [`customers`],
                            },
                            {
                                name: `Meus Dados`,
                                svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.255 3.847a2.75 2.75 0 0 1 2.72-2.347h6.05a2.75 2.75 0 0 1 2.72 2.347l.66 4.46q.095.638.095 1.282v1.661a3.25 3.25 0 0 1-3.25 3.25h-6.5a3.25 3.25 0 0 1-3.25-3.25v-1.66q0-.645.094-1.283zm2.72-.847a1.25 1.25 0 0 0-1.236 1.067l-.583 3.933h2.484c.538 0 1.015.344 1.185.855l.159.474a.25.25 0 0 0 .237.171h1.558a.25.25 0 0 0 .237-.17l.159-.475a1.25 1.25 0 0 1 1.185-.855h2.484l-.583-3.933a1.25 1.25 0 0 0-1.236-1.067z"></path></svg>`,
                                svg_ative: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.976 1.5a2.75 2.75 0 0 0-2.72 2.347l-.662 4.46a9 9 0 0 0-.094 1.282v1.661a3.25 3.25 0 0 0 3.25 3.25h6.5a3.25 3.25 0 0 0 3.25-3.25v-1.66q0-.645-.095-1.283l-.66-4.46a2.75 2.75 0 0 0-2.72-2.347h-6.05Zm-1.237 2.567a1.25 1.25 0 0 1 1.237-1.067h6.048c.62 0 1.146.454 1.237 1.067l.583 3.933h-2.484c-.538 0-1.015.344-1.185.855l-.159.474a.25.25 0 0 1-.237.171h-1.558a.25.25 0 0 1-.237-.17l-.159-.475a1.25 1.25 0 0 0-1.185-.855h-2.484zm-.738 5.433-.001.09v1.66c0 .966.784 1.75 1.75 1.75h6.5a1.75 1.75 0 0 0 1.75-1.75v-1.75h-2.46l-.1.303a1.75 1.75 0 0 1-1.66 1.197h-1.56a1.75 1.75 0 0 1-1.66-1.197l-.1-.303h-2.46Z"></path></svg>`,
                                url: `/account`,
                                pages: [`account`],
                            },
                            {
                                name: `Sair`,
                                svg: `<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path fill="currentColor" d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C3.47005 3.00158 2.96227 3.2128 2.58753 3.58753C2.2128 3.96227 2.00158 4.47005 2 5V19C2.00158 19.5299 2.2128 20.0377 2.58753 20.4125C2.96227 20.7872 3.47005 20.9984 4 21H12V19H4V5Z"/> </svg>`,
                                svg_class: `ml3`,
                                logout: 1,
                            },
                        ];
                    }
                // CUSTOMERS
        
        



                // USERS
                    else if($_GLOBAL.OBJ?.user?.type == 'users'){
                        $menu = [];

                        $menu.push({
                            name: `Sair`,
                            svg: `<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path fill="currentColor" d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C3.47005 3.00158 2.96227 3.2128 2.58753 3.58753C2.2128 3.96227 2.00158 4.47005 2 5V19C2.00158 19.5299 2.2128 20.0377 2.58753 20.4125C2.96227 20.7872 3.47005 20.9984 4 21H12V19H4V5Z"/> </svg>`,
                            svg_class: `ml3`,
                            logout: 1,
                        });
                    }
                // USERS
        




                // ELSE
                    else if($_GLOBAL.OBJ?.user?.id){
                        $menu = [
                            {
                                name: `Sair`,
                                svg: `<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path fill="currentColor" d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C3.47005 3.00158 2.96227 3.2128 2.58753 3.58753C2.2128 3.96227 2.00158 4.47005 2 5V19C2.00158 19.5299 2.2128 20.0377 2.58753 20.4125C2.96227 20.7872 3.47005 20.9984 4 21H12V19H4V5Z"/> </svg>`,
                                svg_class: `ml3`,
                                logout: 1,
                            },
                        ];
                    }
                // ELSE


                $_GLOBAL.OBJ.menu_side_default = clone($menu);
                $_GLOBAL.OBJ.menu_side = {...$menu};

                dashboard__menu_side_permissions();
                dashboard__menu_side_treatment();

            }

            export function dashboard__menu_side__(value: any, submenu: any = []): any
            {
                return {
                    name: value.name,
                    svg: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.255 3.847a2.75 2.75 0 0 1 2.72-2.347h6.05a2.75 2.75 0 0 1 2.72 2.347l.66 4.46q.095.638.095 1.282v1.661a3.25 3.25 0 0 1-3.25 3.25h-6.5a3.25 3.25 0 0 1-3.25-3.25v-1.66q0-.645.094-1.283zm2.72-.847a1.25 1.25 0 0 0-1.236 1.067l-.583 3.933h2.484c.538 0 1.015.344 1.185.855l.159.474a.25.25 0 0 0 .237.171h1.558a.25.25 0 0 0 .237-.17l.159-.475a1.25 1.25 0 0 1 1.185-.855h2.484l-.583-3.933a1.25 1.25 0 0 0-1.236-1.067z"></path></svg>`,
                    svg_ative: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.976 1.5a2.75 2.75 0 0 0-2.72 2.347l-.662 4.46a9 9 0 0 0-.094 1.282v1.661a3.25 3.25 0 0 0 3.25 3.25h6.5a3.25 3.25 0 0 0 3.25-3.25v-1.66q0-.645-.095-1.283l-.66-4.46a2.75 2.75 0 0 0-2.72-2.347h-6.05Zm-1.237 2.567a1.25 1.25 0 0 1 1.237-1.067h6.048c.62 0 1.146.454 1.237 1.067l.583 3.933h-2.484c-.538 0-1.015.344-1.185.855l-.159.474a.25.25 0 0 1-.237.171h-1.558a.25.25 0 0 1-.237-.17l-.159-.475a1.25 1.25 0 0 0-1.185-.855h-2.484zm-.738 5.433-.001.09v1.66c0 .966.784 1.75 1.75 1.75h6.5a1.75 1.75 0 0 0 1.75-1.75v-1.75h-2.46l-.1.303a1.75 1.75 0 0 1-1.66 1.197h-1.56a1.75 1.75 0 0 1-1.66-1.197l-.1-.303h-2.46Z"></path></svg>`,
                    url: `/menus_dynamic/${value.id}`,
                    pages: [`menus_dynamic/${value.id}`],
                    menus_type: value.menus_type,
                    orders_status: value?.orders_status ? Object.keys(value?.orders_status).filter(key => value?.orders_status?.[key] === "true").map(Number) : [],
                    submenu: submenu,
                };
            }

            export function dashboard__menu_side_treatment(): void
            {
                for (const [key, value] of Object.entries($_GLOBAL.OBJ.menu_side)){
                    let val: any = value;
                
                    if(val?.url == `no`){
                        val.url = val.submenu[0]?.url ?? ``;
                        val.no = 1;
                    }
                }
            }


            export function dashboard__menu_side_permissions(): void
            {
                if($_GLOBAL.OBJ?.user?.permissions_all !== undefined){
                    if($_GLOBAL.OBJ?.user?.permissions_all == 1) return;

                    $_GLOBAL.OBJ.user.permissions = is_string($_GLOBAL.OBJ?.user?.permissions) ? json_decode($_GLOBAL.OBJ?.user?.permissions) : $_GLOBAL.OBJ?.user?.permissions;
                    let permissions = sql_json_list($_GLOBAL.OBJ?.user?.permissions);
                    let submenu: any = clone($_GLOBAL.OBJ.menu_side);

                    // SUBMENU
                        for (const [key, value] of Object.entries($_GLOBAL.OBJ.menu_side)){
                            let val: any = value;
                            submenu[key].submenu = [];
                        
                            if(val?.submenu){
                                for (const [key_1, value_1] of Object.entries(val.submenu)){
                                    let val_1: any = value_1;

                                    if(in_array(val_1?.url, permissions)){
                                        submenu[key].submenu.push(val_1);
                                    }
                                }
                            }
                        }
                    // SUBMENU


                    // MENU
                        let menu: any = [];
                        for (const [key, value] of Object.entries($_GLOBAL.OBJ.menu_side)){
                            let val: any = value;

                            // HOME
                                if(val.url == `/` || val?.logout){
                                    menu.push(submenu[key]);
                                }
                            // HOME


                            // NO
                                else if(val.url == `no`){
                                    if(submenu[key]?.submenu[0]?.url){
                                        menu.push(submenu[key]);
                                    }
                                }
                            // NO


                            // ELSE
                                else {
                                    let val_1__ = replace(`/`, ``, val.url);
                                    if(in_array(val_1__, permissions)){
                                        menu.push(submenu[key]);

                                    } else {
                                        if(submenu[key]?.submenu[0]?.url){
                                            submenu[key].url = `no`;
                                            menu.push(submenu[key]);
                                        }
                                    }
                                }
                            // ELSE
                        }
                    // MENU


                    $_GLOBAL.OBJ.menu_side = menu;
                }
            }
        // MENU_SIDE
    // DASHBOARD





    // ADMIN
        // ONBEFOREMOUNT
            export function admin__table(): void
            {

                // ACCOUNTS_RECEIVABLE
                    // if($_GET[1] == `accounts_receivable`){
                    //     $_GLOBAL.FORM.DATATABLE_FILTER['search_dinamic_pf_pj__'] = 1;
                    // }
                // ACCOUNTS_RECEIVABLE

            }

            export function admin__create_edit(): void
            {
                if($_GLOBAL.OBJ?.menu_admin?.table == `accounts_payable`){
                    // CATEGORIES_OMIE
                        if($_GLOBAL.OBJ?.VALUE.categories_omie_name){
                            document.querySelectorAll(`.__INPUT__NAME__categories__ > label > span`).forEach(item => {
                                item.setAttribute('data-after', $_GLOBAL.OBJ?.VALUE.categories_omie_name ?? '');
                            });
                        }
                    // CATEGORIES_OMIE

                    // SUPPLIERS_OMIE
                        if($_GLOBAL.OBJ?.VALUE.suppliers_omie_name){
                            document.querySelectorAll(`.__INPUT__NAME__suppliers__ > label > span`).forEach(item => {
                                item.setAttribute('data-after', $_GLOBAL.OBJ?.VALUE.suppliers_omie_name ?? '');
                            });
                        }
                    // SUPPLIERS_OMIE
                }
            }
        // ONBEFOREMOUNT


        // MODULES__CLICK
            export function new__modules__click(col: any, value: any): any
            {
                // EXTRA
                    let extra = extra__(col?.extra, `|->no_click`);
                    if(extra?.[1]){

                    }
                // EXTRA

                // HTML
                    let match = value[col?.name].match(/click\s*=\s*"([^"]+)"/i);
                    if(match?.[1]) {
                    }
                // HTML
            }
        // MODULES__CLICK


        // MENU_SIDE
            export function admin__menu_side(): void
            {
                for (const [key, value] of Object.entries($_GLOBAL.OBJ.menu_side)){
                    let val: any = value;

                    // HOME
                        if(val?.id == 0){
                            $_GLOBAL.OBJ.menu_side[key].svg = icons__('home');
                            $_GLOBAL.OBJ.menu_side[key].svg_active = icons__('home', true);
                        }
                    // HOME


                    // ICON FAA-
                        else if(val?.icon && compare__(`faa-`, val?.icon)) {
                        }
                    // ICON FAA-

                    // ELSE
                        else {
                            $_GLOBAL.OBJ.menu_side[key].svg = icons__(val?.icon);
                            $_GLOBAL.OBJ.menu_side[key].svg_active = icons__(val?.icon, true);
                        }
                    // ELSE

                }


                // REMOVE LOGOUT
                $_GLOBAL.OBJ.menu_side = $_GLOBAL.OBJ.menu_side.filter((item: any) => item.name !== 'Sair');
                // REMOVE LOGOUT

                // ADD LOGOUT
                    $_GLOBAL.OBJ.menu_side.push({
                        name: `Sair`,
                        svg: `<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path fill="currentColor" d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C3.47005 3.00158 2.96227 3.2128 2.58753 3.58753C2.2128 3.96227 2.00158 4.47005 2 5V19C2.00158 19.5299 2.2128 20.0377 2.58753 20.4125C2.96227 20.7872 3.47005 20.9984 4 21H12V19H4V5Z"/> </svg>`,
                        svg_class: `ml3`,
                        logout: 1,
                    });
                // ADD LOGOUT
            }
        // MENU_SIDE
    // ADMIN
