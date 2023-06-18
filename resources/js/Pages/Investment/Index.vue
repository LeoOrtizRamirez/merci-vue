<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12" v-permission="'investment.create'">
                <div class="card">
                    <Menubar :model="menuItems"/>
                </div>
            </div>
            <div class="p-col-12">
                <div class="card">
                    <DataTable
                        ref="dt"
                        :value="datatable.data"
                        :lazy="true"
                        data-key="id"
                        :paginator="true"
                        :rows="10"
                        :loading="datatable.loading"
                        :total-records="datatable.totalRecords"
                        v-model:filters="datatable.filters"
                        paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        :rows-per-page-options="[5,10,25]"
                        current-page-report-template="Mostrando del {first} al {last} de {totalRecords} resultados"
                        @page="onPage($event)"
                        @sort="onSort($event)"
                        @filter="onSort($event)"
                    >
                        <template #header>
                            <div class="table-header">
                                <h5 class="p-m-0">
                                    Inversiones
                                </h5>
                            </div>
                        </template>
                        <Column field="user_id" header="Usuario" :sortable="true">
                            <template #body="slotProps">
                                {{ slotProps.data.user }}
                            </template>
                        </Column>
                        <Column field="amount" header="Valor" :sortable="true">
                            <template #body="slotProps">
                                {{ moneyFormat(slotProps.data.amount) }}
                            </template>
                        </Column>
                        <Column header="Acciones" style="width: 150px;">
                            <template #body="slotProps">
                                <Button v-permission="'investment.edit'"
                                    icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1"
                                    @click="edit(slotProps.data.id)"
                                />
                                <Button v-permission="'investment.destroy'" icon="pi pi-trash" class="p-button-sm p-button-danger"
                                        @click="showDeleteDialog(slotProps.data)"
                                />
                            </template>
                        </Column>
                        <template #empty>
                            Sin registros.
                        </template>
                    </DataTable>
                </div>
            </div>
            <div class="grid">
                <div class="col-12 lg:col-6 xl:col-3">
                    <div class="card mb-0">
                        <div class="flex justify-content-between mb-3">
                            <div>
                                <span class="block text-500 font-medium mb-3">Inversiones</span>
                                <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(investment) }}</span></div>
                            </div>
                            <div class="flex align-items-center justify-content-center bg-blue-100 border-round" style="width:2.5rem;height:2.5rem">
                                <i class="pi pi-dollar text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 lg:col-6 xl:col-3">
                    <div class="card mb-0">
                        <div class="flex justify-content-between mb-3">
                            <div>
                                <span class="block text-500 font-medium mb-3">Prestamos</span>
                                <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(loans) }}</span></div>
                            </div>
                            <div class="flex align-items-center justify-content-center bg-orange-100 border-round" style="width:2.5rem;height:2.5rem">
                                <i class="pi pi-shopping-cart text-orange-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 lg:col-6 xl:col-3">
                    <div class="card mb-0">
                        <div class="flex justify-content-between mb-3">
                            <div>
                                <span class="block text-500 font-medium mb-3">Ganancia</span>
                                <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(gain) }}</span></div>
                            </div>
                            <div class="flex align-items-center justify-content-center bg-cyan-100 border-round" style="width:2.5rem;height:2.5rem">
                                <i class="pi pi-money-bill text-cyan-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 lg:col-6 xl:col-3">
                    <div class="card mb-0">
                        <div class="flex justify-content-between mb-3">
                            <div>
                                <span class="block text-500 font-medium mb-3">Caja</span>
                                <div class="text-900 font-medium text-xl"><span class="text-green-500 font-medium">{{ new Intl.NumberFormat('en-US').format(current_balance) }}</span></div>
                            </div>
                            <div class="flex align-items-center justify-content-center bg-purple-100 border-round" style="width:2.5rem;height:2.5rem">
                                <i class="pi pi-sort-alt text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DeleteDialog
            ref="deleteDialog"
            v-model:visible="deleteDialog"
            :loading="deletingModel"
            @delete="onDelete"
        />
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import DataTable from "primevue/datatable";
import {FilterMatchMode} from "primevue/api";
import DatatableService from "../../Services/DatatableService";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import Button from "primevue/button";
import DeleteDialog from "../../Components/DeleteDialog";

