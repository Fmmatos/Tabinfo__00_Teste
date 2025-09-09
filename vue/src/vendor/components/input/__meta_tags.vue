<script setup lang="ts">
import { tags } from '@/vendor/services/tags';
import { inject, reactive, onBeforeMount } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.description = 0;
    // SHOW

    // ONBEFOREMOUNT
        onBeforeMount(() => {
            for(let key in OBJ.menu_admin_input){
                let value = OBJ.menu_admin_input[key];

                // if (PROPS.name == 'description'){
                //     SHOW.description = 1;
                // }
            }
        });
    // ONBEFOREMOUNT

</script>


<template>

    <!-- <li class="wr12">
        <div class="mt10 bd_ccc br10">
            <div class="pt10 pb5 pl10 pr10 fz16 fwb5">Meta Tags</div>
            <div>
                <label class="db p10">
                    <span class="db pb2">
                        Titulo da Página:
                    </span>
                    <div class="__input__">
                        <input :type="text" name="meta_name" v-model="FORM.v.meta_name" class="design" maxlength="70" tooltip_focus="Máximo 70 caracteres" />
                        <div class="pt2">* Se não Preenchar este Campo o Titulo da Página será o Nome deste Item</div>
                    </div>
                </label>
                <label v-if="SHOW.description" class="db p10">
                    <span class="db pb2">
                        Descrição da Página:
                    </span>
                    <div class="__input__">
                        <textarea name="meta_description" v-model="FORM.v.meta_description" class="design h150" maxlength="160" tooltip_focus="Máximo 160 caracteres" ></textarea>
                        <div class="pt2">* Se não Preenchar este Campo o Descrição da Página será o Descrição deste Item</div>
                    </div>
                </label>
            </div>
        </div>
    </li> -->


</template>


<style scoped>
    input[readonly] { background: #eee; }
    input[disabled] { background: #eee; }
</style>