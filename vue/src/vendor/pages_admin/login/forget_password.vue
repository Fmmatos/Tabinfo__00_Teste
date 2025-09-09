<script setup lang="ts">
import TEXT__ from '@/vendor/components/input/text.vue';

import api from '@/vendor/services/api';
import { login_remember } from '@/vendor/services/events';
import { inject } from 'vue';

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
    // EVENTS

    // FUNCTIONS
        const __submit = () => {
            api(`/admin/login/forget_password`, FORM.v, (json: any) => {
                SHOW.position = 'forget_password_code';
            });
        }
    // FUNCTIONS
</script>


<template>

    <div class="">
        <form @submit.prevent="__submit()">
            <div class="fz24 fwb6">Recuperar sua senha</div>
        
            <div class="pt30 fz16">E-mail</div>
            <div class="pt5"><TEXT__ name="email_forget" class="design fz14 bd_333_i" required /></div>

            <div class="pt20"><button class="button_admin_3" >Redefinir senha</button></div>
        </form>
        <div class="pt30 pb10 mt30 fz14 tac bdt_aaa">Para fazer login <button @click="SHOW.position = `login`; login_remember(`ADMIN`);" type="button" class="dib fz14 c_blue link">clique aqui!</button></div>
    </div>

</template>

<style scoped>
</style>