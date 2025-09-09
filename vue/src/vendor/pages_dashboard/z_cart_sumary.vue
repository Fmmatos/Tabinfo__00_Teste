<script setup lang="ts">
import { cart_delete, cart_qty, count__, img, MOBILE, open__, tooltip } from '@/vendor/services/events';
import { inject } from 'vue';
tooltip();

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.resumo = false;
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <div class="flex_4">
        <!-- MOBILE -->
            <div v-if="!SHOW.resumo && MOBILE()" class="pb40">
                <a @click="SHOW.resumo = true" class="p20 flexx flex_j flex_ac bg_D9D9D9 br10">
                    <div class="flex_1 fz18 fwb6">
                        <div class="">Resumo ({{ count__(OBJ.CART?.items) }})</div>
                        <div class="pt5 fz12 fwb4">Informações da sua compra</div>
                    </div>
                    <div class="pl10 pr10 fz18 fwb7 c_green">R$&nbsp;{{ OBJ.CART?.total ? OBJ.CART.total : `0,00` }}</div>
                    <div class=""><i class="fz24 fwb6 faa-angle-down"></i></div>
                </a>
            </div>
        <!-- MOBILE -->


        <div v-if="SHOW.resumo || !MOBILE()" class="bdb_E2E8F0 bg_fff br10 shadow" :class="MOBILE() ? `p20 mb40` : `p30 pl20 pr20 mb40`">

            <!-- MOBILE -->
                <div v-if="MOBILE()">
                    <a @click="SHOW.resumo = false" class="flexx flex_j flex_ac">
                        <div class="fz18 fwb6">Resumo ({{ count__(OBJ.CART?.items) }})</div>
                        <div class=""><i class="fz24 fwb6 faa-angle-up"></i></div>
                    </a>
                </div>
            <!-- MOBILE -->





            <!-- RESUMO -->
                <!-- DESK -->
                    <div v-if="!MOBILE()">
                        <div class="pb15 fz18 fwb6">Resumo</div>
                        <div class="p15 pt20 mt5 mb10 fz13 bg_F4F4F4 br5">
                            <div class="flexx flex_j">
                                <div class="fwb5">Produtos</div>
                                <div class="">R$&nbsp;{{ OBJ.CART?.subtotal ? OBJ.CART.subtotal : `0,00` }}</div>
                            </div>
                            <div class="pt15 flexx flex_j">
                                <div class="fwb5">Entrega</div>
                                <div v-if="OBJ.CART?.shipping?.price__ && OBJ.CART.shipping.price__>0">{{ `${OBJ.CART?.shipping?.name ? `(${OBJ.CART?.shipping?.name})` : ``} R$&nbsp;${OBJ.CART.shipping.price}` }}</div>
                                <div v-else-if="OBJ.CART?.shipping?.price__">{{ `${OBJ.CART?.shipping?.name ? `(${OBJ.CART?.shipping?.name})` : ``}` }} <span class="fwb6 c_green">Grátis</span></div>
                                <div v-else>- - - - -</div>
                            </div>
                            <div v-if="OBJ.CART?.credit__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Crédito</div>
                                    <div class="c_green">R$&nbsp;{{ OBJ.CART.credit }}</div>
                                </div>
                            </div>
                            <div v-if="OBJ.CART?.discount__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Desconto</div>
                                    <div class="c_green">R$&nbsp;{{ OBJ.CART.discount }}</div>
                                </div>
                            </div>
                            <div v-if="OBJ.CART?.rates__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Taxa</div>
                                    <div class="c_red">R$&nbsp;{{ OBJ.CART.rates }}</div>
                                </div>
                            </div>
                            <div v-if="OBJ.CART?.fees__ > 0" class="pt15 dni">
                                <div class="flexx flex_j">
                                    <div class="">Juros</div>
                                    <div class="c_red">R$&nbsp;{{ OBJ.CART.fees }}</div>
                                </div>
                            </div>
                            <div class="pt15 pb10 mt15 fwb6 flexx flex_j c_green bdt_EBEBEB">
                                <div class="">Total</div>
                                <!-- <div class="">{{ OBJ.CART.fees__x>1 ? `(${OBJ.CART.fees__x}x) ` : `` }}R$&nbsp;{{ OBJ.CART?.total ? OBJ.CART.total : `0,00` }}</div> -->
                                <div class="">R$&nbsp;{{ OBJ.CART?.total ? OBJ.CART.total : `0,00` }}</div>
                            </div>
                        </div>
                    </div>
                <!-- DESK -->
            <!-- RESUMO -->





            <!-- PRODUCTS -->
                <div class="pt10">
                    <div v-for="(value, key) in OBJ.CART?.items" class="pt15 pb15" :class="key ? `bdt_eee` : ``">
                        <div class="posr">
                            <div @click="cart_delete(value)" class="posa t0 r0 c-p c_vermelho"><svg width="11" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.5 1.41L13.09 0L7.5 5.59L1.91 0L0.5 1.41L6.09 7L0.5 12.59L1.91 14L7.5 8.41L13.09 14L14.5 12.59L8.91 7L14.5 1.41Z" fill="#FF0000"></path></svg></div>
                            <div class="flexx flex_c flex_ac">
                                <a @click="open__(`/product/${value.id}`, {}, 2)" class="w80 tac">
                                    <div v-html="img(value, 80, 80, 'br10')"></div>
                                </a>
                                <div class="pl15 flex_1">
                                    <div class="flexx flex_j flex_ac">
                                        <div class="flex_1">
                                            <div class="fz12">{{ value.name }}</div>
                                            <div class="pt5 fz12 fwb6">R$&nbsp;{{ value.price }}</div>
                                        </div>
                                        
                                        <div class="posr w70 tac no_sel">
                                            <input type="text" name="qty" v-model="value.qty" class="calc-100p_54 h22 m-a fz14 tac op10 bd0i bg_fff no_sel" onclick="this.select()" disabled />
                                            <span @click="cart_qty(value, -1)" class="posa t0 l0 w22 h22 fz17 c-p flexx flex_c flex_ac bd_aaa br50p"><span class="db">-</span></span>
                                            <span @click="cart_qty(value, 1)" class="posa t0 r0 w22 h22 fz17 c-p flexx flex_c flex_ac c_fff bd_aaa bg_0000005F br50p"><span class="db">+</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- PRODUCTS -->





            <!-- RESUMO -->
                <!-- MOBILE -->
                    <div v-if="MOBILE()">
                        <div class="p15 pt20 mt5 mb10 fz13 bg_F4F4F4 br5">
                            <div class="flexx flex_j">
                                <div class="fwb5">Produtos</div>
                                <div class="">R$&nbsp;{{ OBJ.CART?.subtotal ? OBJ.CART.subtotal : `0,00` }}</div>
                            </div>
                            <div class="pt15 flexx flex_j">
                                <div class="fwb5">Entrega</div>
                                <div v-if="OBJ.CART?.shipping?.price__ && OBJ.CART.shipping.price__>0">{{ `${OBJ.CART?.shipping?.name ? `(${OBJ.CART?.shipping?.name})` : ``} R$&nbsp;${OBJ.CART.shipping.price}` }}</div>
                                <div v-else-if="OBJ.CART?.shipping?.price__">{{ `${OBJ.CART?.shipping?.name ? `(${OBJ.CART?.shipping?.name})` : ``}` }} <span class="fwb6 c_green">Grátis</span></div>
                                <div v-else>- - - - -</div>
                            </div>
                            <div v-if="OBJ.CART?.credit__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Crédito</div>
                                    <div class="c_green">R$&nbsp;{{ OBJ.CART.credit }}</div>
                                </div>
                            </div>
                            <div v-if="OBJ.CART?.discount__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Desconto</div>
                                    <div class="c_green">R$&nbsp;{{ OBJ.CART.discount }}</div>
                                </div>
                            </div>
                            <div v-if="OBJ.CART?.rates__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Taxa</div>
                                    <div class="c_red">R$&nbsp;{{ OBJ.CART.rates }}</div>
                                </div>
                            </div>
                            <div v-if="OBJ.CART?.fees__ > 0" class="pt15">
                                <div class="flexx flex_j">
                                    <div class="">Juros</div>
                                    <div class="c_red">R$&nbsp;{{ OBJ.CART.fees }}</div>
                                </div>
                            </div>
                            <div class="pt15 pb10 mt15 fwb6 flexx flex_j c_green bdt_EBEBEB">
                                <div class="">Total</div>
                                <div class="">{{ OBJ.CART.fees__x>1 ? `(${OBJ.CART.fees__x}x) ` : `` }}R$&nbsp;{{ OBJ.CART?.total ? OBJ.CART.total : `0,00` }}</div>
                            </div>
                        </div>
                    </div>
                <!-- MOBILE -->
            <!-- RESUMO -->
            
        </div>
    </div>

</template>


<style scoped>
</style>
