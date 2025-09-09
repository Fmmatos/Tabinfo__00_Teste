<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { tooltip, mask__, f_mask__, value__, extra__, zipcode__, compare__ } from '@/vendor/services/events';
import { tags } from '@/vendor/services/tags';
import { inject, onBeforeMount, reactive } from 'vue';
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

            icon?: String,
            icon_click?: String,
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

    // TAGS
        // MASK
            TEMP.value__ = {
                'type': TEMP.type,
                'name': PROPS.name,
            };

            TEMP.mask = PROPS.mask;
            if (!TEMP.mask && PROPS?.tags){
                const maskMatch = PROPS.tags.match(/mask="([^"]+)"/);
                TEMP.mask = maskMatch ? maskMatch[1] : '';
            }

            TEMP.f_mask = f_mask__(PROPS.mask, TEMP.value__) ? mask__(TEMP.value__.name, f_mask__(PROPS.mask, TEMP.value__)) : undefined;

            // MAXLENGTH
                TEMP.maxlength = null;
                if ((PROPS.type == `tel` || PROPS.name == `phone` || PROPS.mask == `phone`) && !PROPS?.maxlength){
                    TEMP.maxlength = 15;
                }
                if ((PROPS.name == `cpf` || PROPS.mask == `cpf`) && !PROPS?.maxlength){
                    TEMP.maxlength = 14;
                }
                if ((PROPS.name == `cnpj` || PROPS.mask == `cnpj`) && !PROPS?.maxlength){
                    TEMP.maxlength = 18;
                }
                if ((PROPS.name == `cpf_cnpj` || PROPS.mask == `cpf_cnpj`) && !PROPS?.maxlength){
                    TEMP.maxlength = 18;
                }
                if ((PROPS.name == `zipcode` || PROPS.mask == `zipcode`) && !PROPS?.maxlength){
                    TEMP.maxlength = 10;
                }
                if (PROPS.mask == `card_number` && !PROPS?.maxlength){
                    TEMP.maxlength = 19;
                }
                if (PROPS.mask == `card_validate` && !PROPS?.maxlength){
                    TEMP.maxlength = 7;
                }
                if (PROPS.mask == `card_cvv` && !PROPS?.maxlength){
                    TEMP.maxlength = 4;
                }
            // MAXLENGTH
        // MASK
    // TAGS

    // SHOW
    // SHOW

    // FORM
        value__(PROPS);
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(async () => {
                if (f_mask__(TEMP.mask, TEMP.value__)) {
                    mask__(TEMP.value__.name, f_mask__(TEMP.mask, TEMP.value__))
                }
            });
        // ONBEFOREMOUNT
    // EVENTS

    // FUNCTIONS
        const __icon_click = () => {
            let $icon_type = extra__(PROPS?.extra, `|->icon`)?.[2];
            if($icon_type == `zipcode` || PROPS?.icon_click == `zipcode`){
                zipcode__($_GLOBAL.FORM, $_GLOBAL.OBJ);
            }
        }
    // FUNCTIONS
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__TEXT__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr">
                    <div v-if="f_mask__(TEMP.mask, TEMP.value__)">
                        <input v-model="FORM.v[PROPS.name]" v-bind="tags(PROPS, `text`).attributes" v-on="tags(PROPS, `text`, false).events" @input="mask__(TEMP.value__.name, f_mask__(TEMP.mask, TEMP.value__))" :maxlength="TEMP.maxlength" />
                    </div>

                    <div v-else>
                        <input v-model="FORM.v[PROPS.name]" v-bind="tags(PROPS, `text`).attributes" v-on="tags(PROPS, `text`, false).events" />
                    </div>

                    <div v-if="PROPS.icon" class="posa t0 r0 z1 h100p pl10 pr20 fz20 flexx flex_ac c_666">
                        <i v-if="compare__('faa-', PROPS.icon)" :class="PROPS.icon"></i>
                        <img v-else
                        @click="__icon_click()"
                        :class="(extra__(PROPS?.extra, `|->icon`)?.[2] || PROPS?.icon_click) ? `c-p` : ``"
                        :src="require(`@/vendor/assets/img/svg/${PROPS.icon}.svg`)" width="18" />
                    </div>
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>

</style>