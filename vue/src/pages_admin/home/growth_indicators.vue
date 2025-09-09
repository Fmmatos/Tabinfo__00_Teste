<script setup lang="ts">
import { inject, onMounted } from 'vue';
import Chart from 'chart.js/auto';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // EVENTS
        // ONMOUNTED
            onMounted(() => {
                setTimeout(() => {
                    __initGrowthCharts();
                }, 500);
            });
        // ONMOUNTED
    // EVENTS

    // FUNCTIONS
        const __initGrowthCharts = () => {
            // LINE CHART - GROWTH OVERVIEW
                if(OBJ.growth_chart?.labels) {
                    const ctx = document.getElementById('growthChart') as any;
                    if(ctx) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: OBJ.growth_chart.labels,
                                datasets: [
                                    {
                                        label: 'Novos Cadastros',
                                        data: OBJ.growth_chart.registrations,
                                        borderColor: '#52c41a',
                                        backgroundColor: 'rgba(82, 196, 26, 0.1)',
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Usuários Ativados',
                                        data: OBJ.growth_chart.activations,
                                        borderColor: '#1890ff',
                                        backgroundColor: 'rgba(24, 144, 255, 0.1)',
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Indicações Aprovadas',
                                        data: OBJ.growth_chart.indications,
                                        borderColor: '#722ed1',
                                        backgroundColor: 'rgba(114, 46, 209, 0.1)',
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            // LINE CHART
        };
    // FUNCTIONS
</script>


<template>

    <!-- GROWTH SUMMARY -->
        <div v-if="OBJ.growth" class="pl30 pr30 pb30">
            <div class="flexx_x gap_20">
                
                <!-- NEW REGISTRATIONS CARD -->
                    <div class="flex_1 min-w300 p25 bg_fff br20 shadow">
                        <div class="flexx flex_j flex_ac">
                            <div class="flex_1">
                                <div class="fz13 c_666 fwb5">Novos Cadastros</div>
                                <div class="pt10 fz32 fwb7 c_000">
                                    {{ OBJ.growth?.[SHOW.tab_period]?.new_registrations || 0 }}
                                </div>
                                <div class="pt5 fz14 c_888">{{ SHOW.tab_period == 'day' ? 'Hoje' : SHOW.tab_period == 'week' ? 'Esta semana' : 'Este mês' }}</div>
                            </div>
                            <div class="w60 h60 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #52c41a 0%, #237804 100%);">
                                <i class="faa-user-plus fz24 c_fff"></i>
                            </div>
                        </div>
                    </div>
                <!-- NEW REGISTRATIONS CARD -->


                <!-- ACTIVATION RATE CARD -->
                    <div class="flex_1 min-w300 p25 bg_fff br20 shadow">
                        <div class="flexx flex_j flex_ac">
                            <div class="flex_1">
                                <div class="fz13 c_666 fwb5">Taxa de Ativação</div>
                                <div class="pt10 fz32 fwb7 c_000">
                                    {{ OBJ.growth?.[SHOW.tab_period]?.activation_rate || '0%' }}
                                </div>
                                <div class="pt5 fz14 c_888">{{ OBJ.growth?.[SHOW.tab_period]?.activated_users || 0 }} usuários ativos</div>
                            </div>
                            <div class="w60 h60 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #1890ff 0%, #003a8c 100%);">
                                <i class="faa-bolt fz24 c_fff"></i>
                            </div>
                        </div>
                    </div>
                <!-- ACTIVATION RATE CARD -->


                <!-- APPROVED INDICATIONS CARD -->
                    <div class="flex_1 min-w300 p25 bg_fff br20 shadow">
                        <div class="flexx flex_j flex_ac">
                            <div class="flex_1">
                                <div class="fz13 c_666 fwb5">Indicações Aprovadas</div>
                                <div class="pt10 fz32 fwb7 c_000">
                                    {{ OBJ.growth?.[SHOW.tab_period]?.approved_indications || 0 }}
                                </div>
                                <div class="pt5 fz14 c_888">Taxa: {{ OBJ.growth?.[SHOW.tab_period]?.indication_rate || '0%' }}</div>
                            </div>
                            <div class="w60 h60 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #722ed1 0%, #391085 100%);">
                                <i class="faa-share-square fz24 c_fff"></i>
                            </div>
                        </div>
                    </div>
                <!-- APPROVED INDICATIONS CARD -->

            </div>
        </div>
    <!-- GROWTH SUMMARY -->


    <!-- GROWTH DETAILS -->
        <div v-if="OBJ.growth" class="pl30 pr30 pb30">
            <div class="p25 bg_fff br20 shadow">
                <h2 class="fz18 fwb6 c_000 pb20">Detalhamento do Crescimento</h2>
                
                <div class="flexx_x gap_20">
                    <!-- REGISTRATIONS BY PERIOD -->
                        <div class="flex_1 min-w350 p20 br15 bg_f8f9fa">
                            <div class="flexx flex_ac mb15">
                                <div class="w40 h40 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #52c41a 0%, #237804 100%);">
                                    <i class="faa-calendar-check-o fz18 c_fff"></i>
                                </div>
                                <span class="pl10 fz14 fwb6 c_333">Cadastros por Período</span>
                            </div>
                            <div class="mt15">
                                <div class="flexx flex_j mb10">
                                    <span class="fz13 c_666">Hoje</span>
                                    <span class="fz14 fwb6 c_000">{{ OBJ.growth?.day?.new_registrations || 0 }}</span>
                                </div>
                                <div class="flexx flex_j mb10">
                                    <span class="fz13 c_666">Esta Semana</span>
                                    <span class="fz14 fwb6 c_000">{{ OBJ.growth?.week?.new_registrations || 0 }}</span>
                                </div>
                                <div class="flexx flex_j">
                                    <span class="fz13 c_666">Este Mês</span>
                                    <span class="fz14 fwb6 c_000">{{ OBJ.growth?.month?.new_registrations || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    <!-- REGISTRATIONS BY PERIOD -->

                    <!-- ACTIVATION METRICS -->
                        <div class="flex_1 min-w350 p20 br15 bg_f8f9fa">
                            <div class="flexx flex_ac mb15">
                                <div class="w40 h40 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #13c2c2 0%, #006d75 100%);">
                                    <i class="faa-line-chart fz18 c_fff"></i>
                                </div>
                                <span class="pl10 fz14 fwb6 c_333">Métricas de Ativação</span>
                            </div>
                            <div class="mt15">
                                <div class="flexx flex_j mb10">
                                    <span class="fz13 c_666">Primeira Compra</span>
                                    <span class="fz14 fwb6 c_52c41a">{{ OBJ.growth?.[SHOW.tab_period]?.first_sale_users || 0 }}</span>
                                </div>
                                <div class="flexx flex_j mb10">
                                    <span class="fz13 c_666">Primeira Indicação</span>
                                    <span class="fz14 fwb6 c_1890ff">{{ OBJ.growth?.[SHOW.tab_period]?.first_indication_users || 0 }}</span>
                                </div>
                                <div class="flexx flex_j">
                                    <span class="fz13 c_666">Total Ativados</span>
                                    <span class="fz14 fwb6 c_722ed1">{{ OBJ.growth?.[SHOW.tab_period]?.activated_users || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    <!-- ACTIVATION METRICS -->

                    <!-- INDICATION PERFORMANCE -->
                        <div class="flex_1 min-w350 p20 br15 bg_f8f9fa">
                            <div class="flexx flex_ac mb15">
                                <div class="w40 h40 flexx flex_c flex_ac br50p" style="background: linear-gradient(135deg, #fa8c16 0%, #ad4e00 100%);">
                                    <i class="faa-trophy fz18 c_fff"></i>
                                </div>
                                <span class="pl10 fz14 fwb6 c_333">Performance de Indicações</span>
                            </div>
                            <div class="mt15">
                                <div class="flexx flex_j mb10">
                                    <span class="fz13 c_666">Total Indicações</span>
                                    <span class="fz14 fwb6 c_000">{{ OBJ.growth?.[SHOW.tab_period]?.total_indications || 0 }}</span>
                                </div>
                                <div class="flexx flex_j mb10">
                                    <span class="fz13 c_666">Aprovadas</span>
                                    <span class="fz14 fwb6 c_52c41a">{{ OBJ.growth?.[SHOW.tab_period]?.approved_indications || 0 }}</span>
                                </div>
                                <div class="flexx flex_j">
                                    <span class="fz13 c_666">Taxa Conversão</span>
                                    <span class="fz14 fwb6" :class="parseFloat(OBJ.growth?.[SHOW.tab_period]?.indication_rate) > 50 ? 'c_52c41a' : 'c_fa8c16'">
                                        {{ OBJ.growth?.[SHOW.tab_period]?.indication_rate || '0%' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    <!-- INDICATION PERFORMANCE -->
                </div>

                <!-- GROWTH CHART -->
                    <div v-show="SHOW.tab_period == 'month' && OBJ.growth_chart">
                        <div class="pt30 flexx flex_c flex_ac">
                            <div class="w600 h350">
                                <canvas id="growthChart"></canvas>
                            </div>
                        </div>
                    </div>
                <!-- GROWTH CHART -->
            </div>
        </div>
    <!-- GROWTH DETAILS -->

</template>