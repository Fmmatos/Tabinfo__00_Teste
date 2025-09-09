<script setup lang="ts">
import PASSWORD__ from '@/vendor/components/input/password.vue';

import api from '@/vendor/services/api';   
import { isset_COOKIES, login_remember } from '@/vendor/services/events';
import { inject, onBeforeMount } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
    // SHOW

    // FORM
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
            let form = {
                ...FORM.v,
                _method: 'PUT',
            }
            api(`/admin/login/forget_password/update`, form, (json: any) => {
                SHOW.position = `login`;
            });
        }
    // FUNCTIONS
</script>


<template>

    <div class="">
        <form @submit.prevent="__submit()" autocomplete="off">
            <div class="fz24 fwb6">Criar nova senha</div>
            <div class="pt20 fz14">
                <PASSWORD__ label="Senha" name="password" space="h20" space_1="h20" required />
            </div>
            <div class="pt30">
                <button type="submit" class="button_admin_3" ><i class="faa-check fz16 mr5"></i> Salvar</button>
            </div>
        </form>

        <div class="pt30 pb10 mt30 fz14 tac bdt_aaa"><button @click="SHOW.position = `forget_password`" type="button" class="dib fz14 link">Voltar ao começo e tentar novamente!</button></div>
    </div>

</template>

<style scoped>
</style>