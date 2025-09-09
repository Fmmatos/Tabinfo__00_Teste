<script setup lang="ts">
import CHECKBOX__ from '@/vendor/components/input/checkbox.vue';
import TEXT__ from '@/vendor/components/input/text.vue';
import PASSWORD__ from '@/vendor/components/input/password.vue';

import FORGET_PASSWORD__ from '@/vendor/pages_admin/login/forget_password.vue';
import FORGET_PASSWORD_CODE__ from '@/vendor/pages_admin/login/forget_password_code.vue';
import FORGET_PASSWORD_CREATE_PASSWORD__ from '@/vendor/pages_admin/login/forget_password_create_password.vue';

import api from '@/vendor/services/api';   
import { open__, alerts_close, base64_encode, scroll_height, base64_decode, alerts, login_remember } from '@/vendor/services/events';
import { DIR } from '@/vendor/services/localhost';
import { rootAuth } from '@/vendor/storages/auth';
import { inject, onBeforeMount } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.position = ``;
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                login_remember(`ADMIN`);
            });
        // ONBEFOREMOUNT

    // EVENTS

    // FUNCTIONS
        // LOGIN
            const __submit = () => {
                api(`/admin/login/auth`, FORM.v, (json: any) => {
                    if (json.token){
                        // REMEMBER
                            if (FORM.v?.remember?.[1] == true){
                                localStorage.setItem(`REMEMBER_ADMIN_${DIR()}`, base64_encode(base64_encode(`${FORM.v.email}--||--||--${base64_encode(FORM.v.password)}`)));
                            } else {
                                localStorage.removeItem(`REMEMBER_ADMIN_${DIR()}`);
                            }
                        // REMEMBER

                        rootAuth(json.token);
                        open__(`/`);
                        alerts_close();
                    }
                });
            }
        // LOGIN

        // FORGET PASSWORD
        const __email_forget = (x: number) => {
                FORM.v.email_forget = ``;
                FORM.v.email = ``;
                FORM.v.password = ``;
            }
        // FORGET PASSWORD
    // FUNCTIONS
</script>


<template>

    <div class="">
        <div class="posa t0 l0 z1 w100p h100p o-h gradient">
            <div class="gradient_1"></div>
            <div class="gradient_2"></div>
        </div>

        <div class="posr z2 min-h100vh flexx_350 flex_c flex_ac c_333">
            <div class="w500 min-w350 p40 pl40 pr40 m20 bg_fff br10 w100p_500" style="-webkit-box-shadow: 0px 12px 20px -8px rgba(26, 26, 26, 0.24), 0px 1px 0px 0px rgba(204, 204, 204, 0.5) inset, 0px -1px 0px 0px rgba(0, 0, 0, 0.17) inset, -1px 0px 0px 0px rgba(0, 0, 0, 0.13) inset, 1px 0px 0px 0px rgba(0, 0, 0, 0.13) inset; box-shadow: 0px 12px 20px -8px rgba(26, 26, 26, 0.24), 0px 1px 0px 0px rgba(204, 204, 204, 0.5) inset, 0px -1px 0px 0px rgba(0, 0, 0, 0.17) inset, -1px 0px 0px 0px rgba(0, 0, 0, 0.13) inset, 1px 0px 0px 0px rgba(0, 0, 0, 0.13) inset;">

                <!-- LOGIN -->  
                    <div v-if="!SHOW.position || SHOW.position == 'login'">
                        <form @submit.prevent="__submit()">
                            <div class="fz24 fwb6">Fazer login</div>

                            <div class="posr mt30">
                                <div class="pt5 fz16">
                                    <TEXT__ label="E-mail" name="email" class="design pr40 mt5 fz14i bd_333_i" required />
                                    <div class="input_icon_posa c_666">
                                        <i class="faa-envelope-o"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="pt20 fz16">
                                <PASSWORD__ label="Senha" name="password" only="1" class="design mt5 fz14i bd_333_i" required />
                            </div>

                            <div class="pt15 flexx flex_j flex_ac">
                                <div class="flex_1"><CHECKBOX__ name="remember" class="fz14i" extra="|->>1: Lembrar meus dados" /></div>
                            </div>

                            <div class="pt20"><button class="button_admin_3" >Fazer Login</button></div>
                        </form>
                        <div class="pt30 pb10 mt30 fz14 tac bdt_aaa">Se esqueceu sua senha <button @click="SHOW.position = `forget_password`" type="button" class="dib fz14 c_blue link">clique aqui!</button></div>
                    </div>
                <!-- LOGIN -->  


                <!-- FORGET_PASSWORD -->
                    <FORGET_PASSWORD__ v-if="SHOW.position == 'forget_password'" :__email_forget="__email_forget" />
                    <FORGET_PASSWORD_CODE__ v-if="SHOW.position == 'forget_password_code'" :__email_forget="__email_forget" />
                    <FORGET_PASSWORD_CREATE_PASSWORD__ v-if="SHOW.position == 'forget_password_create_password'" :__email_forget="__email_forget" />
                <!-- FORGET_PASSWORD -->
            </div>
        </div>
    </div>

