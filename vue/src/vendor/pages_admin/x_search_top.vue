<script setup lang="ts">
import CHECKBOX__ from '@/vendor/components/input/checkbox.vue';
import EDITOR__ from '@/vendor/components/input/editor.vue';
import FILE__ from '@/vendor/components/input/file.vue';
import PASSWORD__ from '@/vendor/components/input/password.vue';
import RADIO__ from '@/vendor/components/input/radio.vue';
import SELECT__ from '@/vendor/components/input/select.vue';
import SELECT_CITY__ from '@/vendor/components/input/select_city.vue';
import SELECT_UF__ from '@/vendor/components/input/select_uf.vue';
import TEXT__ from '@/vendor/components/input/text.vue';
import TEXTAREA__ from '@/vendor/components/input/textarea.vue';

import { inject, onBeforeMount } from 'vue';
import { modules__search_top, modules__search_reset_all } from '@/vendor//services/modules';
import { compare__, replace } from '../services/events';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
        const __value = (value: any) => {
            return {
                ...value,
                name: `search_dinamic_${value.name}`,
                tags: `${replace(`required`, ``, value.tags)}`,
                sel: `Todos`
            }
        }
    // FUNCTIONS
</script>


<template>

    <div class="p10 bdb_f4f4f4">
        <form @submit.prevent="modules__search_top()" class="search_top">
            <div class="flexx_x flex_b gap_10">

                <!-- DATE -->
                    <div v-if="OBJ.menu_admin?.info?.includes(`search_top_date`)" class="flexx_x gap_10">
                        <div class="w200" style="order: 0">
                            <TEXT__ type="date" label="Data Inicial" name="search_date_ini" class="design fz14" />
                        </div>
                        <div class="w200" style="order: 0">
                            <TEXT__ type="date" label="Data Final" name="search_date_fim" class="design fz14" />
                        </div>
                        <div class="w200" style="order: 0">
                            <SELECT__ label="Data Filtro" name="search_date_field" :options="OBJ.menu_admin?.search_date_field" :value="OBJ.menu_admin?.search_columns_date_current" no_sel="false" required_label_no="true" />
                        </div>
                    </div>
                <!-- DATE -->


                <!-- DINAMIC -->
                    <slot v-for="(value, key) in OBJ.menu_admin?.input" :key="key">
                        <slot v-if="value?.check === true || value?.check === 'true'">
                            <slot v-if="compare__('top', value?.search)">
                                <div v-if="0"></div>

                                <!-- SELECT -->
                                    <div v-else-if="value?.options || value?.extra || compare__('autocomplete', value?.tags)" class="w200" :style="`order: ${value?.search_order ? value?.search_order : 999}`">
                                        <SELECT__ v-bind="__value(value)" required_label_no="true" />
                                    </div>
                                    <div v-else-if="value.type == `city`" class="w200" :style="`order: ${value?.search_order ? value?.search_order : 999}`">
                                        <SELECT_CITY__ v-bind="__value(value)" required_label_no="true" />
                                    </div>
                                    <div v-else-if="value.type == `uf`" class="w200" :style="`order: ${value?.search_order ? value?.search_order : 999}`">
                                        <SELECT_UF__ v-bind="__value(value)" required_label_no="true" />
                                    </div>
                                <!-- SELECT -->

                            </slot>
                        </slot>
                    </slot>
                <!-- DINAMIC -->

                <div class="pt4 pb4" style="order: 9999">
                    <button class="w75 pt6 pb6 pl10 pr10 b_blue br7 button_3">Filtrar</button>
                </div>
                <!-- <div class="pt4 pb4" style="order: 9999">
                    <button @click="modules__search_reset_all()" type="button" class="w75 pt6 pb6 pl10 pr10 b_orange br7 button_3">Resetar</button>
                </div> -->
            </div>
        </form>
    </div>

</template>


<style scoped>

</style>