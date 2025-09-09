<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { NEW__select_name } from '@/services/NEW__events';
import { tooltip, select2, select2_reset, compare__, options__, value__, extra__, replace, explode, is_json, json_decode } from '@/vendor/services/events';
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
            sel?: String,
            no_sel?: String,
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
        SELECT.query = [];
        SELECT.select_id = PROPS.name || '';
    // SELECT

    // SHOW
    // SHOW

    // FORM
        if (PROPS.name == `card_installments`){
            FORM.v[PROPS.name] = 1;
        } else {
            FORM.v[PROPS.name] = PROPS?.value!=null ? PROPS.value : ``;
        }
        if (compare__(`multiple`, PROPS.tags) && !PROPS.tags.match(/\b(multiple_[0-9])\b/i)){
            FORM.v[PROPS.name] = PROPS?.value ?? {};
            if(is_json(FORM.v[PROPS.name])){
                FORM.v[PROPS.name] = json_decode(FORM.v[PROPS.name]);
            }
            if (typeof FORM.v[PROPS.name] !== 'object') {
                FORM.v[PROPS.name] = {};
            }
            if(is_json(FORM.v[PROPS.name])){
                FORM.v[PROPS.name] = json_decode(FORM.v[PROPS.name]);
            }
            FORM.v[PROPS.name] = Object.values(FORM.v[PROPS.name]);

        } else {
            value__(PROPS);
        }
    // FORM

    // OBJ
        // REL
            const __rel = (extra: any) => {
                let result = extra?.[2];
                if (PROPS?.type__ == `json_fields`){
                    let ex = explode(`__json_fields__`, PROPS.name);
                    result = replace(ex[0], extra?.[2], PROPS.name);
                }
                return result;
            }
            const __rel_filter = (extra: any) => {
                let options = [...OBJ[`options__all__${PROPS.name}`]];
                OBJ[`options__${PROPS.name}`] = options.filter((item: any) => {
                    return item[extra?.[2]] == FORM.v?.[__rel(extra)]
                });
                FORM.v[PROPS.name] = ``;
                select2_reset(`#select_${SELECT.select_id}`);

                return 
            }
        // REL

        // OPTIONS
            OBJ[`options__all__${PROPS.name}`] = options__(PROPS);
        // OPTIONS

        // VALUE
            OBJ[`options__${PROPS.name}`] = [...OBJ[`options__all__${PROPS.name}`]];
            if (compare__(`|->rel`, PROPS?.extra)){
                OBJ[`options__${PROPS.name}`] = [];
                let extra = extra__(PROPS?.extra, `|->rel`);
                if (extra?.[2]){
                    let value = FORM.v[PROPS.name];
                    __rel_filter(extra);
                    FORM.v[PROPS.name] = value;
                }
            }
        // VALUE
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                select2();
            });
        // ONBEFOREMOUNT

        // WATCH
            // OPTIONS FILTER
                // |->REL
                    if (compare__(`|->rel`, PROPS?.extra)){
                        let extra = extra__(PROPS?.extra, `|->rel`);
                        if (extra?.[2]){
                            watch(() => FORM.v?.[__rel(extra)], (newValue, oldValue) => {
                                __rel_filter(extra);
                            }, { deep: true })
                        }
                    }
                // |->REL
            // OPTIONS FILTER

            // SELECT2_RESET
                // watch(() => FORM.v[PROPS.name], (newValue, oldValue) => {
                //     select2_reset(`#select_${SELECT.select_id}`);
                // }, { deep: true })
            // SELECT2_RESET
        // WATCH
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__SELECT__" :class="`__INPUT__NAME__${PROPS.name}__ __INPUT__ID__${FORM.v.id}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr">
                    <!-- <em v-if="!split('designx', PROPS.class)" class="posa t0 l0 z0 w100p h100p bd_ccc br7"></em> -->

                    <slot v-if="compare__(`multiple`, PROPS.tags) && !PROPS.tags.match(/\b(multiple_[0-9])\b/i)">
                        <select :name="name" v-model="FORM.v[PROPS.name]" :id="`select_${SELECT.select_id}`" v-bind="tags(PROPS, `select`).attributes" v-on="tags(PROPS, `select`, false).events" multiple>
                            <option v-if="!PROPS.no_sel && !compare__(`no_sel`, PROPS.tags)" value="">{{ PROPS.sel ? PROPS.sel : `Selecione...` }}</option>
                            <option v-for="value_1 in OBJ[`options__${PROPS.name}`]" :key="value_1.id" :value="value_1.id">{{ NEW__select_name(value_1) }}</option>
                        </select>
                    </slot>

                    <slot v-else-if="compare__(`autocomplete=`, PROPS.tags)">
                        <select :name="name" v-model="FORM.v[PROPS.name]" :id="`select_${SELECT.select_id}`" v-bind="tags(PROPS, `select`).attributes" v-on="tags(PROPS, `select`, false).events">
                            <option v-if="!PROPS.no_sel && !compare__(`no_sel`, PROPS.tags)" value="">{{ PROPS.sel ? PROPS.sel : `Selecione...` }}</option>
                            <option v-for="value_1 in OBJ[`options__${PROPS.name}`]" :value="value_1.id" selected>{{ NEW__select_name(value_1) }}</option>
                        </select>
                    </slot>
                    
                    <slot v-else>
                        <select :name="name" v-model="FORM.v[PROPS.name]" :id="`select_${SELECT.select_id}`" v-bind="tags(PROPS, `select`).attributes" v-on="tags(PROPS, `select`, false).events">
                            <option v-if="!PROPS.no_sel && !compare__(`no_sel`, PROPS.tags)" value="">{{ PROPS.sel ? PROPS.sel : `Selecione...` }}</option>

                            <!-- GROUP -->
                                <optgroup v-if="OBJ[`options__${PROPS.name}`]?.[0]?.sub" v-for="value_2 in OBJ[`options__${PROPS.name}`]" :key="value_2.id" :label="value_2.name">
                                    <option v-for="value_3 in value_2.sub" :value="value_3.id">{{ NEW__select_name(value_3) }}</option>
                                </optgroup>
                            <!-- GROUP -->

                            <!-- ELSE -->
                                <option v-else v-for="value_1 in OBJ[`options__${PROPS.name}`]" :key="value_1.id" :value="value_1.id">{{ NEW__select_name(value_1) }}</option>
                            <!-- ELSE -->
                        </select>
                    </slot>
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>

</style>