<script setup lang="ts">
import SELECT__ from '@/vendor/components/input/select.vue';
import TEXTAREA__ from '@/vendor/components/input/textarea.vue';

import api from '@/vendor/services/api';
import { date__, img, MOBILE, month, open__, phone_complete, price, refresh } from '@/vendor/services/events';
import { DIR_P } from '@/vendor/services/localhost';
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
        const __submit_status = () => {
            FORM.v.id = OBJ.VALUE?.id;
            FORM.v._method = 'PUT';
            api(`/admin/orders_actions/status`, FORM, (json: any) => {
                refresh();
            });
        }
        const __submit_status_delete = (id: any, key: any) => {
            FORM.v.order = OBJ.VALUE?.id;
            FORM.v.id = id;
            FORM.v.key = key;
            FORM.v._method = 'PUT';
            if(confirm(`Deseja realmente excluir este status?`)){
                api(`/admin/orders_actions/status_delete`, FORM, (json: any) => {
                    refresh();
                });
            }
        }
        const __submit_tracking = () => {
            FORM.v.id = OBJ.VALUE?.id;
            FORM.v._method = 'PUT';
            api(`/admin/orders_actions/tracking`, FORM, (json: any) => {
                refresh();
            });
        }

        const __attempts_validate = (value: any) => {
            if((value.status == 3 || value.status == 4 || value.status == 5) && Array.isArray(OBJ?.VALUE?.attempts)){
                return true;
            }
            return false;
        }
    // FUNCTIONS
</script>


