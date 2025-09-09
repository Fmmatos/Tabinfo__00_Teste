<script setup lang="ts">
import { open__, img, back, html_treatment } from '@/vendor/services/events';
import { DIR_P } from '@/vendor/services/localhost';
import { inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        const PROPS = defineProps<{
            class?: string
            left?: { open?: string; click?: () => void; icon?: string; history?: boolean }
            title?: string
            right?: { menu?: boolean; icon?: string }
        }>()
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
    // FUNCTIONS
</script>


<template>

    <header>
        <div class="w100p flexx" :class="PROPS.class ? PROPS.class : ``">
            <!-- LEFT -->
                <div class="w35 h35 fz24 tal">
                    <a v-if="PROPS.left?.open" @click="open__(PROPS.left?.open)" class="db h35"><i :class="PROPS.left?.icon ?? `faa-angle-left`" ></i></a>
                    <a v-if="PROPS.left?.history" @click="back()" class="db h35"><i :class="PROPS.left?.icon ?? `faa-angle-left`" ></i></a>
                    <a v-if="typeof PROPS.left?.click === 'function'" @click="PROPS.left?.click()" class="db h35"><i :class="PROPS.left?.icon ?? `faa-angle-left`" ></i></a>
                </div>
            <!-- LEFT -->

            <!-- CENTER -->
                <div class="flex_1 fz18 lh30 tac">
                    <div v-if="PROPS.title == `logo`" v-html="img({ image: `${DIR_P()}/img/logo.png` }, 50, 50, 'db m-a')"></div>
                    <div v-else v-html="html_treatment(PROPS.title ?? ``)"></div>
            </div>
            <!-- CENTER -->

            <!-- RIGHT -->
                <div class="w35 fz24 tar" :class="right?.menu ? '' : 'op0'">
                    <a v-if="PROPS.right?.menu" @click="SHOW.MENU_SIDE_APP = !SHOW.MENU_SIDE_APP;" class="h35 flexx flex_c flex_ac c_fff bd_000 bg_2 br50p"><img src="@/vendor/assets/img/svg/bars.svg" class="" /></a>
                </div>
            <!-- RIGHT -->
        </div>
    </header>

</template>


<style scoped>

</style>

<!-- import TOP_APP__ from '@/pages/z_top_app.vue'; -->
<!-- <TOP_APP__ :left="{ open: `/` }" title="Carteira Digital" :right="{ menu: true }" /> -->