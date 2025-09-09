<script setup lang="ts">
import api from '@/vendor/services/api';
import { COOKIES_DELETE, COOKIES_CREATE, top } from '@/vendor/services/events';
import { inject } from 'vue';
import { modules__search_datatable_create } from '../services/modules';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            table?: string
            css?: string
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
        const __paginate = (url: string) => {
            COOKIES_DELETE(`SEARCH__page`);
            api(url, {}, (json: any) => {
                modules__search_datatable_create();
                if(json.OBJ?.DATATABLE?.current_page){
                    COOKIES_CREATE(`SEARCH__page`, json.OBJ.DATATABLE.current_page.toString());
                }
                top();
            });
        }
    // FUNCTIONS

</script>


<template>

    <div class="dib">
        <ul class="flexx flex_ac" :class="PROPS.css ?? `paginate`">
            <li v-for="value in (OBJ.PAGG?.[PROPS.table] ?? OBJ.PAGG?.DATATABLE)" class="">
                <div v-if="value.url && !value.active" @click="__paginate(value.url)">{{ value.label }}</div>
                <div v-if="value.active" :class="value.active ? `active` : ``">{{ value.label }}</div>
                <div v-if="!value.url">{{ value.label }}</div>
            </li>
        </ul>
    </div>

</template>


<style scoped>

</style>