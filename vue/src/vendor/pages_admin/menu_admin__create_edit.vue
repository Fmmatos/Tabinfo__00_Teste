<script setup lang="ts">
import CREATE_EDIT_FORM__ from '@/vendor/pages_admin/menu_admin__form.vue';
import CREATE_EDIT_COLUMNS__ from '@/vendor/pages_admin/menu_admin__create_edit_columns.vue';
import CREATE_EDIT_RESOURCES__ from '@/vendor/pages_admin/menu_admin__create_edit_resources.vue';

import { MOBILE, open__,select2, sortable, tooltip } from '@/vendor/services/events';
import { modules__store_update } from '@/vendor/services/modules';
import { onBeforeMount, inject, ref, onMounted, onUnmounted } from 'vue';

sortable();
tooltip();

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.save_button = `datatable`;

        SHOW.more_rows = SHOW.more_rows_default = 4;
        SHOW.linhas_max = 200;

        SHOW.linhas_max_atual = SHOW.linhas_max;
        SHOW.linhas_max_individual = {
            // 41: 500,
        };
        
        SHOW.draggedIndex__ = null;
        SHOW.draggedOverIndex__ = null;
    // SHOW

    // FORM
        FORM.v.sortable = [];

        FORM.v.id = OBJ?.VALUE?.id ? OBJ?.VALUE?.id : 0;
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        // ONBEFOREMOUNT
            onBeforeMount(() => {
                if (SHOW.linhas_max_individual?.[FORM.v.id]){
                    SHOW.linhas_max_atual = SHOW.linhas_max_individual?.[FORM.v.id];
                } else {
                    SHOW.linhas_max_atual = SHOW.linhas_max;
                }

                // VALUE
                    for (const [key, value] of Object.entries(OBJ.VALUE)) {
                        FORM.v[key] = value;
                    }
                // VALUE

                // FORM
                    FORM.v.input = {};
                    for(let key in OBJ.VALUE?.input){
                        if (key < SHOW.linhas_max){
                            FORM.v.input[key] = OBJ.VALUE.input[key];
                        }
                    }

                    // TREATMENT NULL
                        for (const [key, value] of Object.entries(FORM.v.input)){
                            if (value && typeof value === 'object') {
                                for (const [key_1, value_1] of Object.entries(value)){
                                    if (key_1 == `options` && value_1 == null){
                                        FORM.v.input[key][key_1] = ``;
                                    }
                                }
                            }
                        }
                        if (FORM.v.categories == null){
                            FORM.v.categories = ``;
                        }
                    // TREATMENT NULL

                    if (OBJ.VALUE.input?.txt_meta){
                        FORM.v.input.txt_meta = OBJ.VALUE.input.txt_meta;
                    }
                    if (OBJ.VALUE.input?.txt){
                        FORM.v.input.txt = OBJ.VALUE.input.txt;
                    }
                // FORM

                // TREATMENT
                    let x = 0;
                    for(let key in FORM.v.input){
                        if (key < SHOW.linhas_max){
                            if (OBJ.VALUE.input[key]?.type){
                                x++;
                            }
                        }
                    }
                    SHOW.more_rows = x;
                // TREATMENT

                select2();
                SHOW.MENU_ADMIN_CREATE_EDIT = 1;
            })
        // ONBEFOREMOUNT


        // SCROLL BUTTON
            const isVisible = ref(false);

            onMounted(() => { // Adiciona o evento de scroll quando o componente é montado
                window.addEventListener('scroll', handleScroll);
                
                // Inicializar drag and drop para tags
                tags_drag();
            });
        
            onUnmounted(() => { // Remove o evento de scroll quando o componente é desmontado
                window.removeEventListener('scroll', handleScroll);
            });

            const handleScroll = () => {
                isVisible.value = window.scrollY >= 100;
            };
        // SCROLL BUTTON
    // EVENTS

    // FUNCTIONS
        // MORE_ROWS
            function __more_rows(x: number) {
                if (SHOW.more_rows + x >= 0) {
                    SHOW.more_rows = SHOW.more_rows + x;

                    if (x === 1) {
                        const i = SHOW.more_rows - 1;
                        if (!FORM.v.input?.[i]) {
                            FORM.v.input[i] = {};
                        }

                        FORM.v.input[i].type = 'text';
                        FORM.v.input[i].wr = 'wr6';
                    } else {
                        FORM.v.input[SHOW.more_rows] = {};
                    }
                    select2();
                    // REINITIALIZE SORTABLE FOR NEW ROWS
                        setTimeout(() => {
                            // REMOVE OLD CLASSES TO FORCE REINITIALIZATION
                                const containers = document.querySelectorAll('.sortable__');
                                const draggables = document.querySelectorAll('.sortable__ > li');
                                
                                containers.forEach(container => {
                                    container.classList.remove('__DRAGGABLE__OK');
                                });
                                
                                draggables.forEach(draggable => {
                                    draggable.classList.remove('__DRAGGABLE__OK');
                                });
                            // REMOVE OLD CLASSES TO FORCE REINITIALIZATION
                            
                            sortable();
                        }, 100);
                    // REINITIALIZE SORTABLE FOR NEW ROWS
                }
            }
        // MORE_ROWS


        // TAGS
            const tags_drag = () => {
                // Aguardar DOM estar pronto
                setTimeout(() => {
                    // Configurar todas as divs com tags como arrastáveis
                    const tagDivs = document.querySelectorAll('.tag-draggable');
                    tagDivs.forEach(div => {
                        div.setAttribute('draggable', 'true');
                        (div as HTMLElement).style.cursor = 'grab';
                        
                        // Evento quando começa a arrastar
                        div.addEventListener('dragstart', (e) => {
                            e.stopPropagation(); // Evitar conflito com sortable
                            const dragEvent = e as DragEvent;
                            if (dragEvent.dataTransfer) {
                                // Marcar como tag drag para diferenciar do sortable
                                dragEvent.dataTransfer.setData('text/tag-drag', 'true');
                                // Pegar o texto da div (remove espaços extras)
                                const tagText = (e.target as HTMLElement).textContent?.trim() || '';
                                dragEvent.dataTransfer.setData('text/plain', tagText);
                                dragEvent.dataTransfer.effectAllowed = 'copy';
                                
                                // Visual feedback
                                (e.target as HTMLElement).style.opacity = '0.5';
                                (e.target as HTMLElement).style.cursor = 'grabbing';
                            }
                        });
                        
                        // Evento quando termina de arrastar
                        div.addEventListener('dragend', (e) => {
                            e.stopPropagation(); // Evitar conflito com sortable
                            (e.target as HTMLElement).style.opacity = '1';
                            (e.target as HTMLElement).style.cursor = 'grab';
                        });
                    });
                    
                    // Configurar todos os inputs extra como drop zones
                    const extraInputs = document.querySelectorAll('input[name="extra"]');
                    extraInputs.forEach(input => {
                        // Permitir drop
                        input.addEventListener('dragover', (e) => {
                            e.preventDefault();
                            e.stopPropagation(); // Evitar conflito com sortable
                            const dragEvent = e as DragEvent;
                            if (dragEvent.dataTransfer) {
                                dragEvent.dataTransfer.dropEffect = 'copy';
                            }
                            // Visual feedback
                            input.classList.add('dragover');
                            (input as HTMLElement).style.backgroundColor = '#e3f2fd';
                            (input as HTMLElement).style.borderColor = '#2196f3';
                        });
                        
                        // Remover visual feedback quando sair
                        input.addEventListener('dragleave', (e) => {
                            e.preventDefault();
                            e.stopPropagation(); // Evitar conflito com sortable
                            input.classList.remove('dragover');
                            (input as HTMLElement).style.backgroundColor = '';
                            (input as HTMLElement).style.borderColor = '';
                        });
                        
                        // Quando soltar a tag
                        input.addEventListener('drop', (e) => {
                            e.preventDefault();
                            e.stopPropagation(); // Evitar conflito com sortable

                            const dragEvent = e as DragEvent;
                            // Verificar se é um drag de tag
                            const isTagDrag = dragEvent.dataTransfer?.getData('text/tag-drag') === 'true';
                            
                            if (isTagDrag) {
                                input.classList.remove('dragover');
                                (input as HTMLElement).style.backgroundColor = '';
                                (input as HTMLElement).style.borderColor = '';
                                
                                const tagText = dragEvent.dataTransfer?.getData('text/plain') || '';
                                
                                if (tagText) {
                                    const inputElement = input as HTMLInputElement;
                                    
                                    // Adicionar o texto na posição do cursor
                                    const startPos = inputElement.selectionStart || inputElement.value.length;
                                    const endPos = inputElement.selectionEnd || inputElement.value.length;
                                    const currentValue = inputElement.value;
                                    
                                    // Adicionar espaço se necessário
                                    const needSpace = startPos > 0 && currentValue[startPos - 1] !== ' ' ? ' ' : '';
                                    
                                    // Inserir o texto
                                    inputElement.value = currentValue.substring(0, startPos) + needSpace + tagText + currentValue.substring(endPos);
                                    
                                    // Disparar evento input para atualizar v-model
                                    inputElement.dispatchEvent(new Event('input', { bubbles: true }));
                                    
                                    // Posicionar cursor após o texto inserido
                                    const newPos = startPos + needSpace.length + tagText.length;
                                    inputElement.setSelectionRange(newPos, newPos);
                                    inputElement.focus();
                                }
                            }
                        });
                    });
                }, 500);
            }
        // TAGS
    // FUNCTIONS
