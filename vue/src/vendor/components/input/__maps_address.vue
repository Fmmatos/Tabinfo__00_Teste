<script setup lang="ts">
import api from '@/vendor/services/api';
import { alerts } from '@/vendor/services/events';
import { inject, onBeforeMount, reactive } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS: any = defineProps<{
            value?: Object,
        }>();
    // PROPS

    // ONBEFOREMOUNT
        onBeforeMount(() => {
            FORM.v[PROPS.name] = ``;
        });
    // ONBEFOREMOUNT

    // FUNCTIONS
        const __keypress = () => {
            if (e.keyCode == 13){ // ENTER
                e.preventDefault();

                __google_maps__search_address('google_maps_lat', 'google_maps_lng');
            }
        }

        const __google_maps__search_address = async ($lat, $lng) => {
            let $return = ``;

            let response = await api(`/admin/google_maps/search_address`, { address: FORM.v.google_maps_address });

            if (response.results[0]?.geometry?.location?.lat != null && response.results[0]?.geometry?.location?.lng != null){
                FORM.v[$lat] = response.results[0].geometry.location.lat;
                FORM.v[$lng] = response.results[0].geometry.location.lng;

                setTimeout(() => {
                    let event = document.createEvent("MouseEvents");
                    event.initEvent("click", true, false);
                    document.querySelector(`.__SALVE__`).dispatchEvent(event);
                }, 50);

            } else {
                alerts(0, 'Endereço Não Encontrado! <br> Tente colocar assim: Nome da Rua Número Cidade Estado! <br> Exemplo: Rua X 95 Belo Horizonte MG');
            }
        }
    // FUNCTIONS
</script>


<template>

    <slot>
        <label class="db">
            <span v-if="PROPS.label" class="db pb2">
                {{ PROPS.label }}:
            </span>

            <div class="__input__">
                <div class="flexx">
                    <input :type="value.type" :name="PROPS.name" v-model="FORM.v[PROPS.name]" class="design brl8" @keypress="__keypress()" placeholder="Digite o endereço e clique em buscar para encontrar a latitude e longitude..." />
                    <button type="button" @click="__google_maps__search_address('google_maps_lat', 'google_maps_lng')" class="button_3 c_fff b_blue brr8 __google_maps__search_address__">Buscar</button>
                </div>
                <div class="pt5 lh18">
                    <div class="">Digite apenas o nome da rua, número, cidade, estado e cep do local desejado (Não coloque o bairro e não digite o número assim "<u>Nº 95</u>"; digite assim "<u>95</u>" ou não digite o número).</div>
                    <div class="">Caso ainda não encontre, procure no site do <a href="http://maps.google.com.br/maps" target="_blank" class="c_blue link">Google Maps</a> e depois escreva aqui o endereço encontrado lá ou a latitude e longitude.</div>
                </div>
            </div>
        </label>
    </slot>

</template>


<style scoped>
    input[readonly] { background: #eee; }
    input[disabled] { background: #eee; }
</style>