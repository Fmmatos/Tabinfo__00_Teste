<script setup lang="ts">
import CHECKBOX__ from '@/vendor/components/input/checkbox.vue';
import TEXT__ from '@/vendor/components/input/text.vue';
import PASSWORD__ from '@/vendor/components/input/password.vue';
import RADIO__ from '@/vendor/components/input/radio.vue';

import FORGET_PASSWORD__ from '@/pages/login/forget_password.vue';
import FORGET_PASSWORD_CODE__ from '@/pages/login/forget_password_code.vue';
import FORGET_PASSWORD_CREATE_PASSWORD__ from '@/pages/login/forget_password_create_password.vue';

import api from '@/vendor/services/api';
import { alerts_close, base64_encode, key__, login_remember, MOBILE, open__ } from '@/vendor/services/events';
import { DIR, DIR_P } from '@/vendor/services/localhost';
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
        FORM.v.type_all = OBJ?.['__GLOBAL__']?.['__TYPES__']?.['__CUSTOMERS__']?.['DASHBOARD']?.['LOGIN'];
        FORM.v.type = key__(FORM.v.type_all)?.[0];
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                login_remember(`DASHBOARD`);
            });
    // EVENTS

    // FUNCTIONS
        // LOGIN
            const __submit = () => {
                api(`/dashboard/login/auth`, FORM.v, (json: any) => {
                    if(json.token){
                        // REMEMBER
                            if(FORM.v.remember[1] == true){
                                localStorage.setItem(`REMEMBER_DASHBOARD_${DIR()}`, base64_encode(base64_encode(`${FORM.v.email}--||--||--${base64_encode(FORM.v.password)}`)));
                            } else {
                                localStorage.removeItem(`REMEMBER_DASHBOARD_${DIR()}`);
                            }
                        // REMEMBER

                        rootAuth(json.token);
                        open__(`/dashboard/`);
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

    <div class="w100p min-h100vh flexx_1000 flex_j __LOGIN__">

        <!-- FORM -->
            <div class="flex_6 flexx flex_r flex_ac flex_c_1000" :class="`${MOBILE() ? `p20` : `p64`} h100vh`">
                <div class="w320 fz13 c_333">

                    <!-- LOGIN -->
                        <div v-if="!SHOW.position || SHOW.position == 'login'" class="fade_in">
                            <form @submit.prevent="__submit()">
                                <div class="h70 tac"><img :src="`${DIR_P()}/img/logo.png`" style="max-width: 220px; max-height: 80px" /></div>
                                <div class="">
                                    <!-- BOXS -->
                                        <div v-if="SHOW?.sign_in" class="posf t0 l0 z3 w100p h100p bg_0000006F">
                                            <div class="flexx flex_c flex_ac w100p h100p fade_in">
                                                <div class="posr w400 p20 m10 bg_fff br10 shadow_0">
                                                    <button @click="SHOW.sign_in=false" class="posa t0 r0 p10 fz20 c_999 c_red"><i class="faa-times"></i></button>
                                                    <div class="fz16 fwb6">Você é um:</div>
                                                    <div class="pt20 flexx flex_j flex_ac gap_10">
                                                        <a v-for="(value, key) in FORM.v.type_all" @click="open__(`/sign_up/${key}`, {}, 1)" class="flex_1 fz16i button_3">{{ value }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- BOXS -->
                                </div>

                                <div class="pt30">
                                    <TEXT__ type="email" label="E-mail" name="email" class="design pr40 fz13i" icon="user" required/>
                                </div>

                                <div class="pt20">
                                    <PASSWORD__ label="Senha de acesso" name="password" only="1" class="design fz13i" required />
                                </div>

                                <div v-if="Object.keys(FORM.v.type_all || {}).length > 1" class="posr p10 pt20 mb--20">
                                    <RADIO__ name="type" class="flexx flex_c flex_ac gap_20" :value="key__(FORM.v.type_all)?.[0]" :options="FORM.v.type_all" required />
                                </div>

                                <div class="pt20 flexx flex_j flex_ac">
                                    <div class=""><CHECKBOX__ name="remember" extra="|->>1: Lembrar meus dados" /></div>
                                    <div class=""><button @click="SHOW.position = `forget_password`" type="button" class="c_1"><u>Recuperar senha?</u></button></div>
                                </div>

                                <div class="pt20">
                                    <button class="button_3">Entrar</button>
                                </div>

                                <div class="pt20 tac">
                                    <div class="fz13">Ainda não tem uma conta?</div>
                                    <div class="pt2 fz14"><a @click="Object.keys(FORM.v.type_all || {}).length > 1 ? SHOW.sign_in=true : open__(`/sign_up/${Object.keys(FORM.v.type_all || {})[0]}`, {}, 1)" class="fwb6 c_1 link">Faça seu cadastro</a></div>
                                </div>
                            </form>
                        </div>
                    <!-- LOGIN -->


                    <!-- FORGET_PASSWORD -->
                        <FORGET_PASSWORD__ v-if="SHOW.position == 'forget_password' && FORM.v.type" :__email_forget="__email_forget" />
                        <FORGET_PASSWORD_CODE__ v-if="SHOW.position == 'forget_password_code' && FORM.v.type" :__email_forget="__email_forget" />
                        <FORGET_PASSWORD_CREATE_PASSWORD__ v-if="SHOW.position == 'forget_password_create_password' && FORM.v.type" :__email_forget="__email_forget" />
                    <!-- FORGET_PASSWORD -->

                </div>
            </div>
        <!-- FORM -->


        <!-- BACKGROUND -->
            <div class="flex_7 flexx flex_ac c_fff bg_2 dn_1000">
                <div class="p64 tal">
                    <div class="max-w500">
                        <div class="fz44 fwb7" v-html="OBJ.info?.pg_login_name"></div>
                        <div class="pt30 fz13 lh24 c_ddd">{{ OBJ.info?.pg_login_description }}</div>
                    </div>
                </div>
            </div>
        <!-- BACKGROUND -->

    </div>

</template>

<style>
    .__DASHBOARD__.__PG__login__ .__TOP__,
    .__DASHBOARD__.__PG__login__ .__MENU_SIDE__,
    .__DASHBOARD__.__PG__login__ .__FOOTER__ { display: none !important; }
    .__DASHBOARD__.__PG__login__ .__BORDER_RADIUS_LEFT__,
    .__DASHBOARD__.__PG__login__ .__BORDER_RADIUS_RIGHT__ { display: none !important; }

    .__input__ label span { font-size: 12px; }
</style>

<style scoped>
</style>