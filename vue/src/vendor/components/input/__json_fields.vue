<script setup lang="ts">
import JSON_FIELDS_FORM__ from '@/vendor/components/input/__json_fields_form.vue';

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
            array: Object,
            value__: Object,
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        TEMP.array = { ...PROPS.array };

        TEMP.min = 1;
        TEMP.max = 200;
        TEMP.value = {};

        // VALUE__
            TEMP.current = count_x(PROPS.value__?.[TEMP.array[0].name])>1 ? count_x(PROPS.value__?.[TEMP.array[0].name]) : TEMP.min;

            for (const [key, value] of Object.entries(TEMP.array)){
                let val: any = value;
                SHOW.json_fields[val.name] = {};

                if (PROPS.value__?.[val.name]){
                    for (const [key_1, value_1] of Object.entries(PROPS.value__?.[val.name])){
                        SHOW.json_fields[val.name][key_1] = value_1;
                    }
                }
            }
        // VALUE__
    // EVENTS

    // FUNCTIONS
        const __button_more = () => {
            TEMP.current++
        }
        const __button_minus = () => {
            TEMP.current--;

            for (const [key, value] of Object.entries(SHOW.json_fields?.[TEMP.array[0].name])){
                if (key > TEMP.current){
                    SHOW.json_fields[TEMP.array[0].name][key] = ``;
                    let name = `${TEMP.array[0].name}__json_fields__${key}`;
                    delete FORM.v[name];
                }
            }

        }
    // FUNCTIONS
</script>

<template>

    <div class="">
        <slot v-for="i in TEMP.max">

            <!-- TITLE -->
                <ul v-for="(value, key) in TEMP.array" :key="key">
                    <li v-if="i <= TEMP.current">
                        <div v-if="value?.title" class="pt20 pl10 pr10 fz15 fwb5 flexx flex_j flex_ac clear __TITLE__" :class="`__TITLE__${value.name}`">
                            <div class="">{{ value.title }} {{ i }}</div>
                        </div>
                    </li>
                </ul>
            <!-- TITLE -->

            <ul v-for="(value, key) in TEMP.array" :key="key" :class="value.wr">
                <li v-if="i <= TEMP.current">
                    <JSON_FIELDS_FORM__ :i="i" :value="value" :TEMP__="TEMP" />
                </li>
            </ul>
            <div v-if="compare__(`clear`, TEMP.array[0].tags)" class="clear"></div>

        </slot>
        <div class="clear"></div>

        <div class="pb10 flexx">
            <button v-if="TEMP.current != TEMP.max" @click="__button_more()" type="button" class="p10 c_blue bd0 bg0 link">Adicionar mais</button>
            <button v-if="TEMP.current > TEMP.min" @click="__button_minus()" type="button" class="p10 c_blue bd0 bg0 link">Remover último</button>
        </div>
    </div>

</template>