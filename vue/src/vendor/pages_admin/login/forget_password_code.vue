<script setup lang="ts">
import CODE__ from '@/vendor/components/input/code.vue';

import api from '@/vendor/services/api';   
import { isset_COOKIES, login_remember } from '@/vendor/services/events';
import { inject, onBeforeMount } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const props = defineProps<{
            __email_forget: Function;
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
        for (let i = 1; i <= 5; i++) FORM.v[`code_${i}`] = '';
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(async () => {
                if(!isset_COOKIES('forget_password_id')){
                    SHOW.position = `login`;
                    login_remember(`ADMIN`);
                }
            });
        // ONBEFOREMOUNT
    // EVENTS

    // FUNCTIONS
        const __submit = () => {
            api(`/admin/login/forget_password/verify_code`, FORM.v, (json: any) => {
                SHOW.position = 'forget_password_create_password';
            });
        }

        const __submit_resend_code = () => {
            api(`/admin/login/forget_password/resend_code`, FORM.v, (json: any) => {
            });
        }
    // FUNCTIONS
</script>


<template>

    <div class="">
        <form @submit.prevent="__submit()">
            <div class="fz24 fwb6">Verificar código</div>
            
            <p class="mt20 fz14 c_666">Enviamos um código de verificação para<br><span class="fwb6">{{ FORM.v.email_forget || FORM.v.email }}</span></p>
            
            <div class="mt30 fz16">Código de verificação</div>
            <div class="pt10 flexx flex_c gap_10">
                <CODE__ name="code" :x="5" class="w60 h60 fz22i fwb6 tac bdw2" 
                    :dynamic_style="(i: number) => FORM.v[`code_${i}`] ? `border-color: #${SHOW.HEX_5}` : ''"
                />
            </div>

            <div class="pt30">
                <button v-if="FORM.v.code_1 && FORM.v.code_2 && FORM.v.code_3 && FORM.v.code_4 && FORM.v.code_5" class="button_admin_3">Verificar código</button>
                <button v-else type="button" class="c_999_i bd_ddd_i bg_f5f5f5_i button_admin_3" disabled>Verificar código</button>
            </div>
        </form>
        <div class="pt30 pb10 mt30 tac bdt_aaa">
            <div class="">Não recebeu o código?</div>
            <div class="pt2">
                <button @click="__submit_resend_code()" type="button" class="dib fz14 c_blue link">Reenviar código</button> ou 
                <button @click="SHOW.position = `login`; login_remember(`ADMIN`);" type="button" class="dib fz14 c_blue link">voltar ao login</button>
            </div>
        </div>
    </div>

</template>

<style scoped>
</style>