<script setup lang="ts">
import EDITOR__ from '@/vendor/components/input/editor.vue';
import FILE__ from '@/vendor/components/input/file.vue';
import TEXT__ from '@/vendor/components/input/text.vue';
import SELECT__ from '@/vendor/components/input/select.vue';
import SELECT_CITY__ from '@/vendor/components/input/select_city.vue';
import SELECT_UF__ from '@/vendor/components/input/select_uf.vue';
import TEXTAREA__ from '@/vendor/components/input/textarea.vue';
import COLOR__ from '@/vendor/components/input/color.vue';
import RANGE__ from '@/vendor/components/input/range.vue';
import DATE__ from '@/vendor/components/input/date.vue';

import NAME__ from '@/vendor/components/input/__name.vue';

import { compare__, count_x, replace, tooltip } from '@/vendor/services/events';
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
            i: Number,
            value: Object,
            TEMP__: Number,
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        const value: any = reactive({...PROPS.value});

        TEMP.name = value.name;

        value.label = `${value.label} ${PROPS.i}`;
        value.name = `${value.name}__json_fields__${PROPS.i}`;

        // TYPE
            if (compare__(`__select__`, value?.tags)){
                value.type__ = value.type;
                value.type = `select`;
            } else if (compare__(`__categories__`, value?.tags)){
                value.type__ = value.type;
                value.type = `categories`;
            } else if (compare__(`__subcategories__`, value?.tags)){
                value.type__ = value.type;
                value.type = `subcategories`;
            } else if (compare__(`__city__`, value?.tags)){
                value.type__ = value.type;
                value.type = `city`;
            } else if (compare__(`__uf__`, value?.tags)){
                value.type__ = value.type;
                value.type = `uf`;

            } else if (compare__(`__textarea__`, value?.tags)){
                value.type = `textarea`;
            } else if (compare__(`__editor__`, value?.tags)){
                value.type = `editor`;

            } else if (compare__(`__number__`, value?.tags)){
                value.type = `number`;
            } else if (compare__(`__color__`, value?.tags)){
                value.type = `color`;
            } else if (compare__(`__range__`, value?.tags)){
                value.type = `range`;
            } else if (compare__(`__date__`, value?.tags)){
                value.type = `date`;
            } else if (compare__(`__file__`, value?.tags)){
                value.type = `file`;
            } else if (compare__(`__text__`, value?.tags)){
                value.type = `text`;
            }
        // TYPE
    // EVENTS

    // FUNCTIONS
        const __value = (value: any, array: boolean = false) => {
            return SHOW.json_fields?.[TEMP.name]?.[PROPS.i] ?? ``;
        }
    // FUNCTIONS
</script>

<template>
    
    <div>

        <div class="p10">
            <NAME__ :value="value" />


            <div v-if="0"></div>


            <!-- SELECT -->
                <div v-else-if="value?.type == `select` || value?.type == `categories` || value?.type == `subcategories`" :class="value?.class ? value?.class : ``">
                    <SELECT__ label_no="true" v-bind="value" :value="__value(value)" />
                </div>
                <div v-else-if="value.type == `city`" :class="value?.class ? value?.class : ``">
                    <SELECT_CITY__ label_no="true" v-bind="value" :value="__value(value)" />
                </div>
                <div v-else-if="value.type == `uf`" :class="value?.class ? value?.class : ``">
                    <SELECT_UF__ label_no="true" v-bind="value" :value="__value(value)" />
                </div>
            <!-- SELECT -->





            <!-- TEXTAREA -->
                <div v-else-if="value?.type == `textarea`" :class="value?.class ? value?.class : ``">
                    <TEXTAREA__ label_no="true" v-bind="value" :value="__value(value)" />
                </div>
            <!-- TEXTAREA -->





            <!-- EDITOR -->
                <div v-else-if="value?.type == `editor`">
                    <EDITOR__ label_no="true" v-bind="value" :value="__value(value)" />
                </div>
            <!-- EDITOR -->





            <!-- INPUTS -->
                <!-- FILE -->
                    <div v-else-if="value?.type == `file`" :class="value?.class ? value?.class : ``">
                        <FILE__ label_no="true" v-bind="value" :value="__value(value, true)" />
                    </div>
                <!-- FILE -->


                <!-- COLOR -->
                    <div v-else-if="value.type == `color`">
                        <COLOR__  label_no="true" v-bind="value" :value="__value(value)" />
                    </div>
                <!-- COLOR -->


                <!-- RANGE -->
                    <div v-else-if="value.type == `range`">
                        <RANGE__  label_no="true" v-bind="value" :value="__value(value)" />
                    </div>
                <!-- RANGE -->


                <!-- DATE -->
                    <div v-else-if="value.type == `date` || value.type == `datetime-local`">
                        <DATE__ label_no="true" v-bind="value" :value="__value(value)" />
                    </div>
                <!-- DATE -->


                <!-- TEXT -->
                    <div v-else :class="value?.class ? value?.class : ``">
                        <TEXT__ label_no="true" v-bind="value" :value="__value(value)" />
                    </div>
                <!-- TEXT -->
            <!-- INPUTS -->
        </div>

    </div>

</template>