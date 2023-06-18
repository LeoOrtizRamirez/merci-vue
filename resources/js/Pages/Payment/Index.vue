<template>
    <div class="p-grid">
        <h5>Lista de Pagos</h5>
        <div class="col-12 lg:col-12 xl:col-12" v-for="p in payments" :key="p.id">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3"><strong>{{p.name}}:</strong> Cuota: {{ p.fee }}</span>
                        <div class="text-900 font-medium text-xl">Valor: <span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(p.amount) }}</span></div>
                    </div>
                    <div class="grid">
                        <div v-permission="'loan.get'" class="flex align-items-center justify-content-center bg-blue-100 border-round margin-right-10" style="width:2.5rem;height:2.5rem">
                            <i @click="loadLoan(p.loan_id)" class="pi pi-eye text-blue-500 text-xl"></i>
                        </div>
                        <div v-permission="'payment.store'" v-if="p.status_id != 2" class="flex align-items-center justify-content-center bg-green-100 border-round" style="width:2.5rem;height:2.5rem">
                            <i @click="setPayment(p.id)" class="pi pi-dollar text-green-500 text-xl"></i>
                        </div>
                    </div>
                    
                </div>
                <span class="font-medium">Fecha de Pago: {{  dateFormat(p.payment_date) }}</span>
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
        dates:[]
    },
    data() {
        return {
        }
    },
    methods: {
        dateFormat(date){
            const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            const d = new Date(date)
            const month = months[d.getMonth()];
            return  d.getDate() + " de " + month + " " + d.getFullYear()
        },
        setPayment(id){
            this.$inertia.post(route('payment.store',[id,'index']))
        },
        loadLoan(id){
            this.$inertia.get(route('loan.get',id))
        }
    }
}
</script>

<style scoped>

</style>

<!--
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

import JetDialogModal from "@/Jetstream/DialogModal";
import JetButton from "@/Jetstream/Button";

</script>

<template>
    <AppLayout title="Pagos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pagos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200 overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="p-3">Cliente</th>
                                    <th class="p-3">Cuota</th>
                                    <th class="p-3">Valor</th>
                                    <th class="p">Estado</th>
                                    <th class="p-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody v-for="d in dates" v-bind:key="d">
                                <br>
                                <tr><strong>{{ dateFormat(d) }}</strong></tr>
                                <tr v-for="p in payments" :key="p.id">
                                        <td v-if="d==p.payment_date" class="p-3 border">{{ p.name }}</td>
                                        <td v-if="d==p.payment_date" class="p-3 border">{{ p.fee }}</td>
                                        <td v-if="d==p.payment_date" class="p-3 border">{{ new Intl.NumberFormat('en-US').format(p.amount) }}</td>
                                        <td v-if="d==p.payment_date" class="p-3 border">{{ p.status_name }}</td>
                                        <td v-if="d==p.payment_date" class="p-3 border">
                                            <Link v-permission="'loan.get'" :href="route('loan.get', { id: p.loan_id})" class="btn btn-sm btn-primary mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </Link>
                                            <button v-permission="'payment.store'" @click="setPayment(p.id)" v-if="p.status_id != 2" class="btn btn-sm btn-primary mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    <jet-dialog-modal :show="modalOpen">
        <template v-slot:title>
            <h1>Eliminar Usuario</h1>
        </template> 
        <template v-slot:content>
            <p v-if="selectPayment">¿Seguro que quieres eliminar el usuario: <strong>{{ selectpayment.name }}</strong>?</p>
        </template> 
        <template v-slot:footer>
            <jet-button @click="modalOpen=false">Cerrar</jet-button>
            <jet-button class="bg-red-500 hover:bg-red-800" @click="deletePayment()">Eliminar</jet-button>
        </template>    
    </jet-dialog-modal> 



    </AppLayout>
</template>
<script>

export default {
    props:{
        payments:[],
        dates:[]
    },
    data(){
        return{
            modalOpen:false,
            selectPayment:Object
        }
    },
    components:{
        AppLayout,
        Link,
        JetDialogModal,
        JetButton
    },
    methods:{
        deletePayment: function(){
            //if(!confirm("¿Seguro que quieres eliminar el usuario?"));
            Inertia.delete(route("payment.destroy", {Payment : this.selectPayment}))
            this.modalOpen = false
        },
        dateFormat(date){
            const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            const d = new Date(date)
            const month = months[d.getMonth()];
            return  d.getDate() + " de " + month + " " + d.getFullYear()
        },
        setPayment(id){
            Inertia.post(route('payment.store',[id,'index']))
        }
    }
}
</script>
-->