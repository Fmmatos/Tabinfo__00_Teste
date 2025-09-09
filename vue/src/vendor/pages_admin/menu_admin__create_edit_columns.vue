<script setup lang="ts">
import api from '@/vendor/services/api';
import { alerts, select2 } from '@/vendor/services/events';
import { inject } from 'vue';


    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.editingField_columns = null;
        SHOW.draggedIndex_columns = null;
        SHOW.draggedOverIndex_columns = null;
        SHOW.editingIndex = null;
        SHOW.showCreateIndex = false;
        SHOW.editingForeignKey = null;
        SHOW.editingForeignKeyField = null;
        SHOW.showCreateForeignKey = false;
    // SHOW

    // FORM
        FORM.v.newIndexName = '';
        FORM.v.newIndexType = 'INDEX';
        FORM.v.newIndexColumns = [];
        
        FORM.v.newFkName = '';
        FORM.v.newFkColumn = '';
        FORM.v.newFkReferencedTable = '';
        FORM.v.newFkReferencedColumn = 'id';
        FORM.v.newFkOnDelete = 'SET NULL';
        FORM.v.newFkOnUpdate = 'CASCADE';
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        const __editField = (columnIndex: number, fieldName: string) => {
            SHOW.editingField_columns = `${columnIndex}-${fieldName}`;
            // COPY CURRENT VALUE TO FORM
                const column = OBJ.TABLE?.COLUMNS[columnIndex];
                if (column) {
                    FORM.v[`${columnIndex}-${fieldName}`] = column[fieldName];
                    // SAVE ORIGINAL COLUMN NAME
                        FORM.v[`${columnIndex}-originalField`] = column.Field;
                    // SAVE ORIGINAL COLUMN NAME
                }
            // COPY CURRENT VALUE TO FORM
        };


        const __saveField = (columnIndex: number, fieldName: string) => {
            const column = OBJ.TABLE?.COLUMNS[columnIndex];
            if (column) {
                // UPDATE VALUE IN OBJECT
                    const originalColumnName = FORM.v[`${columnIndex}-originalField`] || column.Field;
                    const newValue = FORM.v[`${columnIndex}-${fieldName}`];
                    
                    api(`/admin/menu_admin/column_update`, { 
                        table: OBJ.TABLE?.TABLE, 
                        column: originalColumnName, 
                        field: fieldName, 
                        value: newValue, 
                        _method: 'PUT' 
                    }, (json: any) => {
                        // UPDATE LOCAL OBJECT AFTER SUCCESS
                            column[fieldName] = newValue;
                            // UPDATE ORIGINAL FIELD NAME IF RENAMED
                                if (fieldName === 'Field') {
                                    FORM.v[`${columnIndex}-originalField`] = newValue;
                                    // RELOAD TABLE STRUCTURE
                                        api(`/admin/menu_admin/create_edit/${OBJ.VALUE?.id}`, {}, (json2: any) => {
                                            if (json2.OBJ?.TABLE?.COLUMNS) {
                                                OBJ.TABLE.COLUMNS = json2.OBJ.TABLE.COLUMNS;
                                            }
                                        });
                                    // RELOAD TABLE STRUCTURE
                                }
                            // UPDATE ORIGINAL FIELD NAME IF RENAMED
                        // UPDATE LOCAL OBJECT AFTER SUCCESS
                    });
                // UPDATE VALUE IN OBJECT
            }
            SHOW.editingField_columns = null;
        };


        const __cancelEdit = () => {
            SHOW.editingField_columns = null;
        };


        const __handleKeyup = (event: KeyboardEvent, columnIndex: number, fieldName: string) => {
            if (event.key === 'Enter') {
                __saveField(columnIndex, fieldName);
            } else if (event.key === 'Escape') {
                __cancelEdit();
            }
        };


        // DRAG AND DROP EVENTS
            const __dragStart = (event: DragEvent, index: number) => {
                SHOW.draggedIndex_columns = index;
                event.dataTransfer!.effectAllowed = 'move';
                event.dataTransfer!.setData('text/html', '');
            };

            const __dragOver = (event: DragEvent, index: number) => {
                event.preventDefault();
                SHOW.draggedOverIndex_columns = index;
            };

            const __dragLeave = () => {
                SHOW.draggedOverIndex_columns = null;
            };

            const __drop = (event: DragEvent, dropIndex: number) => {
                event.preventDefault();
                
                if (SHOW.draggedIndex_columns !== null && SHOW.draggedIndex_columns !== dropIndex) {
                    // REORDER COLUMNS
                        const columns = [...OBJ.TABLE.COLUMNS];
                        const draggedColumn = columns[SHOW.draggedIndex_columns];
                        columns.splice(SHOW.draggedIndex_columns, 1);
                        columns.splice(dropIndex, 0, draggedColumn);
                        OBJ.TABLE.COLUMNS = columns;
                    // REORDER COLUMNS

                    // SAVE NEW ORDER
                        const columnOrder = columns.map(col => col.Field);
                        api(`/admin/menu_admin/column_reorder`, { 
                            table: OBJ.TABLE?.TABLE, 
                            order: JSON.stringify(columnOrder),
                            _method: 'PUT' 
                        });
                    // SAVE NEW ORDER
                }
                
                SHOW.draggedIndex_columns = null;
                SHOW.draggedOverIndex_columns = null;
            };

            const __dragEnd = () => {
                SHOW.draggedIndex_columns = null;
                SHOW.draggedOverIndex_columns = null;
            };
        // DRAG AND DROP EVENTS


        // INDEX MANAGEMENT
            const __editIndex = (indexPosition: number) => {
                const index = OBJ.TABLE?.INDEXES[indexPosition];
                if (index && index.name !== 'PRIMARY') {
                    SHOW.editingIndex = indexPosition;
                    FORM.v[`index-${indexPosition}-name`] = index.name;
                }
            };

            const __saveIndex = (indexPosition: number) => {
                const oldName = OBJ.TABLE?.INDEXES[indexPosition].name;
                const newName = FORM.v[`index-${indexPosition}-name`];
                
                if (oldName && newName && oldName !== newName) {
                    api(`/admin/menu_admin/indexes_rename`, {
                        id: OBJ.VALUE?.id,
                        table: OBJ.TABLE?.TABLE,
                        oldName: oldName,
                        newName: newName,
                        _method: 'PUT'
                    }, (json: any) => {
                        if (json.status === 200) {
                            alerts(1, 'Índice renomeado com sucesso!');
                        }
                    });
                }
                
                SHOW.editingIndex = null;
            };

            const __createIndex = () => {
                if (!FORM.v.newIndexColumns || FORM.v.newIndexColumns.length === 0) {
                    alerts(0, 'Selecione pelo menos uma coluna!');
                    return;
                }
                
                // GENERATE INDEX NAME IF NOT PROVIDED
                    let indexName = FORM.v.newIndexName;
                    if (!indexName || indexName.trim() === '') {
                        // Create name based on columns
                        if (FORM.v.newIndexColumns.length === 1) {
                            indexName = `idx_${FORM.v.newIndexColumns[0]}`;
                        } else {
                            indexName = `idx_${FORM.v.newIndexColumns.join('_')}`;
                        }
                        
                        // Add unique suffix if UNIQUE type
                        if (FORM.v.newIndexType === 'UNIQUE') {
                            indexName = indexName.replace('idx_', 'unq_');
                        }
                    }
                // GENERATE INDEX NAME IF NOT PROVIDED
                
                api(`/admin/menu_admin/indexes_create`, {
                    id: OBJ.VALUE?.id,
                    table: OBJ.TABLE?.TABLE,
                    name: indexName,
                    type: FORM.v.newIndexType || 'INDEX',
                    columns: JSON.stringify(FORM.v.newIndexColumns),
                    _method: 'POST'
                }, (json: any) => {
                    if (json.status === 200) {
                        alerts(1, 'Índice criado com sucesso!');
                        __cancelCreateIndex();
                    }
                });
            };

            const __cancelCreateIndex = () => {
                SHOW.showCreateIndex = false;
                FORM.v.newIndexName = '';
                FORM.v.newIndexType = 'INDEX';
                FORM.v.newIndexColumns = [];
            };

            const __deleteIndex = (indexName: string) => {
                if (confirm(`Tem certeza que deseja deletar o índice "${indexName}"?`)) {
                    api(`/admin/menu_admin/indexes_delete`, {
                        id: OBJ.VALUE?.id,
                        table: OBJ.TABLE?.TABLE,
                        name: indexName,
                        _method: 'DELETE'
                    }, (json: any) => {
                        if (json.status === 200) {
                            alerts(1, 'Índice deletado com sucesso!');
                        }
                    });
                }
            };
        // INDEX MANAGEMENT


        // FOREIGN KEY MANAGEMENT
            const __editForeignKey = (fkPosition: number) => {
                const fk = OBJ.TABLE?.FOREIGN_KEYS[fkPosition];
                if (fk) {
                    SHOW.editingForeignKey = fkPosition;
                    FORM.v[`fk-${fkPosition}-name`] = fk.CONSTRAINT_NAME;
                }
            };

            const __saveForeignKey = (fkPosition: number) => {
                const oldName = OBJ.TABLE?.FOREIGN_KEYS[fkPosition].CONSTRAINT_NAME;
                const newName = FORM.v[`fk-${fkPosition}-name`];
                
                if (oldName && newName && oldName !== newName) {
                    api(`/admin/menu_admin/foreign_key_rename`, {
                        id: OBJ.VALUE?.id,
                        table: OBJ.TABLE?.TABLE,
                        oldName: oldName,
                        newName: newName,
                        _method: 'PUT'
                    }, (json: any) => {
                        if (json.status === 200) {
                            alerts(1, 'Foreign key renomeada com sucesso!');
                        }
                    });
                }
                
                SHOW.editingForeignKey = null;
            };

            const __createForeignKey = () => {
                if (!FORM.v.newFkColumn || !FORM.v.newFkReferencedTable || !FORM.v.newFkReferencedColumn) {
                    alerts(0, 'Preencha todos os campos obrigatórios');
                    return;
                }

                // GENERATE FK NAME IF NOT PROVIDED
                    let fkName = FORM.v.newFkName;
                    if (!fkName || fkName.trim() === '') {
                        fkName = `${OBJ.TABLE?.TABLE}__${FORM.v.newFkReferencedTable}__foreign`;
                    }
                // GENERATE FK NAME IF NOT PROVIDED

                const data = {
                    id: OBJ.VALUE?.id,
                    table: OBJ.TABLE?.TABLE,
                    name: fkName,
                    column: FORM.v.newFkColumn,
                    referenced_table: FORM.v.newFkReferencedTable,
                    referenced_column: FORM.v.newFkReferencedColumn,
                    on_delete: FORM.v.newFkOnDelete,
                    on_update: FORM.v.newFkOnUpdate
                };

                api(`/admin/menu_admin/foreign_key_create`, data, (json: any) => {
                    if (json.status === 200) {
                        alerts(1, 'Foreign key criada com sucesso!');
                        __cancelCreateForeignKey();
                    }
                });
            };

            const __cancelCreateForeignKey = () => {
                SHOW.showCreateForeignKey = false;
                FORM.v.newFkName = '';
                FORM.v.newFkColumn = '';
                FORM.v.newFkReferencedTable = '';
                FORM.v.newFkReferencedColumn = 'id';
                FORM.v.newFkOnDelete = 'SET NULL';
                FORM.v.newFkOnUpdate = 'CASCADE';
            };

            const __deleteForeignKey = (fk: any) => {
                if (!confirm(`Tem certeza que deseja deletar a foreign key "${fk.CONSTRAINT_NAME}"?`)) {
                    return;
                }

                const data = {
                    id: OBJ.VALUE?.id,
                    table: OBJ.TABLE?.TABLE,
                    name: fk.CONSTRAINT_NAME,
                    _method: 'DELETE'
                };

                api(`/admin/menu_admin/foreign_key_delete`, data, (json: any) => {
                    if (json.status === 200) {
                        alerts(1, 'Foreign key deletada com sucesso!');
                    }
                });
            };

            const __editForeignKeyField = (fkPosition: number, field: string) => {
                const fk = OBJ.TABLE?.FOREIGN_KEYS[fkPosition];
                if (fk) {
                    SHOW.editingForeignKeyField = `${fkPosition}-${field}`;
                    
                    switch(field) {
                        case 'referenced_table':
                            FORM.v[`fk-${fkPosition}-referenced_table`] = fk.REFERENCED_TABLE_NAME;
                            break;
                        case 'referenced_column':
                            FORM.v[`fk-${fkPosition}-referenced_column`] = fk.REFERENCED_COLUMN_NAME;
                            break;
                        case 'delete_rule':
                            FORM.v[`fk-${fkPosition}-delete_rule`] = fk.DELETE_RULE || 'RESTRICT';
                            break;
                        case 'update_rule':
                            FORM.v[`fk-${fkPosition}-update_rule`] = fk.UPDATE_RULE || 'RESTRICT';
                            break;
                    }
                }
            };

            const __saveForeignKeyField = (fkPosition: number, field: string) => {
                const fk = OBJ.TABLE?.FOREIGN_KEYS[fkPosition];
                if (!fk) return;
                
                const data: any = {
                    id: OBJ.VALUE?.id,
                    table: OBJ.TABLE?.TABLE,
                    name: fk.CONSTRAINT_NAME,
                    _method: 'PUT'
                };
                
                switch(field) {
                    case 'referenced_table':
                        data.referenced_table = FORM.v[`fk-${fkPosition}-referenced_table`];
                        break;
                    case 'referenced_column':
                        data.referenced_column = FORM.v[`fk-${fkPosition}-referenced_column`];
                        break;
                    case 'delete_rule':
                        data.on_delete = FORM.v[`fk-${fkPosition}-delete_rule`];
                        break;
                    case 'update_rule':
                        data.on_update = FORM.v[`fk-${fkPosition}-update_rule`];
                        break;
                }
                
                api(`/admin/menu_admin/foreign_key_update`, data, (json: any) => {
                    if (json.status === 200) {
                        alerts(1, 'Foreign key atualizada com sucesso!');
                    }
                });
                
                SHOW.editingForeignKeyField = null;
            };
        // FOREIGN KEY MANAGEMENT
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <div class="pt200 pb40">
        <!-- TITLE -->
            <div class="fz20 fwb6 c_000">Estrutura da Tabela</div>
        <!-- TITLE -->


        <!-- INDEXES -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 c_000">Índices</div>
                    <button @click="SHOW.showCreateIndex = true" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">
                        + Novo Índice
                    </button>
                </div>
                
                <!-- CREATE INDEX FORM -->
                    <div v-if="SHOW.showCreateIndex" class="pt10 p15 mt10 bg_FFF br10">
                        <div class="fz14 fwb6 c_000 pb10">Criar Novo Índice</div>
                        
                        <div class="flexx gap_10">
                            <div class="flex_1">
                                <input 
                                    v-model="FORM.v.newIndexName" 
                                    type="text" 
                                    placeholder="Nome do índice (opcional - será gerado automaticamente)"
                                    class="w100p p8 bd_CCCCCC br4 fz13"
                                />
                            </div>
                            
                            <div class="w150">
                                <select v-model="FORM.v.newIndexType" class="w100p p8 bd_CCCCCC br4 fz13">
                                    <option value="INDEX">INDEX</option>
                                    <option value="UNIQUE">UNIQUE</option>
                                </select>
                            </div>
                            
                            <div class="flex_1">
                                <select v-model="FORM.v.newIndexColumns" multiple class="w100p p8 bd_CCCCCC br4 fz13" style="min-height: 200px;">
                                    <option v-for="col in OBJ.TABLE?.COLUMNS" :key="col.Field" :value="col.Field">
                                        {{ col.Field }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="pt10 flexx flex_r gap_10">
                            <button @click="__createIndex()" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_10B759 bd0 br5 c-p">
                                Criar
                            </button>
                            <button @click="__cancelCreateIndex()" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_333 bg_fff bd_CCCCCC br5 c-p">
                                Cancelar
                            </button>
                        </div>
                    </div>
                <!-- CREATE INDEX FORM -->
                
                <div class="pt10" v-if="OBJ.TABLE?.INDEXES?.length > 0">
                    <table class="w100p bg_fff br10">
                        <thead>
                            <tr class="bdb_E5E7EB">
                                <th class="pt10 pb10 pl15 pr15 tal fz12 fwb5 ttu">Nome do Índice</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Tipo</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Único</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Colunas</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(index, i) in OBJ.TABLE?.INDEXES" :key="i" class="bdb_E5E7EB bg_hover_F8F9FA">
                                <td class="pt10 pb10 pl15 pr15 c_111B2B fz13">
                                    <div v-if="SHOW.editingIndex === i">
                                        <input 
                                            v-model="FORM.v[`index-${i}-name`]"
                                            @keyup.enter="__saveIndex(i)"
                                            @keyup.esc="SHOW.editingIndex = null"
                                            @blur="SHOW.editingIndex = null"
                                            class="w100p p5 bd_007BFF br4 fz13"
                                            :ref="el => { if (el && SHOW.editingIndex === i) (el as HTMLInputElement).focus(); }"
                                        />
                                    </div>
                                    <span v-else @click="__editIndex(i)" class="c-p">{{ index.name }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 tac fz13">
                                    <span v-if="index.type === 'BTREE'" class="dib pt3 pb3 pl8 pr8 c_fff bg_6610F2 br4 fz11 fwb5">{{ index.type }}</span>
                                    <span v-else class="dib pt3 pb3 pl8 pr8 c_fff bg_6C7781 br4 fz11 fwb5">{{ index.type }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 tac fz13">
                                    <span v-if="index.unique" class="c_10B759">SIM</span>
                                    <span v-else class="c_DC3545">NÃO</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 fz13 tac">{{ index.columns.join(', ') }}</td>
                                <td class="pt10 pb10 pl15 pr15 tac">
                                    <button 
                                        v-if="index.name !== 'PRIMARY'"
                                        @click="__deleteIndex(index.name)" 
                                        class="pt3 pb3 pl8 pr8 fz11 m-a fwb5 c_fff bg_DC3545 bd0 br4 c-p"
                                    >
                                        Deletar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div v-else class="pt25 p25 mt10 fz14 tac bg_fff br10">
                    Nenhum índice encontrado...
                </div>
            </div>
        <!-- INDEXES -->


        <!-- FOREIGN KEYS -->
            <div class="pt30">
                <div class="flexx flex_j flex_ac">
                    <div class="fz16 fwb6 c_000">Chaves Estrangeiras</div>
                    <button @click="SHOW.showCreateForeignKey = true; select2();" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_007BFF bd0 br5 c-p">
                        + Nova Foreign Key
                    </button>
                </div>
                
                <!-- CREATE FOREIGN KEY FORM -->
                    <div v-if="SHOW.showCreateForeignKey" class="pt10 p15 mt10 bg_FFF br10">
                        <div class="fz14 fwb6 c_000 pb10">Criar Nova Foreign Key</div>
                        
                        <div class="flexx gap_10 pb10">
                            <div class="flex_1">
                                <label class="db pb5 fz12 c_666">ON DELETE</label>
                                <select v-model="FORM.v.newFkOnDelete" class="w100p p8 bd_CCCCCC br4 fz13 designx">
                                    <option value="RESTRICT">RESTRICT</option>
                                    <option value="CASCADE">CASCADE</option>
                                    <option value="SET NULL">SET NULL</option>
                                    <option value="NO ACTION">NO ACTION</option>
                                </select>
                            </div>
                            
                            <div class="flex_1">
                                <label class="db pb5 fz12 c_666">ON UPDATE</label>
                                <select v-model="FORM.v.newFkOnUpdate" class="w100p p8 bd_CCCCCC br4 fz13 designx">
                                    <option value="RESTRICT">RESTRICT</option>
                                    <option value="CASCADE">CASCADE</option>
                                    <option value="SET NULL">SET NULL</option>
                                    <option value="NO ACTION">NO ACTION</option>
                                </select>
                            </div>

                            <div class="flex_1">
                                <label class="db pb5 fz12 c_666">Coluna Referenciada</label>
                                <input 
                                    v-model="FORM.v.newFkReferencedColumn" 
                                    type="text" 
                                    placeholder="id"
                                    class="w100p p8 bd_CCCCCC br4 fz13 design"
                                />
                            </div>

                            <div class="flex_1">
                                <label class="db pb5 fz12 c_666">Nome da FK (opcional)</label>
                                <input 
                                    v-model="FORM.v.newFkName" 
                                    type="text" 
                                    placeholder="Deixe vazio para gerar: {table}__{column}__foreign"
                                    class="w100p p8 bd_CCCCCC br4 fz13 design"
                                />
                            </div>
                            
                            <div class="flex_1">
                                <label class="db pb5 fz12 c_666">Coluna Local</label>
                                <select v-model="FORM.v.newFkColumn" class="w100p p8 bd_CCCCCC br4 fz13 designx">
                                    <option value="">Selecione...</option>
                                    <option 
                                        v-for="col in OBJ.TABLE?.COLUMNS?.filter((c: any) => c.Type.includes('bigint unsigned'))" 
                                        :key="col.Field" 
                                        :value="col.Field"
                                    >
                                        {{ col.Field }} ({{ col.Type }})
                                    </option>
                                </select>
                            </div>

                            <div class="flex_1">
                                <label class="db pb5 fz12 c_666">Tabela Referenciada</label>
                                <select name="options" v-model="FORM.v.newFkReferencedTable" class="design">
                                    <option value="">- - - -</option>
                                    <slot v-for="(value, key) in OBJ?.QUERY?.menu_admin_all" :key="key">
                                        <option v-if="value.type != 1" :value="value.table__1">{{ value.name }} (#{{ value.id }}) ({{ value.type==2 ? `D` : `M` }})</option>
                                    </slot>
                                </select>
                            </div>                            
                        </div>
                        
                        <div class="pt10 flexx flex_r gap_10">
                            <button @click="__createForeignKey()" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_fff bg_10B759 bd0 br5 c-p">
                                Criar
                            </button>
                            <button @click="__cancelCreateForeignKey()" class="pt5 pb5 pl15 pr15 fz12 fwb6 c_333 bg_fff bd_CCCCCC br5 c-p">
                                Cancelar
                            </button>
                        </div>
                    </div>
                <!-- CREATE FOREIGN KEY FORM -->
                
                <div class="pt10" v-if="OBJ.TABLE?.FOREIGN_KEYS?.length > 0">
                    <table class="w100p bg_fff br10">
                        <thead>
                            <tr class="bdb_E5E7EB">
                                <th class="pt10 pb10 pl15 pr15 tal fz12 fwb5 ttu">Nome da Constraint</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Coluna</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Tabela Referenciada</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Coluna Referenciada</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">ON DELETE</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">ON UPDATE</th>
                                <th class="pt10 pb10 pl15 pr15 tac fz12 fwb5 ttu">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(fk, i) in OBJ.TABLE?.FOREIGN_KEYS" :key="i" class="bdb_E5E7EB bg_hover_F8F9FA">
                                <td class="pt10 pb10 pl15 pr15 c_111B2B fz13">
                                    <div v-if="SHOW.editingForeignKey === i">
                                        <input 
                                            v-model="FORM.v[`fk-${i}-name`]"
                                            @keyup.enter="__saveForeignKey(i)"
                                            @keyup.esc="SHOW.editingForeignKey = null"
                                            @blur="SHOW.editingForeignKey = null"
                                            class="w100p p5 bd_007BFF br4 fz13"
                                            :ref="el => { if (el && SHOW.editingForeignKey === i) (el as HTMLInputElement).focus(); }"
                                        />
                                    </div>
                                    <span v-else @click="__editForeignKey(i)" class="c-p">{{ fk.CONSTRAINT_NAME }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 fz13 tac">
                                    <span class="dib pt3 pb3 pl8 pr8 c_fff bg_007BFF br4 fz11 fwb5">{{ fk.COLUMN_NAME }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 fz13 tac c-p bg_hover_f0f0f0" @click="__editForeignKeyField(i, 'referenced_table')">
                                    <div v-if="SHOW.editingForeignKeyField === `${i}-referenced_table`">
                                        <select 
                                            v-model="FORM.v[`fk-${i}-referenced_table`]"
                                            @change="__saveForeignKeyField(i, 'referenced_table')"
                                            @blur="SHOW.editingForeignKeyField = null"
                                            class="w100p p5 bd_007BFF br4 fz13"
                                            :ref="el => { if (el && SHOW.editingForeignKeyField === `${i}-referenced_table`) (el as HTMLSelectElement).focus(); }"
                                        >
                                            <slot v-for="(value, key) in OBJ?.QUERY?.menu_admin_all" :key="key">
                                                <option v-if="value.type != 1" :value="value.table__1">{{ value.table__1 }}</option>
                                            </slot>
                                        </select>
                                    </div>
                                    <span v-else class="dib pt3 pb3 pl8 pr8 c_fff bg_10B759 br4 fz11 fwb5">{{ fk.REFERENCED_TABLE_NAME }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 fz13 tac c-p bg_hover_f0f0f0" @click="__editForeignKeyField(i, 'referenced_column')">
                                    <div v-if="SHOW.editingForeignKeyField === `${i}-referenced_column`">
                                        <input 
                                            v-model="FORM.v[`fk-${i}-referenced_column`]"
                                            @keyup.enter="__saveForeignKeyField(i, 'referenced_column')"
                                            @keyup.esc="SHOW.editingForeignKeyField = null"
                                            @blur="SHOW.editingForeignKeyField = null"
                                            class="w100p p5 bd_007BFF br4 fz13"
                                            placeholder="id"
                                            :ref="el => { if (el && SHOW.editingForeignKeyField === `${i}-referenced_column`) (el as HTMLInputElement).focus(); }"
                                        />
                                    </div>
                                    <span v-else>{{ fk.REFERENCED_COLUMN_NAME }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 fz13 tac c-p bg_hover_f0f0f0" @click="__editForeignKeyField(i, 'delete_rule')">
                                    <div v-if="SHOW.editingForeignKeyField === `${i}-delete_rule`">
                                        <select 
                                            v-model="FORM.v[`fk-${i}-delete_rule`]"
                                            @change="__saveForeignKeyField(i, 'delete_rule')"
                                            @blur="SHOW.editingForeignKeyField = null"
                                            class="w100p p5 bd_007BFF br4 fz13"
                                            :ref="el => { if (el && SHOW.editingForeignKeyField === `${i}-delete_rule`) (el as HTMLSelectElement).focus(); }"
                                        >
                                            <option value="RESTRICT">RESTRICT</option>
                                            <option value="CASCADE">CASCADE</option>
                                            <option value="SET NULL">SET NULL</option>
                                            <option value="NO ACTION">NO ACTION</option>
                                        </select>
                                    </div>
                                    <span v-else class="dib pt3 pb3 pl8 pr8 c_fff bg_FF6B35 br4 fz11 fwb5">{{ fk.DELETE_RULE || 'RESTRICT' }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 fz13 tac c-p bg_hover_f0f0f0" @click="__editForeignKeyField(i, 'update_rule')">
                                    <div v-if="SHOW.editingForeignKeyField === `${i}-update_rule`">
                                        <select 
                                            v-model="FORM.v[`fk-${i}-update_rule`]"
                                            @change="__saveForeignKeyField(i, 'update_rule')"
                                            @blur="SHOW.editingForeignKeyField = null"
                                            class="w100p p5 bd_007BFF br4 fz13"
                                            :ref="el => { if (el && SHOW.editingForeignKeyField === `${i}-update_rule`) (el as HTMLSelectElement).focus(); }"
                                        >
                                            <option value="RESTRICT">RESTRICT</option>
                                            <option value="CASCADE">CASCADE</option>
                                            <option value="SET NULL">SET NULL</option>
                                            <option value="NO ACTION">NO ACTION</option>
                                        </select>
                                    </div>
                                    <span v-else class="dib pt3 pb3 pl8 pr8 c_fff bg_6F42C1 br4 fz11 fwb5">{{ fk.UPDATE_RULE || 'RESTRICT' }}</span>
                                </td>
                                <td class="pt10 pb10 pl15 pr15 tac">
                                    <button 
                                        @click="__deleteForeignKey(fk)" 
                                        class="pt3 pb3 pl8 pr8 fz11 m-a fwb5 c_fff bg_DC3545 bd0 br4 c-p"
                                    >
                                        Deletar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div v-else class="pt25 p25 mt10 fz14 tac bg_fff br10">
                    Nenhuma chave estrangeira encontrada...
                </div>
            </div>
        <!-- FOREIGN KEYS -->


        <!-- TABLE -->
            <div class="pt30">
                <div class="fz16 fwb6 c_000">Colunas</div>
                <div class="pt10">
                    <table class="w100p bg_fff br10">
                        <!-- TABLE HEADER -->
                            <thead>
                                <tr class="bdb_E5E7EB">
                                    <th class="pt15 pb15 pl20 pr20 tal fz12 fwb5 ttu">Campo</th>
                                    <th class="pt15 pb15 pl20 pr20 tal fz12 fwb5 ttu">Tipo</th>
                                    <th class="pt15 pb15 pl20 pr20 tac fz12 fwb5 ttu">Chave</th>
                                    <th class="pt15 pb15 pl20 pr20 tac fz12 fwb5 ttu">Null</th>
                                    <th class="pt15 pb15 pl20 pr20 tac fz12 fwb5 ttu">Padrão</th>
                                    <th class="pt15 pb15 pl20 pr20 tar fz12 fwb5 ttu">Extra</th>
                                </tr>
                            </thead>
                        <!-- TABLE HEADER -->


                        <!-- TABLE BODY -->
                            <tbody>
                                <tr 
                                    v-for="(column, index) in OBJ.TABLE?.COLUMNS" 
                                    :key="index" 
                                    class="bdb_E5E7EB bg_hover_F8F9FA c-grab"
                                    :class="{
                                        'bg_E8F5E9': SHOW.draggedIndex_columns === index,
                                        'bdt_4CAF50 bdt3': SHOW.draggedOverIndex_columns === index && SHOW.draggedIndex_columns !== null && SHOW.draggedIndex_columns > index,
                                        'bdb_4CAF50 bdb3': SHOW.draggedOverIndex_columns === index && SHOW.draggedIndex_columns !== null && SHOW.draggedIndex_columns < index
                                    }"
                                    draggable="true"
                                    @dragstart="__dragStart($event, index)"
                                    @dragover="__dragOver($event, index)"
                                    @dragleave="__dragLeave()"
                                    @drop="__drop($event, index)"
                                    @dragend="__dragEnd()"
                                >
                                    <!-- FIELD COLUMN -->
                                        <td class="pt15 pb15 pl20 pr20 c_111B2B fz14 c-p bg_hover_f0f0f0" @click="__editField(index, 'Field')" style="position: relative;">
                                            <div v-if="SHOW.editingField_columns === `${index}-Field`" class="flexx flex_ac">
                                                <input 
                                                    type="text" 
                                                    v-model="FORM.v[`${index}-Field`]"
                                                    @keyup="__handleKeyup($event, index, 'Field')"
                                                    @blur="__cancelEdit"
                                                    class="w100p p5 bd_007BFF br4 fz14"
                                                    style="background: #fff; outline: none;"
                                                    :ref="el => { if (el && SHOW.editingField_columns === `${index}-Field`) (el as HTMLInputElement).focus(); }"
                                                />
                                            </div>
                                            <span v-else>{{ column.Field }}</span>
                                        </td>
                                    <!-- FIELD COLUMN -->


                                    <!-- TYPE COLUMN -->
                                        <td class="pt15 pb15 pl20 pr20 fz14 c-p bg_hover_f0f0f0" @click="__editField(index, 'Type')" style="position: relative;">
                                            <div v-if="SHOW.editingField_columns === `${index}-Type`">
                                                <select 
                                                    v-model="FORM.v[`${index}-Type`]"
                                                    @change="__saveField(index, 'Type')"
                                                    @blur="__cancelEdit"
                                                    class="w100p p5 bd_007BFF br4 fz14"
                                                    style="background: #fff; outline: none;"
                                                    :ref="el => { if (el && SHOW.editingField_columns === `${index}-Type`) (el as HTMLSelectElement).focus(); }"
                                                >
                                                    <option value="int">int</option>
                                                    <option value="bigint">bigint</option>
                                                    <option value="bigint unsigned">bigint unsigned</option>
                                                    <option value="decimal(20,2)">decimal(20,2)</option>
                                                    <option value="decimal(10,2)">decimal(10,2)</option>
                                                    <option value="date">date</option>
                                                    <option value="timestamp">timestamp</option>
                                                    <option value="varchar(6)">varchar(6)</option>
                                                    <option value="varchar(10)">varchar(10)</option>
                                                    <option value="varchar(20)">varchar(20)</option>
                                                    <option value="varchar(50)">varchar(50)</option>
                                                    <option value="varchar(100)">varchar(100)</option>
                                                    <option value="varchar(255)">varchar(255)</option>
                                                    <option value="text">text</option>
                                                    <option value="longtext">longtext</option>
                                                </select>
                                            </div>
                                            <span v-else>{{ column.Type }}</span>
                                        </td>
                                    <!-- TYPE COLUMN -->


                                    <!-- KEY COLUMN -->
                                        <td class="pt15 pb15 pl20 pr20 tac fz14 c-p bg_hover_f0f0f0" @click="__editField(index, 'Key')" style="position: relative;">
                                            <div v-if="SHOW.editingField_columns === `${index}-Key`">
                                                <select 
                                                    v-model="FORM.v[`${index}-Key`]"
                                                    @change="__saveField(index, 'Key')"
                                                    @blur="__cancelEdit"
                                                    class="w100p p5 bd_007BFF br4 fz14"
                                                    style="background: #fff; outline: none;"
                                                    :ref="el => { if (el && SHOW.editingField_columns === `${index}-Key`) (el as HTMLSelectElement).focus(); }"
                                                >
                                                    <option value="">Nenhuma</option>
                                                    <option value="PRI">PRIMARY</option>
                                                    <option value="UNI">UNIQUE</option>
                                                    <option value="MUL">INDEX</option>
                                                </select>
                                            </div>
                                            <div v-else>
                                                <span v-if="column.Key === 'PRI'" class="dib pt5 pb5 pl10 pr10 c_fff bg_007BFF br4 fz11 fwb5">PRIMARY</span>
                                                <span v-else-if="column.Key === 'UNI'" class="dib pt5 pb5 pl10 pr10 c_fff bg_FD7E14 br4 fz11 fwb5">UNIQUE</span>
                                                <span v-else-if="column.Key === 'MUL'" class="dib pt5 pb5 pl10 pr10 c_fff bg_6610F2 br4 fz11 fwb5">INDEX</span>
                                                <span v-else class="c_DEE2E6">-</span>
                                            </div>
                                        </td>
                                    <!-- KEY COLUMN -->


                                    <!-- NULL COLUMN -->
                                        <td class="pt15 pb15 pl20 pr20 tac fz14 c-p bg_hover_f0f0f0" @click="__editField(index, 'Null')" style="position: relative;">
                                            <div v-if="SHOW.editingField_columns === `${index}-Null`">
                                                <select 
                                                    v-model="FORM.v[`${index}-Null`]"
                                                    @change="__saveField(index, 'Null')"
                                                    @blur="__cancelEdit"
                                                    class="p5 bd_007BFF br4 fz14"
                                                    style="background: #fff; outline: none;"
                                                    :ref="el => { if (el && SHOW.editingField_columns === `${index}-Null`) (el as HTMLSelectElement).focus(); }"
                                                >
                                                    <option value="YES">SIM</option>
                                                    <option value="NO">NÃO</option>
                                                </select>
                                            </div>
                                            <div v-else>
                                                <span v-if="column.Null === 'YES'" class="c_10B759">SIM</span>
                                                <span v-else class="c_DC3545">NÃO</span>
                                            </div>
                                        </td>
                                    <!-- NULL COLUMN -->


                                    <!-- DEFAULT COLUMN -->
                                        <td class="pt15 pb15 pl20 pr20 tac fz14 c-p bg_hover_f0f0f0" @click="__editField(index, 'Default')" style="position: relative;">
                                            <div v-if="SHOW.editingField_columns === `${index}-Default`" class="flexx flex_ac">
                                                <input 
                                                    type="text" 
                                                    v-model="FORM.v[`${index}-Default`]"
                                                    @keyup="__handleKeyup($event, index, 'Default')"
                                                    @blur="__cancelEdit"
                                                    class="w100p p5 bd_007BFF br4 fz14"
                                                    style="background: #fff; outline: none;"
                                                    placeholder="NULL ou valor"
                                                    :ref="el => { if (el && SHOW.editingField_columns === `${index}-Default`) (el as HTMLInputElement).focus(); }"
                                                />
                                            </div>
                                            <div v-else>
                                                <span v-if="column.Default === null && column.Null === 'YES'" class="c_ADB5BD fsi">NULL</span>
                                                <span v-else-if="column.Default === null" class="c_DEE2E6">-</span>
                                                <span v-else>{{ column.Default }}</span>
                                            </div>
                                        </td>
                                    <!-- DEFAULT COLUMN -->


                                    <!-- EXTRA COLUMN -->
                                        <td class="pt15 pb15 pl20 pr20 tar fz14 c-p bg_hover_f0f0f0" @click="__editField(index, 'Extra')" style="position: relative;">
                                            <div v-if="SHOW.editingField_columns === `${index}-Extra`">
                                                <select 
                                                    v-model="FORM.v[`${index}-Extra`]"
                                                    @change="__saveField(index, 'Extra')"
                                                    @blur="__cancelEdit"
                                                    class="p5 bd_007BFF br4 fz14"
                                                    style="background: #fff; outline: none;"
                                                    :ref="el => { if (el && SHOW.editingField_columns === `${index}-Extra`) (el as HTMLSelectElement).focus(); }"
                                                >
                                                    <option value="">Nenhum</option>
                                                    <option value="auto_increment">AUTO INCREMENT</option>
                                                </select>
                                            </div>
                                            <div v-else>
                                                <span v-if="column.Extra === 'auto_increment'" class="dib pt5 pb5 pl10 pr10 c_fff bg_0DCAF0 br4 fz11 fwb5">AUTO INCREMENT</span>
                                                <span v-else-if="column.Extra" class="c_6C7781">{{ column.Extra }}</span>
                                                <span v-else class="c_DEE2E6">-</span>
                                            </div>
                                        </td>
                                    <!-- EXTRA COLUMN -->
                                </tr>
                            </tbody>
                        <!-- TABLE BODY -->
                    </table>
                </div>
            </div>
        <!-- TABLE -->


        <!-- FOOTER SUMMARY -->
            <div class="pt15 pb15 pl20 pr20 bg_fff flexx flex_j">
                <div class="fz13">{{ OBJ.TABLE?.COLUMNS?.length || 0 }} colunas no total</div>
            </div>
        <!-- FOOTER SUMMARY -->
    </div>

</template>


<style scoped>
    /* EDIT ICON ON HOVER */
        td.c-p:hover::after {
            content: '✏️';
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.5;
            font-size: 12px;
        }
    /* EDIT ICON ON HOVER */
    
    
    /* FOREIGN KEY EDIT STYLES */
        .c-p {
            position: relative;
        }
    /* FOREIGN KEY EDIT STYLES */


    /* DRAG AND DROP STYLES */
        .c-grab {
            cursor: grab;
        }

        .c-grab:active {
            cursor: grabbing;
        }

        /* BORDER CLASSES FOR DRAG INDICATOR */
        .bdt3 {
            border-top-width: 3px !important;
        }

        .bdb3 {
            border-bottom-width: 3px !important;
        }

        /* DRAGGING OPACITY */
        tr[draggable="true"]:active {
            opacity: 0.5;
        }
    /* DRAG AND DROP STYLES */
</style>