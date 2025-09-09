<script setup lang="ts">
// ROOT
    import TOP__ from '@/pages/z_top.vue';
    import MENU_SIDE__ from '@/pages/z_menu_side.vue';
    import FOOTER__ from '@/pages/z_footer.vue';
// ROOT

// ADMIN
    import ADMIN__TOP__ from '@/vendor/pages_admin/z_top.vue';
    import ADMIN__MENU_SIDE__ from '@/vendor/pages_admin/z_menu_side.vue';
    import ADMIN__FOOTER__ from '@/pages_admin/z_footer.vue';
// ADMIN

// DASHBOARD
    import DASHBOARD__TOP__ from '@/pages_dashboard/z_top.vue';
    import DASHBOARD__MENU_SIDE__ from '@/pages_dashboard/z_menu_side.vue';
    import DASHBOARD__FOOTER__ from '@/pages_dashboard/z_footer.vue';
// DASHBOARD

// ALL
    import IFRAME__ from '@/vendor/components/iframe.vue';
// ALL

import { request_obj, replace, ucfirst, __BOXS_INI__, uf, is_iframe, iframe_response_select } from '@/vendor/services/events';
import { index__layout } from '@/services/NEW__events';
import { defineAsyncComponent, markRaw, inject, onBeforeMount, onBeforeUnmount, onMounted  } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.layout = `ROOT`;
        if ($_GET[0] == `admin`){
            SHOW.layout = `ADMIN`;
        }
        if ($_GET[0] == `dashboard`){
            SHOW.layout = `DASHBOARD`;
        }

        SHOW.GET = $_GET;

        SHOW.HEX_1 = $_GLOBAL.HEX_1;
        SHOW.HEX_2 = $_GLOBAL.HEX_2;
        SHOW.HEX_3 = $_GLOBAL.HEX_3;
        SHOW.HEX_4 = $_GLOBAL.HEX_4;
        SHOW.HEX_5 = $_GLOBAL.HEX_5;
        SHOW.HEX_6 = $_GLOBAL.HEX_6??`000000`;
        SHOW.HEX_7 = $_GLOBAL.HEX_7??`000000`;
        SHOW.HEX_8 = $_GLOBAL.HEX_8??`000000`;
        SHOW.HEX_9 = $_GLOBAL.HEX_9??`000000`;
        SHOW.HEX_10 = $_GLOBAL.HEX_10??`000000`;

        SHOW.HEX_COLOR = $_GLOBAL.HEX_COLOR;
        SHOW.HEX_BUTTON_COLOR = $_GLOBAL.HEX_BUTTON_COLOR;
        SHOW.HEX_BUTTON_BD = $_GLOBAL.HEX_BUTTON_BD;
        SHOW.HEX_BUTTON_BDW = $_GLOBAL.HEX_BUTTON_BDW;
        SHOW.HEX_BUTTON_BG = $_GLOBAL.HEX_BUTTON_BG;

        SHOW.MENU_SIDE = 1;
        SHOW.CART_SIDE = false;

        // ADMIN
            SHOW.sel_actions = 0;
            SHOW.sel_all = false;
            SHOW.ACTIONS = {};
        // ADMIN
    // SHOW

    // FORM
        FORM.v = {};

        // ADMIN
            FORM.v.sel = {};
            FORM.v.sel_all_all = false;
        // ADMIN
    // FORM

    // OBJ
        OBJ.CART = {};
        OBJ.PAGG = {};

        OBJ.CITY = [];
        OBJ.UF = uf();
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                // INI
                    index__layout();
                    __BOXS_INI__();
                    request_obj($_GLOBAL.ROUTE?.params?.request);
                // INI

                // PGS
                    // ARRAY
                        // ROOT
                            let page_dinamic = [
                                {
                                    prefix: `vendor/pages`,
                                    require: require.context(`@/vendor/pages`, false, /\.vue$/),
                                },{
                                    prefix: `pages`,
                                    require: require.context(`@/pages`, false, /\.vue$/),
                                },{
                                    type: OBJ?.user?.type,
                                    prefix: OBJ?.user?.type ? `pages/${OBJ?.user?.type}` : `pages`,
                                    require: require.context(`@/pages`, true, /\.vue$/),
                                },
                            ];
                        // ROOT

                        // ADMIN
                            if ($_GET[0] == `admin`){
                                page_dinamic = [
                                    {
                                        prefix: `vendor/pages`,
                                        require: require.context(`@/vendor/pages`, false, /\.vue$/),
                                    },{
                                        prefix: `vendor/pages_admin`,
                                        require: require.context(`@/vendor/pages_admin`, false, /\.vue$/),
                                    },{
                                        prefix: `pages_admin`,
                                        require: require.context(`@/pages_admin`, false, /\.vue$/),
                                    }
                                ];
                            }
                        // ADMIN

                        // DASHBOARD
                            if ($_GET[0] == `dashboard`){
                                page_dinamic = [
                                    {
                                        prefix: `vendor/pages`,
                                        require: require.context(`@/vendor/pages`, false, /\.vue$/),
                                    },{
                                        prefix: `vendor/pages_admin`,
                                        require: require.context(`@/vendor/pages_admin`, false, /\.vue$/),
                                    },{
                                        prefix: `vendor/pages_dashboard`,
                                        require: require.context(`@/vendor/pages_dashboard`, false, /\.vue$/),
                                    },{
                                        prefix: `pages_dashboard`,
                                        require: require.context(`@/pages_dashboard`, false, /\.vue$/),
                                    },{
                                        type: OBJ?.user?.type,
                                        prefix: OBJ?.user?.type ? `pages_dashboard/${OBJ?.user?.type}` : `pages_dashboard`,
                                        require: require.context(`@/pages_dashboard`, true, /\.vue$/),
                                    }
                                ];
                            }
                        // DASHBOARD
                    // ARRAY


                    // DINAMIC
                        const checkPageExists = (context: __WebpackModuleApi.RequireContext, page: string = ''): boolean => {
                            return context.keys().some(fileName => {
                                return fileName.includes(`./${page}.vue`)
                            });
                        }

                        let pg_current = `error404`;
                        let componentLoader: () => Promise<any> = () => import('@/vendor/pages/error404.vue');
                        if ($_GET['PG']){
                            for (const [key, value] of Object.entries(page_dinamic)){

                                // __ (__home)
                                    if (checkPageExists(value.require, `__${$_GET['PG']}`) && !value.type){
                                        // console.log(`@/${value.prefix}/__${$_GET['PG']}.vue`);
                                        pg_current = $_GET['PG'];
                                        componentLoader = () => import(`@/${value.prefix}/__${$_GET['PG']}.vue`);
                                    }
                                // __

                                // DIR (home)
                                    if (checkPageExists(value.require, $_GET['PG']) && !value.type){
                                        // console.log(`@/${value.prefix}/${$_GET['PG']}.vue`);
                                        pg_current = $_GET['PG'];
                                        componentLoader = () => import(`@/${value.prefix}/${$_GET['PG']}.vue`);
                                    }
                                // DIR

                                // TYPE (/motoristas/homeMotoristas)
                                    if (value.type && checkPageExists(value.require, `${value.type}/${$_GET['PG']}${ucfirst(value.type)}`) && value.type == OBJ?.user?.type){
                                        // console.log(`@/${value.prefix}/${$_GET['PG']}${ucfirst(value.type)}.vue`);
                                        pg_current = $_GET['PG'];
                                        componentLoader = () => import(`@/${value.prefix}/${$_GET['PG']}${ucfirst(value.type)}.vue`);
                                    }
                                // TYPE

                            }
                        }


                        // DASHBOARD_MODULES
                            if ($_GET['dashboard_modules']){
                                pg_current = `modules`;
                                componentLoader = () => import(`@/vendor/pages_dashboard/modules.vue`);
                            }
                        // DASHBOARD_MODULES
                    // DINAMIC

                    OBJ.PGS = markRaw(defineAsyncComponent(componentLoader))
                // PGS
            });
        // ONBEFOREMOUNT


        // IFRAME__
            onMounted(() => {
                (window as any).close_iframe__ = () => {
                    SHOW.iframe__ = 0;
                }

                (window as any).receiveFromIframe__ = (response: any) => {
                    iframe_response_select(response);
                }
            })
            onBeforeUnmount(() => {
                delete (window as any).receiveFromIframe__ 
            })
        // IFRAME__
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <div
        class="minw_all"
        :class="`__${SHOW.layout}__ __PG__${replace('/', '_', SHOW.GET['PG'])}__`"
        :get_0="SHOW.GET[0]" :get_1="SHOW.GET[1]" :get_2="SHOW.GET[2]" :get_3="SHOW.GET[3]" :get_4="SHOW.GET[4]" :get_5="SHOW.GET[5]"
        :get_1__="SHOW.GET[1] ? 1 : 0" :get_2__="SHOW.GET[2] ? 1 : 0" :get_3__="SHOW.GET[3] ? 1 : 0" :get_4__="SHOW.GET[4] ? 1 : 0" :get_5__="SHOW.GET[5] ? 1 : 0"
    >
        <!-- BACKGROUND ALL -->
            <div class="posf t0 l0 z-1 w100p h100p" :class="`__bg_${SHOW.layout}__`"></div>
        <!-- BACKGROUND ALL -->


        <!-- ROOT -->
            <div v-if="SHOW.layout == `ROOT`">
                <div class="w100p">
                    <div class="__TOP__"><TOP__ /></div>
                    <div class="__MENU_MOBILE__"><MENU_SIDE__ /></div>

                    <div class="__MAIN__ fade_in"><component :is="OBJ.PGS" /></div>

                    <div class="__FOOTER__"><FOOTER__ /></div>
                </div>
            </div>
        <!-- ROOT -->





        <!-- ADMIN -->
            <div v-if="SHOW.layout == `ADMIN`">
                <div v-if="!is_iframe()" class="__TOP__"><ADMIN__TOP__ type="top" /></div>

                <div class="posr w100p flexx_1400">
                    <div v-if="!is_iframe()" class="__MENU_SIDE__">
                        <ADMIN__MENU_SIDE__ />
                    </div>

                    <div class="flex_1 o-ax_XX __MAIN__ fade_in">
                        <div class=""><component :is="OBJ.PGS" /></div>
                    </div>

                    <div v-if="SHOW.iframe__"><IFRAME__ /></div>
                    <div v-if="!is_iframe()" class="__FOOTER__"><ADMIN__FOOTER__ /></div>

                    <!-- BORDER_RADIUS LEFT / RIGHT -->
                        <img v-if="!is_iframe()" src="@/vendor/assets/img/svg/border_radius_left.svg" class="posa t0 l0 z6 w10 h10 mt--1 __BORDER_RADIUS_LEFT__" />
                        <img v-if="!is_iframe()" src="@/vendor/assets/img/svg/border_radius_right.svg" class="posa t0 r0 z6 w12 h12 mt--1 __BORDER_RADIUS_RIGHT__" />
                    <!-- BORDER_RADIUS LEFT / RIGHT -->
                </div>
            </div>
        <!-- ADMIN -->





        <!-- DASHBOARD -->
            <div v-if="SHOW.layout == `DASHBOARD`">
                <div v-if="!is_iframe()" class="__TOP__"><DASHBOARD__TOP__ type="top" /></div>

                <div class="posr w100p flexx_1400">
                    <div v-if="!is_iframe() && OBJ?.user?.id" class="__MENU_SIDE__">
                        <DASHBOARD__MENU_SIDE__ />
                    </div>


                    <div class="flex_1 o-ax_XX __MAIN__ fade_in">
                        <div v-if="!is_iframe()" class="__TOP__"><DASHBOARD__TOP__ type="top_right" /></div>
                        <div class=""><component :is="OBJ.PGS" /></div>
                    </div>

                    <div v-if="SHOW.iframe"><IFRAME__ /></div>
                    <div v-if="!is_iframe()" class="__FOOTER__"><DASHBOARD__FOOTER__ /></div>


                    <!-- BORDER_RADIUS LEFT / RIGHT -->
                        <img v-if="!is_iframe()" src="@/vendor/assets/img/svg/border_radius_left.svg" class="posa t0 l0 z6 w10 h10 mt--1 __BORDER_RADIUS_LEFT__" />
                        <img v-if="!is_iframe()" src="@/vendor/assets/img/svg/border_radius_right.svg" class="posa t0 r0 z6 w12 h12 mt--1 __BORDER_RADIUS_RIGHT__" />
                    <!-- BORDER_RADIUS LEFT / RIGHT -->
                </div>
            </div>
        <!-- DASHBOARD -->
    </div>

</template>

<style scoped>
</style>