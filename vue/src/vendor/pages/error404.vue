<script setup lang="ts">
import { INTERVAL_FIM } from '@/services/NEW__events';
import { open__ } from '@/vendor/services/events';
import { inject, onUnmounted } from 'vue';

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

    // PROPS
        const PROPS: any = defineProps<{
            h?: String,
        }>();
    // PROPS

    // EVENTS
        // INTERVAL
            const INTERVAL = inject<any>(`INTERVAL`);
            onUnmounted(() => { INTERVAL_FIM(INTERVAL); });
        // INTERVAL
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

        <div class="w100p fz18 flexx flex_c flex_ac c_A0AECB bg_F7FAFC fade_in" :class="h ? h : `h100vh`" style="font-family: 'Nunito', sans-serif">
        <div class="pl20 pr20">
            <div class="flexx flex_c flex_ac">
                <div class="pr10 ttu tac">404</div>
                <div class="pl10 ttu tac bdl_ccc">Não encontrado</div>
            </div>
            <div class="pt40 tac fz14 ttu"><a @click="open__(`/`, {}, SHOW.GET[0]==`dashboard` ? 1 : 0);" class="c_blue"><u>Ir para home</u></a></div>
        </div>
    </div>

</template>

<style scoped>
    /* cyrillic-ext */
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDLshdTk3j77e.woff2) format('woff2');
        unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    /* cyrillic */
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDLshdTA3j77e.woff2) format('woff2');
        unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    /* vietnamese */
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDLshdTs3j77e.woff2) format('woff2');
        unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
    }
    /* latin-ext */
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDLshdTo3j77e.woff2) format('woff2');
        unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    /* latin */
    @font-face {
        font-family: 'Nunito';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/nunito/v26/XRXI3I6Li01BKofiOc5wtlZ2di8HDLshdTQ3jw.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
</style>