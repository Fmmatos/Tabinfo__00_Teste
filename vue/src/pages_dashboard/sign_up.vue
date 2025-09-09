<script setup lang="ts">
import TEXT__ from '@/vendor/components/input/text.vue';
import PASSWORD__ from '@/vendor/components/input/password.vue';
import SELECT__ from '@/vendor/components/input/select.vue';
import ADDRESS__ from '@/vendor/components/input/__address.vue';

import api from '@/vendor/services/api';
import { DIR, DIR_P, LOCALHOST } from '@/vendor/services/localhost';
import { key__, MOBILE, open__, open_blank, open_href } from '@/vendor/services/events';
import { rootAuth } from '@/vendor/storages/auth';
import { inject, ref } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.termo_uso = 0;
        SHOW.position_pay = 'credit_card';
        SHOW.pay = {};
    // SHOW
    
    // FORM
        FORM.v.type_all = OBJ?.['__GLOBAL__']?.['__TYPES__']?.['__CUSTOMERS__']?.['DASHBOARD']?.['LOGIN'];
        FORM.v.type = FORM.v.type_all.hasOwnProperty($_GET['2']) ? $_GET['2'] : key__(FORM.v.type_all)?.[0];
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
        const __submit = () => {
            FORM.v._method = 'PUT';
            api(`/dashboard/sign_up/store`, FORM.v, (json: any) => {
                // GO HOME
                    if(json.token){
                        rootAuth(json.token);
                        open(`/`);
                    }
                // GO HOME
            });
        }
    // FUNCTIONS
</script>


<template>

    <div class="pt10 pb8 tac bg_1">
        <img :src="`${DIR_P()}/img/logo_branca.png`" class="dib" style="max-width: 200px;">
    </div>

    <div class="pt30 pb60 fz13">
        <div class="centerr_5">
            <div class="posr pt20 pb20 bg_fff br20" :class="MOBILE() ? 'pl10 pr10' : 'pl40 pr40'">
                <a @click="open__(`/login`, {}, 1)" class="posa t10 l10 p10 mt1 fz20"><i class="faa-angle-left"></i></a>

                <form @submit.prevent="__submit()">
                    <div class="fz18 fwb7 lh24 tac fz16_1000" :class="MOBILE() ? 'ml--15 mr--15' : ''">Faça parte do {{ OBJ?.info?.name_site }}!</div>

                    <div v-if="OBJ?.indications" class="p10 pl20 pr20 mt30 fz13 bg_d9edf7 br10"><i class="mr5 faa-info-circle"></i> Você foi indicado(a) por <b class="ng-binding">{{ OBJ.indications.name }}</b>.</div>

                    <div class="posr pt30">
                        <TEXT__ label="Nome" name="name" class="design fz13" :value="LOCALHOST() ? 'Teste AAA' : ''" required/>
                    </div>

                    <div class="posr pt20">
                        <TEXT__ label="CPF" name="cpf" mask="cpf" class="design fz13" :value="LOCALHOST() ? '075.673.766-44' : ''" required/>
                    </div>

                    <div class="posr pt20">
                        <SELECT__ label="Sexo" name="sexo" class="design fz13" extra="|->>1: Masculino; 2: Feminino; 3: Outro" :value="LOCALHOST() ? '1' : ''" required/>
                    </div>

                    <div class="posr pt20">
                        <TEXT__ type="date" label="Data de nascimento" name="birth" class="design fz13" :value="LOCALHOST() ? '1990-01-15' : ''" required/>
                    </div> 

                    <div class="posr pt20">
                        <TEXT__ label="Celular / Whatsapp" name="phone" class="design fz13" placeholder="(00) 9 0000-0000" :value="LOCALHOST() ? '11999998888' : ''" required/>
                    </div>

                    <div class="posr pt20">
                        <TEXT__ type="email" label="E-mail" name="email" class="design fz13" :value="LOCALHOST() ? 'teste@email.com' : ''" required/>
                    </div>

                    <div class="posr pt20">
                        <PASSWORD__ label="Senha" name="password" space="h15" space_1="h10" class="design fz13" required />
                    </div>

                    <div class="posr pt10 ml--10 mr--10">
                        <ADDRESS__ class="design fz13"
                            class_1="wr12 p10" class_2="wr12 p10" class_3="wr6 p10" class_4="wr6 p10" class_5="wr12 p10" class_6="wr5 p10" class_7="wr7 p10"
                            :value_1="LOCALHOST() ? '88.010-000' : ''"
                            :value_2="LOCALHOST() ? 'Rua Felipe Schmidt' : ''"
                            :value_3="LOCALHOST() ? '348' : ''"
                            :value_4="LOCALHOST() ? 'Sala 101' : ''"
                            :value_5="LOCALHOST() ? 'Centro' : ''"
                            :value_6="LOCALHOST() ? 'SC' : ''"
                            :value_7="LOCALHOST() ? 'Florianópolis' : ''"
                            required
                        />
                    </div>

                    <div v-if="OBJ?.info?.image_termo_de_uso_cadastro" class="pt20 flexx">
                        <i class="faa-info-circle pt5"></i>
                        <div class="pl5 fz13 fwb6 lh20">Ao concluir seu cadastro, você concorda com os <a @click="OBJ.info?.image_termo_de_uso_cadastro ? open_blank(OBJ.info?.image_termo_de_uso_cadastro) : ``" class="c_blue link">termos e condições</a> da {{ OBJ?.info?.name_site }}.</div>
                    </div>

                    <div class="pt20 pb10">
                        <button type="submit" class="button_3" ><i class="faa-check fz16 mr5"></i> Concluir meu cadastro</button>
                    </div>

                </form>

            </div>

            <div class="pt20 fz14 lh22 tac c_1">
                Já possui uma conta? <a @click="open__(`/dashboard/login/`)" class="fwb7 c_1">Entre aqui para fazer login</a>.
            </div>
        </div>
    </div>


</template>

<style>
    .__DASHBOARD__.__PG__sign_up__ .__bg__ { background: #F1F5F9 !important; }

    .__DASHBOARD__.__PG__sign_up__ .__TOP__,
    .__DASHBOARD__.__PG__sign_up__ .__MENU_SIDE__,
    .__DASHBOARD__.__PG__sign_up__ .__FOOTER__ { display: none !important; }
    .__DASHBOARD__.__PG__sign_up__ .__BORDER_RADIUS_LEFT__,
    .__DASHBOARD__.__PG__sign_up__ .__BORDER_RADIUS_RIGHT__ { display: none !important; }

    .__DASHBOARD__.__PG__sign_up__ .__ADDRESS__XX .__ADDRESS__,
    .__DASHBOARD__.__PG__sign_up__ .__ADDRESS__XX .__NUMBER__,
    .__DASHBOARD__.__PG__sign_up__ .__ADDRESS__XX .__COMPLEMENT__,
    .__DASHBOARD__.__PG__sign_up__ .__ADDRESS__XX .__NEIGHBORHOOD__,
    .__DASHBOARD__.__PG__sign_up__ .__ADDRESS__XX .__UF__,
    .__DASHBOARD__.__PG__sign_up__ .__ADDRESS__XX .__CITY__ { display: none; }
</style>

<style scoped>
</style>