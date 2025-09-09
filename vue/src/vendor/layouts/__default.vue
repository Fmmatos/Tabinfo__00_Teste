<script setup lang="ts">
import FILE__ from '@/vendor/components/input/file.vue';

import CART_SIDE__ from '@/vendor/components/cart_side.vue';

import { DIR_P } from '@/vendor/services/localhost';
import { onBeforeUnmount, onMounted, provide, reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { COOKIES, COOKIES_DELETE, html_treatment } from '../services/events';


    // ROUTE / ROUTER
        $_GLOBAL.ROUTE = useRoute();
        $_GLOBAL.ROUTER = useRouter();
    // ROUTE / ROUTER

    // REACTIVE
        const SHOW: any = reactive({});
        const FORM: any = reactive({});
        const OBJ: any = reactive({});
        const INTERVAL: any = reactive({});

        provide('SHOW', SHOW);
        provide('FORM', FORM);
        provide('OBJ', OBJ);
        provide('INTERVAL', INTERVAL);

        $_GLOBAL.SHOW = SHOW;
        $_GLOBAL.OBJ = OBJ;
        $_GLOBAL.FORM = FORM;
        $_GLOBAL.INTERVAL = INTERVAL;
    // REACTIVE

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        if (Number(COOKIES('ITEMS_PAGE')) > 100){
            COOKIES_DELETE('ITEMS_PAGE');
        }

        // SHOW.WIDTH (MOBILE)
            SHOW.width = window.innerWidth
            function updateWidth() {
                SHOW.width = window.innerWidth
            }
            onMounted(() => {
                window.addEventListener('resize', updateWidth)
            })
            onBeforeUnmount(() => {
                window.removeEventListener('resize', updateWidth)
            })
        // SHOW.WIDTH (MOBILE)


        // MOUSE_RIGHT_CLICK
            onMounted(() => {
                window.addEventListener('contextmenu', __mouse_right_click);
            });
            onBeforeUnmount(() => {
                window.removeEventListener('contextmenu', __mouse_right_click);
            });
            const __mouse_right_click = (event: MouseEvent) => {
                const target = event.target as HTMLElement;
                const maybeAnchor = target.closest('a:not(.not_mouse_right_click):not(.fechar)');
                if (!(maybeAnchor instanceof HTMLAnchorElement)) return;                

                const anchor = maybeAnchor;
                if (!anchor || anchor.hasAttribute('href')) return;

                SHOW.lastMouseEvent   = event;
                SHOW.anchorMouseEvent = anchor;

                // BLOQUEAR CLICK (anchor.click();)
                    const blocker = (e: MouseEvent) => {
                        if (!e.isTrusted && e.target === anchor){
                            e.preventDefault();
                        }
                    };
                    window.addEventListener('click', blocker, { capture: true, once: true });
                // BLOQUEAR CLICK (anchor.click();)

                anchor.click();
            }
        // MOUSE_RIGHT_CLICK
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>

<template>
    <router-view :key="$route.fullPath" />


    <div class="no_print">
        <!-- EVENTS__ -->
            <div class="__EVENTS__">
                <!-- LOADING (Z-1) -->
                    <div class="LOADING Z-1">
                        <div class="posf t0 l0 z-2 w100p h100p no_sel bg_000000DF">
                            <div class="flexx flex_c flex_ac w100p h100p">
                                <div class="">
                                    <div class="p10" style="min-height: 50px;"><img :src="`${DIR_P()}/img/logo_branca.png`" style="width: 120px" class="dib" /></div>
                                    <div class="spinner__CSS" style="margin-top: 0px;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- LOADING (Z-1) -->

                <!-- __LOAD__0 (LOGO) -->
                    <div class="dn __LOAD__0">
                        <div class="posf t0 l0 w100p h100p no_sel" style="z-index: 910001;">
                            <div class="flexx flex_c flex_ac w100p h100p">
                                <div class="">
                                    <div class="p10" style="min-height: 50px;"><img :src="`${DIR_P()}/img/logo.png`" style="width: 120px" class="dib" /></div>
                                    <div class="spinner__CSS" style="margin-top: 0px;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- __LOAD__0 (LOGO) -->

                <!-- __LOAD__1 (LOGO WHITE) -->
                    <div class="__LOAD__1">
                        <div class="posf t0 l0 w100p h100p no_sel bg_000000CF" style="z-index: 910001;">
                            <div class="flexx flex_c flex_ac w100p h100p">
                                <div class="">
                                    <div class="p10" style="min-height: 50px;"><img :src="`${DIR_P()}/img/logo_branca.png`" style="width: 120px" class="dib" /></div>
                                    <div class="spinner__CSS" style="margin-top: 0px;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- __LOAD__1 (LOGO WHITE) -->

                <!-- __LOAD__2 (LOGO WHITE, BACKGROUND BLACK) -->
                    <div class="__LOAD__2">
                        <div class="posf t0 l0 w100p h100p no_sel bg_000000CF" style="z-index: 910001;">
                            <div class="flexx flex_c flex_ac w100p h100p">
                                <div class="">
                                    <div class="p10" style="min-height: 50px;"><img :src="`${DIR_P()}/img/logo_branca.png`" style="width: 120px" class="dib" /></div>
                                    <div class="spinner__CSS" style="margin-top: 0px;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- __LOAD__2 (LOGO WHITE, BACKGROUND BLACK) -->

                <!-- __LOAD__3 (CARREGANDO) -->
                    <div class="dn __LOAD__3">
                        <div class="posf t0 l0 w100p h100p no_sel bg_000000CF">
                            <div class="flexx flex_c flex_ac w100p h100p">
                                <div class="lds-eclipse">
                                    <span class="posa w100p h100p pt88 fz20 tac c_fff" style="padding-top: 88px;">Carregando...</span>
                                    <div style="box-shadow: 0 4px 0 0 #fff;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- __LOAD__3 -->

                <div class="__ALERTS__"><div class="alerts"></div></div>
                <div class="__ERRORS__"></div>
            </div>
        <!-- EVENTS__ -->





        <!-- BOXS__DEFAULT -->
            <div v-if="SHOW.BOXS_FIXED == `DEFAULT` && SHOW.BOXS_FIXED_TEXT">
                <div class="__BOXS__">
                    <em style="z-index: 800020 !important; background: #000000BF;"></em>
                    <div class="top" style="z-index: 800021 !important;">
                        <div :class="SHOW.BOXS_FIXED_CLASS_1 ? SHOW.BOXS_FIXED_CLASS_1 : `w900 p10 w100p_1000`">
                            <div :class="SHOW.BOXS_FIXED_CLASS_2 ? SHOW.BOXS_FIXED_CLASS_2 : `posr p20 m-a bg_fff br7 shadow`">
                                <button 
                                    @click="SHOW.BOXS_FIXED=``; SHOW.BOXS_FIXED_TEXT=``; SHOW.BOXS_FIXED_CLASS_1=``; SHOW.BOXS_FIXED_CLASS_2=``; SHOW.BOXS_FIXED_CLASS_3=``; SHOW.BOXS_FIXED_CLOSE=``; SHOW.BOXS_FIXED_BUTTON=``; SHOW.BOXS_FIXED_BUTTON_CLASS=``; SHOW.BOXS_FIXED_BUTTON_CLASS=``;"
                                    :class="SHOW.BOXS_FIXED_CLASS_3 ? SHOW.BOXS_FIXED_CLASS_3 : `close not_mouse_right_click`"
                                >
                                    <img v-if="!SHOW.BOXS_FIXED_CLOSE" src="@/vendor/assets/img/svg/default/close.svg" />
                                    <div v-else v-html="html_treatment(SHOW.BOXS_FIXED_CLOSE)"></div>
                                </button>
            
                                <div class="">
                                    <div v-html="html_treatment(SHOW.BOXS_FIXED_TEXT)"></div>
                                    <div v-if="SHOW.BOXS_FIXED_BUTTON && SHOW.BOXS_UPLOAD_CLICK">
                                        <button @click="SHOW.BOXS_UPLOAD_CLICK" :class="SHOW.BOXS_FIXED_BUTTON_CLASS ? SHOW.BOXS_FIXED_BUTTON_CLASS : `a p10 mt15 fz16 fwb6 tac c_fff bg_000 br15`"><div v-html="html_treatment(SHOW.BOXS_FIXED_BUTTON)"></div></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- BOXS__DEFAULT -->





        <!-- BOXS__UPLOAD -->
            <div v-if="SHOW.BOXS_UPLOAD == `DEFAULT` && SHOW.BOXS_UPLOAD_CLICK">
                <div class="__BOXS__">
                    <em style="z-index: 800020 !important; background: #000000BF;"></em>
                    <div class="top" style="z-index: 800021 !important;">
                        <div :class="SHOW.BOXS_UPLOAD_CLASS_1 ? SHOW.BOXS_UPLOAD_CLASS_1 : `w900 p10 w100p_1000`">
                            <div :class="SHOW.BOXS_UPLOAD_CLASS_2 ? SHOW.BOXS_UPLOAD_CLASS_2 : `posr p20 m-a bg_fff br7 shadow`">
                                <button
                                    @click="SHOW.BOXS_UPLOAD=``; SHOW.BOXS_UPLOAD_CLICK=``; SHOW.BOXS_UPLOAD_CLASS_1=``; SHOW.BOXS_UPLOAD_CLASS_2=``; SHOW.BOXS_UPLOAD_CLASS_3=``; SHOW.BOXS_UPLOAD_CLOSE=``; SHOW.BOXS_UPLOAD_TAGS=``; SHOW.BOXS_UPLOAD_CLICK=``; SHOW.BOXS_FIXED_BUTTON_CLASS=``; SHOW.BOXS_FIXED_BUTTON=``;"
                                    :class="SHOW.BOXS_UPLOAD_CLASS_3 ? SHOW.BOXS_UPLOAD_CLASS_3 : `close not_mouse_right_click`"
                                >
                                    <img v-if="!SHOW.BOXS_UPLOAD_CLOSE" src="@/vendor/assets/img/svg/default/close.svg" />
                                    <div v-else v-html="html_treatment(SHOW.BOXS_UPLOAD_CLOSE)"></div>
                                </button>
            
                                <div class="posr mt30 pb10 z9">
                                    <FILE__ name="image_boxs_upload" :tags="SHOW.BOXS_UPLOAD_TAGS ?? null" />
                                    <div v-if="SHOW.BOXS_UPLOAD_CLICK" class="pt15">
                                        <button @click="SHOW.BOXS_UPLOAD_CLICK" :class="SHOW.BOXS_FIXED_BUTTON_CLASS ? SHOW.BOXS_FIXED_BUTTON_CLASS : `a p10 fz16 fwb6 tac c_fff bg_000 br15`"><div v-html="html_treatment(SHOW.BOXS_FIXED_BUTTON ?? `Salvar`)"></div></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- BOXS__UPLOAD -->
    </div>










    <!-- COMPONENTS -->
        <CART_SIDE__ />
    <!-- COMPONENTS -->

</template>

<style>
    .spinner__CSS { display: flex; align-items: center; justify-content: space-between; width: 56px; margin: 20px auto 0; }
    .spinner__CSS > div { width: 12px; height: 12px; background-color: #fff; border-radius: 100%; display: inline-block; -webkit-animation: fuse-bouncedelay 1s infinite ease-in-out both; animation: fuse-bouncedelay 1s infinite ease-in-out both }
    .spinner__CSS .bounce1 { -webkit-animation-delay: -.32s; animation-delay: -.32s }
    .spinner__CSS .bounce2 { -webkit-animation-delay: -.16s; animation-delay: -.16s }
    @-webkit-keyframes fuse-bouncedelay { 0%, 80%, 100% { -webkit-transform: scale(0) } 40% { -webkit-transform: scale(1.0) } }
    @keyframes fuse-bouncedelay { 0%, 80%, 100% { -webkit-transform: scale(0); transform: scale(0) } 40% { -webkit-transform: scale(1.0); transform: scale(1.0) } }

</style>