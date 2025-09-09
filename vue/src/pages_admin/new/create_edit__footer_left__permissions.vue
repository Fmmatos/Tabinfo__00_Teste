<script setup lang="ts">
import CHECKBOX__ from '@/vendor/components/input/checkbox.vue';
import SELECT__ from '@/vendor/components/input/select.vue';

import { json_decode } from '@/vendor/services/events';
import { inject } from 'vue';

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

    // FUNCTIONS
        const __menu_side = () => {
            let array: any[] = [];
            for (const [key, value] of Object.entries(OBJ.menu_side) as [string, { id: number; submenu: any[] }][]) {
                if(value && value?.id){
                    array.push(value);
                    if(value?.submenu){
                        array.push(...value.submenu);
                    }
                }
            }
            return array;
        }
    // FUNCTIONS
</script>


<template>

    <slot v-if="OBJ?.VALUE?.id != 1 && OBJ?.VALUE?.id != 2">
        <div v-if="OBJ?.user?.id == 1 || OBJ?.user?.id == 2" class="wr3 p10 users_permissions">
            <SELECT__ label="Acesso Total" name="permissions_all" class="design" :value="OBJ.VALUE.permissions_all" no_sel="true" extra="|->>0: Não; 1: Sim" />
        </div>
        
        <div v-if="FORM.v.permissions_all != 1" class="wr12 p10 users_permissions">
            <CHECKBOX__ label="Permissões" name="permissions" :value="(json_decode(OBJ.VALUE.permissions) as object)" :options="__menu_side()" />
        </div>
    </slot>

</template>


<style>
</style>