</script>


<template>
    <div class="posr w1600 pl10 pr10 m-a fade_in_2">

        <form @submit.prevent="modules__store_update()">

            <!-- TOPO -->
                <div class="flexx_700 flex_j flex_ac">
                    <div class="pb10 flexx">
                        <a @click="open__(`/menu_admin`);" class="w16 mt5 mr10 c_555"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="currentColor" d="M14.75 8a.75.75 0 0 1-.75.75h-9.69l2.72 2.72a.749.749 0 1 1-1.06 1.06l-4-4a.747.747 0 0 1 0-1.06l4-4a.749.749 0 1 1 1.06 1.06l-2.72 2.72h9.69a.75.75 0 0 1 .75.75"></path></svg></a>
                        <div class="flex_1 fz20 fwb6 c_333 flexx flex_ac">
                            <div class="limit_2">{{ OBJ?.menu_admin?.name }} ({{ FORM.v.name ? FORM.v.name : `Novo Item` }})</div>
                        </div>
                    </div>
                    <div>
                        <div :class="`${MOBILE(700) ? `pt5 pb5 flexx` : `pl40 flexx flex_ac`} ${isVisible ? `__FIXED__` : ``}`">
                            <button @click="SHOW.save_button = `datatable`" class="dni"></button>
                            <button v-if="FORM.v.id" @click="SHOW.save_button = ``" class="w100 ml8 button_admin_1">Salvar</button>
                            <button @click="SHOW.save_button = `datatable`" class="dib w150 ml10 b_blue button_admin_1">Salvar&nbsp;e&nbsp;Fechar</button>

                            <!-- <button @click="SHOW.save_button = `force_create_model_again`" class="dib w150 ml10 button_admin_1" :class="compare__(`_categories`, OBJ?.VALUE.table__1) ? `bg_A64DFF5F bg_hover_A64DFF5F` : `b_purple`" :disabled="compare__(`_categories`, OBJ?.VALUE.table__1) ? true : false">Salvar&nbsp;e&nbsp;Editar&nbsp;Model</button> -->
                            <button @click="SHOW.save_button = `force_create_model_again`" class="dib w170 ml10 b_purple button_admin_1">Salvar&nbsp;e&nbsp;Editar&nbsp;Model</button>
                        </div>
                    </div>
                </div>
            <!-- TOPO -->


            <!-- FORM -->
                <ul class="__CAMPOS_MENU_ADMIN__">
                    <li class="pt10">

                        <!-- INFORMATIONS -->
                            <div class="">
                                <div class="fz14 fwb5">Informations</div>

                                <!-- NAME / TABLE / ICON / CATEGORIES / TYPE_ITEMS / ORDER -->
                                    <div class="pt10">
                                        <div class="w200 fll pb10">
                                            <input type="text" name="name" v-model="FORM.v.name" class="design" placeholder="Name" required>
                                        </div>
                                        <div class="w300 fll pb10 pl10">
                                            <input type="text" name="table__" v-model="FORM.v.table__" class="design" placeholder="Table" required>
                                        </div>
                                        <div class="w200 fll pb10 pl10">
                                            <input type="text" name="icon" v-model="FORM.v.icon" class="design" placeholder="Icon">
                                        </div>
                                        <div class="w160 fll pb10 pl10">
                                            <select name="categories" v-model="FORM.v.categories" class="design" required>
                                                <option value=""> - - - </option>
                                                <option v-for="value in OBJ?.QUERY?.menu_admin_categoriries" :key="value.id" :value="value.id">{{ value.name }}</option>
                                            </select>
                                        </div>
                                        <div class="w250 fll pb10 pl10">
                                            <input type="text" name="type_items" v-model="FORM.v.type_items" class="design" placeholder="Type Items" />
                                        </div>
                                        <div class="w350 fll pb10 pl10 flexx flex_ac">
                                            <div class="flex_1"><input type="text" name="orderby" v-model="FORM.v.orderby" class="design w100p" placeholder="name ASC, email DESC"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                <!-- NAME / TABLE / ICON / CATEGORIES / TYPE_ITEMS / ORDER -->


                                <!-- COLUMNS_EXTRA / COLUMNS_SAVE / SAVE / FILTER -->
                                    <div class="pt10">
                                        <div class="w250 fll pb10">
                                            <div class="flex_1"><input type="text" name="columns_extra" v-model="FORM.v.columns_extra" class="design w100p" placeholder="Columns extra table"></div>
                                        </div>
                                        <div class="w350 fll pb10 pl10 flexx flex_ac">
                                            <div class="flex_1"><input type="text" name="columns_save" v-model="FORM.v.columns_save" class="design" placeholder="checkbox=permissions, radio=permissions_all" /></div>
                                        </div>
                                        <div class="w500 fll pb10 pl10">
                                            <div class=""><textarea type="text" name="save" v-model="FORM.v.save" class="design w100p textarea__CSS" placeholder="[ 'customers' => $request->user()->id ]"></textarea></div>
                                        </div>
                                        <div class="w480 fll pb10 pl10">
                                            <div class=""><textarea type="text" name="filter" v-model="FORM.v.filter" class="design w100p textarea__CSS" placeholder="$query->where('customers', $request->user()->id);"></textarea></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                <!-- COLUMNS_EXTRA / COLUMNS_SAVE / SAVE / FILTER -->


                                <!-- TYPE / INFO -->
                                    <div class="pt20 flexx_x flex_ac gap_14">
                                        <div class="flexx flex_ac gap_15">
                                            <label class="c-p flexx flex_ac"><input type="radio" name="type" v-model="FORM.v.type" value="0" class="design"><div class="pl4 ws">Modules</div></label>
                                            <label class="c-p flexx flex_ac"><input type="radio" name="type" v-model="FORM.v.type" value="1" class="design"><div class="pl4 ws">Module Single</div></label>
                                            <label class="c-p flexx flex_ac"><input type="radio" name="type" v-model="FORM.v.type" value="2" class="design"><div class="pl4 ws">Dashboard</div></label>
                                            <label class="c-p flexx flex_ac"><input type="radio" name="type" v-model="FORM.v.type" value="99" class="design"><div class="pl4 ws">Outros</div></label>
                                        </div>
                                        <div class="pl20 flexx flex_ac gap_15 bdl_aaa">
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="create" class="design"><div class="pl4 ws">Create</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="create_pf_pj" class="design"><div class="pl4 ws">PF / PJ</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="create_hide" class="design"><div class="pl4 ws">Create (Hide)</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="edit" class="design"><div class="pl4 ws">Edit</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="delete" class="design"><div class="pl4 ws">Delete</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="clone" class="design"><div class="pl4 ws">Clone</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="excel" class="design"><div class="pl4 ws">Excel</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="pdf" class="design"><div class="pl4 ws">PDF</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="hide" class="design"><div class="pl4 ws">Hide</div></label>
                                        </div>
                                        <div class="pl20 flexx flex_ac gap_15 bdl_aaa">
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="star_1" class="design"><div class="pl4 ws">Star1</div></label> 
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="star_2" class="design"><div class="pl4 ws">Star2</div></label> 
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="star_3" class="design"><div class="pl4 ws">Star3</div></label> 
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="star_4" class="design"><div class="pl4 ws">Star4</div></label> 
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="star_5" class="design"><div class="pl4 ws">Star5</div></label> 
                                        </div>
                                    </div>
                                <!-- TYPE / INFO -->


                                <!-- INFO -->
                                    <div class="pt20 flexx flex_ac">
                                        <div class="pr20 flexx flex_ac gap_15 bdr_aaa">
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="items_page" class="design"><div class="pl4 ws">Itens Page</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="columns_orders" class="design"><div class="pl4 ws">Columns</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="table_thead_fixed" class="design"><div class="pl4 ws">Table Thead Fixed</div></label>
                                        </div>

                                        <div class="pl20 pr20 flexx flex_ac gap_15 bdr_aaa">
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="search_top_on" class="design"><div class="pl4 ws">Search Top</div></label>
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="search_top_date" class="design"><div class="pl4 ws">Search Top (Data)</div></label>
                                            <div class="dib pl5 c-p flexx flex_ac"><input type="text" name="search_columns_date" v-model="FORM.v.search_columns_date" class="design w250 h30"></div>
                                        </div>

                                        <div class="pl20 pr20 flexx flex_ac gap_15">
                                            <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="meta_tags" class="design"><div class="pl4 ws">Meta Tags</div></label>
                                        </div>
                                    </div>
                                <!-- INFO -->


                                <!-- COLUMNS -->
                                    <div class="pt20 flexx flex_ac">
                                        <div class="flexx flex_ac gap_15">
                                            <div class="c-p flexx flex_ac"> Columns: </div> 
                                            <div class="flexx flex_ac">
                                                <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="columns_active" class="design"><div class="pl4">Active</div></label> 
                                                <div class="dib pl5 c-p flexx flex_ac"><input type="text" name="columns_active_n" v-model="FORM.v.columns_active_n" class="design w50 h30 tac"></div>
                                            </div>
                                            <div class="flexx flex_ac">
                                                <label class="c-p flexx flex_ac"><input type="checkbox" name="info" v-model="FORM.v.info" value="columns_order" class="design"><div class="pl4">Order</div></label>
                                                <div class="dib pl5 c-p flexx flex_ac"><input type="text" name="columns_order_n" v-model="FORM.v.columns_order_n" class="design w50 h30 tac"></div>
                                            </div>
                                        </div>
                                    </div>
                                <!-- COLUMNS -->

                            </div>
                        <!-- INFORMATIONS -->





                        <!-- COLUMNS -->
                            <div class="posr pt20">
                                <div class="fz14 fwb5">Columns</div>

                                <div class="pt10">
                                    <div class="pb10 pl5 flexx flex_j flex_ac">
                                        <div class="flexx flex_ac">
                                            <button type="button" @click="__more_rows(1)" v-if="SHOW.more_rows<SHOW.linhas_max_atual" class="link c_blue"> More Row </button>
                                            <button type="button" @click="__more_rows(-1)" v-if="SHOW.more_rows>1" class="ml10 link c_blue"> Less Row </button> 
                                        </div>
                                    </div>

                                    <div>
                                        <div class="posa t0 r0 z1 w185 p10 mt--190 mr--200 bd_ccc bg_fff br5">
                                            <div class="">
                                                <div class="pb5 fz14 fwb6">TABLE</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="css para items da tabela">|->table_css->(class)</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="nao permite clicar no item">|->no_click</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostrar anos">|->year</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostrar anos">|->years</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostrar meses">|->month</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostrar data">|->date</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostrar preco">|->price</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="items">|->items</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="weight kg">|->set->set[weight] kg</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="balao no thead">|->tooltip_thead->xx</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="para longtext (json) -> colocar <br> em cada item o array (json)">|->implode-><br></div>
                                                
                                                <div class="pt10 pb5 fz14 fwb6">SEARCH</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="nao mostrar no search (__Resource)">|->no_search</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="valor fixado">|->value__-> </div>

                                                <div class="pt10 pb5 fz14 fwb6">CREATE_EDIT</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="usar iframe | pode usar assim tbm: |->new->xx">|->new</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="Não Salvar">|->no_save</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="Não Novo">|->no_new</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="Não Editar">|->no_edit</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="valor fixado">|->value__-> </div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="balao no input">|->tooltip_input->xx</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="Mostra esta campo se o select (options) xx = y ou z">|->value_rel->xx->y|z</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="Mostra esta campo se o select (options) xx != y ou z">|->value_not_rel->xx->y|z</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="icone no input">|->icon->xx->function_type</div>

                                                <div class="pt10 pb5 fz14 fwb6">OPTIONS</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostrar todos os itens">|->options_all</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="coluna de relacionamento xx = columns table rel | yy = columns table atual">|->options_whereIn->xx->yy</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="mostra coluna xx">|->options_columns->xx</div>
                                                <div class="pt5 pb5 limit tag-draggable" tooltip="Primeiro item do select">|->options_select->Selecione...</div>
                                            </div>
                                        </div>    
                                    </div>

                                    <ul class="sortable__">
                                        <li v-for="(n__, index) in SHOW.linhas_max_atual" :key="index" v-show="SHOW.more_rows >= n__"
                                            class="pb5 draggable" :dir="String(n__-1)"
                                            :class="{
                                                'bg_E8F5E9': SHOW.draggedIndex__ === index,
                                                'bdt_4CAF50 bdt3': SHOW.draggedOverIndex__ === index && SHOW.draggedIndex__ !== null && SHOW.draggedIndex__ > index,
                                                'bdb_4CAF50 bdb3': SHOW.draggedOverIndex__ === index && SHOW.draggedIndex__ !== null && SHOW.draggedIndex__ < index
                                            }"
                                        >
                                            <CREATE_EDIT_FORM__ v-if="SHOW.more_rows >= n__" :n__="(n__-1)" />
                                        </li>
                                        <li class="pt20 pb5" dir="txt_meta">
                                            <CREATE_EDIT_FORM__ n__="txt_meta" />
                                        </li>
                                        <li class="" dir="txt">
                                            <CREATE_EDIT_FORM__ n__="txt" />
                                        </li>
                                    </ul>
                                    <div class="pt10 pb5 pl5 flexx flex_ac">
                                        <a @click="__more_rows(1)" v-if="SHOW.more_rows<SHOW.linhas_max_atual" class="link c_blue"> More Row </a>
                                        <a @click="__more_rows(-1)" v-if="SHOW.more_rows>1" class="ml10 link c_blue"> Less Row </a> 
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <!-- COLUMNS -->

                    </li>
                </ul>
            <!-- FORM -->
        </form>





        <CREATE_EDIT_RESOURCES__ />
        <CREATE_EDIT_COLUMNS__ />

    </div>

</template>


<style>
    .__PG__menu_admin__[get_2__="1"] .__MENU_SIDE__ { display: none; }

    .textarea__CSS { height: 40px !important; }
    .textarea__CSS:focus { height: 150px !important; }    
</style>

<style scoped>
    @media (min-width: 1000px){
        .__FIXED__ { position: fixed; top: 5px; z-index: 10; width: 300px; margin-left:  -300px; justify-content: flex-end; -ms-flex-pack: flex-end; }
    }
    @media (max-width: 1000px){
        .__FIXED__ { position: fixed; top: 5px; z-index: 10; }
    }
</style>