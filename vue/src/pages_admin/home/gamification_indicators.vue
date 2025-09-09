<script setup lang="ts">
import { inject, onMounted } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // EVENTS
        // ONMOUNTED
            onMounted(() => {
                // Inicialização se necessário
            });
        // ONMOUNTED
    // EVENTS

    // FUNCTIONS
        const __get_level_color = (level: string) => {
            const colors: Record<string, string> = {
                'Cristal': '#e8e8e8',
                'Safira': '#1890ff', 
                'Esmeralda': '#52c41a',
                'Rubi': '#f5222d',
                'Diamante': '#722ed1'
            };
            return colors[level] || '#cccccc';
        };

        const __get_level_icon = (level: string) => {
            const icons: Record<string, string> = {
                'Cristal': 'faa-diamond',
                'Safira': 'faa-star',
                'Esmeralda': 'faa-trophy',
                'Rubi': 'faa-fire',
                'Diamante': 'faa-star-o'
            };
            return icons[level] || 'faa-circle';
        };
    // FUNCTIONS
</script>


<template>

    <!-- GAMIFICATION INDICATORS MAIN -->
        <div v-if="OBJ.gamification" class="pl30 pr30 pb20">
            
            <!-- SECTION TITLE -->
                <div class="mb25">
                    <h2 class="fz20 fwb7 c_000 mb5">Indicadores de Gamificação</h2>
                    <div class="fz13 c_666">Sistema de níveis e ranking de performance dos afiliados</div>
                </div>
            <!-- SECTION TITLE -->

            <!-- LEVELS DISTRIBUTION -->
                <div class="mb30">
                    <div class="p25 bg_fff br15 shadow_light">
                        <div class="flexx flex_j flex_ac mb20">
                            <h3 class="fz16 fwb6 c_000">Distribuição por Níveis</h3>
                            <div class="flexx flex_ac gap_8">
                                <i class="faa-users fz12 c_999"></i>
                                <span class="fz11 c_999">Total: {{ (OBJ.gamification?.levels?.cristal || 0) + (OBJ.gamification?.levels?.safira || 0) + (OBJ.gamification?.levels?.esmeralda || 0) + (OBJ.gamification?.levels?.rubi || 0) + (OBJ.gamification?.levels?.diamante || 0) }} afiliados</span>
                            </div>
                        </div>

                        <div class="flexx_x gap_15">
                            
                            <!-- CRISTAL LEVEL -->
                                <div class="flex_1 p20 br10 bg_f8f9fa bdt3_e8e8e8">
                                    <div class="mb15">
                                        <div class="w40 h40 m-a flexx flex_c flex_ac br50p" style="background: #e8e8e8;">
                                            <i class="faa-diamond fz18 c_fff"></i>
                                        </div>
                                        <div class="pt10 fz14 fwb6 c_000 tac">Cristal</div>
                                        <div class="fz11 c_888 tac">Iniciante</div>
                                    </div>
                                    <div class="tac">
                                        <div class="fz28 fwb8 c_e8e8e8">{{ OBJ.gamification?.levels?.cristal || 0 }}</div>
                                        <div class="fz11 c_999">afiliados</div>
                                    </div>
                                </div>
                            <!-- CRISTAL LEVEL -->

                            <!-- SAFIRA LEVEL -->
                                <div class="flex_1 p20 br10 bg_f8f9fa bdt3_1890ff">
                                    <div class="mb15">
                                        <div class="w40 h40 m-a flexx flex_c flex_ac br50p" style="background: #1890ff;">
                                            <i class="faa-star fz18 c_fff"></i>
                                        </div>
                                        <div class="pt10 fz14 fwb6 c_000 tac">Safira</div>
                                        <div class="fz11 c_888 tac">Crescendo</div>
                                    </div>
                                    <div class="tac">
                                        <div class="fz28 fwb8 c_1890ff">{{ OBJ.gamification?.levels?.safira || 0 }}</div>
                                        <div class="fz11 c_999">afiliados</div>
                                    </div>
                                </div>
                            <!-- SAFIRA LEVEL -->

                            <!-- ESMERALDA LEVEL -->
                                <div class="flex_1 p20 br10 bg_f8f9fa bdt3_52c41a">
                                    <div class="mb15">
                                        <div class="w40 h40 m-a flexx flex_c flex_ac br50p" style="background: #52c41a;">
                                            <i class="faa-trophy fz18 c_fff"></i>
                                        </div>
                                        <div class="pt10 fz14 fwb6 c_000 tac">Esmeralda</div>
                                        <div class="fz11 c_888 tac">Experiente</div>
                                    </div>
                                    <div class="tac">
                                        <div class="fz28 fwb8 c_52c41a">{{ OBJ.gamification?.levels?.esmeralda || 0 }}</div>
                                        <div class="fz11 c_999">afiliados</div>
                                    </div>
                                </div>
                            <!-- ESMERALDA LEVEL -->

                            <!-- RUBI LEVEL -->
                                <div class="flex_1 p20 br10 bg_f8f9fa bdt3_f5222d">
                                    <div class="mb15">
                                        <div class="w40 h40 m-a flexx flex_c flex_ac br50p" style="background: #f5222d;">
                                            <i class="faa-fire fz18 c_fff"></i>
                                        </div>
                                        <div class="pt10 fz14 fwb6 c_000 tac">Rubi</div>
                                        <div class="fz11 c_888 tac">Avançado</div>
                                    </div>
                                    <div class="tac">
                                        <div class="fz28 fwb8 c_f5222d">{{ OBJ.gamification?.levels?.rubi || 0 }}</div>
                                        <div class="fz11 c_999">afiliados</div>
                                    </div>
                                </div>
                            <!-- RUBI LEVEL -->

                            <!-- DIAMANTE LEVEL -->
                                <div class="flex_1 p20 br10 bg_f8f9fa bdt3_722ed1">
                                    <div class="mb15">
                                        <div class="w40 h40 m-a flexx flex_c flex_ac br50p" style="background: #722ed1;">
                                            <i class="faa-star-o fz18 c_fff"></i>
                                        </div>
                                        <div class="pt10 fz14 fwb6 c_000 tac">Diamante</div>
                                        <div class="fz11 c_888 tac">Elite</div>
                                    </div>
                                    <div class="tac">
                                        <div class="fz28 fwb8 c_722ed1">{{ OBJ.gamification?.levels?.diamante || 0 }}</div>
                                        <div class="fz11 c_999">afiliados</div>
                                    </div>
                                </div>
                            <!-- DIAMANTE LEVEL -->

                        </div>
                    </div>
                </div>
            <!-- LEVELS DISTRIBUTION -->

            <!-- RANKINGS SECTION -->
                <div class="flexx_x gap_20">

                    <!-- RANKING POR PONTOS -->
                        <div class="flex_1 min-w300 p25 bg_fff br15 shadow_light">
                            <div class="flexx flex_ac mb20">
                                <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #fa8c16 0%, #ad4e00 100%);">
                                    <i class="faa-star fz18 c_fff"></i>
                                </div>
                                <div>
                                    <div class="fz15 fwb6 c_000">Ranking por Pontos</div>
                                    <div class="fz11 c_888">Top afiliados por pontuação</div>
                                </div>
                            </div>

                            <div v-if="OBJ.gamification?.rankings?.points?.length" class="ranking-list">
                                <div v-for="(affiliate, index) in OBJ.gamification.rankings.points.slice(0, 10)" :key="affiliate.id" 
                                     class="flexx flex_ac flex_j p12 mb8 br8 transition hover:bg_f8f9fa">
                                    <div class="flexx flex_ac">
                                        <div class="w24 h24 flexx flex_c flex_ac br50p mr12 fz12 fwb6"
                                             :class="index === 0 ? 'bg_fa8c16 c_fff' : index === 1 ? 'bg_13c2c2 c_fff' : index === 2 ? 'bg_52c41a c_fff' : 'bg_f0f0f0 c_666'">
                                            {{ index + 1 }}
                                        </div>
                                        <div>
                                            <div class="fz13 fwb6 c_000">{{ affiliate.name }}</div>
                                            <div class="fz11 c_888">{{ affiliate.level }}</div>
                                        </div>
                                    </div>
                                    <div class="fz14 fwb6 c_fa8c16">{{ affiliate.points || 0 }} pts</div>
                                </div>
                            </div>

                            <div v-else class="tac p20 c_999">
                                <i class="faa-star fz24 mb10"></i>
                                <div class="fz12">Nenhum dado disponível</div>
                            </div>
                        </div>
                    <!-- RANKING POR PONTOS -->

                    <!-- RANKING POR VENDAS -->
                        <div class="flex_1 min-w300 p25 bg_fff br15 shadow_light">
                            <div class="flexx flex_ac mb20">
                                <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #52c41a 0%, #237804 100%);">
                                    <i class="faa-shopping-cart fz18 c_fff"></i>
                                </div>
                                <div>
                                    <div class="fz15 fwb6 c_000">Ranking por Vendas</div>
                                    <div class="fz11 c_888">Top afiliados por receita</div>
                                </div>
                            </div>

                            <div v-if="OBJ.gamification?.rankings?.sales?.length" class="ranking-list">
                                <div v-for="(affiliate, index) in OBJ.gamification.rankings.sales.slice(0, 10)" :key="affiliate.id" 
                                     class="flexx flex_ac flex_j p12 mb8 br8 transition hover:bg_f8f9fa">
                                    <div class="flexx flex_ac">
                                        <div class="w24 h24 flexx flex_c flex_ac br50p mr12 fz12 fwb6"
                                             :class="index === 0 ? 'bg_52c41a c_fff' : index === 1 ? 'bg_13c2c2 c_fff' : index === 2 ? 'bg_fa8c16 c_fff' : 'bg_f0f0f0 c_666'">
                                            {{ index + 1 }}
                                        </div>
                                        <div>
                                            <div class="fz13 fwb6 c_000">{{ affiliate.name }}</div>
                                            <div class="fz11 c_888">{{ affiliate.level }}</div>
                                        </div>
                                    </div>
                                    <div class="fz14 fwb6 c_52c41a">R$ {{ (affiliate.sales || 0).toLocaleString('pt-BR') }}</div>
                                </div>
                            </div>

                            <div v-else class="tac p20 c_999">
                                <i class="faa-shopping-cart fz24 mb10"></i>
                                <div class="fz12">Nenhum dado disponível</div>
                            </div>
                        </div>
                    <!-- RANKING POR VENDAS -->

                    <!-- RANKING POR CONTEÚDO -->
                        <div class="flex_1 min-w300 p25 bg_fff br15 shadow_light">
                            <div class="flexx flex_ac mb20">
                                <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #1890ff 0%, #003a8c 100%);">
                                    <i class="faa-video-camera fz18 c_fff"></i>
                                </div>
                                <div>
                                    <div class="fz15 fwb6 c_000">Ranking por Conteúdo</div>
                                    <div class="fz11 c_888">Top criadores de conteúdo</div>
                                </div>
                            </div>

                            <div v-if="OBJ.gamification?.rankings?.content?.length" class="ranking-list">
                                <div v-for="(affiliate, index) in OBJ.gamification.rankings.content.slice(0, 10)" :key="affiliate.id" 
                                     class="flexx flex_ac flex_j p12 mb8 br8 transition hover:bg_f8f9fa">
                                    <div class="flexx flex_ac">
                                        <div class="w24 h24 flexx flex_c flex_ac br50p mr12 fz12 fwb6"
                                             :class="index === 0 ? 'bg_1890ff c_fff' : index === 1 ? 'bg_13c2c2 c_fff' : index === 2 ? 'bg_fa8c16 c_fff' : 'bg_f0f0f0 c_666'">
                                            {{ index + 1 }}
                                        </div>
                                        <div>
                                            <div class="fz13 fwb6 c_000">{{ affiliate.name }}</div>
                                            <div class="fz11 c_888">{{ affiliate.level }}</div>
                                        </div>
                                    </div>
                                    <div class="fz14 fwb6 c_1890ff">{{ affiliate.content_count || 0 }} vídeos</div>
                                </div>
                            </div>

                            <div v-else class="tac p20 c_999">
                                <i class="faa-video-camera fz24 mb10"></i>
                                <div class="fz12">Nenhum dado disponível</div>
                            </div>
                        </div>
                    <!-- RANKING POR CONTEÚDO -->

                    <!-- RANKING POR INDICAÇÕES -->
                        <div class="flex_1 min-w300 p25 bg_fff br15 shadow_light">
                            <div class="flexx flex_ac mb20">
                                <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #722ed1 0%, #391085 100%);">
                                    <i class="faa-share fz18 c_fff"></i>
                                </div>
                                <div>
                                    <div class="fz15 fwb6 c_000">Ranking por Indicações</div>
                                    <div class="fz11 c_888">Top indicadores da rede</div>
                                </div>
                            </div>

                            <div v-if="OBJ.gamification?.rankings?.indications?.length" class="ranking-list">
                                <div v-for="(affiliate, index) in OBJ.gamification.rankings.indications.slice(0, 10)" :key="affiliate.id" 
                                     class="flexx flex_ac flex_j p12 mb8 br8 transition hover:bg_f8f9fa">
                                    <div class="flexx flex_ac">
                                        <div class="w24 h24 flexx flex_c flex_ac br50p mr12 fz12 fwb6"
                                             :class="index === 0 ? 'bg_722ed1 c_fff' : index === 1 ? 'bg_13c2c2 c_fff' : index === 2 ? 'bg_fa8c16 c_fff' : 'bg_f0f0f0 c_666'">
                                            {{ index + 1 }}
                                        </div>
                                        <div>
                                            <div class="fz13 fwb6 c_000">{{ affiliate.name }}</div>
                                            <div class="fz11 c_888">{{ affiliate.level }}</div>
                                        </div>
                                    </div>
                                    <div class="fz14 fwb6 c_722ed1">{{ affiliate.indications || 0 }} indicações</div>
                                </div>
                            </div>

                            <div v-else class="tac p20 c_999">
                                <i class="faa-share fz24 mb10"></i>
                                <div class="fz12">Nenhum dado disponível</div>
                            </div>
                        </div>
                    <!-- RANKING POR INDICAÇÕES -->

                </div>
            <!-- RANKINGS SECTION -->

        </div>
    <!-- GAMIFICATION INDICATORS MAIN -->

</template>