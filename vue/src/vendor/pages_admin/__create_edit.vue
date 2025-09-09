<script setup lang="ts">
import CHECKBOX__ from '@/vendor/components/input/checkbox.vue';
import EDITOR__ from '@/vendor/components/input/editor.vue';
import FILE__ from '@/vendor/components/input/file.vue';
import TEXT__ from '@/vendor/components/input/text.vue';
import PASSWORD__ from '@/vendor/components/input/password.vue';
import RADIO__ from '@/vendor/components/input/radio.vue';
import SELECT__ from '@/vendor/components/input/select.vue';
import SELECT_CITY__ from '@/vendor/components/input/select_city.vue';
import SELECT_UF__ from '@/vendor/components/input/select_uf.vue';
import TEXTAREA__ from '@/vendor/components/input/textarea.vue';
import COLOR__ from '@/vendor/components/input/color.vue';
import RANGE__ from '@/vendor/components/input/range.vue';
import DATE__ from '@/vendor/components/input/date.vue';
import INFO__ from '@/vendor/components/input/info.vue';
import MAPS_ADDRESS__ from '@/vendor/components/input/__maps_address.vue';
import JSON_FIELDS__ from '@/vendor/components/input/__json_fields.vue';

import NEW__CREATE_EDIT__ from '@/pages_admin/__new__create_edit.vue';

import { alerts_close, count, count__, extra__, html_treatment, is_iframe, MOBILE, tooltip, value_not_rel__, value_rel__ } from '@/vendor/services/events';
import { modules__back, modules__delete, modules__store_update } from '@/vendor/services/modules';
import { admin__create_edit } from '@/services/NEW__events';
import { inject, onBeforeMount, onMounted, onUnmounted, ref } from 'vue';

tooltip();

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        $_GET['__TABLE__'] = 0;
        $_GET['__EDIT__'] = 1;
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            x_settings?: String,
        }>();
    // PROPS

    // FORM
        FORM.v.id = OBJ?.VALUE?.id ? OBJ?.VALUE?.id : 0;
    // FORM

    // SHOW
        SHOW.save_button = `datatable`;

        SHOW.PASSWORD_EDIT = 0;
        SHOW.PASSWORD_EDIT_SHOW = 0;
        if (OBJ?.menu_admin?.input){
            for (const [key, value] of Object.entries(OBJ?.menu_admin?.input)){
                if (typeof value === 'object' && value !== null) {
                    for (const [key_1, value_1] of Object.entries(value)) {
                        if (value_1?.type == `password` && FORM.v.id){
                            SHOW.PASSWORD_EDIT = 1;
                        }        
                    }
                }
            }
        }

        SHOW.META_TAGS_SHOW = 0;
        SHOW.META_TAGS_EDIT = 0;
        if (OBJ.menu_admin?.info && OBJ.menu_admin?.info.indexOf(`meta_tags`) > 0){
            SHOW.META_TAGS_EDIT = 1;
        }

        SHOW.json_fields = {};
    // SHOW

    // OBJ
    // OBJ

    // EVENTS
        onBeforeMount(() => {
            alerts_close();
            setTimeout(() => {
                let input = document.querySelector(`form.__CREATE_EDIT__ ul > li ul > li:nth-child(2) input`) as HTMLInputElement;
                if (input){
                    input.focus();
                }
            }, 300);

            setTimeout(() => { admin__create_edit(); }, 50);

            // PF_PJ
                setTimeout(() => {
                    if (OBJ.menu_admin?.info?.includes(`create_pf_pj`)){
                        if ($_GET?.pf || (OBJ?.VALUE?.id && OBJ?.VALUE?.cpf != `` && OBJ?.VALUE.cpf != null && (OBJ?.VALUE.cnpj == `` || OBJ?.VALUE.cnpj == null))){
                            document.querySelectorAll(`.pj`).forEach(item => {
                                item.closest(`.__input_all__`)?.closest(`.p10`)?.remove()
                            });                        
                        }
                        if ($_GET?.pj || (OBJ?.VALUE?.id && OBJ?.VALUE?.cnpj != `` && OBJ?.VALUE.cnpj != null && (OBJ?.VALUE.cpf == `` || OBJ?.VALUE.cpf == null))){
                            document.querySelectorAll(`.pf`).forEach(item => {
                                item.closest(`.__input_all__`)?.closest(`.p10`)?.remove()
                            });
                        }
                    }
                }, 50);
            // PF_PJ
        })
    // EVENTS

    // FUNCTIONS
        const __value = (value: any, array: boolean = false) => {
            if (array){
                // ARRAY / FILES
                    if (OBJ?.VALUE?.[`${value.name}__array`] != null){
                        return OBJ?.VALUE?.[`${value.name}__array`];
                    }
                // ARRAY / FILES
                return [];
            }

            if (OBJ?.VALUE?.[value.name] != null){
                return OBJ?.VALUE?.[value.name];
            }

            return ``;
        }

        const __password_edit_show = () => {
            SHOW.PASSWORD_EDIT_SHOW = !SHOW.PASSWORD_EDIT_SHOW;
            if (SHOW.PASSWORD_EDIT_SHOW){
                FORM.v[`password`] = ``;
                FORM.v[`password_confirmation`] = ``;
            } else {
                delete FORM.v[`password`];
                delete FORM.v[`password_confirmation`];
            }
        }
        const __meta_tags_show = () => {
            SHOW.META_TAGS_SHOW = !SHOW.META_TAGS_SHOW;
        }
    // FUNCTIONS

    // SCROLL BUTTON
        const isVisible = ref(false);

        onMounted(() => { // Adiciona o evento de scroll quando o componente é montado
            window.addEventListener('scroll', handleScroll);
        });
       
        onUnmounted(() => { // Remove o evento de scroll quando o componente é desmontado
            window.removeEventListener('scroll', handleScroll);
        });

        const handleScroll = () => {
            isVisible.value = window.scrollY >= 100;
        };
    // SCROLL BUTTON
