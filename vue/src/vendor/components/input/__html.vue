<script setup lang="ts">
import { NEW__html_array_click } from '@/services/NEW__events';
import { compare__, html_treatment, json_decode } from '@/vendor/services/events';
import { inject, reactive } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        const TEMP: any = reactive({});
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            col: Object,
            value?: Object,
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        TEMP.value = PROPS.value[PROPS.col.name];
    // EVENTS

    // FUNCTIONS
        const __html = (value: string) => {
            let result = value.split('-->HTML->')[1] || ``;
            result = result.replace(/<script[\s\S]*?<\/script>/gi, '');
            return result ? result : ``;
        }

        const __array = (value: string) => {
            let result = value.split('-->HTML_ARRAY->')[1] || ``;
            return json_decode(result) as any[];
        }
    // FUNCTIONS
</script>


<template>

    <!-- HTML_ARRAY -->
        <div v-if="TEMP.value && compare__(`-->HTML_ARRAY->`, TEMP.value)" :style="PROPS.col.name==`name` ? `min-width: 200px;` : ``">
            <slot v-for="(value, key) in __array(TEMP.value)" :key="key">
                <div :class="value?.div">
                    <button v-if="value?.button" @click="NEW__html_array_click(value?.json)" type="button" :class="value?.button"><div v-html="html_treatment(value?.html)"></div></button>
                    <slot v-else><div v-html="html_treatment(value?.html)"></div></slot>
                </div>
            </slot>
        </div>
    <!-- HTML_ARRAY -->





    <!-- HTML -->
        <div v-else-if="TEMP.value && compare__(`-->HTML->`, TEMP.value)" :style="PROPS.name==`name` ? `min-width: 200px;` : ``">
            <div v-html="html_treatment(__html(TEMP.value))"></div>
        </div>
    <!-- HTML -->

</template>


<style scoped>

</style>