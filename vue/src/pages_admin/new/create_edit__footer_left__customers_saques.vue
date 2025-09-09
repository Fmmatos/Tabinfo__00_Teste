<script setup lang="ts">
import { open__, price } from '@/vendor/services/events';
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

    <div class="clear"></div>

    <div class="p20 pt15">
        <div class=""><b>Cliente:</b> {{ OBJ.VALUE?.name }}</div>
        <div class="pt10"><b>Valor:</b> {{ price(OBJ.VALUE?.price__) }}</div>
        <div class="pt10"><b>Conta:</b> {{ OBJ.VALUE?.conta }}</div>
    </div>

</template>


<style scoped>
</style>