</template>

<style>
    .__ADMIN__.__PG__login__ .__bg_ADMIN__ { background: #141414; }
    .__ADMIN__.__PG__login__ .__TOP__,
    .__ADMIN__.__PG__login__ .__MENU_SIDE__,
    .__ADMIN__.__PG__login__ .__FOOTER__ { display: none !important; }
    .__ADMIN__.__PG__login__ .__BORDER_RADIUS_LEFT__,
    .__ADMIN__.__PG__login__ .__BORDER_RADIUS_RIGHT__ { display: none !important; }
</style>

<style scoped>
    .gradient { -webkit-filter: blur(250px); filter: blur(250px); -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
    .gradient > div { mix-blend-mode: lighten; -webkit-animation-duration: 20s; animation-duration: 20s; position: absolute; border-radius: 100%; -webkit-animation-iteration-count: infinite; animation-iteration-count: infinite; -webkit-animation-timing-function: cubic-bezier(0.1, 0, 0.9, 1); animation-timing-function: cubic-bezier(0.1, 0, 0.9, 1); }
    .gradient_1 { bottom: 0; left: 0; width: 700px; height: 700px; background: #8e7bff; mix-blend-mode: lighten; -webkit-transform: translate(-30%, 40%); transform: translate(-30%, 40%); -webkit-animation-name: gradientShapeAnimation1; animation-name: gradientShapeAnimation1; }
    .gradient_2 { top: 0; right: 0; width: 600px; height: 600px; background: #005BD3; -webkit-transform: translate(20%, -40%); transform: translate(20%, -40%); -webkit-animation-name: gradientShapeAnimation2; animation-name: gradientShapeAnimation2; }

    @keyframes gradientShapeAnimation1 {
        0% {
            -webkit-transform: translate(-30%, 40%) rotate(-20deg);
            transform: translate(-30%, 40%) rotate(-20deg)
        }

        25% {
            -webkit-transform: translate(0%, 20%) skew(-15deg, -15deg) rotate(80deg);
            transform: translate(0%, 20%) skew(-15deg, -15deg) rotate(80deg)
        }

        50% {
            -webkit-transform: translate(30%, -10%) rotate(180deg);
            transform: translate(30%, -10%) rotate(180deg)
        }

        75% {
            -webkit-transform: translate(-30%, 40%) skew(15deg, 15deg) rotate(240deg);
            transform: translate(-30%, 40%) skew(15deg, 15deg) rotate(240deg)
        }

        100% {
            -webkit-transform: translate(-30%, 40%) rotate(-20deg);
            transform: translate(-30%, 40%) rotate(-20deg)
        }
    }

    @keyframes gradientShapeAnimation2 {
        0% {
            -webkit-transform: translate(20%, -40%) rotate(-20deg);
            transform: translate(20%, -40%) rotate(-20deg)
        }

        20% {
            -webkit-transform: translate(0%, 0%) skew(-15deg, -15deg) rotate(80deg);
            transform: translate(0%, 0%) skew(-15deg, -15deg) rotate(80deg)
        }

        40% {
            -webkit-transform: translate(-40%, 50%) rotate(180deg);
            transform: translate(-40%, 50%) rotate(180deg)
        }

        60% {
            -webkit-transform: translate(-20%, -20%) skew(15deg, 15deg) rotate(80deg);
            transform: translate(-20%, -20%) skew(15deg, 15deg) rotate(80deg)
        }

        80% {
            -webkit-transform: translate(10%, -30%) rotate(180deg);
            transform: translate(10%, -30%) rotate(180deg)
        }

        100% {
            -webkit-transform: translate(20%, -40%) rotate(340deg);
            transform: translate(20%, -40%) rotate(340deg)
        }
    }
</style>