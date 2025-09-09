<script setup lang="ts">
import NAME__ from '@/vendor/components/input/__name.vue';

import { tooltip, count_array, upload_verify, img_blob, json_encode, compare__, lower, substr, is_img, json_decode, is_file } from '@/vendor/services/events';
import { accept__ } from '@/vendor/services/tags';
import { defineComponent, h, inject, reactive } from 'vue';

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
            class_file?: String,
            style_1?: Record<string, string> | string,
            style__file?: Record<string, string> | string,
            multiple?: String,
            text?: String,
            no_alert?: String,
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
        TEMP.alert = 0;
        TEMP.alert_time = {};

        TEMP.tooltip = PROPS.tooltip;    
    // TEMP

    // SHOW
    // SHOW

    // FORM
        FORM.v[PROPS.name] = [];
        if (PROPS?.value != null){
            for (const [key, value] of Object.entries(PROPS.value)) {
                FORM.v[PROPS.name].push(json_encode(value));
            }
        }
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
        const __update = (event: Event) => {
            const target = event.target as HTMLInputElement;
            const files = target.files;

            if (files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    if (upload_verify(file)){
                        FORM.v[PROPS.name].push(file);
                    }
                }
            }
            target.value = '';

            __timeout();
        };

        const __update_remove = (index: number) => {
            const file = FORM.v[PROPS.name][index];
            if (file) {
                URL.revokeObjectURL(file);
            }
            FORM.v[PROPS.name].splice(index, 1);

            __timeout();
        };

        const __photo_main = (index: number) => {
            let $return: Record<string, any> = [];
            const file = FORM.v[PROPS.name];
            if (file){
                $return[0] = file[index];
                for (const [key, value] of Object.entries(file)){
                    if (key != index.toString()){
                        $return.push(value);
                    }
                }
            }
            FORM.v[PROPS.name] = $return;

            __timeout();
        };

        const __ext = (file: any) => {
            let url = ``;
            if (file?.name){
                url = file.name;
            } else {
                url = img_blob(file, true);
            }

            let ext = url.split('.').pop() || '';
            ext = lower(ext);
            return ext;
        }

        const __timeout = () => {
            TEMP.alert = 1;
            clearTimeout(TEMP.alert_time);
            TEMP.alert_time = setTimeout(() => { TEMP.alert = 0 }, 3000);            
        }
    // FUNCTIONS

    // COMPONENTE
        const Image__ = defineComponent({
            props: {
                value: {
                    type: [String, Object],
                    default: ''
                }
            },
            setup(props) {
                return () => {
                    const ext = __ext(props.value);
                    if (!ext) return null;

                    // EXT
                        else if ( ["pdf"].includes(ext) // PDF
                            || ["doc"].includes(ext) || ["docx"].includes(ext) // DOC
                            || ["ppt"].includes(ext) || ["pptx"].includes(ext) || ["pps"].includes(ext) || ["ppsx"].includes(ext) || ["odp"].includes(ext) || ["pot"].includes(ext) || ["potx"].includes(ext) // PPT
                            || ["txt"].includes(ext) // TXT
                            || ["xls"].includes(ext) || ["xlsx"].includes(ext) // XLS
                            || ["csv"].includes(ext) // CSV
                            || ["mp3"].includes(ext) || ["wav"].includes(ext) || ["ogg"].includes(ext) || ["m4a"].includes(ext) || ["flac"].includes(ext) || ["wma"].includes(ext) || ["aac"].includes(ext) // AUDIO
                            || ["flv"].includes(ext) || ["swf"].includes(ext) // FALSH
                        ){
                            return h("svg", { class: "svg-icon db w100 h100 m-a p10 bg_fff", xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 384 512" }, [
                                h("path", { d: "M224 0V128C224 145.7 238.3 160 256 160H384V448C384 483.3 355.3 512 320 512H64C28.65 512 0 483.3 0 448V64C0 28.65 28.65 0 64 0H224zM256 0L384 128H256V0z" }),
                                h("text", { x: "50%", y: "65%", "font-size": 140, "font-weight": "bold", "text-anchor": "middle", fill: "#fff" }, ext),
                            ]);
                        }
                    // EXT

                    // MP4
                        else if (["mp4"].includes(ext) || ["m4v"].includes(ext) || ["mov"].includes(ext) || ["mkv"].includes(ext) || ["webm"].includes(ext) || ["avi"].includes(ext) || ["wmv"].includes(ext) || ["3gp"].includes(ext)){
                            return h("video", { src: img_blob(props.value, true), muted: true, class: `db max-w100p max-h100p min-h100 m-a bg_fff`,  style: `object-fit: cover;` });
                        }
                    // MP4
                    
                    // IMAGE
                        else if(is_img((json_decode(props.value as any) as any)?.image || '') || is_file(props.value as string)) {
                            return h("img", { src: img_blob(props.value), class: `db max-w100p max-h100p min-h100 m-a bg_fff`, style: `object-fit: cover;` });
                        }
                    // IMAGE

                    // ELSE
                        else {
                            return h("svg", { class: "svg-icon db w100 h100 m-a p10 bg_fff", xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 384 512" }, [
                                h("path", { d: "M224 0V128C224 145.7 238.3 160 256 160H384V448C384 483.3 355.3 512 320 512H64C28.65 512 0 483.3 0 448V64C0 28.65 28.65 0 64 0H224zM256 0L384 128H256V0z" }),
                                h("text", { x: "50%", y: "65%", "font-size": 140, "font-weight": "bold", "text-anchor": "middle", fill: "#fff" }, substr(ext, -5)),
                            ]);
                        }
                    // ELSE
                };
            }, 
        });
    // COMPONENTE
    
