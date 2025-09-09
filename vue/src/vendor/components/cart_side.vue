<script setup lang="ts">
import { cart_delete, cart_qty, img, open__ } from '../services/events';
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

    // EVENTS
    // EVENTS

    // FUNCTIONS
    // FUNCTIONS
</script>


<template>

    <div class="no-print __CART_SIDE__ right" :class="SHOW.CART_SIDE ? `ativo` : ``">
        <div class="">
            <div @click="SHOW.CART_SIDE=false" class="dv op0 posf t0 l0 z94 w100p h100p menuu_resp_fundo" style="background: rgba(0, 0, 0, .4);"></div>
            <div class="posf t0 z95 menuu_resp_principal">
                <div class="posf w350 h100p z1 bdr_eee bg_fff"></div>
                <div class="posf z2 w350 h100p">
                    <div class="posr p10 pl15 pr15 bdb_eee">
                        <div class="fz16 fwb5">Meu Carrinho</div>
                        <span @click="SHOW.CART_SIDE=false" class="posa t0 r0 z3 mt12 mr10 fz14 fwb c-p"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#202020"></path></svg></span>
                    </div>
                    <div class="posr z2 calch-100p_150 o-a pl15 pr15 mb--1">

                        <!-- CART -->
                            <div v-for="(value, key) in OBJ.CART?.items" class="pt15 pb15 bdb_EBEBEB">
                                <div class="posr">
                                    <div @click="cart_delete(value)" class="posa t0 r0 mt--5 c-p c_vermelho"><svg width="11" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.5 1.41L13.09 0L7.5 5.59L1.91 0L0.5 1.41L6.09 7L0.5 12.59L1.91 14L7.5 8.41L13.09 14L14.5 12.59L8.91 7L14.5 1.41Z" fill="#FF0000"></path></svg></div>
                                    <div class="flexx">
                                        <a @click="open__(`/product/${value.id}`, {}, 2)" class="db w100">
                                            <div v-html="img(value, 100, 100)"></div>
                                        </a>
                                        <div class="calc-100p_90 pl20 flex_1">
                                            <div class="mr20 fz14">{{ value.name }}</div>
                                            <div class="pt8 fz14 fwb6">R$&nbsp;{{ value.price }}</div>
                                        
                                            <div class="posr w120 mt8 tac bd_dadada br2 no_sel">
                                                <input type="text" name="qty" v-model="value.qty" class="calc-100p_54 h34 m-a fz14 tac op10 bd0i bg_fff no_sel" onclick="this.select()" disabled />
                                                <span @click="cart_qty(value, -1)" class="posa t0 l0 pt7 pb7 c-p"><span class="db h20 pt5 pl10 pr10 fz16 lh10 bdr_dadada c_666">-</span></span>
                                                <span @click="cart_qty(value, 1)" class="posa t0 r0 pt7 pb7 c-p"><span class="db h20 pt5 pl10 pr10 fz16 lh10 bdl_dadada c_666">+</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- CART -->

                        <!-- CART VAZIO -->
                            <div v-if="OBJ.CART?.items?.length==0" class="pt40 pb40 fz14 tac">Nenhum produto no carrinho...</div>
                        <!-- CART VAZIO -->

                    </div>
                    <div class="pt10 pb10 ml15 mr15 fz16 fwb6 flexx flex_j bdt_EBEBEB bdb_EBEBEB">
                        <div class="">Subtotal</div>
                        <div class="CC_subtotal">R$&nbsp;{{ OBJ.CART?.subtotal ? OBJ.CART?.subtotal : `0,00` }}</div>
                    </div>
                    <div class="pt10 pl15 pr15"> <a @click="open__('/cart', {}, 1)" aria-label="Carrinho Pagamento" class="db p10 pl40 pr40 fz15 fwb5 tac c_fff bd0 b_degrade_green br20"> <span class="dib vam">Ir Para o Carrinho</span> </a> </div>
                </div>
            </div>
        </div>
    </div>

</template>


<style>
</style>
