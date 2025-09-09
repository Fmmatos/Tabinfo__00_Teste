<script setup lang="ts">
import TABLE__ from '@/vendor/pages_admin/__table.vue';
import CREATE_EDIT__ from '@/vendor/pages_admin/__create_edit.vue';

import { MOBILE } from '@/vendor/services/events';
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
    // FUNCTIONS
</script>


<template>

    <div :class="MOBILE() ? `p10` : `p20 pl30 pr30`">

        <!-- CREATE_EDIT -->
            <div v-if="SHOW.GET['dashboard_modules'] == `edit`">
                <CREATE_EDIT__ />
            </div>
        <!-- CREATE_EDIT -->





        <!-- TABLE / TABLE (MENU_ADMIN) -->
            <div v-else-if="SHOW.GET['dashboard_modules'] == `table`">
                <TABLE__ />
            </div>
        <!-- TABLE / TABLE (MENU_ADMIN) -->

    </div>

</template>


<style>
</style>