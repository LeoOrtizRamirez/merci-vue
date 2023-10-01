<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="title">
                        <Button v-permission="'actividad.create'" icon="pi pi-fw pi-plus"
                            class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="this.$inertia.get(this.route('actividades.create'));" />
                        <h4>Actividades</h4>
                    </div>



                    <DataTable ref="dt" :value="datatable.data" :lazy="true" data-key="id" :paginator="true" :rows="50"
                        :loading="datatable.loading" :total-records="datatable.totalRecords"
                        v-model:filters="datatable.filters"
                        paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        :rows-per-page-options="[10, 25, 50]"
                        current-page-report-template="Mostrando del {first} al {last} de {totalRecords} resultados"
                        @page="onPage($event)" @sort="onSort($event)" @filter="onSort($event)">
                        <!-- <template #header>
                                <h5 class="p-m-0">
                                    Clientes
                                </h5>
                        </template> -->
                        <Column field="id" header="ID">
                            <template #body="slotProps">
                                {{ slotProps.data.id }}
                            </template>
                        </Column>
                        <Column field="name" header="Nombre">
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column field="categoria_name" header="Categoria">
                            <template #body="slotProps">
                                {{ slotProps.data.categoria_name }}
                            </template>
                        </Column>
                        <Column header="Acciones" class="actions">
                            <template #body="slotProps">
                                <Button v-permission="'actividad.edit'" icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                                    @click="edit(slotProps.data.id)" />
                                <Button v-permission="'actividad.destroy'" v-if="slotProps.data.visible == false" icon="pi pi-trash"
                                    class="p-button-sm p-button-danger p-button-rounded p-button-outlined"
                                    @click="showDeleteDialog(slotProps.data)" />
                            </template>
                        </Column>
                        <template #empty>
                            Sin registros.
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>

        <DeleteDialog ref="deleteDialog" v-model:visible="deleteDialog" :loading="deletingModel" @delete="onDelete" />
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import DataTable from "primevue/datatable";
import { FilterMatchMode } from "primevue/api";
import DatatableService from "../../Services/DatatableService";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import Button from "primevue/button";
import DeleteDialog from "../../Components/DeleteDialog";
import Toast from 'primevue/toast';

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
        Toast
    },
    data() {
        return {
            datatable: {
                loading: true,
                totalRecords: 0,
                data: null,
                filters: {
                    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
                    'status': { value: null, matchMode: FilterMatchMode.EQUALS },
                },
                lazyParams: {}
            },
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
            this.datatableService.getData(this.route('actividades.datatable'), this.datatable.lazyParams).then(data => {
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
            this.$inertia.get(this.route('actividades.edit', id));
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('actividades.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                    this.$toast.add({
                        severity: "success",
                        summary: "Exitoso",
                        detail: "Actividad Eliminada!",
                        life: 3000,
                    });
                }
            })
        },
    }
}
</script>

<style scoped></style>