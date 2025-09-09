<script setup lang="ts">
import { inject, onMounted } from 'vue';
import { price } from '@/vendor/services/events';
import Chart from 'chart.js/auto';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // EVENTS
        // ONMOUNTED
            onMounted(() => {
                setTimeout(() => {
                    __initCommercialCharts();
                }, 500);
            });
        // ONMOUNTED
    // EVENTS

    // FUNCTIONS
        const __initCommercialCharts = () => {
            // PIE CHART - SALES BY ORIGIN
                if(OBJ.charts?.sales_by_origin) {
                    const ctx = document.getElementById('pieChartOrigins') as any;
                    if(ctx) {
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: OBJ.charts.sales_by_origin.name,
                                datasets: [{
                                    data: OBJ.charts.sales_by_origin.data,
                                    backgroundColor: OBJ.charts.sales_by_origin.colors.map((c: any) => c[0]),
                                    borderWidth: 2,
                                    borderColor: '#fff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.label + ': ' + price(context.raw as number);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            // PIE CHART
        };
    // FUNCTIONS
</script>


<template>

    <!-- SALES SUMMARY -->
        <div v-if="OBJ.sales" class="pl30 pr30 pb30">
            <div class="flexx_x gap_20">
                
                <!-- TOTAL SALES CARD -->
                    <div class="flex_1 min-w300 p25 bg_fff br20 shadow">
                        <div class="flexx flex_j flex_ac">
                            <div class="flex_1">
                                <div class="fz13 c_666 fwb5">Vendas Totais</div>
                                <div class="pt10 fz32 fwb7 c_000">
                                    {{ OBJ.sales?.[SHOW.tab_period]?.total?.price ? price(OBJ.sales[SHOW.tab_period].total.price) : 'R$ 0,00' }}
                                </div>
                                <div class="pt5 fz14 c_888">{{ OBJ.sales?.[SHOW.tab_period]?.total?.units || 0 }} vendas</div>
                            </div>
                            <div class="w60 h60 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #52c41a 0%, #237804 100%);">
                                <i class="faa-shopping-cart fz24 c_fff"></i>
                            </div>
                        </div>
                    </div>
                <!-- TOTAL SALES CARD -->


                <!-- AVERAGE TICKET CARD -->
                    <div class="flex_1 min-w300 p25 bg_fff br20 shadow">
                        <div class="flexx flex_j flex_ac">
                            <div class="flex_1">
                                <div class="fz13 c_666 fwb5">Ticket Médio</div>
                                <div class="pt10 fz32 fwb7 c_000">
                                    {{ OBJ.average_ticket?.formatted || 'R$ 0,00' }}
                                </div>
                                <div class="pt5 fz14 c_888">No mês atual</div>
                            </div>
                            <div class="w60 h60 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #1890ff 0%, #003a8c 100%);">
                                <i class="faa-dollar fz24 c_fff"></i>
                            </div>
                        </div>
                    </div>
                <!-- AVERAGE TICKET CARD -->

            </div>
        </div>
    <!-- SALES SUMMARY -->


    <!-- SALES BY ORIGIN -->
        <div v-if="OBJ.sales" class="pl30 pr30 pb30">
            <div class="p25 bg_fff br20 shadow">
                <h2 class="fz18 fwb6 c_000 pb20">Detalhamento por Origem</h2>
                
                <div class="flexx_x gap_20">
                    <!-- INDICATIONS -->
                        <div class="flex_1 min-w250 p20 br15 bg_f8f9fa">
                            <div class="flexx flex_ac mb10">
                                <div class="w40 h40 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #722ed1 0%, #391085 100%);">
                                    <i class="faa-users fz18 c_fff"></i>
                                </div>
                                <span class="pl10 fz14 fwb6 c_333">Indicações</span>
                            </div>
                            <div class="pt10 fz24 fwb7 c_000">{{ OBJ.sales?.[SHOW.tab_period]?.origins?.indications?.price ? price(OBJ.sales[SHOW.tab_period].origins.indications.price) : 'R$ 0,00' }}</div>
                            <div class="pt5 fz13 c_666">{{ OBJ.sales?.[SHOW.tab_period]?.origins?.indications?.units || 0 }} vendas</div>
                        </div>
                    <!-- INDICATIONS -->

                    <!-- INTERNAL STORE -->
                        <div class="flex_1 min-w250 p20 br15 bg_f8f9fa">
                            <div class="flexx flex_ac mb10">
                                <div class="w40 h40 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #13c2c2 0%, #006d75 100%);">
                                    <i class="faa-shopping-bag fz18 c_fff"></i>
                                </div>
                                <span class="pl10 fz14 fwb6 c_333">Loja Interna</span>
                            </div>
                            <div class="pt10 fz24 fwb7 c_000">{{ OBJ.sales?.[SHOW.tab_period]?.origins?.internal_store?.price ? price(OBJ.sales[SHOW.tab_period].origins.internal_store.price) : 'R$ 0,00' }}</div>
                            <div class="pt5 fz13 c_666">{{ OBJ.sales?.[SHOW.tab_period]?.origins?.internal_store?.units || 0 }} vendas</div>
                        </div>
                    <!-- INTERNAL STORE -->

                    <!-- LINK/COUPON -->
                        <div class="flex_1 min-w250 p20 br15 bg_f8f9fa">
                            <div class="flexx flex_ac mb10">
                                <div class="w40 h40 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #fa8c16 0%, #ad4e00 100%);">
                                    <i class="faa-link fz18 c_fff"></i>
                                </div>
                                <span class="pl10 fz14 fwb6 c_333">Link/Cupom</span>
                            </div>
                            <div class="pt10 fz24 fwb7 c_000">{{ OBJ.sales?.[SHOW.tab_period]?.origins?.link_coupon?.price ? price(OBJ.sales[SHOW.tab_period].origins.link_coupon.price) : 'R$ 0,00' }}</div>
                            <div class="pt5 fz13 c_666">{{ OBJ.sales?.[SHOW.tab_period]?.origins?.link_coupon?.units || 0 }} vendas</div>
                        </div>
                    <!-- LINK/COUPON -->
                </div>

                <!-- PIE CHART -->
                    <div v-show="SHOW.tab_period == 'month'">
                        <div class="pt30 flexx flex_c flex_ac">
                            <div class="w400 h300">
                                <canvas id="pieChartOrigins"></canvas>
                            </div>
                        </div>
                    </div>
                <!-- PIE CHART -->
            </div>
        </div>
    <!-- SALES BY ORIGIN -->


    <!-- WITHDRAWALS -->
        <div v-if="OBJ.withdrawals" class="pl30 pr30 pb30">
            
            <!-- WITHDRAWALS CARD -->
                <div class="mb20 p25 bg_fff br20 shadow">
                    <div class="flexx flex_j flex_ac">
                        <div class="flex_1">
                            <div class="fz13 c_666 fwb5">Saques Solicitados</div>
                            <div class="pt10 fz32 fwb7 c_000">
                                {{ OBJ.withdrawals?.[SHOW.tab_period]?.requested?.count || 0 }}
                            </div>
                            <div class="pt5 fz14 c_888">
                                Total: {{ OBJ.withdrawals?.[SHOW.tab_period]?.requested?.total ? price(OBJ.withdrawals[SHOW.tab_period].requested.total) : 'R$ 0,00' }}
                            </div>
                        </div>
                        <div class="w60 h60 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #f5222d 0%, #a8071a 100%);">
                            <i class="faa-money fz24 c_fff"></i>
                        </div>
                    </div>
                </div>
            <!-- WITHDRAWALS CARD -->

            <!-- WITHDRAWALS LIST -->
                <div class="p25 bg_fff br20 shadow">
                    <h2 class="fz18 fwb6 c_000 pb20">Próximos Saques</h2>
                    
                    <div v-if="OBJ.withdrawals?.pending_list?.length">
                        <div v-for="withdrawal in OBJ.withdrawals.pending_list" :key="withdrawal.id" 
                             class="p15 mb12 br12 bg_f8f9fa flexx flex_j flex_ac">
                            
                            <!-- USER INFO -->
                                <div class="flexx flex_ac">
                                    <div class="w40 h40 flexx flex_c flex_ac br50p bg_d9d9d9 mr15 fz14 fwb6 c_666">
                                        {{ withdrawal.user_name?.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="fz14 fwb6 c_000">{{ withdrawal.user_name }}</div>
                                        <div class="fz12 c_888">{{ withdrawal.method }}</div>
                                    </div>
                                </div>
                            <!-- USER INFO -->

                            <!-- AMOUNT -->
                                <div class="tac">
                                    <div class="fz16 fwb7 c_000">{{ price(withdrawal.amount) }}</div>
                                    <div class="fz11 c_888">Solicitado {{ withdrawal.requested_date }}</div>
                                </div>
                            <!-- AMOUNT -->

                            <!-- DEADLINE -->
                                <div class="tac">
                                    <div class="fz13 fwb6" :class="withdrawal.days_left <= 1 ? 'c_f5222d' : withdrawal.days_left <= 3 ? 'c_fa8c16' : 'c_52c41a'">
                                        {{ withdrawal.days_left <= 0 ? 'Prazo vencido' : withdrawal.days_left == 1 ? 'Falta 1 dia' : `Faltam ${withdrawal.days_left} dias` }}
                                    </div>
                                    <div class="fz11 c_888">{{ withdrawal.deadline_date }}</div>
                                </div>
                            <!-- DEADLINE -->

                            <!-- ACTION -->
                                <div>
                                    <button class="p8 pl15 pr15 fz12 fwb6 br8 transition c_fff" 
                                            :class="withdrawal.days_left <= 1 ? 'bg_f5222d hover:bg_a8071a' : 'bg_1890ff hover:bg_003a8c'">
                                        <i class="faa-external-link mr5"></i> Processar
                                    </button>
                                </div>
                            <!-- ACTION -->

                        </div>
                    </div>

                    <!-- EMPTY STATE -->
                        <div v-else class="tac p40 c_999">
                            <i class="faa-money fz48 mb15"></i>
                            <div class="fz16 fwb6 mb5">Nenhum saque pendente</div>
                            <div class="fz13">Não há solicitações de saque no período</div>
                        </div>
                    <!-- EMPTY STATE -->

                </div>
            <!-- WITHDRAWALS LIST -->

        </div>
    <!-- WITHDRAWALS -->




    <!-- TOP PRODUCTS -->
        <div v-if="OBJ.top_products" class="pl30 pr30 pb30">
            <div class="flexx_x gap_20">
                
                <!-- BY REVENUE -->
                    <div class="flex_1 min-w500 p25 bg_fff br20 shadow">
                        <h2 class="fz18 fwb6 c_000 pb20">Top Produtos por Receita</h2>
                        <div class="table_1">
                            <table class="w100p">
                                <thead>
                                    <tr>
                                        <th class="tal">#</th>
                                        <th class="tal">Produto</th>
                                        <th class="tar">Receita</th>
                                        <th class="tar">Qtd</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in OBJ.top_products.by_revenue" :key="item.products">
                                        <td class="fwb6">{{ index + 1 }}º</td>
                                        <td class="limit">{{ item.name }}</td>
                                        <td class="tar fwb6" :style="index == 0 ? 'color: #66d976' : ''">{{ price(item.revenue) }}</td>
                                        <td class="tar">{{ item.units }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- BY REVENUE -->

                <!-- BY VOLUME -->
                    <div class="flex_1 min-w500 p25 bg_fff br20 shadow">
                        <h2 class="fz18 fwb6 c_000 pb20">Top Produtos por Volume</h2>
                        <div class="table_1">
                            <table class="w100p">
                                <thead>
                                    <tr>
                                        <th class="tal">#</th>
                                        <th class="tal">Produto</th>
                                        <th class="tar">Qtd</th>
                                        <th class="tar">Receita</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in OBJ.top_products.by_volume" :key="item.products">
                                        <td class="fwb6">{{ index + 1 }}º</td>
                                        <td class="limit">{{ item.name }}</td>
                                        <td class="tar fwb6" :style="index == 0 ? 'color: #5c8fee' : ''">{{ item.units }}</td>
                                        <td class="tar">{{ price(item.revenue) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- BY VOLUME -->

            </div>
        </div>
    <!-- TOP PRODUCTS -->

</template>