<script setup lang="ts">
import { DIR_API } from '@/vendor/services/localhost';
import { html_treatment, replace } from '@/vendor/services/events';
import { inject } from 'vue';

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

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <div class="__input_all__ db __INPUT__info__" :class="`__INPUT__NAME__${PROPS.name}__`">
        <div class="db mt--10 lh18">
            {{ PROPS.label }}
            <span v-html="html_treatment(replace(`https://URL-DO-SEU-SITE`, DIR_API(), PROPS.tags))"></span>
        </div>
        <div class="clear"></div>
    </div>

</template>


<style scoped>
</style>