export default {
    name: "Index",
    layout: AppLayout,
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        Button,
        DeleteDialog
    },
    props:{
        investment:0,
        loans:0,
        gain:0,
        current_balance:0
    },
    data() {
        return {
            datatable: {
                loading: true,
                totalRecords: 0,
                data: null,
                filters: {
                    'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
                    'status': {value: null, matchMode: FilterMatchMode.EQUALS},
                },
                lazyParams: {}
            },
            menuItems: [
                {
                    label: 'Crear',
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$inertia.get(this.route('investments.create'));
                    },
                },
            ],
            importDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
            selectedModel: null,
            deleteDialog: false,
            deletingModel: false,
        }
    },
    datatableService: null,
    created() {
        this.datatableService = new DatatableService();
    },
    mounted() {
        this.datatable.loading = true;
        this.datatable.lazyParams = {
            first: 0,
            rows: this.$refs.dt.rows,
            sortField: 'id',
            sortOrder: -1,
            filters: this.datatable.filters
        };
        this.loadLazyData();
    },
    methods: {
        loadLazyData() {
            this.datatable.loading = true;
            this.datatableService.getData(this.route('investments.datatable'), this.datatable.lazyParams).then(data => {
                this.datatable.data = data.data;
                this.datatable.totalRecords = data.total;
                this.datatable.loading = false;
            });
        },
        onPage(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        edit(id) {
            console.log(id)
            this.$inertia.get(this.route('investments.edit', id));
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('investments.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                }
            })
        },
        moneyFormat(value){
            return(new Intl.NumberFormat('en-US').format(value))
        },
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
    <AppLayout title="Inversiones">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inversiones
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200 overflow-x-auto">
                        <Link v-permission="'investment.create'" :href="route('investment.create')" class="btn btn-sm btn-primary mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </Link>
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="p-3">Usuario</th>
                                    <th class="p-3">Valor</th>
                                    <th class="p-3">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="i in investments" :key="i.id">
                                    <td class="p-3 border">{{ i.user.name }}</td>
                                    <td class="p-3 border">{{ new Intl.NumberFormat('en-US').format(i.amount) }}</td>
                                    <td class="p-3 border">{{ dateFormat(i.created_at) }}</td>
                                    <td class="p-3 border">
                                        <Link v-permission="'investment.edit'" :href="route('investment.edit', { investment: i})" class="btn btn-sm btn-success mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>
                                        <button v-permission="'investment.destroy'" @click="modalOpen=true;selectInvestment=i" class="btn btn-sm btn-danger mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <div class="row">
                            <div class="col text-center">
                                <h2>Balance</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Inversiones</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ new Intl.NumberFormat('en-US').format(investment) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Prestamos</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ new Intl.NumberFormat('en-US').format(loans) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Ganancia</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ new Intl.NumberFormat('en-US').format(gain) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <h2>Caja</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4"></div>
                            <div class="col-md-4 col-md-offset-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Saldo actual</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ new Intl.NumberFormat('en-US').format(investment - loans) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-4"></div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        


    <jet-dialog-modal :show="modalOpen">
        <template v-slot:title>
            <h1>Eliminar Inversión</h1>
        </template> 
        <template v-slot:content>
            <p v-if="selectInvestment">¿Seguro que quieres eliminar la inversión?</p>
        </template> 
        <template v-slot:footer>
            <jet-button @click="modalOpen=false">Cerrar</jet-button>
            <jet-button class="bg-red-500 hover:bg-red-800" @click="deleteInvestment()">Eliminar</jet-button>
        </template>    
    </jet-dialog-modal> 



    </AppLayout>
</template>
<script>

export default {
    props:{
        investments:[],
        investment:0,
        loans:0,
        gain:0
    },
    data(){
        return{
            modalOpen:false,
            selectInvestment:Object
        }
    },
    components:{
        AppLayout,
        Link,
        JetDialogModal,
        JetButton
    },
    methods:{
        deleteInvestment: function(){
            //if(!confirm("¿Seguro que quieres eliminar el usuario?"));
            Inertia.delete(route("investment.destroy", {investment : this.selectInvestment}))
            this.modalOpen = false
        },
        dateFormat(date){
            const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            const d = new Date(date)
            const month = months[d.getMonth()];
            return  d.getDate() + " de " + month + " " + d.getFullYear()
        }
    }
}
</script>
-->