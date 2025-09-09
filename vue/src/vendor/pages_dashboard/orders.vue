<script setup lang="ts">
import { count, date__, img, MOBILE, month, open__, phone_complete, price } from '@/vendor/services/events';
import { DIR_P } from '@/vendor/services/localhost';
import { inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.orders_mobile = $_GET['2'] ? true : false;
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

    <div class="">

        <div class="flexx">

            <!-- MENU -->
                <div v-if="MOBILE() ? !SHOW.orders_mobile : true" class="bg_fff" :class="MOBILE() ? `w100p` : `w380`">
                    <div class="posf w380 h100p bg_fff"></div>

                    <div class="posr z1">
                        <!-- TITTLE -->
                            <div class="p30 pl35 pr35 fz30 fwb8 bdb_E2E8F0 bg_fff">Minha Compras</div>
                        <!-- TITTLE -->

                        <ul>
                            <li v-for="(value, key) in OBJ.orders">
                                <a @click="open__(`/orders/${value.id}`, {}, 1)" class="db p20 pl40 pr40 bdb_ddd bg_hover_F7F7F7" :class="MOBILE() ? `` : value.id==OBJ?.order?.id ? `bg_F7F7F7` : ``">
                                    <div class="fz14 fwb5 lh21">Pedido: #{{ value.id }}</div>
                                    <div class="fz14 fwb5 lh24">Data: {{ date__(value.created_at, 'd') }} de {{ month(date__(value.created_at, 'm')) }} de {{ date__(value.created_at, 'Y') }} </div>
                                    <div class="fwb5">
                                        <div class="dib pr5 fz14 fwb5 lh21 vam">Valor: {{ price(value.total) }}</div>
                                        <img v-if="value.pay_method==`boleto`" :src="`${DIR_P()}/img/default/pay/boleto.svg`" class="dib max-w50 vam" height="25">
                                        <img v-if="value.pay_method==`pix`" :src="`${DIR_P()}/img/default/pay/pix__.svg`" class="dib max-w50 vam" height="30">
                                        <img v-if="value.pay_method==`card_credit` && value.pay_brand" :src="`${DIR_P()}/img/default/pay/card-${value.pay_brand}.svg`" class="dib max-w50 vam" height="30">
                                    </div>
                                    <div class="pt10 fwb5">
                                        <div class="dib p5 pl15 pr15 vam br10" :style="`color: #fff; background: ${OBJ.orders_status[value.status].color_1 ? `linear-gradient(30deg, ${OBJ.orders_status[value.status].color}, ${OBJ.orders_status[value.status].color_1})` : OBJ.orders_status[value.status].color}`">
                                            <div class="dib vam fz13 fwb5">{{ OBJ.orders_status[value.status].name }}</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <div v-if="!count(OBJ.orders)" class="p20 pt40 fz14 fwb5 tac">Nenhum pedido encontrado...</div>
                    </div>
                </div>
            <!-- MENU -->










            <!-- CONTEXT -->
                <div v-if="MOBILE() ? SHOW.orders_mobile : true" class="posr flex_1 o-h" :class="MOBILE() ? `` : `p30`">

                    <div v-if="OBJ?.order?.id">
                        <!-- <div class="pb20 tar no-print">
                            <a href="https://dashboard.useluminus.com.br/imprimir/orders/29" class="button_3 dib w-a pt5 pb5" target="_blank" aria-label="link">Imprimir pedido</a>
                        </div> -->

                        <div class="bg_fff" :class="MOBILE() ? `p20 pt20 pb40` : `p20 br20 shadow_1`">

                            <!-- TOP -->
                                <div class="flexx_1000 flex_j flex_ac">
                                    <div class="pr20 fz24 flexx flex_j flex_ac p0_1000">
                                        <div class=""><span class="fwb6">Pedido</span> #{{ OBJ.order.id }}</div>
                                        <a v-if="MOBILE()" @click="open__(`/orders`, {}, 1)" class="fz14" aria-label="link"><i class="faa-angle-left"></i> voltar</a>
                                    </div>
                                    <div class="pt10_1000">
                                        <div class="">Data do pedido:</div>
                                        <div class="fwb limit">{{ date__(OBJ.order.created_at, 'd') }} de {{ month(date__(OBJ.order.created_at, 'm')) }} de {{ date__(OBJ.order.created_at, 'Y') }}</div>
                                    </div>
                                </div>
                            <!-- TOP -->

                            <div class="pt20">

                                <!-- INFO -->
                                    <div class="p10 bd_ddd bg_F7F7F7 br5">
                                        <div class="flexx_1000 flex_j">
                                            <div class="flex_1 p10 pb30">
                                                <div class="">
                                                    <div class="fz14 lh24 fwb5">Dados do Cliente:</div>
                                                    <div class="pt10 fz13 lh22 cor_666">
                                                        <div class="">Nome: {{ OBJ.order?.json?.CART?.users?.name }}</div>
                                                        <div v-if="OBJ.order?.json?.CART?.users?.cpf">CPF: {{ OBJ.order?.json?.CART?.users?.cpf }}</div>
                                                        <div v-if="OBJ.order?.json?.CART?.users?.cnpj">CNPJ: {{ OBJ.order?.json?.CART?.users?.cnpj  }}</div>
                                                        <div v-if="OBJ.order?.json?.CART?.users?.birth">Nascimento: {{ OBJ.order?.json?.CART?.users?.birth  }}</div>
                                                        <div v-if="OBJ.order?.json?.CART?.users?.email">Email: {{ OBJ.order?.json?.CART?.users?.email  }}</div>
                                                        <div v-if="OBJ.order?.json?.CART?.users?.phone">
                                                            Telefone: {{ OBJ.order?.json?.CART?.users?.phone }}
                                                            <a :href="`https://api.whatsapp.com/send?phone=${phone_complete(OBJ.order?.json?.CART?.users?.phone)}`" class="dib vam c_green" target="_blank"><i class="faa-whatsapp fz20 ml5"></i></a>
                                                        </div>
                                                        <div class="">Cadastado em: {{ date__(OBJ.order?.json?.CART?.users?.created_at) }}</div>
                                                        <div class="">Compras Finalizados: {{ OBJ.orders_statistics.count }} ({{ price(OBJ.orders_statistics.price ?? 0) }})</div>
                                                        <div class="">Compras Total: {{ OBJ.orders_statistics_all.count }} ({{ price(OBJ.orders_statistics_all.price ?? 0) }})</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex_1 p10 pb30">
                                                <!-- PAGAMENTO -->
                                                    <div class="">
                                                        <div class="fz14 lh24 fwb5">Forma de Pagamento:</div>
                                                        <div class="pt10 fz13 lh22 cor_666">
                                                            <!-- BOLETO -->
                                                                <div v-if="OBJ.order.pay_method==`boleto`">
                                                                    <img :src="`${DIR_P()}/img/default/pay/boleto.svg`" class="dib max-w50 vam" height="25">
                                                                    <div class="pt5">Pagamento no Boleto</div>
                                                                    <div class="">{{ price(OBJ.order.total) }}</div>
                                                                </div>
                                                            <!-- BOLETO -->

                                                            <!-- PIX -->
                                                                <div v-else-if="OBJ.order.pay_method==`pix`">
                                                                    <img :src="`${DIR_P()}/img/default/pay/pix__.svg`" class="dib max-w50 vam" height="35">
                                                                    <div class="pt5">Pagamento no Pix</div>
                                                                    <div class="">{{ price(OBJ.order.total) }}</div>
                                                                </div>
                                                            <!-- PIX -->

                                                            <!-- CARD_CREDIT -->
                                                                <div v-else-if="OBJ.order.pay_method==`card_credit`">
                                                                    <img v-if="OBJ.order.pay_brand" :src="`${DIR_P()}/img/default/pay/card-${OBJ.order.pay_brand}.svg`" class="dib max-w50 vam" height="30">
                                                                    <div class="pt5">
                                                                        <div v-if="OBJ.order.pay_last_four">Cartão com Final {{ OBJ.order?.pay_last_four }}</div>
                                                                        <div class="">{{ price(OBJ.order.total) }} ({{ OBJ.order.installments }}x)</div>
                                                                    </div>
                                                                </div>
                                                            <!-- CARD_CREDIT -->

                                                            <!-- STATUS -->
                                                                <div class="dib p5 pl20 pr20 br10" :style="`color: #fff; background: ${OBJ.orders_status[OBJ.order.status].color_1 ? `linear-gradient(30deg, ${OBJ.orders_status[OBJ.order.status].color}, ${OBJ.orders_status[OBJ.order.status].color_1})` : OBJ.orders_status[OBJ.order.status].color}`">
                                                                    <div class="fz13 fwb5">{{ OBJ.orders_status[OBJ.order.status].name }}</div>
                                                                </div>
                                                                <div v-if="OBJ.order.status_description_customer" class="pt5 fz11">{{ OBJ.order.status_description_customer }}</div>
                                                            <!-- STATUS -->
                                                        </div>
                                                    </div>
                                                <!-- PAGAMENTO -->

                                                <!-- ENTREGA -->
                                                    <div class="pt20">
                                                        <div class="fz14 lh24 fwb5">Dados da Entrega:</div>
                                                        <div class="pt10 fz13 lh22 cor_666">
                                                            <div class="">Destinatário: {{ OBJ.order?.json?.CART?.address?.name }}</div>
                                                            <div class="">{{ OBJ.order?.json?.CART?.address?.address }}{{ OBJ.order?.json?.CART?.address?.number ? `, ${OBJ.order?.json?.CART?.address?.number}` : `` }} {{ OBJ.order?.json?.CART?.address?.complement }} </div>
                                                            <div class="">{{ OBJ.order?.json?.CART?.address?.neighborhood ? `${OBJ.order?.json?.CART?.address?.neighborhood} - ` : `` }}{{ OBJ.order?.json?.CART?.address?.city }}/{{ OBJ.order?.json?.CART?.address?.state }}</div>
                                                            <div class="">CEP: {{ OBJ.order?.json?.CART?.address?.zipcode }}</div>
                                                            <div v-if="OBJ.order.json.CART?.shipping?.dias">Em até {{ OBJ.order.json.CART.shipping.dias }} dias úteis</div>
                                                        </div>
                                                    </div>
                                                <!-- ENTREGA -->
                                            </div>
                                            <div class="flex_1 p10 pb30">
                                                <div class="fz14 lh24 fwb5">Valores do Pedido:</div>
                                                <div class="pt10 fz13 lh22 cor_666">
                                                    <div class="">Subtotal: {{ price(OBJ.order.subtotal) }}</div>
                                                    <div class="">
                                                        Frete: 
                                                        <span v-html="OBJ.order.shipping>0 ? price(OBJ.order.shipping) : `<span class='fwb6 c_green'>Grátis</span>`"></span>
                                                        ({{ OBJ.order.shipping_name }})
                                                    </div>
                                                    <div v-if="OBJ.order.credit>0" class="">Crédito: {{ price(OBJ.order.credit) }}</div>
                                                    <div v-if="OBJ.order.discount>0" class="">Desconto: {{ price(OBJ.order.discount) }}</div>
                                                    <div v-if="OBJ.order.rate>0" class="">Taxa: {{ price(OBJ.order.rate) }}</div>
                                                    <div v-if="OBJ.order.fees>0" class="">Juros: {{ price(OBJ.order.fees) }}</div>
                                                    <div v-if="OBJ.order.installments_fees>0" class="">Juros: {{ price(OBJ.order.installments_fees) }}</div>
                                                    <div class="pt5 fz16 fwb6">Total: <span class="c_green">{{ price(OBJ.order.total) }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- INFO -->


                            <!-- PRODUCTS -->
                                <div class="pt40 pb40">
                                    <div class="tablee">
                                        <table class="w100p">
                                            <thead>
                                                <tr>
                                                    <th class="tal">Produtos</th>
                                                    <th class="tac">Preço</th>
                                                    <th class="tac">{{ MOBILE() ? `Qtd.` : `Quantidade` }}</th>
                                                    <th class="tac">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="value in OBJ.order?.json?.CART?.items">
                                                    <td class="tal">
                                                        <div class="min-w200 flexx flex_ac">
                                                            <div v-if="value.image__" class="w64 tac" v-html="img(value, 64, 64)"></div>
                                                            <div class="flex_1 pl10">
                                                                <div class="fz14">{{ value.name }}</div>
                                                                <div class="pt5 fz12 cor_666">Cod.: {{ value.code }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="min-w80 tac">R$&nbsp;{{ value.price }}</td>
                                                    <td class="min-w80 tac">{{ value.qty }}</td>
                                                    <td class="min-w80 tac">{{ price(value.price__*value.qty) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <!-- PRODUCTS -->

                        </div>
                    </div>
                </div>
            <!-- CONTEXT -->

        </div>
    </div>

</template>


<style scoped>
    .TD_1 {
        float: left;
        width: 50%;
    }

    .TD_2 {
        float: left;
        width: 16.66666666%;
        height: 64px;
    }

    .TD_3 {
        float: left;
        width: 16.66666666%;
        height: 64px;
    }

    .TD_4 {
        float: left;
        width: 16.66666666%;
        height: 64px;
    }

    @media screen AND (max-width: 1000px) {
        .TD_1 {
            float: left;
            width: 41.66666666%
        }

        .TD_2 {
            display: none;
        }

        .TD_3 {
            float: left;
            width: 25%;
        }

        .TD_4 {
            float: left;
            width: 33.33333333%;
        }
    }

    .orders_status_linha_do_tempo .barraa {
        background: #DCE6E3;
    }

    .orders_status_linha_do_tempo .faa-circle {
        display: inline-block;
    }

    .orders_status_linha_do_tempo .faa-check-circle {
        display: none;
    }

    .orders_status_linha_do_tempo .ativo {
        font-weight: 600;
    }

    .orders_status_linha_do_tempo .ativo .faa-circle {
        display: none;
    }

    .orders_status_linha_do_tempo .ativo .faa-check-circle {
        display: inline-block;
    }

    .orders_status_linha_do_tempo .ativo .barraa {
        background: #35AA47;
    }

    .barraa {
        height: 6px;
        margin-top: 13px;
    }

    .iconn {
        font-size: 30px;
    }

    @media screen AND (max-width: 1000px) {
        .barraa {
            height: 3px;
            margin-top: 10px;
        }

        .iconn {
            font-size: 20px;
        }
    }
</style>