</script>


<template>

    <slot>
        <div class="__input_all__ db __INPUT__FILE__" :class="`__INPUT__NAME__${PROPS.name}__`">

            <input type="file" @change="__update" :id="PROPS.name" class="dni" :accept="accept__(PROPS)" :multiple="PROPS.multiple || compare__('multiple', PROPS.tags)" />

            <!-- PREVIEW -->
                <div v-if="count_array(FORM.v[PROPS.name])">
                    <NAME__ v-if="PROPS.label_no==undefined" :value="PROPS" />

                    <div class="pb10">
                        <slot v-for="(value, key) in FORM.v[PROPS.name]" :key="key">
                            <div v-if="!key" class="posr">

                                <a :href="img_blob(value, true)" target="_blank" class="db w100p o-h bd_ccc br7 load_file__CSS">
                                    <Image__ :value="value" :class="PROPS.class_file" :style="PROPS.style_file" />
                                </a>
                                
                                <div class="posa t0 r0 z9 mt--4 flexx flex_r flex_ac">
                                    <!-- <button type="button" class="w20 h20 mt--4 mr4 fz11 fwb4 flexx flex_c flex_ac c_fff b_blue br50p edit" tooltip="Editar"><i class="faa-pencil"></i></button>  -->
                                    <button type="button" @click="__update_remove(key)" class="w20 h20 mt--4 mr--4 flexx flex_c flex_ac c_fff b_red br50p delete" tooltip="Deletar"><img src="@/vendor/assets/img/svg/default/close_1.svg" class="h15"></button>
                                </div>
                            </div>
                        </slot>
                    </div>


                    <div v-if="(PROPS.multiple || compare__('multiple', PROPS.tags))" class="gridd grid_2 gap_10">
                        <slot v-for="(value, key) in FORM.v[PROPS.name]" :key="key">
                            <div v-if="key" class="posr">

                                <a :href="img_blob(value, true)" target="_blank" class="db w100p o-h bd_ccc br7 load_file__CSS">
                                    <Image__ :value="value" />
                                </a>

                                <div class="posa t0 r0 mt--2 flexx flex_r flex_ac">
                                    <!-- <a class="w20 h20 mt--4 mr4 fz11 fwb4 flexx flex_c flex_ac c_fff b_blue br50p edit" tooltip="Editar"><i class="faa-pencil"></i></a>  -->
                                    <button type="button" @click="__photo_main(key)" class="w20 h20 mt--4 mr4 fz11 fwb4 flexx flex_c flex_ac c_fff b_yellow br50p star" tooltip="Marcar para Foto Principal"><i class="faa-star"></i></button> 
                                    <button type="button" @click="__update_remove(key)" class="w20 h20 mt--4 mr--4 flexx flex_c flex_ac c_fff b_red br50p delete" tooltip="Deletar"><img src="@/vendor/assets/img/svg/default/close_1.svg" class="h15"></button>
                                </div>

                            </div>
                        </slot>

                        <label :for="PROPS.name" class="w60 h60 fz16 c-p flexx flex_c flex_ac o-h bd_dashed bd_ccc br7">+</label>
                    </div>

                    <div v-if="TEMP.alert && !PROPS?.no_alert" class="pt10 c_red fwb5">* Você deve salvar para validar as alterações</div>
                </div>
            <!-- PREVIEW -->


            <!-- ELSE -->
                <div v-else>
                    <label :for="PROPS.name">
                        <span v-if="PROPS.label" class="db pb5">
                            {{ PROPS.label }}<div v-if="(PROPS.required!=undefined || PROPS.required_label!=undefined) && PROPS.required_label_no==null" class="dib fz13 c_red">*</div>:
                        </span>

                        <div class="posr" :tooltip="TEMP.tooltip ? TEMP.tooltip : undefined">
                            <div class="p20 tac c-p bd_999 bdd bg_fff bg_hover_f4f4f4 br8" :class="PROPS.class" :style="PROPS.style">
                                <div class="mt10 mb10 fz11 fwb5 flexx flex_c flex_ac c_333" :class="PROPS.class_1" :style="PROPS.style_1">
                                    <div class="fz13">{{ PROPS.text ? PROPS.text : `Enviar Arquivo` }} </div>
                                    <div class="pl10"><img src="@/vendor/assets/img/svg/default/file_upload.svg"></div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            <!-- ELSE -->

        </div>
    </slot>

</template>


<style scoped>
    .load_file__CSS { background: url('@/vendor/assets/img/default/load.gif') no-repeat center; }
</style>