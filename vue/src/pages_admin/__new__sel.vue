<script setup lang="ts">
import api from '@/vendor/services/api';
import { MOBILE, open__, url_admin_dashboard } from '@/vendor/services/events';
import { inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            type?: String,
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
    // FUNCTIONS
</script>


<template>

    <slot v-if="OBJ.menu_admin?.table == `xxx`">
    </slot>


</template>


<style scoped>
</style>