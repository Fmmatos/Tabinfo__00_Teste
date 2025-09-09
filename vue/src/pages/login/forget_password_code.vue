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
                if(!isset_COOKIES('forget_password_id') && !isset_COOKIES('forget_password_type')){
                    SHOW.position = `login`;
                    login_remember(SHOW.GET[0]==`dashboard` ? 'DASHBOARD' : 'ROOT');
                }
            });
        // ONBEFOREMOUNT
    // EVENTS

    // FUNCTIONS
        const __submit = () => {
            api(`/login/forget_password/verify_code`, FORM.v, (json: any) => {
                SHOW.position = 'forget_password_create_password';
            });
        }

        const __submit_resend_code = () => {
            api(`/login/forget_password/resend_code`, FORM.v, (json: any) => {
            });
        }
    // FUNCTIONS
</script>


<template>

    <div>

        <div class="fade_in">
            <p class="fz16 tac" style="line-height: 1.5">Nós enviamos um código para<br>{{ FORM.v.email_forget }}</p>

            <form @submit.prevent="__submit()" class="fz14">
                <div class="pt30">
                    <div class="fz16 fwb6">Código de verificação</div>
                    <div class="pt20 flexx flex_c gap_10">
                        <CODE__ name="code" :x="5" class="w60 h60 fz22i fwb6 tac bdw2" 
                            :dynamic_style="(i: number) => FORM.v[`code_${i}`] ? `border-color: #${SHOW.HEX_5}` : ''"
                        />
                    </div>
                </div>
                
                <div class="pt30">
                    <button v-if="FORM.v.code_1 && FORM.v.code_2 && FORM.v.code_3 && FORM.v.code_4 && FORM.v.code_5" class="button_3">Verificar</button>
                    <button v-else type="button" class="c_999_i bd_ddd_i bg_f5f5f5_i button_3">Verificar</button>
                </div>
            </form>

            <div class="pt30 pb10 mt30 fz14 tac bdt_aaa">
                <div class="">Não recebeu o código?</div>
                <div class="pt2">
                    <!-- <button @click="__submit_resend_code()" type="button" class="dib fz14 c_blue link">Reenviar código</button> ou  -->
                    <button @click="SHOW.position = `login`; login_remember(SHOW.GET[0]==`dashboard` ? 'DASHBOARD' : 'ROOT');" type="button" class="dib fz14 c_blue link">voltar ao login</button>
                </div>
            </div>
        </div>

    </div>
                   
</template>