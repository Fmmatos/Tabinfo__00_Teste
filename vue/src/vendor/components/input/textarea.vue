<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { tooltip, value__ } from '@/vendor/services/events';
import { tags } from '@/vendor/services/tags';
import { inject, reactive } from 'vue';
tooltip();

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        const TEMP: any = reactive({});
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            check?: String,
            type?: String,
            title?: String,
            wr?: String,
            align?: String,
            label?: String,
            name?: String,
            tags?: String,
            options?: String | any[],
            extra?: String,
            create_column?: String,
            order?: String,
            search?: String,
            search_order?: String,
            table_align?: String,
            fieldset?: String,


            class?: String,
            style?: Record<string, string> | string,
            value?: String | object,

            placeholder?: String,
            label_no?: String,
            required?: String|Boolean,
            required_label?: String,
            required_label_no?: String,
            required_label_no_point?: String,
            readonly?: String,
            disabled?: String,

            min?: String,
            minlength?: String,
            max?: String,
            maxlength?: String,

            tooltip?: String,
            mask?: String,

            click?: (event: Event) => void;
            input?: (event: Event) => void; 
            keyup?: (event: KeyboardEvent) => void;
            keydown?: (event: KeyboardEvent) => void;
            change?: (event: Event) => void;
            focus?: (event: Event) => void;
            blur?: (event: Event) => void;


            limit?: String,
        }>();
    // PROPS

    // EMIT
        const emit = defineEmits([
            'keydown',
            'keyup',
            'input',
            'change',
            'focus',
            'blur'
        ]);
    // EMIT

    // SHOW
    // SHOW

    // FORM
        value__(PROPS);
    // FORM

    // OBJ
    // OBJ
    
    // EVENTS
    // EVENTS

    // FUNCTIONS
        const __keyup = () => {
            if (PROPS.limit){
                TEMP.limit = `(${(FORM.v[PROPS.name]).length} caracteres usados de ${PROPS.limit})`;  
            }
        }
    // FUNCTIONS
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__TEXTAREA__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr" style="height: auto !important">
                    <textarea @input="__keyup" v-model="FORM.v[PROPS.name]" v-bind="tags(PROPS, `textarea`).attributes" v-on="tags(PROPS, `textarea`, false).events"></textarea>

                    <div v-if="TEMP.limit" class="pt5">{{ TEMP.limit }}</div>
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>

</style>