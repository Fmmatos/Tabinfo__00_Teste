<script setup lang="ts">
import { MOBILE, refresh } from '@/vendor/services/events';
import { DIR, DIR_P, LOCALHOST } from '@/vendor/services/localhost';
import api from '@/vendor/services/api';
import { inject } from 'vue';

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
        const __refresh_bd_local = () => {
            if(confirm('Deseja realmente atualizar o banco de dados local?')){
                api(`/admin/refresh_bd_local`, {}, (json: any) => {
                });
            }
        }
    // FUNCTIONS
</script>


<template>

    <header class="posr z6 no_sel">
        <div class="h50 flexx flex_j flex_ac bg_1A1A1A">
            <div class="pl10 pr10 flexx flex_ac">
                <a v-if="!MOBILE(500)" :href="`${DIR()}/admin/`"><img :src="`${DIR_P()}/img/logo_top.png`" class="db ml10" style="max-width: 170px; max-height: 20px;"></a>
                <a v-if="MOBILE(500)" :href="`${DIR()}/admin/`"><img :src="`${DIR_P()}/img/logo_top.png`" class="db ml10" style="max-width: 120px; max-height: 20px;"></a>
                <a @click="SHOW.MENU_SIDE = !SHOW.MENU_SIDE" class="h45 p10 fz16 c-p c_fff"><svg class="w24 h24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" preserveAspectRatio="xMidYMid meet" focusable="false"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></a>
            </div>
            <div class="pl10 pr20 fz24 fwb5 ttu c_fff dn_1000" style="font-family: monospace;">Administração</div>
            <div class="flexx flex_ac pl20 pr20 fz14">
                <button v-if="LOCALHOST()" @click="__refresh_bd_local()" class="p5 pl10 pr10 mr10 flexx flex_ac c_fff bg_303030 bg_hover_4A4A4A br5" tooltip="Apagar Banco de Dados" pos="bottom" target="_blank">
                    <i class="fz17 faa-refresh"></i>
                </button>
                <a :href="`${DIR()}/`" class="p5 pl10 pr10 mr10 flexx flex_ac c_fff bg_303030 bg_hover_4A4A4A br5" tooltip="Visitar Site" pos="bottom" target="_blank">
                    <img src="@/vendor/assets/img/svg/tv.svg" class="w20 h20" />
                </a>
                <a :href="`${DIR()}/admin/login/logout`" class="p5 pl10 pr10 flexx flex_ac c_fff bg_303030 bg_hover_4A4A4A br5" tooltip="Sair" pos="bottom">
                    <img src="@/vendor/assets/img/svg/logout.svg" class="w20 h20" />
                    <div class="pl5 fz12">Sair</div>
                </a>
            </div>
        </div>
    </header>

</template>


<style scoped>

</style>