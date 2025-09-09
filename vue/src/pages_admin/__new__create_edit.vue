<script setup lang="ts">
import { inject, defineAsyncComponent, markRaw } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            type?: String,
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // FUNCTIONS
        const get_dynamic_component = (type: string, vue: string) => {
            const componentName = `create_edit__${type}__${vue}`;
            let componentLoader: () => Promise<any>;
            
            try {
                componentLoader = () => import(`./new/${componentName}.vue`);
                return markRaw(defineAsyncComponent(componentLoader));
            } catch (error) {
                return null;
            }
        };
    // FUNCTIONS
</script>


<template>

    <div v-for="(value, key) in OBJ?.menu_admin?.resources?.html_create_edit || []" :key="key">
        <component 
            :is="get_dynamic_component(PROPS.type, value.vue)" 
            v-if="value.type == PROPS.type" 
        />
    </div>

</template>


<style>
</style>