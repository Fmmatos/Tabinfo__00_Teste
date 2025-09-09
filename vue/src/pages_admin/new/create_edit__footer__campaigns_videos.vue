<script setup lang="ts">
import { clear, count, date__, img_blob, } from '@/vendor/services/events';
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

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <!-- CAMPAIGNS_VIDEOS -->
        <div v-if="OBJ.menu_admin.table == `campaigns_videos`">
            <div v-if="count(OBJ.VALUE?.campaigns_videos)" class="fz16 fwb6">Vídeos Anteriores</div>
            <div v-for="(value, key) in OBJ.VALUE?.campaigns_videos">
                <div class="wr4 p20">
                    <div class="posr max-w250 m-a tac br10">
                        <a :href="value.image_file" target="_blank" class=""><video :src="img_blob(value.image_file)" class="dib w100p h300 min-h100 bg_ccc br10" playsinline muted style="object-fit: cover;"></video></a>
                    </div>

                    <div class="pt50 pb20 pl30 pr30 mt--40 bg_fff br20">
                        <div class="pt10 fwb6">{{ date__(value?.created_at , 'd/m/Y H:i') }} | {{ OBJ.VALUE?.campaigns_status?.[value?.status]?.name }}</div>
                        <div class="pt10 fwb6"></div>
                        <div class="pt10">{{ value.status_text }}</div>
                    </div>
                </div>
                <div v-html="clear(key, 3)"></div>
            </div>
            <div class="clear"></div>
        </div>
    <!-- CAMPAIGNS_VIDEOS -->

</template>


<style scoped>
</style>