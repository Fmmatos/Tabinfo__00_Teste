<script setup lang="ts">
import { dashboard__menu_side } from '@/services/NEW__events';
import { compare__, open__ } from '@/vendor/services/events';
import { logout } from '@/vendor/storages/auth';
import { inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.MENU_SIDE__WIDTH = 280;
    // SHOW

    // FORM
    // FORM

    // OBJ
        dashboard__menu_side();
    // OBJ

    // EVENTS
        // MENU MOBILE
        if (window.innerWidth < 1601){
                SHOW.MENU_SIDE = 0;
            }
        // MENU MOBILE
    // EVENTS

    // FUNCTIONS
        const __active = (value: any) => {
            if (value?.pages && value.pages.includes(`${$_GET['1']}/${$_GET['2']}`)){
                return true;

            } else if ((value?.pages == `` || value?.pages == `/`) && $_GET['1'] == 'home'){
                return true;

            } else if (value?.pages && value.pages.includes($_GET['1'])){
                return true;
            }
            return false;
        }
        const __open = (value: any) => {
            if (value?.logout){
                logout();
            }
            if (value?.url){
                open__(value.url, { init__: 1 }, 1);
            }
            return null;
        }

        const __active_open_submenu = (value: any) => {
            if (__active(value)){
                return true;
            }

            for(let key_1 in value.submenu){
                let value_1 = value.submenu[key_1];
            
                if (__active(value_1)){
                    return true;
                }
            }

            return false;            
        }

        const __count = (value: any) => {
            let $return: any = ``;
            if (compare__(`/menus_dynamic/`, value.url)){
                if (value?.orders_status && value.orders_status[0] != 50 && value.orders_status[1] == undefined){
                    $return = 0;
                    for (const [key_1, value_1_raw] of Object.entries(value.orders_status)) {
                        const menuSideCount = OBJ.menu_side__count as Record<string, number>;
                        const value_1 = value_1_raw as string;

                        if (menuSideCount?.[value_1]) {
                            $return += menuSideCount[value_1];
                        }
                    }
                    $return = $return>0 ? `(${$return})` : ``;
                }
            }
            return $return;
        }
    // FUNCTIONS
</script>


<template>

    <!-- MENU -->
        <div v-if="OBJ?.menu_side" class="posr z5 w240 bg_EBEBEB" style="transition: margin .5s" :style="`${SHOW.MENU_SIDE ? `` : `margin-left: -240px;`}`">
            <div class="posf t0 z1 w240 h100p bg_EBEBEB"></div>

            <ul class="posr z2 pt5 pb12 pl10 pr10 pb40 menu">
                <li v-for="(value, key) in OBJ.menu_side" :key="key" class="posr">
                    <a @click="value.menus_type!=5 ? __open(value) : null" class="db min-h28 mt10 pl10 pr10 fwb5 bg_hover_ffffff5F br6" :class="`${__active(value) ? `active` : ``} ${value?.svg_class ?? ``} ${value?.logout ? `not_mouse_right_click` : ``}`">
                        <div class="pt5 pb5 flexx flex_ac">
                            <!-- ICON -->
                                <div class="w20 flexx flex_c flex_ac c_444">
                                    <slot v-if="value?.svg">
                                        <div v-if="__active(value) && value?.svg_ative" class="w16 flexx flex_c flex_ac" v-html="value.svg_ative"></div>
                                        <div v-else class="w16 flexx flex_c flex_ac" v-html="value.svg"></div>
                                    </slot>
                                    <slot v-else-if="value?.icon">
                                        <div v-if="__active(value) && value?.icon_ative"><i class="fz14" :class="value.icon_ative"></i></div>
                                        <div v-else><i class="fz14" :class="value.icon"></i></div>
                                    </slot>
                                </div>
                            <!-- ICON -->
                            <div class="pl8 fz13" :class="compare__(`<div `, value?.name) ? `` : `limit_2`">
                                <div class=""><span v-html="value.name"></span> {{ __count(value) }}</div>
                                <div v-if="value.name_ref && OBJ.user.type == 'customers'" class="fz12 fwb4">(Ref: {{ value.name_ref }})</div>
                            </div>
                        </div>
                    </a>

                    <!-- v-if="__active_open_submenu(value)" -->
                    <ul class="submenu">
                        <li v-for="(value_1, key_1) in value.submenu" :key="key_1" class="posr">
                            <a @click="__open(value_1)" class="min-h20 p5 pl10 pr10 flexx flex_ac c_444 br6" :class="`${value_1?.class ? value_1.class : ``} ${__active(value_1) ? `active` : ``} ${value?.logout ? `not_mouse_right_click` : ``}`">
                                <div class="w20"></div>
                                <div class="pl8 fz13">
                                    <div class="">{{ value_1.name }} {{ __count(value_1) }}</div>
                                    <div v-if="value_1.name_ref && OBJ.user.type == 'customers'" class="fz12 fwb4">(Ref: {{ value_1.name_ref }})</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    <!-- MENU -->

    <!-- BACKGROUND -->
        <div v-if="SHOW.MENU_SIDE" @click="SHOW.MENU_SIDE = !SHOW.MENU_SIDE" class="posf t0 l0 z4 w100p h100p bg_0000009F dnn_1600"></div>
    <!-- BACKGROUND -->

</template>


<style>
    /* RESP */
        .__MENU_SIDE__ .title { justify-content: center !important; -ms-flex-pack: center !important; }
        .__MENU_SIDE__ .title span { display: none; }
        @media screen AND (max-width: 1600px) {
            .__MENU_SIDE__ { position: absolute; }
            .__MENU_SIDE__ .title { justify-content: start !important; -ms-flex-pack: start !important; }
            .__MENU_SIDE__ .title span { display: block; }
        }
    /* RESP */

    .__MENU_SIDE__ ul > li a.active { font-weight: 600; background: #FAFAFA; }

    .__MENU_SIDE__ ul > li a:before { position: absolute; top: 0; }
    .__MENU_SIDE__ ul > li a:after { position: absolute; top: 0;}
    .__MENU_SIDE__ svg path { fill: #444 !important; }

    /* | MENU */
        .__MENU_SIDE__ ul.menu > li:has(ul.submenu > li a.active) > a:after { content: url(data:image/svg+xml,%3Csvg%20width%3D%2221%22%20height%3D%2228%22%20viewBox%3D%220%200%2021%2028%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%0A%3Cpath%20d%3D%22M9%2024.75C9%2024.3358%209.33579%2024%209.75%2024V24C10.1642%2024%2010.5%2024.3358%2010.5%2024.75V28H9V24.75Z%22%20fill%3D%22%23B5B5B5%22%2F%3E%0A%3C%2Fsvg%3E) }
    /* | MENU */

    /* | SUBMENU */
        .__MENU_SIDE__ ul.submenu > li a:hover:before { content: url(data:image/svg+xml,%3Csvg%20width%3D%2221%22%20height%3D%2228%22%20viewBox%3D%220%200%2021%2028%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%0A%3Cpath%20d%3D%22M9%207.75C9%207.33579%209.33579%207%209.75%207V7C10.1642%207%2010.5%207.33579%2010.5%207.75V9.95C10.5%2010.8025%2010.5006%2011.3967%2010.5384%2011.8593C10.5755%2012.3132%2010.6446%2012.574%2010.7452%2012.7715C10.961%2013.1948%2011.3052%2013.539%2011.7285%2013.7548C11.926%2013.8554%2012.1868%2013.9245%2012.6407%2013.9616C13.1033%2013.9994%2013.6975%2014%2014.55%2014H17.9393L16.2197%2012.2803C15.9268%2011.9874%2015.9268%2011.5126%2016.2197%2011.2197C16.5126%2010.9268%2016.9874%2010.9268%2017.2803%2011.2197L20.2803%2014.2197C20.5732%2014.5126%2020.5732%2014.9874%2020.2803%2015.2803L17.2803%2018.2803C16.9874%2018.5732%2016.5126%2018.5732%2016.2197%2018.2803C15.9268%2017.9874%2015.9268%2017.5126%2016.2197%2017.2197L17.9393%2015.5H14.5179C13.705%2015.5%2013.0494%2015.5%2012.5185%2015.4566C11.9719%2015.412%2011.4918%2015.3176%2011.0475%2015.0913C10.3419%2014.7317%209.76825%2014.1581%209.40873%2013.4525C9.18239%2013.0082%209.08803%2012.5281%209.04336%2011.9815C8.99999%2011.4506%208.99999%2010.795%209%209.98212V7.75Z%22%20fill%3D%22%23CCCCCC%22%2F%3E%0A%3C%2Fsvg%3E); }
        .__MENU_SIDE__ ul.submenu > li a.active:hover:before { display: none; }
    /* | SUBMENU */

    /* -> */
        .__MENU_SIDE__ ul.submenu > li a:after { display: none; content: url(data:image/svg+xml,%3Csvg%20width%3D%2221%22%20height%3D%2228%22%20viewBox%3D%220%200%2021%2028%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Crect%20x%3D%229%22%20width%3D%221.5%22%20height%3D%2228%22%20fill%3D%22%23B5B5B5%22%2F%3E%3C%2Fsvg%3E) }
        .__MENU_SIDE__ ul.submenu > li:has(~ li .active) a:after { display: block; }
        .__MENU_SIDE__ ul.submenu > li a.active:after { display: block; content: url("data:image/svg+xml,%3Csvg%20width%3D'21'%20height%3D'28'%20viewBox%3D'0%200%2021%2028'%20fill%3D'none'%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%3E%3Cpath%20d%3D'M19%2014.25H19.75V15.75H19V14.25ZM10.077%2013.362L10.7452%2013.0215V13.0215L10.077%2013.362ZM11.388%2014.673L11.7285%2014.0048H11.7285L11.388%2014.673ZM10.5%200V10.2H9V0H10.5ZM14.55%2014.25H19V15.75H14.55V14.25ZM10.5%2010.2C10.5%2011.0525%2010.5006%2011.6467%2010.5384%2012.1093C10.5755%2012.5632%2010.6446%2012.824%2010.7452%2013.0215L9.40873%2013.7025C9.18239%2013.2582%209.08803%2012.7781%209.04336%2012.2315C8.99942%2011.6936%209%2011.0277%209%2010.2H10.5ZM14.55%2015.75C13.7223%2015.75%2013.0564%2015.7506%2012.5185%2015.7066C11.9719%2015.662%2011.4918%2015.5676%2011.0475%2015.3413L11.7285%2014.0048C11.926%2014.1054%2012.1868%2014.1745%2012.6407%2014.2116C13.1033%2014.2494%2013.6975%2014.25%2014.55%2014.25V15.75ZM10.7452%2013.0215C10.9609%2013.4448%2011.3052%2013.7891%2011.7285%2014.0048L11.0475%2015.3413C10.3419%2014.9817%209.76825%2014.4081%209.40873%2013.7025L10.7452%2013.0215Z'%20fill%3D'%23B5B5B5'/%3E%3Cpath%20d%3D'M17%2012L20%2015L17%2018'%20stroke%3D'%23B5B5B5'%20stroke-width%3D'1.5'%20stroke-linecap%3D'round'%20stroke-linejoin%3D'round'/%3E%3C/svg%3E%0A") !important; }
    /* -> */
</style>

<style scoped>
    /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background-color: transparent;
        }
        ::-webkit-scrollbar-track:hover {
            background-color: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background-color: transparent;
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: transparent;
        }
    /* SCROLLBAR */
</style>
