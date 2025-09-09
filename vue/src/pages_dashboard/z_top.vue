<script setup lang="ts">
import { img, MOBILE, name, name_ini, split } from '@/vendor/services/events';
import { DIR, DIR_P } from '@/vendor/services/localhost';
import { inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            type?: String,
            extra?: String,
        }>();
    // PROPS

    // SHOW
        SHOW.top_mobile = false;
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <!-- TOP -->
        <header v-if="PROPS?.type == `top`">
            <div class="posr z6 no_sel">
                <div class="h56 flexx flex_j flex_ac bg_1A1A1A">
                    <div class="pl20 pr20 flexx flex_ac">
                        <a v-if="!MOBILE(500)" :href="`${DIR()}/dashboard/`"><img :src="`${DIR_P()}/img/logo_branca.png`" class="db" style="max-width: 170px; max-height: 20px;"></a>
                        <a v-if="MOBILE(500)" :href="`${DIR()}/dashboard/`"><img :src="`${DIR_P()}/img/logo_branca.png`" class="db" style="max-width: 120px; max-height: 20px;"></a>
                        <a @click="SHOW.MENU_SIDE = !SHOW.MENU_SIDE" class="h45 p10 fz16 c-p c_fff"><svg class="w24 h24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" preserveAspectRatio="xMidYMid meet" focusable="false"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></a>
                    </div>
                </div>
            </div>
        </header>
    <!-- TOP -->





    <!-- TOP_RIGHT -->
        <header v-if="PROPS?.type == `top_right`">
        </header>
    <!-- TOP_RIGHT -->

</template>


<style scoped>

</style>