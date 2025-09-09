<script setup lang="ts">
import TEXT__ from '@/vendor/components/input/text.vue';
import SELECT_CITY__ from '@/vendor/components/input/select_city.vue';
import SELECT_UF__ from '@/vendor/components/input/select_uf.vue';

import { inject, reactive } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        const TEMP: any = reactive({});
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            class?: String,

            class_1?: String,
            class_2?: String,
            class_3?: String,
            class_4?: String,
            class_5?: String,
            class_6?: String,
            class_7?: String,

            class_input_1?: String,
            class_input_2?: String,
            class_input_3?: String,
            class_input_4?: String,
            class_input_5?: String,
            class_input_6?: String,
            class_input_7?: String,

            value?: Object,
            tags?: String,

            value_1?: String,
            value_2?: String,
            value_3?: String,
            value_4?: String,
            value_5?: String,
            value_6?: String,
            value_7?: String,

            disabled?: String,
            label_no?: String,
            required?: String|Boolean,
            required_label?: String,
            tooltip__?: String,
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // TEMP
        TEMP.class = PROPS.class;

        TEMP.class_1 = PROPS.class_1 ? PROPS.class_1 : `wf2 p10`;
        TEMP.class_2 = PROPS.class_2 ? PROPS.class_2 : `wf6 p10`;
        TEMP.class_3 = PROPS.class_3 ? PROPS.class_3 : `wf2 p10`;
        TEMP.class_4 = PROPS.class_4 ? PROPS.class_4 : `wf2 p10`;
        TEMP.class_5 = PROPS.class_5 ? PROPS.class_5 : `wf4 p10`;
        TEMP.class_6 = PROPS.class_6 ? PROPS.class_6 : `wf4 p10`;
        TEMP.class_7 = PROPS.class_7 ? PROPS.class_7 : `wf4 p10`;

        TEMP.class_input_1 = PROPS.class_input_1 ? PROPS.class_input_1 : ``;
        TEMP.class_input_2 = PROPS.class_input_2 ? PROPS.class_input_2 : ``;
        TEMP.class_input_3 = PROPS.class_input_3 ? PROPS.class_input_3 : ``;
        TEMP.class_input_4 = PROPS.class_input_4 ? PROPS.class_input_4 : ``;
        TEMP.class_input_5 = PROPS.class_input_5 ? PROPS.class_input_5 : ``;
        TEMP.class_input_6 = PROPS.class_input_6 ? PROPS.class_input_6 : ``;
        TEMP.class_input_7 = PROPS.class_input_7 ? PROPS.class_input_7 : ``;

        TEMP.tooltip__ = PROPS.tooltip__ ? PROPS.tooltip__ : undefined;
    // TEMP

    // EVENTS
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <div>
        <div class="__ZIPCODE__" :class="TEMP.class_1">
            <TEXT__ label="Cep" name="zipcode" tags="change" :class="TEMP.class_input_1 ? TEMP.class_input_1 : TEMP.class" :value="PROPS.value_1 ? PROPS.value_1 : PROPS.value?.zipcode" mask="zipcode" icon="search" icon_click="zipcode" placeholder="00.000-000" :required="PROPS.required!=undefined ? PROPS.required : undefined" :required_label="PROPS.required_label!=undefined ? PROPS.required_label : undefined" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>
        <div class="__ADDRESS__" :class="TEMP.class_2">
            <TEXT__ label="Endereço" name="address" :class="TEMP.class_input_2 ? TEMP.class_input_2 : TEMP.class" :value="PROPS.value_2 ? PROPS.value_2 : PROPS.value?.address" :required="PROPS.required!=undefined ? PROPS.required : undefined" :required_label="PROPS.required_label!=undefined ? PROPS.required_label : undefined" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>
        
        <div class="__NUMBER__" :class="TEMP.class_3">
            <TEXT__ label="Número" name="number" :class="TEMP.class_input_3 ? TEMP.class_input_3 : TEMP.class" :value="PROPS.value_3 ? PROPS.value_3 : PROPS.value?.number" :required="PROPS.required!=undefined ? PROPS.required : undefined" :required_label="PROPS.required_label!=undefined ? PROPS.required_label : undefined" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>
        <div class="__COMPLEMENT__" :class="TEMP.class_4">
            <TEXT__ label="Complemento" name="complement" :class="TEMP.class_input_4 ? TEMP.class_input_4 : TEMP.class" :value="PROPS.value_4 ? PROPS.value_4 : PROPS.value?.complement" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>
        <div class="__NEIGHBORHOOD__" :class="TEMP.class_5">
            <TEXT__ label="Bairro" name="neighborhood" :class="TEMP.class_input_5 ? TEMP.class_input_5 : TEMP.class" :value="PROPS.value_5 ? PROPS.value_5 : PROPS.value?.neighborhood" :required="PROPS.required!=undefined ? PROPS.required : undefined" :required_label="PROPS.required_label!=undefined ? PROPS.required_label : undefined" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>

        <div class="__UF__" :class="TEMP.class_6">
            <SELECT_UF__ label="Estado" name="uf" :class="TEMP.class_input_6 ? TEMP.class_input_6 : TEMP.class" :value="PROPS.value_6 ? PROPS.value_6 : PROPS.value?.uf" :required="PROPS.required!=undefined ? PROPS.required : undefined" :required_label="PROPS.required_label!=undefined ? PROPS.required_label : undefined" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>

        <div class="__CITY__" :class="TEMP.class_7">
            <SELECT_CITY__ label="Cidade" name="city" :class="TEMP.class_input_7 ? TEMP.class_input_7 : TEMP.class" :value="PROPS.value_7 ? PROPS.value_7 : PROPS.value?.city" :required="PROPS.required!=undefined ? PROPS.required : undefined" :required_label="PROPS.required_label!=undefined ? PROPS.required_label : undefined" :disabled="PROPS.disabled!=undefined ? PROPS.disabled : undefined" :tooltip="TEMP.tooltip__ ? TEMP.tooltip__ : undefined" />
        </div>
        <div class="clear"></div>
    </div>

</template>


<style scoped>

</style>