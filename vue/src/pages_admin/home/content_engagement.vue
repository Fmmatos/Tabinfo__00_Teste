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
        const __get_status_color = (status: string) => {
            const colors: Record<string, string> = {
                'pendente': '#fa8c16',
                'aprovado': '#52c41a',
                'rejeitado': '#f5222d',
                'revisao': '#1890ff'
            };
            return colors[status] || '#666666';
        };

        const __get_status_text = (status: string) => {
            const texts: Record<string, string> = {
                'pendente': 'Pendente',
                'aprovado': 'Aprovado',
                'rejeitado': 'Rejeitado',
                'revisao': 'Em Revisão'
            };
            return texts[status] || 'Desconhecido';
        };

        const __visualizar_video = (video: any) => {
            // TODO: Implementar visualização do vídeo
            console.log('Visualizar vídeo:', video);
        };

        const __aprovar_video = (video: any) => {
            // TODO: Implementar aprovação do vídeo
            console.log('Aprovar vídeo:', video);
        };

        const __revisar_video = (video: any) => {
            // TODO: Implementar revisão do vídeo
            console.log('Revisar vídeo:', video);
        };
    // FUNCTIONS
</script>


<template>

    <!-- CONTENT ENGAGEMENT MAIN -->
        <div v-if="OBJ.content_engagement" class="pl30 pr30 pb20">
            
            <!-- SECTION TITLE -->
                <div class="mb25">
                    <h2 class="fz20 fwb7 c_000 mb5">Engajamento de Conteúdo</h2>
                    <div class="fz13 c_666">Gerenciamento de vídeos enviados pelos afiliados</div>
                </div>
            <!-- SECTION TITLE -->

            <!-- CONTENT STATS -->
                <div class="flexx_x gap_20 mb30">
                    
                    <!-- TOTAL VIDEOS -->
                        <div class="flex_1 min-w250 p20 bg_fff br15 shadow_light bdt3_1890ff">
                            <div class="flexx flex_ac flex_j">
                                <div class="flexx flex_ac">
                                    <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #1890ff 0%, #003a8c 100%);">
                                        <i class="faa-video-camera fz18 c_fff"></i>
                                    </div>
                                    <div>
                                        <div class="fz13 c_666 fwb6">TOTAL DE VÍDEOS</div>
                                        <div class="fz11 c_999">{{ SHOW.tab_period == 'day' ? 'Hoje' : SHOW.tab_period == 'week' ? 'Esta semana' : 'Este mês' }}</div>
                                    </div>
                                </div>
                                <div class="fz28 fwb8 c_1890ff">{{ OBJ.content_engagement?.stats?.total || 0 }}</div>
                            </div>
                        </div>
                    <!-- TOTAL VIDEOS -->

                    <!-- PENDING VIDEOS -->
                        <div class="flex_1 min-w250 p20 bg_fff br15 shadow_light bdt3_fa8c16">
                            <div class="flexx flex_ac flex_j">
                                <div class="flexx flex_ac">
                                    <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #fa8c16 0%, #ad4e00 100%);">
                                        <i class="faa-clock-o fz18 c_fff"></i>
                                    </div>
                                    <div>
                                        <div class="fz13 c_666 fwb6">PENDENTES</div>
                                        <div class="fz11 c_999">Aguardando análise</div>
                                    </div>
                                </div>
                                <div class="fz28 fwb8 c_fa8c16">{{ OBJ.content_engagement?.stats?.pending || 0 }}</div>
                            </div>
                        </div>
                    <!-- PENDING VIDEOS -->

                    <!-- APPROVED VIDEOS -->
                        <div class="flex_1 min-w250 p20 bg_fff br15 shadow_light bdt3_52c41a">
                            <div class="flexx flex_ac flex_j">
                                <div class="flexx flex_ac">
                                    <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #52c41a 0%, #237804 100%);">
                                        <i class="faa-check-circle fz18 c_fff"></i>
                                    </div>
                                    <div>
                                        <div class="fz13 c_666 fwb6">APROVADOS</div>
                                        <div class="fz11 c_999">Conteúdo validado</div>
                                    </div>
                                </div>
                                <div class="fz28 fwb8 c_52c41a">{{ OBJ.content_engagement?.stats?.approved || 0 }}</div>
                            </div>
                        </div>
                    <!-- APPROVED VIDEOS -->

                    <!-- REJECTED VIDEOS -->
                        <div class="flex_1 min-w250 p20 bg_fff br15 shadow_light bdt3_f5222d">
                            <div class="flexx flex_ac flex_j">
                                <div class="flexx flex_ac">
                                    <div class="w40 h40 flexx flex_c flex_ac br10 mr12" style="background: linear-gradient(135deg, #f5222d 0%, #a8071a 100%);">
                                        <i class="faa-times-circle fz18 c_fff"></i>
                                    </div>
                                    <div>
                                        <div class="fz13 c_666 fwb6">REJEITADOS</div>
                                        <div class="fz11 c_999">Necessitam ajustes</div>
                                    </div>
                                </div>
                                <div class="fz28 fwb8 c_f5222d">{{ OBJ.content_engagement?.stats?.rejected || 0 }}</div>
                            </div>
                        </div>
                    <!-- REJECTED VIDEOS -->

                </div>
            <!-- CONTENT STATS -->

            <!-- VIDEOS LISTING -->
                <div class="p25 bg_fff br15 shadow_light">
                    
                    <!-- LISTING HEADER -->
                        <div class="flexx flex_j flex_ac mb20 pb15 bdb_f0f0f0">
                            <h3 class="fz16 fwb6 c_000">Vídeos Enviados</h3>
                            <div class="flexx flex_ac gap_10">
                                <!-- STATUS FILTER -->
                                    <select class="p8 pl12 pr12 fz12 br8 bd_eee" v-model="SHOW.filter_status">
                                        <option value="">Todos os status</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="aprovado">Aprovado</option>
                                        <option value="rejeitado">Rejeitado</option>
                                        <option value="revisao">Em Revisão</option>
                                    </select>
                                <!-- STATUS FILTER -->
                                
                                <!-- SEARCH -->
                                    <input type="text" placeholder="Buscar afiliado..." class="p8 pl12 pr12 fz12 br8 bd_eee w200" v-model="SHOW.search_affiliate">
                                <!-- SEARCH -->
                            </div>
                        </div>
                    <!-- LISTING HEADER -->

                    <!-- VIDEOS TABLE -->
                        <table v-if="OBJ.content_engagement?.videos?.length" class="w100p">
                            
                            <!-- TABLE HEADER -->
                                <thead>
                                    <tr class="bdb_f0f0f0">
                                        <th class="p12 pb8 fz12 fwb6 c_666 tal">AFILIADO</th>
                                        <th class="p12 pb8 fz12 fwb6 c_666 tal">DATA ENVIO</th>
                                        <th class="p12 pb8 fz12 fwb6 c_666 tal">CAMPANHA</th>
                                        <th class="p12 pb8 fz12 fwb6 c_666 tal">MARCA</th>
                                        <th class="p12 pb8 fz12 fwb6 c_666 tal">STATUS</th>
                                        <th class="p12 pb8 fz12 fwb6 c_666 tal">AÇÕES</th>
                                    </tr>
                                </thead>
                            <!-- TABLE HEADER -->

                            <!-- VIDEO ROWS -->
                                <tbody>
                                    <tr v-for="video in OBJ.content_engagement.videos" :key="video.id" 
                                        class="transition hover:bg_f8f9fa">
                                        
                                        <!-- AFFILIATE INFO -->
                                            <td class="p12">
                                                <div class="flexx flex_ac">
                                                    <div class="w32 h32 flexx flex_c flex_ac br50p bg_f0f0f0 mr10 fz12 fwb6 c_666">
                                                        {{ video.affiliate_name?.charAt(0).toUpperCase() }}
                                                    </div>
                                                    <div>
                                                        <div class="fz13 fwb6 c_000">{{ video.affiliate_name }}</div>
                                                        <div class="fz11 c_888">{{ video.affiliate_level }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        <!-- AFFILIATE INFO -->

                                        <!-- SUBMISSION DATE -->
                                            <td class="p12">
                                                <div class="fz12 c_000">{{ video.submission_date }}</div>
                                                <div class="fz11 c_888">{{ video.submission_time }}</div>
                                            </td>
                                        <!-- SUBMISSION DATE -->

                                        <!-- CAMPAIGN -->
                                            <td class="p12">
                                                <div class="fz13 fwb6 c_000">{{ video.campaign_name }}</div>
                                                <div class="fz11 c_888">{{ video.campaign_type }}</div>
                                            </td>
                                        <!-- CAMPAIGN -->

                                        <!-- BRAND -->
                                            <td class="p12">
                                                <div class="fz13 c_000">{{ video.brand_name }}</div>
                                            </td>
                                        <!-- BRAND -->

                                        <!-- STATUS -->
                                            <td class="p12">
                                                <span class="pt4 pb4 pl8 pr8 fz11 fwb6 br12 c_fff"
                                                      :style="`background: ${__get_status_color(video.status)}`">
                                                    {{ __get_status_text(video.status) }}
                                                </span>
                                            </td>
                                        <!-- STATUS -->

                                        <!-- ACTIONS -->
                                            <td class="p12">
                                                <div class="flexx flex_ac gap_8">
                                                    <!-- VISUALIZAR -->
                                                        <!-- <button @click="__visualizar_video(video)" 
                                                                class="p6 pl10 pr10 fz11 fwb6 br6 transition bg_1890ff c_fff hover:bg_003a8c">
                                                            <i class="faa-eye mr4"></i> Ver
                                                        </button> -->
                                                    <!-- VISUALIZAR -->
                                                    
                                                    <!-- APROVAR -->
                                                        <!-- <button v-if="video.status === 'pendente' || video.status === 'revisao'"
                                                                @click="__aprovar_video(video)" 
                                                                class="p6 pl10 pr10 fz11 fwb6 br6 transition bg_52c41a c_fff hover:bg_237804">
                                                            <i class="faa-check mr4"></i> Aprovar
                                                        </button> -->
                                                    <!-- APROVAR -->
                                                    
                                                    <!-- REVISAR -->
                                                        <!-- <button @click="__revisar_video(video)" 
                                                                class="p6 pl10 pr10 fz11 fwb6 br6 transition bg_fa8c16 c_fff hover:bg_ad4e00">
                                                            <i class="faa-edit mr4"></i> Revisar
                                                        </button> -->
                                                    <!-- REVISAR -->
                                                </div>
                                            </td>
                                        <!-- ACTIONS -->

                                    </tr>
                                </tbody>
                            <!-- VIDEO ROWS -->

                        </table>
                    <!-- VIDEOS TABLE -->

                    <!-- EMPTY STATE -->
                        <div v-else class="tac p40 c_999">
                            <i class="faa-video-camera fz48 mb15"></i>
                            <div class="fz16 fwb6 mb5">Nenhum vídeo enviado</div>
                            <div class="fz13">Não há vídeos enviados no período selecionado</div>
                        </div>
                    <!-- EMPTY STATE -->

                </div>
            <!-- VIDEOS LISTING -->

        </div>
    <!-- CONTENT ENGAGEMENT MAIN -->

</template>


<style scoped>
.videos-table {
    max-height: 600px;
    overflow-y: auto;
}
</style>