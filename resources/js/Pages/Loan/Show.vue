<template>
    <div>
        <div class="p-grid">
            <h5>Prestamo de {{customer}}</h5>
            <div class="col-12 lg:col-12 xl:col-12" v-for="p in payments" :key="p.id">
                <div class="card mb-0">
                    <div class="flex justify-content-between mb-3">
                        <div>
                            <span class="block text-500 font-medium mb-3">Cuota: {{ p.fee }}</span>
                            <div class="text-900 font-medium text-xl">Valor: <span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(p.amount) }}</span></div>
                        </div>
                        <div v-if="p.status_id != 2" class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width:2.5rem;height:2.5rem">
                                <i @click="setPayment(p.id)" class="pi pi-shopping-cart text-blue-500 text-xl"></i>
                        </div>
                    </div>
                    <span class="font-medium">Fecha de Pago: {{  dateFormat(p.payment_date) }}</span>
                </div>
            </div>
            <div class="col-12 lg:col-12 xl:col-12">
                <div class="card mb-0">
                    <div class="flex justify-content-between mb-3">
                        <div>
                            <div class="text-900 font-medium text-xl">TOTAL: <span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(total) }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import Button from "primevue/button";

export default {
    name: "Index",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
    },
    props:{
        payments:[],
        customer:"",
        total: 0,
    },
    data() {
        return {
        }
    },
    methods: {
        moneyFormat(value){
            return(new Intl.NumberFormat('en-US').format(value))
        },
        dateFormat(date){
            const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            const d = new Date(date)
            const month = months[d.getMonth()];
            return  d.getDate() + " de " + month + " " + d.getFullYear()
        },
        setPayment(id){
            this.$inertia.post(route('payment.store',[id,'show']))
        }
    }
}
</script>

<style scoped>
</style>