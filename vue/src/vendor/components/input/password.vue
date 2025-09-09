<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { tooltip, token, compare__ } from '@/vendor/services/events';
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


            space?: String,
            space_1?: String,
            only?: String,
            eyes?: String,
            no_legend?: String,
            class_legend?: String,
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

        TEMP.password_eyes_1 = 0;
        TEMP.password_eyes_2 = 0;

        TEMP.password_1;
        TEMP.password_2;
        TEMP.password_3;
        TEMP.password_4;
        TEMP.password_5;
    // TEMP

    // FORM
        FORM.v[PROPS.name] = ``;
        FORM.v[`${PROPS.name}_confirmation`] = ``;
    // FORM

    // OBJ
    // OBJ

    // FUNCTIONS
        const __password_eyes = (x: number, y: number) => {
            if (y == 1) TEMP.password_eyes_1 = x;
            if (y == 2) TEMP.password_eyes_2 = x;
        }

        const __password_create = () => {
            let password = `${token(5, true, false, false)}${token(2, false, true, false)}${token(2, false, false, true)}${token(3, true, false, false)}${token(2, false, true, false)}${token(2, false, false, true)}`;
            FORM.v[PROPS.name] = password;
            FORM.v[`${PROPS.name}_confirmation`] = password;
            __rules_password();
        }

        const __rules_password = () => {
            let $senha = FORM.v[PROPS.name];
            let $confirmar_senha = FORM.v[`${PROPS.name}_confirmation`];

            // REGRA 1 (MINIMO 8 CHARS)
                if ($senha.length < 8){
                    TEMP.password_1 = `c_red`;
                } else {
                    TEMP.password_1 = `c_green`;
                }
            // REGRA 1

            // REGRA 2 (PELO MENOS 1 NUMERO)
                if ( !($senha.match(/[0-9]/i)) ){
                    TEMP.password_2 = `c_red`;
                } else {
                    TEMP.password_2 = `c_green`;
                }   
            // REGRA 2

            // REGRA 3 (PELO MENOS 1 LETRA)
                if ( !($senha.match(/[A-Z]/i)) ){
                    TEMP.password_3 = `c_red`;
                } else {
                    TEMP.password_3 = `c_green`;
                }
            // REGRA 3

            // REGRA 5 (PELO MENOS 1 CHAR ESPECIAL)
                if ( !($senha.match(/[^a-zA-Z0-9\s]/i)) ){
                    TEMP.password_4 = `c_red`;
                } else {
                    TEMP.password_4 = `c_green`;
                }
            // REGRA 5

            // REGRA 5 (CONFIRMAR SENHA)
                if ($senha != $confirmar_senha){
                    TEMP.password_5 = `c_red`;
                } else {
                    TEMP.password_5 = `c_green`;
                }
            // REGRA 5
        }
    // FUNCTIONS
</script>


<template>

    <slot>
        <div v-if="!PROPS.only" class="clear"></div>

        <div class="__input_all__ db __INPUT__SELECT_PASSWORD__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr">
                    <input
                        :type="TEMP.password_eyes_1 ? 'text' : 'password'"
                        v-model="FORM.v[PROPS.name]" @keyup="__rules_password()"
                        :name="PROPS.name"
                        :class="`${(compare__(`design_no`, PROPS.class) || compare__(`default`, PROPS.class)) ?  `` : `design `} ${TEMP.class ? TEMP.class : ``} ${only ? `pr50` : `pr80`}`"
                        minlength="8"
                        :style="PROPS.style!=undefined ? PROPS.style : undefined"
                        :required="PROPS.required!=undefined ? PROPS.required : undefined"
                        :placeholder="PROPS.placeholder!=undefined ? PROPS.placeholder : undefined"
                    />

                    <div v-if="!PROPS.only" class="posa t0 r0 z1 h100p pl10 pr50 fz20 flexx flex_ac c_666" :class="PROPS.eyes ? PROPS.eyes : ``">
                        <i @click="__password_create()" class="faa-leaf c-p vat" tooltip="Criar senha segura"></i>
                    </div>
                    <div class="posa t0 r0 z1 h100p pl10 pr20 fz20 flexx flex_ac c_666" :class="PROPS.eyes ? PROPS.eyes : ``">
                        <i v-show="TEMP.password_eyes_1" @click="__password_eyes(0, 1)" class="faa-eye c-p vat" tooltip="Ocultar senha"></i>
                        <i v-show="!TEMP.password_eyes_1" @click="__password_eyes(1, 1)" class="faa-eye-slash c-p vat" tooltip="Ver senha"></i>
                    </div>
                </div>
            </div>

            <div v-if="!PROPS.only" :class="PROPS.space ? PROPS.space : `h20`"></div>

            <div v-if="!PROPS.only">
                <span v-if="PROPS.label" class="db pb5">
                    Confirmar {{ PROPS.label }}<div v-if="(PROPS.required!=undefined || PROPS.required_label!=undefined) && PROPS.required_label_no==null" class="dib fz13 c_red">*</div>:
                </span>

                <div class="__input__ posr">
                    <input
                        :type="TEMP.password_eyes_2 ? 'text' : 'password'"
                        v-model="FORM.v[`${PROPS.name}_confirmation`]"
                        :name="`${PROPS.name}_confirmation`" class="pr50"
                        :class="`${(compare__(`design_no`, PROPS.class) || compare__(`default`, PROPS.class)) ?  `` : `design `} ${TEMP.class ? TEMP.class : ``}`"
                        @keyup="__rules_password()"
                    />

                    <div class="posa t0 r0 z1 h100p pl10 pr20 fz20 flexx flex_ac c_666" :class="PROPS.eyes ? PROPS.eyes : ``">
                        <i v-show="TEMP.password_eyes_2" @click="__password_eyes(0, 2)" class="faa-eye c-p vat" tooltip="Ocultar senha"></i>
                        <i v-show="!TEMP.password_eyes_2" @click="__password_eyes(1, 2)" class="faa-eye-slash c-p vat" tooltip="Ver senha"></i>
                    </div> 
                </div>
            </div>

            <div v-if="!PROPS.only" :class="PROPS.space_1 ? PROPS.space_1 : `h20`"></div>

            <div v-if="!PROPS.no_legend && !PROPS.only" class="fz12 lh20" :class="PROPS.class_legend ? PROPS.class_legend : ``">
                <div :class="TEMP.password_1">* Mínimo 8 caracteres.</div>
                <div :class="TEMP.password_2">* Pelo menos 1 número.</div>
                <div :class="TEMP.password_3">* Pelo menos 1 letra.</div>
                <div :class="TEMP.password_4">* Pelo menos 1 caractere especial (@ # $).</div>
                <div :class="TEMP.password_5">* A Senha e a Confirmação da Senha não são Iguais!</div>
            </div>

        </div>
    </slot>

</template>


<style scoped>

</style>