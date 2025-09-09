<script setup lang="ts">
import PASSWORD__ from '@/vendor/components/input/password.vue';

import { inject, onBeforeMount } from 'vue';
import { isset_COOKIES, login_remember } from '../../vendor/services/events';
import api from '@/vendor/services/api';

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
            let form = {
                ...FORM.v,
                _method: 'PUT',
            }
            api(`/login/forget_password/update`, form, (json: any) => {
                SHOW.position = `login`;
            });
        }
    // FUNCTIONS
</script>


<template>

    <div>

        <div class="fz13 fade_in">
            <div class="fz30 fwb8 lh40 ls-08">Criar nova senha</div>
            <div class="pt5">Sua nova senha deve ser diferente das anteriores.</div>
            
            <form @submit.prevent="__submit()" class="fz14">
                <div class="pt30">
                    <div class="fz14">
                        <PASSWORD__ label="Senha" name="password" space="h40" class="fz13i" required />
                    </div>

                    <div class="pt30">
                        <button class="button_3">Salvar nova senha</button>
                    </div>
                </div>
            </form>

            <div class="pt20 pb10 mt30 fz14 tac bdt_aaa"><button @click="SHOW.position = `forget_password`" type="button" class="dib fz14 link">Voltar ao começo e tentar novamente!</button></div>
        </div>

    </div>

</template>