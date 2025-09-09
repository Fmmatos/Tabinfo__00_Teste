<script setup lang="ts">
import HTML__ from '@/vendor/components/input/__html.vue';

import PAGINATE__ from '@/vendor/components/paginate.vue';
import SEARCH_TOP__ from '@/vendor/pages_admin/x_search_top.vue';

import NEW__TABLE__ from '@/pages_admin/__new__table.vue';
import NEW__SEL__ from '@/pages_admin/__new__sel.vue';

import { img, replace, tooltip, limit, MOBILE, open__,count, compare__fim, compare__, in_array, compare__ini, url_admin_dashboard, implode, extra__, price, in_array_key, count_x, COOKIES, is_number, html_treatment } from '@/vendor/services/events';
import { mouse_move_action, modules__search, modules__search_reset, modules__create_edit, modules__delete, modules__actions, modules__actions_sel, modules__sel, modules__sel_all, modules__sel_item, mouse_press_start, mouse_press_cancel, mouse_move, modules__order, modules__clone, modules__table_order, modules__table_order_click, modules__table_items, modules__click, items_page_dashboard, modules__table__export } from '@/vendor/services/modules';
import { LOCALHOST, PROG } from '@/vendor/services/localhost';
import { inject, onBeforeMount, onErrorCaptured  } from 'vue';

tooltip();
mouse_move_action();

    // GLOBALTHIS
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        $_GET['__TABLE__'] = 1;
        $_GET['__EDIT__'] = 0;
    // GLOBALTHIS
    
    // SHOW
        SHOW.sel_show = 0;
        SHOW.FILTER_TOP = MOBILE() ? 0 : 1;
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        onBeforeMount(() => {
            FORM.v.search = COOKIES('SEARCH__search') || '';
        })
        // IGNORE ERROR
            onErrorCaptured((err) => {
                if (
                    err instanceof DOMException &&
                    err.name === 'NotFoundError' &&
                    err.message.includes('insertBefore')
                ) {
                    return false;
                }
            });
        // IGNORE ERROR
    // EVENTS

    // FUNCTIONS
        const __order_x = () => {
            if (!$_GLOBAL['x']){
                $_GLOBAL['x'] = 0;
            }

            $_GLOBAL['x']++;
            return $_GLOBAL['x'];
        }

        const __total = (col: any, sel: boolean = false, price__: boolean = false) => {
            let $return = 0;
            for (const [key, value] of Object.entries(OBJ?.DATATABLE)){
                let val: any = value;
                for (const [key_1, value_1] of Object.entries(val)){
                    if (col.name == key_1){
                        if (!sel || (sel && in_array_key(val.id, FORM.v.sel))){
                            if(price__){
                                $return += Number(val?.[`${key_1}__`] ?? 0);
                            } else {
                                if(is_number(val?.[key_1])){
                                    $return += Number(val?.[key_1] ?? 0);
                                } else {
                                    $return += Number(val?.[`${key_1}__`] ?? 0);
                                }
                            }
                        }
                    }
                }
            }
            return price__ ? price($return) : $return;
        }

        const __tfoot = () => {
            // NO DATA
                if (!count(OBJ?.DATATABLE) || OBJ?.DATATABLE == null){
                    return false;
                }
            // NO DATA

            for (const [key, col] of Object.entries(OBJ?.COLUMNS)){
                let col__: any = col;
                if (col__.type != `price`){
                    return true;
                }
            }
            return false;
        }

        const __router_current = () => {
            let title = ``;
            for (const [key, value] of Object.entries($_GLOBAL.OBJ.menu_side)) {
                let val: any = value;

                if ($_GLOBAL.ROUTE.path == `/dashboard${val?.url}`){
                    title = val?.name;
                }
            
                if (val?.submenu){
                    for (const [key_1, value_1] of Object.entries(val.submenu)) {
                        let val_1: any = value_1;
                        if ($_GLOBAL.ROUTE.path == `/dashboard${val_1?.url}`){
                            title = val_1?.name;
                        }
                    }
                }
            }
            return title;
        }
    // FUNCTIONS
</script>


