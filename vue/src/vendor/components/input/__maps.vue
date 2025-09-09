<script setup lang="ts">
import { DIR_API } from '@/vendor/services/localhost';
import { tags } from '@/vendor/services/tags';
import { inject, onBeforeMount } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT
    // OBJ
        OBJ.mapa_show = 0;
    // OBJ

    onBeforeMount(() => {
        setTimeout(() => {
            OBJ.mapa_show = 1;
        }, 500);
    });
</script>


<template>

    <slot>
        <div v-show="OBJ.mapa_show">
            <div v-if="OBJ.mapa" class="pt20">
                <iframe :src="`${DIR_API()}/admin/google_maps/iframe`" width="100%" height="450" class="br10" background="no" scrolling="No" marginwidth="0" marginheight="0" frameborder="0"></iframe>
            </div>

            <div v-else class="p20 mt10 bg_eee">
                <div class="pt5">Endereço não encontrado...</div>
            </div>
        </div>
    </slot>

</template>


<style scoped>
    input[readonly] { background: #eee; }
    input[disabled] { background: #eee; }
</style>