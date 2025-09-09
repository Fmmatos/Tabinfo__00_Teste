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
            api(`/login/forget_password`, FORM.v, (json: any) => {
                FORM.v.email_forget = ``;
                SHOW.position = 'forget_password_code';
            });
        }
    // FUNCTIONS
</script>


<template>

    <div>

        <div class="fz13 fade_in">
            <div class="fz30 fwb8 lh40 ls-08">Recuperar senha</div>
            <div class="pt5">Digite seu email para recuperar a sua senha!</div>

            <div class="pt20">
                <form @submit.prevent="__submit()">
                    <TEXT__ type="email" label="E-mail" name="email_forget" class="design pr50 fz13i" icon="faa-envelope-o" />

                    <div class="pt30">
                        <button class="button_3">Enviar código de recuperação</button>
                    </div>
                </form>
            </div>

            <div class="pt20 pb10 mt30 fz14 tac bdt_aaa">
                <button @click="SHOW.position = `login`; login_remember(SHOW.GET[0]==`dashboard` ? 'DASHBOARD' : 'ROOT');" class="dib fz14 c_1 link">Fazer login!</button>
            </div>
        </div>

    </div>

</template>


<style scoped>
</style>