import { new__modules__click, NEW__modules__create_edit } from '@/services/NEW__events';
import api from '@/vendor/services/api';
import { alerts, compare__ini, COOKIES_DELETE, COOKIES_LIST, COOKIES_CREATE, copy_table__, iframe_send_father, open__,open_form,refresh, replace, strip_tags, url_admin_dashboard, json_encode } from '@/vendor/services/events';
import { DIR_API } from './localhost';
import { rootAuth } from '../storages/auth';





    // SEARCH
        export function modules__search(): void
        {
            modules__search_reset_sel();
            modules__search_datatable_create();

            api(`/${$_GET[0]}/${url_admin_dashboard()}`);
        }

        export function modules__search_reset(): void
        {
            modules__search_reset_sel();

            if ($_GLOBAL.FORM.v.search === ``){
                COOKIES_DELETE(`SEARCH__search`);
                api(`/${$_GET[0]}/${url_admin_dashboard()}`);
            }
        }

        export function modules__search_top(): void
        {
            modules__search_reset_sel();
            modules__search_datatable_create();
            COOKIES_DELETE(`SEARCH__page`);


            api(`/${$_GET[0]}/${url_admin_dashboard()}`);
        }

        export function modules__search_reset_all(): void
        {
            COOKIES_DELETE(`SEARCH__id`);

            COOKIES_DELETE(`SEARCH__search`);

            COOKIES_DELETE(`SEARCH__search_date_ini`);
            COOKIES_DELETE(`SEARCH__search_date_fim`);
            COOKIES_DELETE(`SEARCH__search_date_field`);

            for (const [key, value] of Object.entries(COOKIES_LIST())){
                if(compare__ini(`SEARCH__`, value.name)){
                    COOKIES_DELETE(value.name);
                }
            }

            api(`/${$_GET[0]}/${url_admin_dashboard()}`);
        }

        export function modules__search_datatable_create(): void
        {
            COOKIES_CREATE(`SEARCH__id`, $_GLOBAL.OBJ.menu_admin.id);

            COOKIES_CREATE(`SEARCH__search`, $_GLOBAL.FORM.v.search ?? ``);

            COOKIES_CREATE(`SEARCH__search_date_ini`, $_GLOBAL.FORM.v.search_date_ini ?? ``);
            COOKIES_CREATE(`SEARCH__search_date_fim`, $_GLOBAL.FORM.v.search_date_fim ?? ``);
            COOKIES_CREATE(`SEARCH__search_date_field`, $_GLOBAL.FORM.v.search_date_field ?? ``);

            // SEARCH_DINAMIC_
                for (const [key, value] of Object.entries($_GLOBAL.FORM.v)){
                    let val: any = value;
                    if (compare__ini(`search_dinamic_`, key)){
                        COOKIES_CREATE(`SEARCH__${key}`, val);
                    }
                }
            // SEARCH_DINAMIC_
        }

        export function modules__search_reset_sel(): void
        {
            $_GLOBAL.SHOW.sel_actions = 0;
            $_GLOBAL.SHOW.sel_all = false;

            $_GLOBAL.FORM.v.sel = {};
            $_GLOBAL.FORM.v.sel_all_all = false;
        }
    // SEARCH




















    // CREATE / EDIT / DELETE
        // CREATE / EDIT
            export function modules__create_edit(id: number = 0, value: any = null): any
            {
                if (NEW__modules__create_edit(id, value)){
                    return ;
                }

                modules__search_datatable_create();

                // CUSTOMIZED
                    if ($_GLOBAL.OBJ?.menu_admin?.BUTTON_EDIT){
                       $_GLOBAL.OBJ?.menu_admin?.BUTTON_EDIT(id, value);
                    }
                // CUSTOMIZED

                // NORMAL
                    else {
                        // SEL
                            if ($_GLOBAL.SHOW.sel_all && value != null){
                                if (!$_GLOBAL.SHOW?.mouse_press_ok){
                                    $_GLOBAL.FORM.v.sel[value.id] = !$_GLOBAL.FORM.v.sel[value.id];
                                    modules__sel_item(value);

                                } else {
                                    $_GLOBAL.SHOW.mouse_press_ok = false;
                                }
                            }
                        // SEL

                        // OPEN CREATE / EDIT
                            else {
                                let $ok: boolean = false;
                                if (id && $_GLOBAL.OBJ.menu_admin?.info?.includes(`edit`)){
                                    $ok = true;
                                }
                                else if (!id && $_GLOBAL.OBJ.menu_admin?.info?.includes(`create`)) {
                                    $ok = true;
                                }

                                // VARIABLES
                                    let $variables: any = { table: value.table__ };
                                    // GETS
                                        // ITEMS
                                            if ($_GET['items__']){
                                                $variables.items__ = $_GET['items__'];
                                            }
                                        // ITEMS
                                    // GETS
                                // VARIABLES

                                if ($ok){
                                    open__(`/${url_admin_dashboard()}/${id}`, $variables, 1);

                                } else {
                                    let event: MouseEvent | undefined;
                                    if ($_GLOBAL.SHOW.lastMouseEvent){
                                        event = $_GLOBAL.SHOW.lastMouseEvent;
                                        $_GLOBAL.SHOW.lastMouseEvent = null;
                                    } else {
                                        event = window.event as MouseEvent;
                                    }

                                    // SHIFT PRESS COPY
                                        if (event?.shiftKey) {
                                            copy_table__(event);
                                        }
                                    // SHIFT PRESS COPY
                                }
                            }
                        // OPEN CREATE / EDIT
                    }
                // NORMAL
            }
        // CREATE / EDIT


        // DELETE
            export function modules__delete(id: number = 0): void
            {
                if ($_GLOBAL.OBJ.menu_admin?.info?.includes(`delete`)){
                    if (confirm(`Deseja realmente excluir os itens selecionados? Após a exclusão, esta ação não pode ser desfeita!`)){
                        let form = {
                            ...$_GLOBAL.FORM.v,
                            sel: $_GLOBAL.FORM.v.sel,
                            _method: 'DELETE'
                        }
                        api(`/${$_GET[0]}/${url_admin_dashboard()}/delete${id ? `/${id}` : ``}`, form, (json: any) => {
                            open__(`/${url_admin_dashboard()}`, {}, 1);
                        });
                    }
                }
            }
        // DELETE
    // CREATE / EDIT / DELETE




















    // STORE / UPDATE
        export function modules__store_update(): void
        {
            // VARIABLES
                let $variables: any = {};
                // GETS
                    // ITEMS
                        if ($_GET['items__']){
                            $variables.items__ = $_GET['items__'];
                        }
                    // ITEMS
                // GETS
            // VARIABLES

            let form = { ...$_GLOBAL.FORM };
            form.v._method = 'PUT';
            form.v.save_button = $_GLOBAL.SHOW.save_button;

            api(`/${$_GET[0]}/${url_admin_dashboard()}/${$_GLOBAL.FORM.v.id>0 ? `${$_GLOBAL.FORM.v.id}/update` : `store`}`, { FORM: form }, (json: any) => {
                if ($_GLOBAL.SHOW.save_button == `datatable`){
                    open__(json?.url ? json?.url : `/${url_admin_dashboard()}`, $variables, 1);
                    $_GLOBAL.SHOW.EDIT_SHOW = 2;
                    iframe_send_father(json?.response);
                    (window as any).parent?.close_iframe__?.();

                } else if ($_GLOBAL.SHOW.save_button == `new`){
                    open__(json?.url ? json?.url : `/${url_admin_dashboard()}/0`, $variables, 1);

                } else {
                    refresh();
                    $_GLOBAL.SHOW.EDIT_SHOW = 2;
                }
            });    
        }
    // STORE / UPDATE




















    // ACTIONS
        // ACTIVE / STAR
            export function modules__actions(value: { id: number }, type: string): void
            {
                let form: any = {
                    id: value.id,
                    _method: 'PUT'
                };
                api(`/${$_GET[0]}/${url_admin_dashboard()}/actions/${type}`, form, (json: any) => {
                    if (json.val != null){
                        const obj = $_GLOBAL.OBJ.DATATABLE as Record<string, { id: number; active: any }>;
                        for (const [key_1, value_1] of Object.entries(obj)) {
                            if (value.id == value_1.id) {
                                $_GLOBAL.OBJ.DATATABLE[key_1][type] = json.val[value_1.id];
                            }
                        }
                    }
                });
            }
        // ACTIVE / STAR


        // ACTIVE_SEL
            export function modules__actions_sel(type: string): void
            {
                let form = {
                    ...$_GLOBAL.FORM.v,
                    sel: $_GLOBAL.FORM.v.sel,
                    _method: 'PUT'
                };
                api(`/${$_GET[0]}/${url_admin_dashboard()}/actions/${type}`, form, (json: any) => {
                    if (json.val != null){
                        const obj = $_GLOBAL.OBJ.DATATABLE as Record<string, { id: number; active: any }>;
                        for (const [key_1, value_1] of Object.entries(obj)) {
                            if (json.val[value_1.id] != null){
                                $_GLOBAL.OBJ.DATATABLE[key_1][type] = json.val[value_1.id];
                            }
                        }
                    }
                });
            }
        // ACTIVE_SEL


        // ORDER
            export function modules__order(): void
            {
                let form: any = {
                    order: $_GLOBAL.FORM.ORDER,
                    _method: 'PUT'
                };
                api(`/${$_GET[0]}/${url_admin_dashboard()}/actions/order`, form, (json: any) => {
                    if (json.val != null){
                        for (const [key, value] of Object.entries(json.val)) {
                            $_GLOBAL.FORM.ORDER[key] = value;
                            refresh();
                        }
                    }
                });
            }
        // ORDER


        // CLONE
            export function modules__clone(): void
            {
                let form = {
                    ...$_GLOBAL.FORM.v,
                    sel: $_GLOBAL.FORM.v.sel,
                    _method: 'PUT'
                };
                api(`/${$_GET[0]}/${url_admin_dashboard()}/actions/clone`, form, (json: any) => {
                    refresh();
                });
            }
        // CLONE


        // BACK
            export function modules__back(): void
            {
                // VARIABLES
                    let $variables: any = {};
                    // GETS
                        // ITEMS
                            if ($_GET['items__']){
                                $variables.items__ = $_GET['items__'];
                            }
                        // ITEMS
                    // GETS
                // VARIABLES

                if ($_GET[0] == `admin`){
                    open__(`/modules/${$_GLOBAL.OBJ.menu_admin.id}`, $variables, 1);

                } else if ($_GET[0] == `dashboard`){
                    open__(`/${url_admin_dashboard()}`, $variables, 1);
                }
            }
        // BACK


        // ITEMS_PAGE_DASHBOARD
            export function items_page_dashboard(): void
            {
                COOKIES_CREATE(`ITEMS_PAGE`, $_GLOBAL.FORM.z_items_page);
                COOKIES_CREATE(`SEARCH__page`, `1`);
                api(`/${$_GET[0]}/${url_admin_dashboard()}`);
            }
        // ITEMS_PAGE_DASHBOARD
    // ACTIONS




















    // SEL
        // SEL
            export function modules__sel(): void
            {
                if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type==`sel`){
                    $_GLOBAL.SHOW.sel_all = !$_GLOBAL.SHOW.sel_all;
                    if ($_GLOBAL.SHOW.sel_all){
                        $_GLOBAL.SHOW.sel_actions = 1;

                        const obj = $_GLOBAL.OBJ?.DATATABLE as Record<string, { id: string }>;
                        if (obj) {
                            for (const [key, value] of Object.entries(obj)) {
                                $_GLOBAL.FORM.v.sel[value.id] = true;
                            }
                        }

                    } else {
                        $_GLOBAL.SHOW.sel_actions = 0;
                        $_GLOBAL.FORM.v.sel = {};
                    }
                }
            }
        // SEL

        // SEL_ALL
            export function modules__sel_all(x: number): void
            {
                if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type == `sel`) {
                    $_GLOBAL.FORM.v.sel_all_all = x;
            
                    const obj = $_GLOBAL.OBJ?.DATATABLE as Record<string, { id: string }>;
            
                    if (obj) {
                        for (const [key, value] of Object.entries(obj)) {
                            $_GLOBAL.FORM.v.sel[value.id] = true;
                        }
                    }
                }
            }
        // SEL_ALL

        // SEL_ITEM
            export function modules__sel_item(value: { id: number }): void
            {
                if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type==`sel`){
                    setTimeout(() => {
                        if (Object.values($_GLOBAL.FORM.v.sel).filter(Boolean).length>0){
                            $_GLOBAL.SHOW.sel_all = true;
                        } else {
                            $_GLOBAL.SHOW.sel_all = false;
                        }

                        if (Object.entries($_GLOBAL.FORM.v.sel).filter(([key, value]) => !value)){
                            $_GLOBAL.FORM.v.sel_all_all = 0;
                        }
                    }, 50);
                }
            }
        // SEL_ITEM

        
        // MOUSE
            // PRESS
                export function mouse_press_start(value: { id: number }): void
                {
                    if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type==`sel` && $_GLOBAL.SHOW.sel_show){
                        if (!$_GLOBAL.SHOW.sel_all){
                            $_GLOBAL.SHOW.mouse_press_move = true;
                            $_GLOBAL.SHOW.mouse_press = setTimeout(() => {
                                $_GLOBAL.SHOW.mouse_press_ok = true;
                                $_GLOBAL.FORM.v.sel[value.id] = !$_GLOBAL.FORM.v.sel[value.id];
                                $_GLOBAL.SHOW.sel_all = true;
                            }, 500)
                        }
                    }
                }

                export function mouse_press_cancel(): void
                {
                    if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type==`sel` && $_GLOBAL.SHOW.sel_show){
                        clearTimeout($_GLOBAL.SHOW.mouse_press);
                        $_GLOBAL.SHOW.mouse_press_move = false;
                    }
                }
            // PRESS


            // MOVE (HOVER)
                export function mouse_move(value: { id: number }): void
                {
                    if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type==`sel` && $_GLOBAL.SHOW.sel_show){
                        if ($_GLOBAL.SHOW.MOUSE__CLICK && $_GLOBAL.SHOW.sel_all){
                            $_GLOBAL.FORM.v.sel[value.id] = true;
                        }
                    }
                }

                export function mouse_move_action(): void
                {
                    if ($_GLOBAL.OBJ?.COLUMNS?.[0]?.type==`sel`){
                        $_GLOBAL.SHOW.MOUSE__CLICK = false;
                        if ($_GLOBAL.SHOW?.MOUSE__DOWN == null){
                            document.addEventListener('mousedown', function(event: MouseEvent) {
                                if (event?.target){
                                    let $e = event.target as HTMLElement;

                                    let target_1 = $e.tagName === 'TD';
                                    let target_2 = $e.parentNode instanceof HTMLElement && $e.parentNode.tagName === 'TD';
                                    let target_3 = $e.parentNode?.parentNode instanceof HTMLElement && $e.parentNode.parentNode.tagName === 'TD';
                                    let target_4 = $e.parentNode?.parentNode?.parentNode instanceof HTMLElement && $e.parentNode.parentNode.parentNode.tagName === 'TD';
                                    let target_5 = $e.parentNode?.parentNode?.parentNode?.parentNode instanceof HTMLElement && $e.parentNode.parentNode.parentNode.parentNode.tagName === 'TD';
                                    let target_6 = $e.parentNode?.parentNode?.parentNode?.parentNode?.parentNode instanceof HTMLElement && $e.parentNode.parentNode.parentNode.parentNode.parentNode.tagName === 'TD';
                
                                    if (event.buttons == 1 && (target_1 || target_2 || target_3 || target_4 || target_5 || target_6)){
                                        $_GLOBAL.SHOW.MOUSE__CLICK = true;
                                    }
                                }
                            });
                            document.addEventListener('mouseup', function() {
                                $_GLOBAL.SHOW.MOUSE__CLICK = false;
                                setTimeout(() => { $_GLOBAL.SHOW.mouse_press_ok = false; }, 50);
                            });
                        }

                        $_GLOBAL.SHOW.MOUSE__DOWN = true;
                    }
                }
            // MOVE (HOVER)
        // MOUSE
    // SEL




















    // MODULES__TABLE__EXPORT
        export function modules__table__export(type: string): void
        {
            let form: any = {};
            form.name = $_GLOBAL.OBJ.menu_admin.name;
            form.table = $_GLOBAL.OBJ.menu_admin.table;


            // AUTH
                let token = rootAuth();
                if (token){
                    form.Authorization = `Bearer ${token}`;
                }
            // AUTH


            // THEAD
                form.thead = [];
                document.querySelectorAll(`.list_${$_GLOBAL.OBJ.menu_admin.id} table.datatable thead tr th.th_`).forEach(td => {
                    let data = strip_tags(td.innerHTML).trim();
                    form.thead.push(data);
                });
            // THEAD


            // TBODY
                form.tbody = [];
                document.querySelectorAll(`.list_${$_GLOBAL.OBJ.menu_admin.id} table.datatable tbody tr`).forEach(tr => {
                    let tbody_td: any = [];
                    tr.querySelectorAll(`td.td_`).forEach(td => {
                        let data: any = ``;
                        if (td.getAttribute('exportar') != null){
                            data = td.getAttribute('exportar');

                        } else {
                            data = td.innerHTML;
                            data = replace('</div></div', '</div>;z;</div', data);
                            data = replace('</div> <div', '</div>;z; <div', data);
                            data = replace('<div><div>', ';z;', data);
                            data = strip_tags(data);
                            data = data.trim();
                            if (type == 'pdf'){
                                data = replace(';z;', '<br>', data);
                            } else {
                                data = replace(';z;', '', data);
                            }
                        }

                        tbody_td.push(data);
                    });

                    form.tbody.push(tbody_td);
                });
            // TBODY


            form.thead = JSON.stringify(form.thead);
            form.tbody = JSON.stringify(form.tbody);
            open_form(`${DIR_API()}/export/${type}`, form);

            alerts(1, `Aguarde um momento: sua exportação está em andamento e estará pronta em breve!`, 0, 0, `top mt50`);
            $_GLOBAL.SHOW.BOXS = ``;
        }
    // MODULES__TABLE__EXPORT




















    // TABLE_ORDER
        export function modules__table_order(name: string): string
        {
            let $return = ``;

            if ($_GLOBAL.OBJ.PAGG.DATATABLE_all?.z_table_order?.[0] != null){
                let $col = $_GLOBAL.OBJ.PAGG.DATATABLE_all.z_table_order[0].toLowerCase();
                $col = replace('`', '', $col);

                if ($col == `${name} asc`){
                    $return = `asc`
                }
                if ($col == `${name} desc`){
                    $return = `desc`
                }
            }

            return $return;
        }

        export function modules__table_order_click(name: string): void
        {
            let input_atual = ``;
            if ($_GLOBAL.OBJ.PAGG.DATATABLE_all?.z_table_order?.[0] != null){
                input_atual = $_GLOBAL.OBJ.PAGG.DATATABLE_all.z_table_order[0].toLowerCase();
                input_atual = replace('`', '', input_atual);
                input_atual = input_atual.replace(' asc', '');
                input_atual = input_atual.replace(' desc', '');
            }

            let orderBy = modules__table_order(name);
            if (input_atual == name){
                orderBy = orderBy=='desc' ? 'asc' : 'desc';
            } else {
                orderBy = 'asc';
            }

            let array: any = [];
            array.push(`${name} ${orderBy}`);

            COOKIES_CREATE(`SEARCH__orderBy`, json_encode(array));
            api(`/${$_GET[0]}/${url_admin_dashboard()}`);
        }
    // TABLE_ORDER




















    // TABLE_ITEMS
        export function modules__table_items(items__: { id: number }, value: { id: number | string, table__: string }, col_name: any): void
        {
            open__(`/modules/${items__.id}`, { n: col_name, items__: `${$_GLOBAL.OBJ.menu_admin.id}-${value.id}-${$_GLOBAL.OBJ.menu_admin.table}` }, 1);
        }
    // TABLE_ITEMS




















    // CLICK
        export function modules__click(col: any, value: any): any
        {
            return new__modules__click(col, value);
        }
    // CLICK