</script>


<template>

    <div :class="`__CREATE_EDIT__${OBJ?.menu_admin?.table}__ __CREATE_EDIT__${OBJ?.menu_admin?.id}__`">
        <div class="fade_in_2 p0_1000" :class="`centerr_${SHOW.GET[0]}`">
            <form @submit.prevent="modules__store_update()" class="__CREATE_EDIT__">

                <!-- TOPO -->
                    <!-- X_SETTINGS -->
                        <div v-if="PROPS?.x_settings" class="flexx_700 flex_j flex_ac">
                            <div class="pt5 pb5 flexx">
                                <button @click="SHOW.save_button = ``" class="dib vam button_admin_1">Salvar</button>
                            </div>
                        </div>
                    <!-- X_SETTINGS -->

                    <!-- NORMAL -->
                        <div v-else-if="!is_iframe()" class="flexx_700 flex_j flex_ac __CREATE_EDIT__TOP__">
                            <div class="pb10 flexx">
                                <a @click="modules__back();" class="w16 mt5 mr10 c_555">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="currentColor" d="M14.75 8a.75.75 0 0 1-.75.75h-9.69l2.72 2.72a.749.749 0 1 1-1.06 1.06l-4-4a.747.747 0 0 1 0-1.06l4-4a.749.749 0 1 1 1.06 1.06l-2.72 2.72h9.69a.75.75 0 0 1 .75.75"></path></svg>
                                </a>
                                <div class="flex_1 fz20 fwb6 c_333 flexx flex_ac">
                                    <div class="limit_2">{{ (FORM.v.name || FORM.v.id) ? FORM.v.name : `Novo Item` }}</div>
                                </div>
                            </div>
                            <div>
                                <div :class="`${MOBILE(700) ? `pt5 pb5 flexx_x gap_8` : `min-w320 pl20 flexx flex_r flex_ac gap_8`}  ${isVisible ? `__FIXED__` : ``}`">
                                    <div v-html="html_treatment(OBJ?.individual?.button)"></div>
                                    <NEW__CREATE_EDIT__ type="button" />
                                    <div v-if="SHOW.META_TAGS_EDIT"><a @click="__meta_tags_show()" class="dib vam c_rose button_admin_1">Meta Tags</a></div>
                                    <button @click="SHOW.save_button = `datatable`" class="dni"></button>
                                    <button v-if="FORM.v.id && (OBJ.menu_admin?.info?.includes(`create`) || OBJ.menu_admin?.info?.includes(`edit`))" @click="SHOW.save_button = ``" class="dib min-w100 vam b_black button_admin_1 __salve__">Salvar</button>
                                    <button v-if="(OBJ.menu_admin?.info?.includes(`create`) || OBJ.menu_admin?.info?.includes(`edit`))" @click="SHOW.save_button = `datatable`" class="dib min-w120 vam b_blue button_admin_1 __salve_close__">Salvar e Fechar</button>
                                    <button v-if="OBJ.menu_admin?.info?.includes(`create`)" @click="SHOW.save_button = `new`" class="dib min-w145 vam button_admin_1 __salve_new__">Salvar e Criar Novo</button>
                                    <button v-if="FORM.v.id && OBJ.menu_admin?.info?.includes(`delete`)" @click="modules__delete(FORM.v.id)" type="button" class="dib min-w100 vam b_red button_admin_1 __delete__">Deletar</button>
                                    <div v-if="SHOW.PASSWORD_EDIT"><a @click="__password_edit_show()" class="dib min-w140 vam b_purple button_admin_1 __password__"><div class="dib"><div v-show="!SHOW.PASSWORD_EDIT_SHOW">Alterar Senha</div><div v-show="SHOW.PASSWORD_EDIT_SHOW">Não Alterar Senha</div></div></a></div>
                                    <!-- <button class="dib vam h30 p5 pl10 pr10 mb10 mr10 fwb5 flexx flex_ac c_fff bd0 bg_303030 bg_hover_4A4A4A br7 limit1">Ver</button> -->
                                    <!-- <button class="dib vam button_admin_2">Pré-visualizar</button> -->
                                </div>
                            </div>
                        </div>
                    <!-- NORMAL -->


                    <!-- IFRAME -->
                        <div v-else class="tar">
                            <button v-if="(OBJ.menu_admin?.info?.includes(`create`) || OBJ.menu_admin?.info?.includes(`edit`))" @click="SHOW.save_button = `datatable`" class="dib min-w120 vam b_blue button_admin_1 __salve_close__">Salvar e Fechar</button>
                        </div>
                    <!-- IFRAME -->
                <!-- TOPO -->


                <!-- BODY -->
                    <!-- INDIVIDUAL (TOP) -->
                        <div v-html="html_treatment(OBJ?.individual?.top)"></div>
                        <NEW__CREATE_EDIT__ type="top" />
                    <!-- INDIVIDUAL (TOP) -->

                    <ul class="pt10 flexx_1000">
                        <slot v-for="(value_align, key_align) in OBJ?.menu_admin?.input" :key="key_align">
                            <li v-if="count(value_align)" :class="String(key_align)==`right` ? `flex_4 ml20 m0_1000` : `flex_9`">
                                <div class="p6 pl5 pr5 mb20 bd_ddd bg_fff br10 shadow_0">
                                    <ul>
                                        <slot v-for="(value, key) in value_align" :key="key">

                                            <!-- INDIVIDUAL (TOP) -->
                                                <li v-if="String(key_align)==`left` && key === 0">
                                                    <div v-html="html_treatment(OBJ?.individual?.top_left)"></div>
                                                    <NEW__CREATE_EDIT__ type="top_left" />
                                                </li>
                                                <li v-if="String(key_align)==`right` && key === 0">
                                                    <div v-html="html_treatment(OBJ?.individual?.top_right)"></div>
                                                    <NEW__CREATE_EDIT__ type="top_right" />
                                                </li>
                                            <!-- INDIVIDUAL (TOP) -->

                                        
                                            <slot v-if="value?.check && value?.check == `true`">
                                                <slot v-if="value?.type && value?.type != `column` && value?.type != `query`">
                                                    <slot v-if="( !(FORM.v.id && value.type == `password`) || (SHOW.PASSWORD_EDIT_SHOW && value.type == `password`) )">
                                                        <slot v-if="value_rel__(value) && value_not_rel__(value)">

                                                            <!-- TITLE -->
                                                                <slot v-if="value?.title && value.type != `json_fields`">
                                                                    <li class="pt20 pl10 pr10 fz15 fwb5 flexx flex_j flex_ac clear __TITLE__" :class="`__TITLE__${value.name}`">
                                                                        <div class="">{{ value.title }}</div>
                                                                    </li>
                                                                </slot>
                                                            <!-- TITLE -->

                                                            <li :class="value.type == `json_fields` ? `wr12` : value.wr">
                                                                <div v-if="0"></div>

                                                                <!-- OTHERS -->
                                                                    <!-- INFO -->
                                                                        <div v-else-if="value.type == `info`">
                                                                            <div v-if="value?.label || value?.name || value?.tags" class="p10">
                                                                                <INFO__ v-bind="value" :value="__value(value)" />
                                                                            </div>
                                                                        </div>
                                                                    <!-- INFO -->


                                                                    <!-- MAPS -->
                                                                        <div v-else-if="value.name == `google_maps_address`" class="p10">
                                                                            <MAPS_ADDRESS__ v-bind="value" :value="__value(value)" />
                                                                        </div>
                                                                    <!-- MAPS -->


                                                                    <!-- JSON_FIELDS -->
                                                                        <div v-else-if="value.type == `json_fields`">
                                                                            <JSON_FIELDS__ :array="value.array" :value__="OBJ?.VALUE" />
                                                                        </div>
                                                                    <!-- JSON_FIELDS -->
                                                                <!-- OTHERS -->




















                                                                <!-- SELECT -->
                                                                    <div v-else-if="value?.type == `select` || value?.type == `categories` || value?.type == `subcategories`" class="p10" :class="value?.class ? value?.class : ``">
                                                                        <SELECT__ v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" :sel="extra__(value?.extra, `|->options_select`)?.[1]" />
                                                                    </div>
                                                                    <div v-else-if="value.type == `city`" class="p10" :class="value?.class ? value?.class : ``">
                                                                        <SELECT_CITY__ v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                    </div>
                                                                    <div v-else-if="value.type == `uf`" class="p10" :class="value?.class ? value?.class : ``">
                                                                        <SELECT_UF__ v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                    </div>
                                                                <!-- SELECT -->





                                                                <!-- CHECKBOX / RADIO -->
                                                                    <div v-else-if="value.type == `checkbox`" class="p10">
                                                                        <CHECKBOX__ v-bind="value" :value="__value(value)" />
                                                                    </div>
                                                                    <div v-else-if="value.type == `radio`" class="p10">
                                                                        <RADIO__ v-bind="value" :value="__value(value)" />
                                                                    </div>
                                                                <!-- CHECKBOX / RADIO -->





                                                                <!-- TEXTAREA -->
                                                                    <div v-else-if="value?.type == `textarea`" class="p10" :class="value?.class ? value?.class : ``">
                                                                        <TEXTAREA__ v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                    </div>
                                                                <!-- TEXTAREA -->





                                                                <!-- EDITOR -->
                                                                    <div v-else-if="value?.type == `editor`" class="p10">
                                                                        <EDITOR__ v-bind="value" :value="__value(value)" />
                                                                    </div>
                                                                <!-- EDITOR -->





                                                                <!-- INPUTS -->
                                                                    <!-- FILE -->
                                                                        <div v-else-if="value?.type == `file`" class="p10" :class="value?.class ? value?.class : ``">
                                                                            <FILE__ v-bind="value" :value="__value(value, true)" />
                                                                        </div>
                                                                    <!-- FILE -->


                                                                    <!-- COLOR -->
                                                                        <div v-else-if="value.type == `color`" class="p10">
                                                                            <COLOR__  v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                        </div>
                                                                    <!-- COLOR -->


                                                                    <!-- RANGE -->
                                                                        <div v-else-if="value.type == `range`" class="p10">
                                                                            <RANGE__  v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                        </div>
                                                                    <!-- RANGE -->


                                                                    <!-- DATE -->
                                                                        <div v-else-if="value.type == `date` || value.type == `datetime-local`" class="p10">
                                                                            <DATE__ v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                        </div>
                                                                    <!-- DATE -->


                                                                    <!-- PASSWORD -->
                                                                        <div v-else-if="value.type == `password`" class="p10">
                                                                            <PASSWORD__ v-bind="value" :value="__value(value)" class="design" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" />
                                                                        </div>
                                                                    <!-- PASSWORD -->


                                                                    <!-- TEXT -->
                                                                        <div v-else class="p10" :class="value?.class ? value?.class : ``">
                                                                            <TEXT__ v-bind="value" :value="__value(value)" :tooltip="extra__(value?.extra, `|->tooltip_input`)?.[1]" :icon="extra__(value?.extra, `|->icon`)?.[1]" />
                                                                        </div>
                                                                    <!-- TEXT -->
                                                                <!-- INPUTS -->

                                                            </li>

                                                        </slot>
                                                    </slot>
                                                </slot>
                                            </slot>


                                            <!-- INDIVIDUAL (FOOTER) -->
                                            <li v-if="String(key_align)==`left` && count__(value_align) == key+1">
                                                    <div v-html="html_treatment(OBJ?.individual?.footer_left)"></div>
                                                    <NEW__CREATE_EDIT__ type="footer_left" />
                                                </li>
                                                <li v-if="String(key_align)==`right` && count__(value_align) == key+1">
                                                    <div v-html="html_treatment(OBJ?.individual?.footer_right)"></div>
                                                    <NEW__CREATE_EDIT__ type="footer_right" />
                                                </li>
                                            <!-- INDIVIDUAL (FOOTER) -->

                                        </slot>
                                        <li class="clear"></li>
                                    </ul>
                                </div>
                            </li>
                        </slot>


                        <!-- RIGHT IF TO NEED -->
                            <slot v-if="!count(OBJ?.menu_admin?.input?.right) && (OBJ?.individual?.top_right || OBJ?.individual?.footer_right)">
                                <li class="flex_4 ml20 m0_1000">
                                    <div class="p6 pl5 pr5 mb20 bd_ddd bg_fff br10 shadow_0">
                                        <ul>
                                            <!-- INDIVIDUAL (TOP) -->
                                                <li>
                                                    <div v-html="html_treatment(OBJ?.individual?.top_right)"></div>
                                                    <NEW__CREATE_EDIT__ type="top_right" />
                                                </li>
                                            <!-- INDIVIDUAL (TOP) -->

                                            <!-- INDIVIDUAL (FOOTER) -->
                                                <li>
                                                    <div v-html="html_treatment(OBJ?.individual?.footer_right)"></div>
                                                    <NEW__CREATE_EDIT__ type="footer_right" />
                                                </li>
                                            <!-- INDIVIDUAL (FOOTER) -->
                                        </ul>
                                    </div>
                                </li>
                            </slot>
                        <!-- RIGHT IF TO NEED -->
                    </ul>
                <!-- BODY -->

            </form>

            <!-- INDIVIDUAL (FOOTER) -->
                <div v-html="html_treatment(OBJ?.individual?.footer)"></div>
                <NEW__CREATE_EDIT__ type="footer" />
            <!-- INDIVIDUAL (FOOTER) -->
        </div>
    </div>

</template>


<style scoped>
    .__FIXED__ .__salve_new__,
    .__FIXED__ .__password__ { display: none;}

    @media (min-width: 1000px){
        .__FIXED__ { position: fixed; top: 5px; z-index: 10; width: 300px; margin-left:  -300px; justify-content: flex-end; -ms-flex-pack: flex-end; }
    }
    @media (max-width: 1000px){
        .__FIXED__ { position: fixed; top: 5px; z-index: 10; }
    }
</style>