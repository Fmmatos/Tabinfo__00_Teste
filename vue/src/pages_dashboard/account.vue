<script setup lang="ts">
import PASSWORD__ from '@/vendor/components/input/password.vue';
import SELECT__ from '@/vendor/components/input/select.vue';
import TEXT__ from '@/vendor/components/input/text.vue';

import api from '@/vendor/services/api';   
import { open__, alerts, name_ini, __BOXS__, __BOXS_CLOSE__, img, upload_files, tooltip, MOBILE } from '@/vendor/services/events';
import { accept__ } from '@/vendor/services/tags';
import { inject } from 'vue';

tooltip();

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
    // EVENTS

    // FUNCTIONS
        const __submit = () => {
            FORM.v.form = `dados`;
            FORM.v._method = 'PUT';
            api(`/dashboard/account/update`, { FORM: FORM }, (json: any) => {
                alerts(1, 'Cadastrado com sucesso!')
                open__(`/account`, {}, 1);
            });
        }
        const __submit_password = () => {
            FORM.v.form = `password`;
            FORM.v._method = 'PUT';
            api(`/dashboard/account/update`, { FORM: FORM }, (json: any) => {
                alerts(1, 'Cadastrado com sucesso!');
                open__(`/account`, {}, 1);
            });
        }

        // UPLOAD
            // NEW
                const __update_photo = (event: Event) => {
                    // FILES
                        upload_files(event, `image`);
                    // FILES

                    FORM.v.form = `photo`;
                    FORM.v._method = 'PUT';
                    api(`/dashboard/account/update`, { FORM: FORM }, (json: any) => {
                        open__(`/account`, {}, 1);
                    });
                }
            // NEW

            // DELETE
                const __update_photo_delete = () => {
                    if(confirm('Deseja realmente excluir esta foto?')){
                        FORM.v.image = [];
                        FORM.v.form = `photo`;
                        FORM.v._method = 'PUT';
                        api(`/dashboard/account/update`, { FORM: FORM }, (json: any) => {
                            open__(`/account`, {}, 1);
                        });
                    }
                }
            // DELETE
        // UPLOAD
    // FUNCTIONS
</script>


