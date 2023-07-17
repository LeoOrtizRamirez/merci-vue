<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between">
                    <div>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">1</span></div>
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
            <div class="card mb-0">
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
            <div class="card mb-0">
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
                <div class="flex justify-content-between">
                    <img src="/images/logo-merci.png" alt="" class="w-full">
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


        <div class="col-12 lg:col-12 xl:col-12">
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
                            <tr>
                                <td>
                                    <img src="/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">COMPONENTE ESTRÁTEGICO</h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value="80"></ProgressBar>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">CANALES DE VENTA</h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value="20"></ProgressBar>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">MECÁNICA COMERCIAL</h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value="10"></ProgressBar>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">TRANSFERENCIA DE CONOCIMIENTO
                                    </h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value="25"></ProgressBar>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">MERCADEO</h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value="10"></ProgressBar>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="/images/icons/icono-azul.png" class="absolute" style="width: 30px" alt="" />
                                    <h6 class="categoria">INFRAESTRUCTURA</h6>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <ProgressBar :value="50"></ProgressBar>
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
        indicadores_ids: []
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
    }
};
</script>