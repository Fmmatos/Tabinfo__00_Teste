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

        <!-- X_SETTINGS -->
            <div v-if="SHOW.GET['n'] == `x_settings`">
                <CREATE_EDIT__ x_settings="1"  />
            </div>
        <!-- X_SETTINGS -->





        <!-- CREATE_EDIT -->
            <div v-else-if="SHOW.GET[3]">
                <CREATE_EDIT__ />
            </div>
        <!-- CREATE_EDIT -->





        <!-- TABLE / TABLE (MENU_ADMIN) -->
            <div v-else-if="SHOW.GET?.['n']">
                <TABLE__ />
            </div>
        <!-- TABLE / TABLE (MENU_ADMIN) -->

    </div>

</template>


<style>
</style>