<template>

    <div class="pt30">
        <div class="centerr">

            <div class="pb30">
                <div class="fz24 fwb5 lh24 ls-05">Meus Dados</div>
            </div>

            <div class=" bd_ddd bg_fff br10 shadow_0">
                <div class="p20">

                    <div class="flexx_1000">
                        <!-- PHOTO -->
                            <div class="mr40 m0_1000" :class="MOBILE() ? `flexx flex_t` : ``">
                                <div class="posr dib" :class="MOBILE() ? `mr40` : ``">
                                    <div class="db">
                                        <div v-if="OBJ?.user?.image__" class="w120 h120 o-h m-a flexx flex_c flex_ac br10" v-html="img(OBJ.user)"></div>
                                        <div v-else class="w120 h120 m-a fz28 flexx flex_c flex_ac bg_0cf br10">{{ name_ini(OBJ?.user?.name) }}</div>
                                    </div>
                                    <a v-if="OBJ?.user?.image__" @click="__update_photo_delete()" class="posa t0 r0 w22 h22 mt--5 mr--4 fz14 flexx flex_c flex_ac c_fff b_red br50p" tooltip="Excluir Foto"><img src="@/vendor/assets/img/svg/default/close_1.svg" class="h15"></a>
                                    <label>
                                        <a class="posa b0 r0 w30 h30 mb--10 mr--5 fz14 flexx flex_c flex_ac bd_ccc bg_fff br50p" aria-label="Alterar Foto" tooltip="Alterar Foto"><i class="faa-camera"></i></a>
                                        <input type="file" :accept="accept__()" @change="__update_photo" class="dni" />
                                    </label>
                                </div>
                                <div class="flex_1">
                                    <div class="pt15"><a @click="__BOXS__(`__PASSWORD__`)" class="w120 pl5 pr5 tac button_2" aria-label="Alterar Senha" tooltip="Alterar Senha">Alterar Senha</a></div>
                                    <!-- <div class="pt15"><a @click="open__(`/cards`, { init: 1 }, 1)" class="w120 pl5 pr5 tac button_2" aria-label="Meus Cartões" tooltip="Ver Meus Cartões">Meus Cartões</a></div> -->
                                    <div class="pt15"><a @click="open__(`/address`, { init: 1 }, 1)" class="w120 pl5 pr5 tac button_2" aria-label="Meus Endereços" tooltip="Ver Meus Endereços">Meus Endereços</a></div>
                                    <!-- <div class="pt15"><a @click="open__(`/banks`, { init: 1 }, 1)" class="w120 pl5 pr5 tac button_2" aria-label="Contas Bancários" tooltip="Ver Minhas Contas Bancários">Contas Bancários</a></div> -->
                                </div>
                            </div>
                        <!-- PHOTO -->

                        <div class="h40 dnn_1000"></div>
                        <div class="flex_1">
                            <form @submit.prevent="__submit()" autocomplete="off">
                                <div class="wr6 p10">
                                    <TEXT__ label="Nome" name="name" :value="OBJ.user.name" required/>
                                </div>

                                <div class="wr6 p10">
                                    <TEXT__ type="email" label="E-mail" name="email" :value="OBJ.user.email" disabled="" />
                                </div>
                                <div class="clear"></div>

                                <div class="wr6 p10">
                                    <TEXT__ label="Celular" name="phone" :value="OBJ.user.phone" placeholder="(00) 9 0000-0000" required/>
                                </div>

                                <div class="wr6 p10">
                                    <TEXT__ label="CPF" name="cpf" mask="cpf" :value="OBJ.user.cpf" disabled="" />
                                </div>
                                <div class="clear"></div>

                                <div class="wr6 p10">
                                    <TEXT__ type="date" label="Data de nascimento" name="birth" :value="OBJ.user.birth" required/>
                                </div>

                                <div class="wr6 p10 fz12">
                                    <SELECT__ label="Sexo" name="sexo" :value="OBJ.user.sexo" extra="|->>1: Masculino; 2: Feminino; 3: Outro" required/>
                                </div>
                                <div class="clear"></div>

                                <div class="p10 tar">
                                    <button class="w-a p7 pl40 pr40 button_3" :class="MOBILE() ? `m-a` : ``">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- __PASSWORD__ -->
        <div v-if="SHOW.BOXS == `__PASSWORD__`">
            <div class="__BOXS__">
                <em></em>
                <div>
                    <div class="w500 pl10 pr10">
                        <div class="posr p20 m-a bg_fff br10 shadow_0">
                            <button @click="__BOXS_CLOSE__()" class="close"><svg width="15" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_183_498)"><path fill="currentColor" d="M11.6531 14.1409L2.11833 23.6757L0 21.5561L9.53421 12.0219L0 2.48704L2.11833 0.368713L11.6531 9.90292L21.1874 0.368713L23.307 2.48704L13.7722 12.0219L23.307 21.5561L21.1874 23.6757L11.6531 14.1409Z"/></g><defs><clipPath id="clip0_183_498"><rect width="24.307" height="24.307" fill="white" transform="translate(0 0.368713)"/></clipPath></defs></svg></button>

                            <div>
                                <form @submit.prevent="__submit_password()" autocomplete="off">
                                    <div class="fz18 fwb6 tac">Alterar Senha</div>
                                    <div class="pt20">
                                        <PASSWORD__ label="Senha" name="password" space="h20" space_1="h20" class="design fz14" required />
                                    </div>
                                    <div class="pt30">
                                        <button type="submit" class="h45 fz15i bd0 button_3" >
                                            <i class="faa-check fz16 mr5"></i>
                                            Salvar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- __PASSWORD__ -->

</template>

<style>
</style>