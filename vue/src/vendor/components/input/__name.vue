<script setup lang="ts">
import { compare__, ex, extra__, limit } from '@/vendor/services/events';
import { inject, reactive } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);

        const TEMP: any = reactive({});
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            value?: Object,
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
        TEMP.class = PROPS.value.class;
    // EVENTS

    // FUNCTIONS
        const __new = () => {
            return compare__(`|->new`, PROPS.value.extra)
        }
    // FUNCTIONS
</script>


<template>

    <span v-if="PROPS.value.label" class="db pb3 flexx_x flex_ac">
        <div class="h18 o-h">{{ limit(PROPS.value.label, __new() ? 50 : 70) }}</div><div v-if="(PROPS.value.required!=undefined || PROPS.value.required_label!=undefined || compare__(`required`, PROPS.value.tags)) && PROPS.value.required_label_no==undefined" class="fz13 lh13 c_red">*</div><span v-if="PROPS.value.required_label_no_point==undefined">:</span>

        <div v-if="__new()" class="h18 pl10">
            <button @click="SHOW.iframe__ = (extra__(`|->new`, PROPS.value.extra)[1] ?? PROPS.value.name); SHOW.iframe_array = PROPS.value" type="button" class="h17 pt0 pb1 pl10 pr10 c_blue button_admin_2"><i class="mr4 faa-plus"></i> Novo</button>
        </div>
    </span>

</template>


<style scoped>

</style>