<template>

    <div class="pedidos_edit">

        <div class="FONT_Poppins">

            <!-- TOP -->
                <div class="flexx_1000 flex_j flex_ac">
                    <div class="pr20 fz24 flexx flex_j flex_ac p0_1000">
                        <a @click="open__(`/modules/${OBJ.menu_admin.id}`, SHOW.GET?.['items__'] ? { items__: SHOW.GET['items__'] } : {}, 1);" class="w16 mr10 c_555">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="currentColor" d="M14.75 8a.75.75 0 0 1-.75.75h-9.69l2.72 2.72a.749.749 0 1 1-1.06 1.06l-4-4a.747.747 0 0 1 0-1.06l4-4a.749.749 0 1 1 1.06 1.06l-2.72 2.72h9.69a.75.75 0 0 1 .75.75"></path></svg>
                        </a>

                        <div class=""><span class="fwb6">Pedido</span> #{{ OBJ.VALUE?.id }}</div>
                    </div>
                    <div class="fz14 pt10_1000">
                        <div class="">Data do pedido:</div>
                        <div class="fwb limit">{{ date__(OBJ.VALUE?.created_at, 'd') }} de {{ month(date__(OBJ.VALUE?.created_at, 'm')) }} de {{ date__(OBJ.VALUE?.created_at, 'Y') }}</div>
                    </div>
                </div>
            <!-- TOP -->


            <div class="pt20">

                <!-- INFOS -->
                    <div class="p10 bd_ddd bg_fff br5">
                        <div class="flexx_1000 flex_j">
                            <div class="flex_1 p10 pb30">
                                <div class="">
                                    <div class="fz14 lh24 fwb5">Dados do Cliente:</div>
                                    <div class="pt10 fz13 lh22 c_666">
                                        <div class="">Nome: {{ OBJ.VALUE?.json?.CART?.users?.name }}</div>
                                        <div v-if="OBJ.VALUE?.json?.CART?.users?.cpf">CPF: {{ OBJ.VALUE?.json?.CART?.users?.cpf }}</div>
                                        <div v-if="OBJ.VALUE?.json?.CART?.users?.cnpj">CNPJ: {{ OBJ.VALUE?.json?.CART?.users?.cnpj  }}</div>
                                        <div v-if="OBJ.VALUE?.json?.CART?.users?.birth">Nascimento: {{ OBJ.VALUE?.json?.CART?.users?.birth  }}</div>
                                        <div v-if="OBJ.VALUE?.json?.CART?.users?.email">Email: {{ OBJ.VALUE?.json?.CART?.users?.email  }}</div>
                                        <div v-if="OBJ.VALUE?.json?.CART?.users?.phone">
                                            Telefone: {{ OBJ.VALUE?.json?.CART?.users?.phone }}
                                            <a :href="`https://api.whatsapp.com/send?phone=${phone_complete(OBJ.VALUE?.json?.CART?.users?.phone)}`" class="dib vam c_green" target="_blank"><i class="faa-whatsapp fz20 ml5"></i></a>
                                        </div>
                                        <div class="">Cadastado em: {{ date__(OBJ.VALUE?.json?.CART?.users?.created_at) }}</div>
                                        <div class="">Compras Finalizados: {{ OBJ.VALUE?.QUERY?.orders_statistics?.count }} ({{ price(OBJ.VALUE?.QUERY?.orders_statistics?.price ?? 0) }})</div>
                                        <div class="">Compras Total: {{ OBJ.VALUE?.QUERY?.orders_statistics_all?.count }} ({{ price(OBJ.VALUE?.QUERY?.orders_statistics_all?.price ?? 0) }})</div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_1 p10 pb30">
                                <!-- PAGAMENTO -->
                                    <div class="">
                                        <div class="fz14 lh24 fwb5">Forma de Pagamento:</div>
                                        <div class="pt10 fz13 lh22 c_666">
                                            <!-- BOLETO -->
                                                <div v-if="OBJ.VALUE?.pay_method==`boleto`">
                                                    <img src="@/vendor/assets/img/svg/pay/boleto.svg" class="dib max-w50 vam" height="25">
                                                    <div class="pt5">Pagamento no Boleto</div>
                                                    <div class="">{{ price(OBJ.VALUE?.total) }}</div>
                                                </div>
                                            <!-- BOLETO -->

                                            <!-- PIX -->
                                                <div v-else-if="OBJ.VALUE?.pay_method==`pix`">
                                                    <img src="@/vendor/assets/img/svg/pay/pix__.svg" class="dib max-w50 vam" height="35">
                                                    <div class="pt5">Pagamento no Pix</div>
                                                    <div class="">{{ price(OBJ.VALUE?.total) }}</div>
                                                </div>
                                            <!-- PIX -->

                                            <!-- CARD_CREDIT -->
                                                <div v-else-if="OBJ.VALUE?.pay_method==`card_credit`">
                                                    <img v-if="OBJ.VALUE?.pay_brand" :src="`${DIR_P()}/img/default/pay/card-${OBJ.VALUE?.pay_brand}.svg`" class="dib max-w50 vam" height="30">
                                                    <div class="pt5">
                                                        <div v-if="OBJ.VALUE?.pay_last_four">Cartão com Final {{ OBJ.VALUE?.pay_last_four }}</div>
                                                        <div class="">{{ price(OBJ.VALUE?.total) }} ({{ OBJ.VALUE?.installments }}x)</div>
                                                    </div>
                                                </div>
                                            <!-- CARD_CREDIT -->
                                            
                                            <!-- STATUS -->
                                                <div class="dib p5 pl20 pr20 br10" :style="`color: #fff; background: ${OBJ.VALUE?.QUERY?.orders_status?.[OBJ.VALUE?.status]?.color_1 ? `linear-gradient(135deg, ${OBJ.VALUE?.QUERY?.orders_status?.[OBJ.VALUE?.status]?.color}, ${OBJ.VALUE?.QUERY?.orders_status?.[OBJ.VALUE?.status]?.color_1})` : OBJ.VALUE?.QUERY?.orders_status?.[OBJ.VALUE?.status]?.color}`">
                                                    <div class="fz13 fwb5">{{ OBJ.VALUE?.QUERY?.orders_status?.[OBJ.VALUE?.status]?.name }}</div>
                                                </div>
                                                <div v-if="OBJ.VALUE?.status_description_customer" class="pt5 fz11">{{ OBJ.VALUE?.status_description_customer }}</div>
                                            <!-- STATUS -->
                                        </div>
                                    </div>
                                <!-- PAGAMENTO -->

                                <!-- ENTREGA -->
                                    <div class="pt20">
                                        <div class="fz14 lh24 fwb5">Dados da Entrega:</div>
                                        <div class="pt10 fz13 lh22 c_666">
                                            <div class="">Destinatário: {{ OBJ.VALUE?.json?.CART?.address?.name }}</div>
                                            <div class="">{{ OBJ.VALUE?.json?.CART?.address?.address }}{{ OBJ.VALUE?.json?.CART?.address?.number ? `, ${OBJ.VALUE?.json?.CART?.address?.number}` : `` }} {{ OBJ.VALUE?.json?.CART?.address?.complement }} </div>
                                            <div class="">{{ OBJ.VALUE?.json?.CART?.address?.neighborhood ? `${OBJ.VALUE?.json?.CART?.address?.neighborhood} - ` : `` }}{{ OBJ.VALUE?.json?.CART?.address?.city }}/{{ OBJ.VALUE?.json?.CART?.address?.state }}</div>
                                            <div class="">CEP: {{ OBJ.VALUE?.json?.CART?.address?.zipcode }}</div>
                                            <div v-if="OBJ.VALUE?.json?.CART?.shipping?.dias">Em até {{ OBJ.VALUE?.json?.CART?.shipping?.dias }} dias úteis</div>
                                        </div>
                                    </div>
                                <!-- ENTREGA -->
                            </div>
                            <div class="flex_1 p10 pb30">
                                <div class="fz14 lh24 fwb5">Valores do Pedido:</div>
                                <div class="pt10 fz13 lh22 c_666">
                                    <div class="">Subtotal: {{ price(OBJ.VALUE?.subtotal) }}</div>
                                    <div class="">
                                        Frete:
                                        <span v-html="OBJ.VALUE?.shipping>0 ? price(OBJ.VALUE?.shipping) : `<span class='fwb6 c_green'>Grátis</span>`"></span>
                                        ({{ OBJ.VALUE?.shipping_name }} / {{ OBJ.VALUE?.json?.CART?.shipping?.api }})
                                    </div>
                                    <div v-if="OBJ.VALUE?.credit>0" class="">Crédito: {{ price(OBJ.VALUE?.credit) }}</div>
                                    <div v-if="OBJ.VALUE?.discount>0" class="">Desconto: {{ price(OBJ.VALUE?.discount) }}</div>
                                    <div v-if="OBJ.VALUE?.rate>0" class="">Taxa: {{ price(OBJ.VALUE?.rate) }}</div>
                                    <div v-if="OBJ.VALUE?.fees>0" class="">Juros: {{ price(OBJ.VALUE?.fees) }}</div>
                                    <div v-if="OBJ.VALUE?.installments_fees>0" class="">Juros: {{ price(OBJ.VALUE?.installments_fees) }}</div>
                                    <div class="pt5 fz16 fwb6">Total: <span class="c_green">{{ price(OBJ.VALUE?.total) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- INFOS -->


                <!-- ACTIONS -->
                    <div class="p10 mt20 bd_ddd bg_fff br5">
                        <div class="flexx_1000 flex_j">
                            <!-- STATUS DA COMPRA -->
                                <div class="flex_1 p10">
                                    <div class="fz14 fwb5">Status da Compra</div>
                                    <div class="pt10">
                                        <div class="p10 bd_ddd bg_f4f4f4 br10">
                                            <ul class="">
                                                <li v-for="(value, key) in OBJ?.VALUE?.QUERY?.orders_status_history" class="flexx_1000 flex_ac gap_10" :class="`${key ? `bdt_eee` : ``} ${MOBILE() ? `pt10 pb10` : `pt2 pb2`}`">
                                                    <div class="p5 tal">{{ date__(value.status_date, `d/m/Y H:i`) }}</div>
                                                    <div class="flex_1 p5 tal" 
                                                        :tooltip="(__attempts_validate(value) ? OBJ?.VALUE.attempts.map((msg: any) => `<div>${msg}</div>`).join('') : '')" >
                                                        <i class="mr5 fz14"
                                                            :class="OBJ.VALUE?.QUERY?.orders_status?.[value.status]?.icon"
                                                            :style="`background: ${OBJ.VALUE?.QUERY?.orders_status?.[value.status]?.color_1 ? `linear-gradient(135deg, ${OBJ.VALUE?.QUERY?.orders_status?.[value.status]?.color}, ${OBJ.VALUE?.QUERY?.orders_status?.[value.status]?.color_1})` : OBJ.VALUE?.QUERY?.orders_status?.[value.status]?.color}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;`"></i>
                                                        {{ OBJ.VALUE?.QUERY?.orders_status?.[value.status]?.name }}
                                                        {{ __attempts_validate(value) ? `**` : `` }}
                                                        <span v-if="value.status==4 && value.status_users" class="fwb6 c_red">*Precisa fazer o estorno no {{ OBJ.VALUE?.gateway }}</span>
                                                    </div>
                                                    <div class="p5" :class="MOBILE() ? `` : `tar`">
                                                        <div class="dib vam" :tooltip="(value?.status_description_admin || value?.status_description_customer) ? `<div style='min-width: 200px !important; max-width: 400px !important;'><div>${value.status_description_admin ? `Observação interna: ${value.status_description_admin}` : ``}</div><div class=pt5>${value?.status_description_customer ? `Observação par ao cliente: ${value?.status_description_customer}` : ``}</div></div>` : ``">
                                                            {{ (value.status_users && OBJ.VALUE?.QUERY?.users[value?.status_users]?.name) ? OBJ.VALUE?.QUERY.users[value.status_users].name : `(automático)` }}
                                                            {{ (value?.status_description_admin || value?.status_description_customer) ? `**` : `` }}
                                                        </div>
                                                        <a @click="__submit_status_delete(value.id, key)" class="dib pb1 ml10 fz15 vam"><i class="faa-times-circle c_red"></i></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <form @submit.prevent="__submit_status()">
                                            <div class="pt10"><SELECT__ name="status" class="design" :options="OBJ.VALUE?.QUERY?.orders_status__" required /></div>
                                            <div class="pt8"><TEXTAREA__ name="status_description_admin" class="w100p h60 p5 design" placeholder="Observação interna (Máximo de 150 caracteres)" /></div>
                                            <div class="pt5"><TEXTAREA__ name="status_description_customer" class="w100p h60 p5 design" placeholder="Observação para o cliente (Máximo de 150 caracteres)" /></div>
                                            <div class="pt5"><button class="button_admin_1"> <i class="mr5 faa-check"></i> Salvar</button></div>
                                        </form>
                                    </div>
                                </div>
                            <!-- STATUS DA COMPRA -->

                            <div class="w40"></div>

                            <!-- INFORMACOES DO RASTREAMENTO -->
                                <div class="flex_1 p10">
                                    <div class="fz14 fwb5">Rastreamento</div>
                                    <div class="pt10">
                                        <form @submit.prevent="__submit_tracking()">
                                            <TEXTAREA__ name="tracking" :value="OBJ.VALUE?.tracking" class="w100p h100 mt5 p5 design" placeholder="Código de Rastreio" required />
                                            <div class="pt5"><button class="button_admin_1"> <i class="mr5 faa-check"></i> Salvar</button></div>
                                        </form>
                                    </div>
                                </div>
                            <!-- INFORMACOES DO RASTREAMENTO -->
                        </div>
                    </div>
                <!-- ACTIONS -->


                <!-- PRODUCTS -->
                    <div class="p10 mt20 bd_ddd bg_fff br5">
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
                                    <tr v-for="value in OBJ.VALUE?.json?.CART?.items">
                                        <td class="tal">
                                            <div class="min-w200 flexx flex_ac">
                                                <div v-if="value.image__" class="w64 tac" v-html="img(value, 64, 64)"></div>
                                                <div class="flex_1 pl10">
                                                    <div class="fz14">{{ value.name }}</div>
                                                    <div class="pt5 fz12 c_666">Cod.: {{ value.code }}</div>
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

</template>


<style>
    .__CREATE_EDIT__61__ .centerr_admin { width: 100% !important; }
    .__CREATE_EDIT__61__ .__CREATE_EDIT__TOP__ { display: none !important; }
</style>