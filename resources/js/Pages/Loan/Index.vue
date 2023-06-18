<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12" v-permission="'loan.create'">
                <div class="card">
                    <Menubar :model="menuItems"/>
                </div>
            </div>
            <div class="p-col-12">
                <div class="card ">
                    <div class="p-datatable-wrapper">

                    
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
                                    Prestamos
                                </h5>
                            </div>
                        </template>
                        <Column field="id" header="Cliente" :sortable="true">
                            <template #body="slotProps">
                                {{ slotProps.data.customer }}
                            </template>
                        </Column>
                        <Column field="amount" header="Valor" :sortable="true">
                            <template #body="slotProps">
                                {{ moneyFormat(slotProps.data.amount) }}
                            </template>
                        </Column>
                        <Column field="interest" header="Interes">
                            <template #body="slotProps">
                                {{ moneyFormat(slotProps.data.interest) + "%"}}
                            </template>
                        </Column>
                        <Column field="balance" header="Saldo" :sortable="true">
                            <template #body="slotProps">
                                {{ moneyFormat(slotProps.data.balance) }}
                            </template>
                        </Column>
                        <Column field="total_fee" header="Cuotas">
                            <template #body="slotProps">
                                {{ slotProps.data.total_fee }}
                            </template>
                        </Column>
                        <Column field="way_to_pay" header="Forma de Pago">
                            <template #body="slotProps">
                                {{ getWayToPay(slotProps.data.way_to_pay) }}
                            </template>
                        </Column>
                        <Column field="status_id" header="Estado" :sortable="true">
                            <template #body="slotProps">
                                <span class="loan-badge" :style="{ 'background': slotProps.data.status_background_color, 'color': slotProps.data.status_color}">
                                {{ slotProps.data.status }}
                                </span>
                            </template>
                        </Column>
                        <Column header="Acciones" style="width: 150px;">
                            <template #body="slotProps">
                                <Link v-permission="'loan.show'" :href="route('loans.show', { loan: slotProps.data})">
                                    <Button icon="pi pi-eye" class="p-button-sm p-button-primary"/>
                                </Link>
                                <Button
                                v-permission="'loan.edit'"
                                    icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1"
                                    @click="edit(slotProps.data.id)"
                                />
                                <Button v-permission="'loan.destroy'" icon="pi pi-trash" class="p-button-sm p-button-danger"
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
import { Link } from '@inertiajs/inertia-vue3';

export default {
    name: "Index",
    layout: AppLayout,
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        Button,
        DeleteDialog,
        Link
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
                        this.$inertia.get(this.route('loans.create'));
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
            this.datatableService.getData(this.route('loans.datatable'), this.datatable.lazyParams).then(data => {
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
            this.$inertia.get(this.route('loans.edit', id));
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('loans.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                }
            })
        },
        getWayToPay(val){
            switch (val) {
                case "1":
                    return "Diario"
                    break;
                case "7":
                    return "Semanal"
                    break;
                case "14":
                    return "Quincenal"
                    break;
                case "15":
                    return "Quincenal"
                    break;
                case "30":
                    return "Mensual"
                    break;
            
                default:
                    break;
            }
        },
        moneyFormat(value){
            return(new Intl.NumberFormat('en-US').format(value))
        },
    }
}
</script>

<style scoped>
</style>