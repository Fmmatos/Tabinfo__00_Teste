<script setup lang="ts">
import { inject } from 'vue';
import { DIR } from '@/vendor/services/localhost';
import { rand } from '../services/events';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            value?: Object,
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
        const __url = () => {
            let url = SHOW.iframe__;

            // ADMIN
                if(SHOW.GET[0] == `admin`){
                    if(SHOW.iframe_array?.options){
                        url = `/${SHOW.GET[0]}/modules/${SHOW.iframe_array?.options}/0`;
                    } else {
                        return ``;
                    }
                }
            // ADMIN

            // DASHBOARD
                if(SHOW.GET[0] == `dashboard`){
                    url = `/${SHOW.GET[0]}/${SHOW.iframe__}/0`;
                }
            // DASHBOARD

            return `${DIR()}${url}?v=${rand()}`
        }
    // FUNCTIONS
</script>


<template>

    <div class="posf t0 l0 z9 w100p h100p bg_000000AF"></div>
    <div class="posf t0 l0 z9 w100p h100p p40">
        <div class="posr w100p h100p">
            <a @click="SHOW.iframe__=0" class="posa t0 r0 w40 h40 p10 m10 z9 bg_fff bg_hover_eee br50p not_mouse_right_click"><img src="@/vendor/assets/img/svg/default/close.svg" /> </a>
            <iframe v-if="__url()" :src="`${__url()}`" width="100%" height="100%" class="posa t0 l0 z8 br10 shadow" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe>
        </div>
    </div>

</template>


<style scoped>

</style>