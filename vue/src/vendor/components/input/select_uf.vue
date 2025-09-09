<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { tooltip, city__, split, select2, select2_reset, value__ } from '@/vendor/services/events';
import { tags } from '@/vendor/services/tags';
import { reactive, onBeforeMount, watch, inject } from 'vue';

tooltip();

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT
    
    // REACTIVE
        const SELECT: any = reactive({})
    // REACTIVE

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


            type__?: String,
            ab?: String,
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

    // SELECT
        SELECT.select_id = PROPS.name;
    // SELECT

    // SHOW
    // SHOW

    // FORM
        value__(PROPS);
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                select2();
                if (PROPS.value){
                    FORM.v[PROPS.value] = ``;
                    setTimeout(() => {
                        city__(FORM, OBJ, 0);
                    }, 50);
                }
            });
        // ONBEFOREMOUNT

        // WATCH
            // watch(() => FORM.v[PROPS.name], (newValue, oldValue) => {
            //     select2_reset(`#select_${SELECT.select_id}`);
            // }, { deep: true })
        // WATCH
    // EVENTS

    // FUNCTIONS
        const __city = () => {
            city__(FORM, OBJ);
        }
    // FUNCTIONS
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__SELECT_UF__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr">
                    <!-- <em v-if="!split('designx', PROPS.class)" class="posa t0 l0 z0 w100p h100p bd_ccc br7"></em> -->

                    <select @change="__city()" :name="PROPS.name" v-model="FORM.v[PROPS.name]" :id="`select_${SELECT.select_id}`" v-bind="tags(PROPS, `select`).attributes" v-on="tags(PROPS, `select`, false).events">
                        <option value="">Selecione...</option>
                        <option v-for="value_1 in OBJ.UF" :key="value_1.id" :value="value_1.id">{{ ab ? value_1.id : value_1.name }}</option>
                    </select>
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>

</style>