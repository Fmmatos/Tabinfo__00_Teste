<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { compare__, tooltip, value__ } from '@/vendor/services/events';
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


            x?: number,
            dynamic_style?: Function,
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
        value__(PROPS);
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
        const __focus_code_input = (name: string) => {
            setTimeout(() => (document.querySelector(`input[name="${name}"]`) as HTMLInputElement)?.focus(), 10);
        }

        const __handle_code_input = (index: number) => {
            if (FORM.v[`${PROPS.name}_${index}`] && PROPS.x && index < PROPS.x) {
                __focus_code_input(`${PROPS.name}_${index + 1}`);
            }
        }
        
        const __handle_paste = (e: ClipboardEvent, index: number) => {
            e.preventDefault();
            const pastedText = e.clipboardData?.getData('text') || '';
            const numbers = pastedText.replace(/\D/g, '').substring(0, PROPS.x); // Limita ao tamanho máximo
            
            if (numbers && PROPS.x) {
                // Limpa todos os campos primeiro
                for (let i = 1; i <= PROPS.x; i++) {
                    FORM.v[`${PROPS.name}_${i}`] = '';
                }
                
                // Preenche todos os campos com o código colado
                for (let i = 0; i < numbers.length && i < PROPS.x; i++) {
                    FORM.v[`${PROPS.name}_${i + 1}`] = numbers[i];
                }
                
                // Se preencheu todos os campos, foca no último
                // Se não, foca no próximo campo vazio
                const filledCount = numbers.length;
                const focusIndex = filledCount >= PROPS.x ? PROPS.x : filledCount + 1;
                
                setTimeout(() => {
                    const targetInput = document.querySelector(`input[name="${PROPS.name}_${focusIndex}"]`) as HTMLInputElement;
                    if (targetInput) {
                        targetInput.focus();
                        if (filledCount >= PROPS.x) {
                            targetInput.select();
                        }
                    }
                }, 50);
            }
        }
        
        const __handle_keydown = (e: KeyboardEvent, index: number) => {
            // Permitir Ctrl+V para colar
            if (e.ctrlKey && e.key === 'v') {
                return; // Permite que o evento paste funcione
            }
            
            // Permitir Ctrl+A para selecionar tudo
            if (e.ctrlKey && e.key === 'a') {
                return;
            }
            
            // Backspace - volta para o campo anterior quando vazio
            if (e.key === 'Backspace' && !FORM.v[`${PROPS.name}_${index}`] && index > 1 && PROPS.x && index <= PROPS.x) {
                e.preventDefault();
                __focus_code_input(`${PROPS.name}_${index - 1}`);
                setTimeout(() => FORM.v[`${PROPS.name}_${index - 1}`] = '', 10);
            } 
            // Bloquear caracteres não numéricos (exceto teclas de controle)
            else if (e.key.length === 1 && !/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        }
    // FUNCTIONS
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__COLOR__" :class="`__INPUT__NAME__${PROPS.name}__`">
            <div>
                <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                <div class="__input__ posr flexx gap_10">
                    <input  v-for="i in PROPS.x" :key="i" type="text" :name="`${PROPS.name}_${i}`" v-model="FORM.v[`${PROPS.name}_${i}`]"
                        :class="`${(compare__(`design_no`, PROPS.class) || compare__(`default`, PROPS.class)) ?  `` : `design `} ${PROPS.class}`"
                        :style="PROPS.dynamic_style ? PROPS.dynamic_style(i) : PROPS.style"
                        maxlength="1"
                        pattern="[0-9]"
                        inputmode="numeric"
                        @input="__handle_code_input(i)"
                        @paste="__handle_paste($event, i)"
                        @keydown="__handle_keydown($event, i)"
                    />
                </div>
            </div>
        </div>
    </slot>

</template>


<style scoped>
</style>