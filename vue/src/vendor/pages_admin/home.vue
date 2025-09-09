<script setup lang="ts">
import { compare__, html_treatment, MOBILE, open__ } from '@/vendor/services/events';
import { logout } from '@/vendor/storages/auth';
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
        const __open = (value: any) => {
            if (value?.logout){
                logout();
            }
            if (value?.url){
                if (value?.table){
                    open__(value.url, { n: value.table });
                } else {
                    open__(value.url);
                }
            }
            return null;
        }
    // FUNCTIONS
</script>


<template>

    <div :class="MOBILE() ? `p10` : `p20 pl30 pr30`">
        <ul class="">
            <li v-for="(value, key) in OBJ.menu_side" :key="key">
                <ul v-if="value.name != `Início`">
                    <li class="w190 h100 fll p5 mb8 tac">
                        <a @click="__open(value)" class="db p10 pt20 pb20 c_777 c_hover_666 bd_f4f4f4 bg_fff bs2_hover br10">
                            <i v-if="value?.icon && compare__(`faa-`, value.icon)" :class="value.icon" class="fz30"></i>
                            <div v-else><div class="w30 m-a __SVG__" v-html="html_treatment(value.svg)"></div></div>
                            <div class="pt10 limit">{{ value.name }}</div>
                        </a>
                    </li>

                    <li v-for="(value_1, key_1) in value.submenu" :key="key_1">
                        <div class="w190 h100 fll p5 mb8 tac">
                            <a @click="__open(value_1)" class="db p10 pt20 pb20 c_777 c_hover_666 bd_f4f4f4 bg_fff bs2_hover br10">
                                <i v-if="(value_1?.icon && compare__(`faa-`, value_1.icon)) || (value?.icon && compare__(`faa-`, value.icon))" :class="value_1?.icon ? value_1.icon : value.icon" class="fz30"></i>
                                <div v-else><div class="w30 m-a __SVG__" v-html="html_treatment(value.svg)"></div></div>
                                <div class="pt10 limit">{{ value_1.name }}</div>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="clear"></div>
    </div>

</template>


<style>
    .__SVG__ svg,
    .__SVG__ svg path { fill: #777 !important; }
</style>