<template>

    <div :class="`__TABLE__${OBJ?.menu_admin?.table}__`">
        <!-- TOP -->
            <div>
                <div class="flexx_700 flex_j flex_ac">
                    <div class="h40 pb10 pl5 pr5 flexx flex_ac">
                        <div v-if="SHOW.GET[0] == `admin`" class="pr20 fz20 fwb6 c_333">{{ OBJ.menu_admin?.name }}</div>
                        <div v-else-if="__router_current()" class="pr20 fz20 fwb6 c_333">{{ __router_current() }}</div>
                        <div v-else class="pr20 fz20 fwb6 c_333">{{ OBJ.menu_admin?.name }}</div>
                    </div>
                    <div class="flexx_x flex_ac">
                        <div v-html="html_treatment(OBJ?.individual?.button)"></div>
                        <!-- EXPORT -->
                            <button v-if="OBJ.menu_admin?.info?.includes(`excel`) || OBJ.menu_admin?.info?.includes(`pdf`)" @click="SHOW.BOXS = `EXPORT`" class="mb10 mr8 limit1 button_admin_2 __EXPORTAR__">Exportar</button>
                        <!-- EXPORT -->

                        <!-- NEW -->
                            <div v-if="OBJ.menu_admin?.info?.includes(`create`) && OBJ.menu_admin?.info?.includes(`create_pf_pj`)" class="flexx_x flex_ac">
                                <a @click="open__(`/${url_admin_dashboard()}/0`, { pf: 1 })" class="mb10 mr8 b_black limit1 button_admin_1 __CREATE__">Novo (PF)</a>
                                <a @click="open__(`/${url_admin_dashboard()}/0`, { pj: 1 })" class="mb10 mr8 b_black limit1 button_admin_1 __CREATE__">Novo (PJ)</a>
                            </div>
                            <a v-else-if="OBJ.menu_admin?.info?.includes(`create`) && !OBJ.menu_admin?.info?.includes(`create_hide`)" @click="open__(`/${url_admin_dashboard()}/0`, SHOW.GET?.['items__'] ? { items__: SHOW.GET['items__'] } : {}, 1)" class="mb10 mr8 b_black limit1 button_admin_1 __CREATE__">Criar Novo</a>
                        <!-- NEW -->

                        <NEW__TABLE__ type="button" />
                    </div>
                </div>
            </div>
        <!-- TOP -->










        <!-- BOX TABLE -->
            <!-- INDIVIDUAL (TOP_1) -->
                <div v-html="html_treatment(OBJ?.individual?.top_1)"></div>
                <NEW__TABLE__ type="top_1" />
            <!-- INDIVIDUAL (TOP_1) -->


            <div class="bd_ddd bg_fff br10 shadow_0 fade_in_3">
                <!-- INDIVIDUAL (TOP_2) -->
                    <div v-html="html_treatment(OBJ?.individual?.top_2)"></div>
                    <NEW__TABLE__ type="top_2" />
                <!-- INDIVIDUAL (TOP_2) -->


                <!-- SEARCH TOP -->
                    <SEARCH_TOP__ v-if="OBJ.menu_admin?.info?.includes(`search_top_on`) && SHOW.FILTER_TOP" />
                <!-- SEARCH TOP -->


                <!-- SEARCH / TAGS / SEL ACTIONS ABSOLUTE -->
                    <div class="p5 flexx_x flex_j flex_ac gap_10">

                        <!-- TAGS -->
                            <div v-show="!(SHOW.sel_actions || (FORM.v?.sel && Object.values(FORM.v.sel).filter(Boolean).length>0))">
                                <ul class="flex_1 pl10 pr10 flexx flex_ac">
                                    <li v-if="count(OBJ?.TAGS)" v-for="value, key in OBJ.TAGS" :key="key">
                                        <a @click="open__(`/${url_admin_dashboard()}`, { t: value.id ? value.id : `all` }, 1)" class="posr p5 pl10 pr10 mr10 fwb6 br8" :class="SHOW.GET['t']==value.id ? `bg_EBEBEB` : ``">{{ value.name }}</a>
                                    </li>
                                    <li v-else>
                                        <a class="posr p5 pl10 pr10 mr10 fwb6 bg_EBEBEB br8">Tudo</a>
                                    </li>
                                </ul>
                            </div>
                        <!-- TAGS -->


                        <!-- SEL ACTIONS ABSOLUTE -->
                            <div v-show="OBJ?.COLUMNS?.[0]?.type==`sel`">
                                <div v-show="SHOW.sel_actions || (FORM.v?.sel && Object.values(FORM.v.sel).filter(Boolean).length>0)">
                                    <div class="w100p pl20 pr20 flex_ac" :class="MOBILE() ? `` : `flexx`">
                                        <div v-if="!FORM.v.sel_all_all" class="flexx flex_ac">
                                            <div v-if="FORM.v?.sel" class="fwb6">{{ Object.values(FORM.v.sel).filter(Boolean).length }} selecionados</div>
                                            <a @click="modules__sel_all(1)" class="ml10 fwb6 c_blue">Selecionar tudo os itens</a>
                                        </div>
                                        <div v-else class="flexx flex_ac">
                                            <div class="fwb6">Todos os itens estão selecionados</div>
                                            <a @click="modules__sel_all(0)" class="ml10 fwb6 c_blue">Desfazer</a>
                                        </div>
                                        <div :class="MOBILE() ? `pt10` : `pl20`">
                                            <div class="mr10 flexx flex_ac gap_5">
                                                <slot v-for="star in OBJ.menu_admin?.info">
                                                    <a v-if="compare__(`star_`, star)" @click="modules__actions_sel(star)" class="db fwb5 flexx flex_c flex_ac c_fff_i button_admin_2 shadow_0" :class="OBJ?.COLUMNS?.z_star?.info?.icons?.[star]?.bg">
                                                        <div class="dni">{{ SHOW.sel_show = 1 }}</div>
                                                        <div class="mr5"><i :class="OBJ?.COLUMNS?.z_star?.info?.icons?.[star]?.icon"></i></div>
                                                        <div v-if="!MOBILE()">{{ OBJ?.COLUMNS?.z_star?.info?.tooltip?.[star] }}</div>
                                                    </a>
                                                </slot>
                                                <a v-if="OBJ.menu_admin?.info && in_array(`clone`, OBJ.menu_admin?.info)" @click="modules__clone()" class="db fwb5 flexx flex_c flex_ac c_fff_i b_purple button_admin_2 shadow_0">
                                                    <div class="dni">{{ SHOW.sel_show = 2 }}</div>
                                                    <div class="mr5"><i class="faa-copy (alias)"></i></div>
                                                    <div v-if="!MOBILE()">Clonar</div>
                                                </a>
                                                <a v-if="OBJ.menu_admin?.info && in_array(`columns_active`, OBJ.menu_admin?.info)" @click="modules__actions_sel(`active`)" class="db fwb5 flexx flex_c flex_ac c_fff_i b_blue button_admin_2 shadow_0">
                                                    <div class="dni">{{ SHOW.sel_show = 3 }}</div>
                                                    <div class="mr5"><i class="faa-unlock-alt"></i></div>
                                                    <div v-if="!MOBILE()">Ativar/Desativar</div>
                                                </a>
                                                <a v-if="OBJ.menu_admin?.info && in_array(`delete`, OBJ.menu_admin?.info)" @click="modules__delete()" class="db fwb5 flexx flex_c flex_ac c_fff_i b_red button_admin_2 shadow_0">
                                                    <div class="dni">{{ SHOW.sel_show = 4 }}</div>
                                                    <div class="mr5"><i class="faa-times"></i></div>
                                                    <div v-if="!MOBILE()">Excluir</div>
                                                </a>
                                                <NEW__SEL__ />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- SEL ACTIONS ABSOLUTE -->


                        <!-- SEARCH // ETC -->
                            <div class="flexx flex_ac">
                                <!-- SEARCH_TOP -->
                                    <div v-if="OBJ.menu_admin?.info?.includes(`search_top_on`)" @click="SHOW.FILTER_TOP = !SHOW.FILTER_TOP" class="p5 pl8 pr8 ml10 mr5 c-p flexx flex_c flex_ac bd_ddd bg_fff br7 __SEARCH_TOP__"><i class="faa-search fz14"></i></div>
                                <!-- SEARCH_TOP -->

                                <!-- BOX_ITEMS_PAGE__ -->
                                    <div v-if="OBJ.menu_admin?.info?.includes(`items_page`)" class="pr5">
                                        <select v-model="FORM.z_items_page" @change="items_page_dashboard()" class="w70 h30 pl5 pr5 flexx flex_ac bd_ddd bg_fff br7">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="250">250</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                            <option value="5000">5000</option>
                                            <!-- <option value="10000">10000</option> -->
                                        </select>
                                    </div>
                                <!-- BOX_ITEMS_PAGE__ -->

                                <!-- COLUMNS -->
                                    <div v-if="SHOW.GET[0] == `admin` && OBJ.menu_admin?.info?.includes(`columns_orders`)" class="p3 mr5 c-p flexx flex_c flex_ac bd_ddd bg_fff br7 __COLUMNS__" tooltip="Colunas"><svg class="w20 c-p c_666" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M3 6.75c0-2.071 1.679-3.75 3.75-3.75h6.5c2.071 0 3.75 1.679 3.75 3.75v6.5c0 2.071-1.679 3.75-3.75 3.75h-6.5c-2.071 0-3.75-1.679-3.75-3.75v-6.5Zm3.75-2.25c-1.243 0-2.25 1.007-2.25 2.25v6.5c0 1.243 1.007 2.25 2.25 2.25h.5v-11h-.5Zm4.5 11h-2.5v-11h2.5v11Zm1.5 0h.5c1.243 0 2.25-1.007 2.25-2.25v-6.5c0-1.243-1.007-2.25-2.25-2.25h-.5v11Z"></path></svg></div>
                                <!-- COLUMNS -->

                                <!-- SEARCH -->
                                    <div class="__SEARCH__">
                                        <div class="posa pt5 pl10"><svg class="w20 c_666" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path fill="currentColor" fill-rule="evenodd" d="M12.323 13.383a5.5 5.5 0 1 1 1.06-1.06l2.897 2.897a.75.75 0 1 1-1.06 1.06l-2.897-2.897Zm.677-4.383a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"></path></svg></div>
                                        <input type="search" @keyup.enter="modules__search()" @input="modules__search_reset()" v-model="FORM.v.search" class="h30 pl35 pr10 flexx flex_ac bd_ddd bg_fff br7 search_focus__CSS" :class="MOBILE(700) ? 'w200' : 'w300'" placeholder="Pesquisar..." tooltip="Aperte Enter para Pesquisar" />
                                    </div>
                                <!-- SEARCH -->
                            </div>
                        <!-- SEARCH // ETC -->

                    </div>
                <!-- SEARCH / TAGS / SEL ACTIONS ABSOLUTE -->
                









                <!-- TABLE -->
                    <form @submit.prevent="modules__order()">
                        <div class="table_mobile" :class="`list_${OBJ.menu_admin.id}`">
                            <table class="datatable" @selectstart="SHOW.sel_show && $event.preventDefault()">
                                <thead  class="posr">
                                    <tr :class="OBJ.menu_admin?.info?.includes(`table_thead_fixed`) ? `fixed` : ``">
                                        <slot v-for="(col, key) in OBJ?.COLUMNS" :key="key">
                                            <th
                                                v-if="(col.type != `no` && col.type != `sel`) || (SHOW.sel_show && col.type == `sel`)"
                                                class="th_"
                                                :class="[
                                                    `type_${col.type}`,
                                                    col?.class ? col.class : ``,
                                                    col?.table_align ? (col.type == `sel` ? `tal` : col.table_align) : `tac`,
                                                    col.type==`sel` ? `w38` : ``,
                                                    col.name==`id` ? `w100` : ``,
                                                    col?.type == `file` || col?.name == `image` || compare__ini('image_', col?.name) ? `w56` : ``,
                                                    modules__table_order(col.name),
                                                ]"
                                                :tooltip="extra__(col?.extra, `|->tooltip_thead`)?.[1]"
                                            >
                                                <div v-if="col.type == `sel`" class="posr w38 h14">
                                                    <label class="posa t0 l0 w100p h100p c-p flexx flex_c flex_ac"><input type="checkbox" @click="modules__sel()" v-model="SHOW.sel_all" /></label>
                                                </div>
                                                <div v-else @click="modules__table_order_click(col.name)">
                                                    <div class="c-p">{{ col.label }}</div>
                                                </div>
                                            </th>
                                        </slot>
                                    </tr>
                                </thead>


                                <tbody>
                                    <tr v-for="value in OBJ?.DATATABLE" :key="value.id" :dir="value.id">
                                        <slot v-for="(col, key) in OBJ?.COLUMNS" :key="`${value.id}.${key}`">
                                            <td
                                                v-if="(col.type != `no` && col.type != `sel`) || (SHOW.sel_show && col.type == `sel`)"
                                                class="td_"
                                                :class="[
                                                    `type_${col.type}`,
                                                    col?.class ? col.class : ``,
                                                    col.table_align ? col.table_align : `tac`,
                                                    (col.type==`sel` || col?.type == `file` || col?.name == `image` || compare__ini('image_', col?.name) || compare__(`new_fields__`, col.tags) || compare__(`|->table_css->w`, col?.extra)) ? `` : `min-w100`,
                                                    col.name==`active` || col.name==`order` ? `w150 c-d` : ``,
                                                    col?.type == `file` || col?.name == `image` || compare__ini('image_', col?.name) ? `w56` : ``,
                                                    compare__(`|->table_css->`, col?.extra) ? extra__(col?.extra, `|->table_css`)?.[1] : ``,
                                                ]"
                                                :val="!compare__(`-->HTML`, value?.[col.name]) ? value?.[col.name] : `-->HTML`"
                                                :val__="value?.[`${col.name}__`]"

                                                @click="
                                                    col.type!=`sel` 
                                                    // && col.type!=`checkbox` 
                                                    // && col.type!=`radio` 
                                                    && col.type!=`file` 
                                                    && col.name!=`active` 
                                                    && col.name!=`order` 
                                                    && col.type!=`actions` 
                                                    && !compare__(`|->items`, col.extra)
                                                    && !compare__(`|->no_click`, col.extra)
                                                    && !compare__(`-->HTML_ARRAY`, value?.[col.name])
                                                    && !(compare__(`-->HTML`, value?.[col.name]) && compare__(`href=`, value?.[col.name]))
                                                    ? modules__create_edit(value.id, value)
                                                    : compare__(`|->no_click`, col.extra) ? modules__click(col, value) : ``"

                                                @mousedown="mouse_press_start(value)"
                                                @mouseup="mouse_press_cancel()"
                                                @mouseleave="mouse_press_cancel()"
                                                @mousemove="mouse_move(value)"
                                            >
                                                <a
                                                    class="db"
                                                    :class="[
                                                        col.type!=`sel` 
                                                        // && col.type!=`checkbox` 
                                                        // && col.type!=`radio` 
                                                        && col.type!=`file` 
                                                        && col.name!=`active` 
                                                        && col.name!=`order` 
                                                        && col.type!=`actions` 
                                                        && !compare__(`|->items`, col.extra)
                                                        && !compare__(`|->no_click`, col.extra)
                                                        ? ``
                                                        : `not_mouse_right_click`
                                                    ]"
                                                >
                                                    <div v-if="
                                                        (
                                                            key==0
                                                            || (
                                                                !SHOW.sel_show
                                                                && key==1
                                                                && (LOCALHOST() || PROG())
                                                            )
                                                        )
                                                        && (
                                                            SHOW.GET[0]!=`dashboard`
                                                            || LOCALHOST()
                                                            || PROG()
                                                        )
                                                    " class="posa l0 pb2 pl2 fz10" style="bottom: -4px; min-height: auto !important; color: #999">#{{ value.id }}</div>


                                                    <!-- NEW_FIELDS -->
                                                        <div v-if="compare__(`new_fields__`, col.tags)">
                                                            <NEW__TABLE__ type="new_fields" :col="col" :value="value" :item="value[col.name]" />
                                                        </div>
                                                    <!-- NEW_FIELDS -->





                                                    <!-- SEL -->
                                                        <div v-else-if="col.type == `sel`" class="posr w38 h20">
                                                            <label class="posa t0 l0 w100p h100p c-p flexx flex_c flex_ac">
                                                                <input type="checkbox" @click="modules__sel_item(value)" v-model="FORM.v.sel[value.id]" />
                                                            </label>
                                                        </div>
                                                    <!-- SEL -->





                                                    <!-- CREATED_AT / UPDATED_AT / ETC -->
                                                        <div v-else-if="compare__fim(`_at`, col.name)">
                                                            <div class="">{{ value[col.name] }} </div>
                                                        </div>
                                                    <!-- CREATED_AT / UPDATED_AT / ETC -->





                                                    <!-- JSON_FIELDS -->
                                                        <div v-else-if="col.type == `json_fields`">
                                                            <div v-if="compare__(`|->implode->`, col?.extra)">
                                                                <div v-html="html_treatment(implode(extra__(col.extra, '|->implode')[1], value[col.name]))"></div>
                                                            </div>
                                                            <div v-else v-html="html_treatment(implode(`, `, value[col.name]))"></div>
                                                        </div>
                                                    <!-- JSON_FIELDS -->





                                                    <!-- COLOR -->
                                                        <div v-else-if="col.type == `color` || (typeof col.name === 'string' && col.name.indexOf(`color_`)>0)">
                                                            <div class="dib w40 h40 bd_ccc br5" :style="`background: ${value[col.name]??'#000'}`"></div>
                                                        </div>
                                                    <!-- COLOR -->





                                                    <!-- PRICE -->
                                                        <div v-else-if="col?.type == `price` && !(value[col.name] && compare__(`-->HTML`, value[col.name]))" class="">
                                                            <div v-if="col?.name == `price` || compare__(`price_`, col?.name) || compare__(`_price`, col?.name)">{{ OBJ?.info?.currency ? OBJ.info.currency : `R$` }}&nbsp;{{ value[col.name] }}</div>
                                                            <div v-else>{{ value[col.name] }}</div>
                                                        </div>
                                                    <!-- PRICE -->





                                                    <!-- IMAGE -->
                                                        <div v-else-if="col?.type == `file` || col?.name == `image` || compare__ini('image_', col?.name)" class="">
                                                            <a :href="value[`${col.name}__`] ? value[`${col.name}__`] : `javascript:void(0)`" :target="value[`${col.name}__`] ? `_blank` : ``">
                                                                <div class="w40 tac" v-html="img(value, 40, 40, `dib bd_aaa br5`)"></div>
                                                            </a>
                                                        </div>
                                                    <!-- IMAGE -->





                                                    <!-- ACTIONS -->
                                                        <!-- ACTIVE -->
                                                            <div v-else-if="col.name == `active`">
                                                                <div class="">
                                                                    <div class="dni">{{ value[col.name] }}</div>
                                                                    <button type="button" v-show="value[col.name]==1" @click="modules__actions(value, col.name)" class="dib w60 bd0 bg0"><div class="pt4 pb4 pl5 pr5 fwb5 flexx flex_c flex_ac c_fff b_green br5" tooltip="Clique para Desativar" ><i class="faa-check dib vam mr2 fz12"></i><div class="dib vam">Ativo</div></div></button>
                                                                    <button type="button" v-show="value[col.name]==0" @click="modules__actions(value, col.name)" class="dib w80 bd0 bg0"><div class="pt5 pb5 pl5 pr5 fwb5 c_333 bg_ccc br5"  tooltip="Clique para Ativar">Desativado</div></button>
                                                                </div>
                                                            </div>
                                                        <!-- ACTIVE -->





                                                        <!-- ORDER -->
                                                            <div v-else-if="col.type == `order`">
                                                                <div class="">
                                                                    <input type="text" v-model="FORM.ORDER[value.id]" size="1" class="w60 h30 pl10 pr10 design tac order_focus" :maxlength="OBJ.menu_admin.id==1 ? 4 : 3" onclick="this.select()" :tabindex="__order_x()" tooltip="Aperte Enter para Salvar">
                                                                </div>
                                                            </div>
                                                        <!-- ORDER -->





                                                        <!-- ITEMS -->
                                                            <div v-else-if="col.extra != null && compare__(`|->items`, col.extra)">
                                                                <a @click="modules__table_items(value[`items__${col.order}__${col.name}__`], value, col.name)" class="w50 m-a">
                                                                    <i class="faa-bars"></i>
                                                                    &nbsp;|&nbsp;
                                                                    <span :class="`items__${col.order}__${col.name}`">{{value[`items__${col.order}__${col.name}`]}}</span>
                                                                </a>
                                                            </div>
                                                        <!-- ITEMS -->





                                                        <!-- STAR -->
                                                            <div v-else-if="col.type == `actions` && col.name == `star`">
                                                                <div class="dib">
                                                                    <ul class="flexx flex_c flex_ac">
                                                                        <slot v-for="star in OBJ.menu_admin?.info">
                                                                            <li v-if="compare__(`star_`, star)"><i @click="modules__actions(value, star)" class="dib p10 pl5 pr5 fz16" :class="[value?.[star] ? OBJ?.COLUMNS[key]?.info?.icons?.[star]?.color : `c_ccc`, OBJ?.COLUMNS[key]?.info?.icons?.[star]?.icon]" :tooltip="OBJ?.COLUMNS[key]?.info?.tooltip?.[star]"></i></li>
                                                                        </slot>
                                                                    </ul>
                                                                </div>

                                                            </div>
                                                        <!-- STAR -->
                                                    <!-- ACTIONS -->





                                                    <!-- JSON -->
                                                        <div v-else-if="col.extra != null && col.extra.match(`json->`)">
                                                            <div v-if="col.extra == 'json->icons'"><i :class="`${value[col.name]} ${value?.color ? `c_${replace(`#`, ``, value?.color)}` : ``}`" class="fz16"></i></div>
                                                        </div>
                                                    <!-- JSON -->





                                                    <!-- TEXTAREA -->
                                                        <div v-else-if="col.type != null && col.type == `textarea`">
                                                            <div class="dib" :tooltip="value[col.name] && value[col.name].length>100 ? value[col.name] : ``" >
                                                                {{ limit(value[col.name], 100) }}
                                                            </div>
                                                        </div>
                                                    <!-- TEXTAREA -->





                                                    <!-- HTML -->
                                                        <div v-else-if="value[col.name] && compare__(`-->HTML`, value[col.name])" :style="col.name==`name` ? `min-width: 200px;` : ``">
                                                            <HTML__ :col="col" :value="value" />
                                                        </div>
                                                    <!-- HTML -->





                                                    <!-- ID -->
                                                        <div v-else-if="col.name == `id`">
                                                            <div>#{{ value[col.name] }}</div>
                                                        </div>
                                                    <!-- ID -->





                                                    <!-- TEXT -->
                                                        <div v-else>
                                                            <div class="dib" :style="[!compare__(`|->table_css->w`, col?.extra) ? (col.name==`name` ? `min-width: 200px;` : `min-width: 100px;`) : ``, count_x(OBJ?.COLUMNS)>=3 ? `max-width: 400px` : ``]">
                                                                {{ limit(value[col.name], 200) }}
                                                            </div>
                                                        </div>
                                                    <!-- TEXT -->

                                                </a>
                                            </td>
                                        </slot>
                                    </tr>
                                </tbody>


                                <tfoot v-if="__tfoot()"  class="posr">
                                    <tr>
                                        <slot v-for="(col, key) in OBJ?.COLUMNS" :key="key">
                                            <td
                                                v-if="(col.type != `no` && col.type != `sel`) || (SHOW.sel_show && col.type == `sel`)"
                                                class="td_"
                                                :class="[
                                                    `type_${col.type}`,
                                                    col?.table_align ? (col.type == `sel` ? `tal` : col.table_align) : `tac`,
                                                    modules__table_order(col.name),
                                                ]"
                                            >
                                                <div v-if="col.type == `price` || compare__(`price`, col.tags)" class="min-w100">
                                                    <div>{{ __total(col, false, true) }}</div>
                                                    <div class="pt10" v-show="SHOW.sel_actions || (FORM.v?.sel && Object.values(FORM.v.sel).filter(Boolean).length>0)">({{ __total(col, true, true) }})</div>
                                                </div>
                                                <div v-else-if="col.type == `number`" class="min-w100">
                                                    <div>{{ __total(col) }}</div>
                                                    <div class="pt10" v-show="SHOW.sel_actions || (FORM.v?.sel && Object.values(FORM.v.sel).filter(Boolean).length>0)">({{ __total(col, true) }})</div>
                                                </div>
                                                <div v-else>&nbsp;</div>
                                            </td>
                                        </slot>
                                    </tr>
                                </tfoot>
                            </table>


                            <!-- NO FOUND -->
                                <div v-if="!count(OBJ?.DATATABLE) || OBJ?.DATATABLE == null" class="p10 pt30 fz13 tac c_666">Nenhum item encontrado...</div>
                            <!-- NO FOUND -->

                            
                            <!-- PAGINATE__ -->
                                <div class="pt20 pl10 pr10 flexx flex_j">
                                    <div class="pt10 pb10 info">{{ `${OBJ?.PAGG?.DATATABLE_all?.from ? OBJ?.PAGG?.DATATABLE_all?.from : 0} - ${OBJ?.PAGG?.DATATABLE_all?.to ? OBJ?.PAGG?.DATATABLE_all?.to : 0} de ${OBJ?.PAGG?.DATATABLE_all?.total ? OBJ?.PAGG?.DATATABLE_all?.total : 0} resultados` }}</div>
                                    <div class="pb5"><PAGINATE__ /></div>
                                </div>
                            <!-- PAGINATE__ -->

                        </div>

                        <button class="dni"></button>
                    </form>
                <!-- TABLE -->


                <!-- INDIVIDUAL (FOOTER_1) -->
                    <div v-html="html_treatment(OBJ?.individual?.footer_1)"></div>
                    <NEW__TABLE__ type="footer_1" />
                <!-- INDIVIDUAL (FOOTER_1) -->
            </div>


            <!-- INDIVIDUAL (FOOTER_2) -->
                <div v-html="html_treatment(OBJ?.individual?.footer_2)"></div>
                <NEW__TABLE__ type="footer_2" />
            <!-- INDIVIDUAL (FOOTER_2) -->
        <!-- BOX TABLE -->










        <!-- BOXS -->
            <!-- EXPORT -->
                <div v-if="SHOW.BOXS == `EXPORT`">
                    <div class="__BOXS__">
                        <em></em>
                        <div class="top">
                            <div class="posr w600 p20 m-a bg_fff br7 shadow w100p_600">
                                <button @click="SHOW.BOXS=``" class="close not_mouse_right_click"><img src="@/vendor/assets/img/svg/default/close.svg" /></button>

                                <div class="">
                                    <div class="pb10 fz16 fwb5 bdb_eee">Exportar</div>
                                    <div class="pt20 flexx flex_ac gap_10">
                                        <a v-if="OBJ.menu_admin?.info?.includes(`excel`)" @click="modules__table__export(`excel`)" class="mb10 button_admin_2"><i class="mb2 mr5 faa-file-excel-o c_green"></i> Exportar em Excel</a>
                                        <a v-if="OBJ.menu_admin?.info?.includes(`pdf`)" @click="modules__table__export(`pdf`)" class="mb10 button_admin_2"><i class="mb2 mr5 faa-file-pdf-o c_red"></i> Exportar em PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- EXPORT -->





            <!-- <SEARCH_ADV__ v-if="SHOW.ACTIONS__SEARCH_ADV" /> -->
            <!-- <COLUMNS__ v-if="SHOW.ACTIONS__COLUMNS" /> -->
        <!-- BOXS -->
    </div>

</template>

<style scoped>
    .search_focus__CSS:focus { border: 1px solid #666; }
</style>