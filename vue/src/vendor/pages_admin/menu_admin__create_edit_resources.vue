<script setup lang="ts">
import SELECT__ from '@/vendor/components/input/select.vue';
import TEXT__ from '@/vendor/components/input/text.vue';
import TEXTAREA__ from '@/vendor/components/input/textarea.vue';

import { compare__, count, count_x } from '@/vendor/services/events';
import api from '@/vendor/services/api';
import { inject, onBeforeMount } from 'vue';


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
        // ONBEFOREMOUNT
            onBeforeMount(async () => {
                OBJ.boxs_resources = OBJ.VALUE?.resources ?? {};
            });
        // ONBEFOREMOUNT
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
        const __add = (section: string) => {
            const newBox = {
                columns: '',
                function: '',
                vue: '',

                code: '',
                type: '',
                menu_admin: '',
                table: '',
            };
            if(!OBJ.boxs_resources?.[section]){
                OBJ.boxs_resources[section] = {};
            }
            let ult_key: any = 0;
            for (const [key, value] of Object.entries(OBJ.boxs_resources[section])) {
                if(key > ult_key){
                    ult_key = Number(key);
                }
            }
            OBJ.boxs_resources[section][ult_key + 1] = newBox;
        };

        const __remove = (section: string, key: number) => {
            if(!OBJ.boxs_resources?.[section]){
                return;
            }

            const newSection = { ...OBJ.boxs_resources[section] };
            delete newSection[key];
            OBJ.boxs_resources[section] = newSection;

            for (const [key_1, value_1] of Object.entries(FORM.v)) {
                if(compare__(`resources__${section}[${key}]`, key_1)){
                    delete FORM.v[key_1];
                }
            }
        };

        const __save = (section: string) => {
            // Implementar lógica de salvamento
            console.log('Salvando:', section);
        };
    // FUNCTIONS
</script>


