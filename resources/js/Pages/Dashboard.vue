<template>
    <div class="grid">
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between">
                    <div>
                        <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new
                            Intl.NumberFormat('en-US').format(investment) }}</span></div>
                        <span class="block text-500 font-medium mb-3">Encuentros</span>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round"
                        style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-dollar text-blue-500 text-xl"></i>
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
                        <i class="pi pi-shopping-cart text-orange-500 text-xl"></i>
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
                        <i class="pi pi-money-bill text-cyan-500 text-xl"></i>
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
        <div class="col-12 lg:col-6 xl :col-6">
            <div class="card">
                <h6 class="text-center">VENTAS vs PRESUPUESTO en mill.</h6>
                <Chart type="bar" :data="chartVentasPresupuesto" :options="chartOptions" />
            </div>
        </div>
        <div class="col-12 lg:col-6 xl :col-6">
            <div class="card">
                <h6 class="text-center">COTIZACIONES</h6>
                <Chart type="bar" :data="chartTtlCotizaciones" :options="chartOptions" />
            </div>
        </div>

        <div class="col-12 lg:col-6 xl :col-6">
            <div class="card">
                <h6 class="text-center">EFECTIVIDAD COMERCIAL</h6>
                <Chart type="line" :data="chartEfectividadComercial" />
            </div>
        </div>

        <div class="col-12 lg:col-6 xl :col-6">
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


export default {
    layout: AppLayout,
    components: {
        AppLayout,
        Chart,
    },
    props: {
        chartVentasPresupuesto: [],
        chartTtlCotizaciones: [],
        chartEfectividadComercial: [],
        chartClientesNuevos: []
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