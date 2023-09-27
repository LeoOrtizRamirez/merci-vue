<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between">
                    <div>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ total_actas }}</span></div>
                        <span class="block text-500 font-medium mb-3">Encuentros</span>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round"
                        style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-book text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0 cursor-pointer" @click="showCronograma()">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium">Acceso <br> Cronograma</span>
                        <!-- <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(loans) }}</span></div> -->
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round"
                        style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-external-link text-orange-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0 cursor-pointer" @click="showEntregables()">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium">Acceso <br> Entregables</span>
                        <!-- <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(gain) }}</span></div> -->
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                        style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-external-link text-cyan-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="justify-content-between text-center">
                    <img :src="logo" alt="" width="120">
                    <!-- <div>
                        <span class="block text-500 font-medium mb-3">Caja</span>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(current_balance) }}</span></div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-purple-100 border-round" style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-sort-alt text-purple-500 text-xl"></i>
                    </div> -->
                </div>
            </div>
        </div>


        <div class="col-12 lg:col-12 xl:col-12" v-if="total_actas > 0">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center w-full" id="categorias">
                        <thead>
                            <tr>
                                <th>
                                    <h5 class="mb-2 text-left"><strong>CATEGORÍAS</strong></h5>
                                </th>
                                <th>
                                    <h6 class="mb-2 text-left">% de avance</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in result">
                                <td>
                                    <img src="/public/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">{{ item.categoria_name }}</h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value=" Math.round(item.tareas_terminadas * 100 / item.tareas_totales)"></ProgressBar>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div class="col-12 lg:col-6 xl :col-6" v-if="indicadores_ids.includes(1)">
            <div class="card">
                <h6 class="text-center">VENTAS vs PRESUPUESTO en mill.</h6>
                <Chart type="bar" :data="chartVentasPresupuesto" :options="chartOptions" />
            </div>
        </div>
        <div class="col-12 lg:col-6 xl :col-6" v-if="indicadores_ids.includes(2)">
            <div class="card">
                <h6 class="text-center">COTIZACIONES</h6>
                <Chart type="bar" :data="chartTtlCotizaciones" :options="chartOptions" />
            </div>
        </div>

        <div class="col-12 lg:col-6 xl :col-6" v-if="indicadores_ids.includes(3)">
            <div class="card">
                <h6 class="text-center">EFECTIVIDAD COMERCIAL</h6>
                <Chart type="line" :data="chartEfectividadComercial" />
            </div>
        </div>

        <div class="col-12 lg:col-6 xl :col-6" v-if="indicadores_ids.includes(4)">
            <div class="card">
                <h6 class="text-center">CLIENTES NUEVOS</h6>
                <Chart type="bar" :data="chartClientesNuevos" :options="basicOptions" />
            </div>
        </div>

    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Chart from 'primevue/chart';
import ProgressBar from 'primevue/progressbar';


export default {
    layout: AppLayout,
    components: {
        AppLayout,
        Chart,
        ProgressBar
    },
    props: {
        chartVentasPresupuesto: [],
        chartTtlCotizaciones: [],
        chartEfectividadComercial: [],
        chartClientesNuevos: [],
        indicadores_ids: [],
        result:[],
        acta_id:"",
        total_actas:0,
        logo:"",
        empresa_id:null
    },
    data() {
        return {
            chartOptions: {
                responsive: true,
                legend: {
                    display: false
                }
            },
        }
    },
    methods:{
        showCronograma() {
            this.$inertia.get('/actas/cronograma?empresa_id='+this.empresa_id);
        },
        showEntregables(){
            this.$inertia.get(this.route('entregables.indexV2', this.empresa_id));
        },
        showActas(){
            this.$inertia.get(this.route('actas.index'));
        }
    }
};
</script>