<template>

    <div class="pt200 pb40">
        <!-- PAGE TITLE -->
            <div class="fz20 fwb6 c_000">Recursos</div>
        <!-- PAGE TITLE -->


        <!-- QUERY CUSTOM -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Query Custom</div>
                    <button @click="__add('query_custom')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.query_custom)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.query_custom" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('query_custom', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <label class="db pb5 fz12 fwb6">Code</label>
                                <TEXTAREA__ :name="`resources__query_custom[${key}][code]`" :value="value.code" placeholder="Query" class="" />
                            </div>
                            <div class="wr6">
                                <div class="p10">
                                    <SELECT__ label="Tipo" :name="`resources__query_custom[${key}][type]`" :value="value.type" extra="|->>model: Model; menu_admin: Menu Admin; table: Table; filters: Filtros"/>
                                </div>
                                <div v-if="FORM.v?.[`resources__query_custom[${key}][type]`] === 'menu_admin'" class="p10">
                                    <SELECT__ label="Menu Admin" :name="`resources__query_custom[${key}][menu_admin]`" :value="value.menu_admin" :options="OBJ?.QUERY?.menu_admin_all" />
                                </div>
                                <div v-if="FORM.v?.[`resources__query_custom[${key}][type]`] === 'table'" class="p10">
                                    <TEXT__ label="Table" :name="`resources__query_custom[${key}][table]`" :value="value.table" />
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.query_custom)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum query custom cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- QUERY CUSTOM -->





        <!-- AUTOCOMPLETE -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Autocomplete</div>
                    <button @click="__add('autocomplete')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.autocomplete)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.autocomplete" :key="key" class="posr pb5 mb5 bdb_eee">
                            <button @click="__remove('autocomplete', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <TEXT__ label="Columns" :name="`resources__autocomplete[${key}][columns]`" :value="value.columns" placeholder="Colunas para busca" />
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Function" :name="`resources__autocomplete[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.autocomplete)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum autocomplete cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- AUTOCOMPLETE -->





        <!-- SEARCH -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Search</div>
                    <button @click="__add('search')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.search)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.search" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('search', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <TEXT__ label="Columns" :name="`resources__search[${key}][columns]`" :value="value.columns" placeholder="Colunas para busca" />
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Function" :name="`resources__search[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.search)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhuma search cadastrada...</div>
                    </div>
                </div>
            </div>
        <!-- SEARCH -->





        <!-- FIELDS -->
            <!-- <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Fields</div>
                    <button @click="__add('fields')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.fields)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.fields" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('fields', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <TEXT__ label="Columns" :name="`resources__fields[${key}][columns]`" :value="value.columns" placeholder="Colunas para busca" />
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Function" :name="`resources__fields[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.fields)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum fields cadastrado...</div>
                    </div>
                </div>
            </div> -->
        <!-- FIELDS -->





        <!-- FIELDS DATATABLE -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Fields Data Table</div>
                    <button @click="__add('fields_datatable')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.fields_datatable)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.fields_datatable" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('fields_datatable', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <TEXT__ label="Columns" :name="`resources__fields_datatable[${key}][columns]`" :value="value.columns" placeholder="Colunas para busca" />
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Function" :name="`resources__fields_datatable[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.fields_datatable)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum fields datatable cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- FIELDS DATATABLE -->





        <!-- FIELDS CREATE EDIT -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Fields Create Edit</div>
                    <button @click="__add('fields_create_edit')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.fields_create_edit)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.fields_create_edit" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('fields_create_edit', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <TEXT__ label="Columns" :name="`resources__fields_create_edit[${key}][columns]`" :value="value.columns" placeholder="Colunas para busca" />
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Function" :name="`resources__fields_create_edit[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.fields_create_edit)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum fields create edit cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- FIELDS CREATE EDIT -->





        <!-- SAVE VALIDATE -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Save Validate</div>
                    <button @click="__add('save_validate')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.save_validate)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.save_validate" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('save_validate', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="p10">
                                <TEXT__ label="Function" :name="`resources__save_validate[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.save_validate)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum save validate cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- SAVE VALIDATE -->





        <!-- SAVE -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Save</div>
                    <button @click="__add('save')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.save)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.save" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('save', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <SELECT__ label="Type" :name="`resources__save[${key}][type]`" :value="value.type" class="h150 designx" tags="multiple" extra="|->>store_pre: Store Pre; update_pre: Update Pre; store_pos: Store Pós; update_pos: Update Pós; delete_pre: Delete Pre; delete_pos: Delete Pós" />
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Function" :name="`resources__save[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.save)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum save cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- SAVE -->





        <!-- HTML_TABLE -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Table (HTML)</div>
                    <button @click="__add('html_table')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.html_table)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.html_table" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('html_table', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr6 p10">
                                <SELECT__ label="Type" :name="`resources__html_table[${key}][type]`" :value="value.type" extra="|->>button: Button; top_1: Top 1; top_2: Top 2; footer: Footer"/>
                            </div>
                            <div class="wr6 p10">
                                <TEXT__ label="Vue" :name="`resources__html_table[${key}][vue]`" :value="value.vue" placeholder="Vue" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.html_table)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhuma html table cadastrada...</div>
                    </div>
                </div>
            </div>
        <!-- HTML_TABLE -->





        <!-- HTML_CREATE_EDIT -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 ttu c_000">Create Edit (HTML)</div>
                    <button @click="__add('html_create_edit')" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">+ Novo</button>
                </div>  

                <div class="pt10">
                    <div v-if="count(OBJ.boxs_resources.html_create_edit)" class="pl15 pr15 bg_FFF br10">
                        <div v-for="(value, key) in OBJ.boxs_resources.html_create_edit" :key="key" class="posr posr pb5 mb5 bdb_eee">
                            <button @click="__remove('html_create_edit', key)" class="posa t0 r0 p10 fz16"><i class="c_red faa-times"></i></button>
                            <div class="wr4 p10">
                                <SELECT__ label="Type" :name="`resources__html_create_edit[${key}][type]`" :value="value.type" extra="|->>button: Button; top: Top; top_left: Top Left; footer_left: Footer Left; top_right: Top Right; footer_right: Footer Right; footer: Footer"/>
                            </div>
                            <div class="wr4 p10">
                                <TEXT__ label="Function" :name="`resources__html_create_edit[${key}][function]`" :value="value.function ? value.function : ``" placeholder="Function (to variables)" />
                            </div>
                            <div class="wr4 p10">
                                <TEXT__ label="Vue" :name="`resources__html_create_edit[${key}][vue]`" :value="value.vue" placeholder="Vue (component)" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div v-if="!count(OBJ.boxs_resources.html_create_edit)" class="pl15 pr15 bg_FFF br10">
                        <div class="pt15 pb15 tac fz14 c_666">Nenhum html create edit cadastrado...</div>
                    </div>
                </div>
            </div>
        <!-- HTML_CREATE_EDIT -->
    </div>

</template>


<style>
    /* DYNAMIC TEXTAREA CLASSES */
        .dynamic_height_200,
        .dynamic_height_300,
        .dynamic_height_400,
        .dynamic_height_500 {  height: auto;  transition: height 0.3s ease;  }

        .dynamic_height_200:focus { position: relative; z-index: 1; height: 200px !important; }
        .dynamic_height_300:focus { position: relative; z-index: 1; height: 300px !important; }
        .dynamic_height_400:focus { position: relative; z-index: 1; height: 400px !important; }
        .dynamic_height_500:focus { position: relative; z-index: 1; height: 500px !important; }
    /* DYNAMIC TEXTAREA CLASSES */
</style>