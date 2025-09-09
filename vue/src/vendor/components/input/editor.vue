<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { editor_create, value__ } from '@/vendor/services/events';
import { onBeforeMount, inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
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
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                value__(PROPS);

                setTimeout(() => {
                    editor_create(PROPS.name);
                }, 50);
            });
        // ONBEFOREMOUNT
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS

</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__EDITOR__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

            <div class="__input__">
                <div :class="`editor__${PROPS.name}`" class="editor__ __EDITOR__">
                    <div class="toolbar-container"></div>
                    <div class="editor"></div>
                    <textarea :name="PROPS.name" v-model="FORM.v[PROPS.name]" class="dni"></textarea>
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>
</style>