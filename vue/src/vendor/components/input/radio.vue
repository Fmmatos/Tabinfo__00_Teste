<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { NEW__select_name } from '@/services/NEW__events';
import { tooltip, options__, value__ } from '@/vendor/services/events';
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


            class_1?: String,
            class_2?: String,
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

    // TEMP
        TEMP.class = PROPS.class;
        TEMP.tooltip = PROPS.tooltip;
    // TEMP

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
    // FUNCTIONS
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__RADIO__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr pt5" style="height: auto !important">
                    <div :class="TEMP.class ? TEMP.class : `flexx_x flex_c flex_col gap_10`">
                        <div v-for="(value_1, key) in options__(PROPS)" :class="PROPS.class_1">
                            <label class="c-p flexx flex_ac" :class="PROPS.class_2" :tooltip="TEMP.tooltip ? TEMP.tooltip : undefined" v-on="tags(PROPS, `radio`, false).events">
                                <input type="radio" v-model="FORM.v[PROPS.name]" :value="value_1.id" v-bind="tags(PROPS, `radio`).attributes" />
                                <span class="dib pl7 vam">{{ NEW__select_name(value_1) }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>

</style>