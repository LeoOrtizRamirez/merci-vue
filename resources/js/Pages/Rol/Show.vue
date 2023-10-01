<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="title">
                        <h4>Permisos - {{ role.name }}</h4>
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
                        <Column header="Acciones" class="actions">
                            <template #body="slotProps">
                                <InputSwitch :modelValue="role_has_permissions.includes(slotProps.data.id)"
                                    @change="togglePermission(slotProps.data.id)" />
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
import InputSwitch from 'primevue/inputswitch';
import axios from 'axios'

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
        InputSwitch
    },
    props: {
        role_has_permissions: [],
        role: {}
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
            defaultValuesToggle: false,
            role_has_permissions: this.role_has_permissions
        }
    },
    datatableService: null,
    created() {
        this.datatableService = new DatatableService();
    },
    mounted() {
        console.log("role_has_permissions", this.role_has_permissions)
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
            this.datatableService.getData(this.route('roles.permissions.datatable'), this.datatable.lazyParams).then(data => {
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
        show(id) {
            this.$inertia.get(this.route('roles.show', id));
        },
        edit(id) {
            this.$inertia.get(this.route('roles.edit', id));
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('roles.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                }
            })
        },
        togglePermission(permission_id) {
            console.log("permission_id", permission_id)
            // Actualiza el role_has_permissions
            /* if (this.role_has_permissions.includes(permission_id)) {
                console.log("si")
                this.role_has_permissions = this.role_has_permissions.filter(x => x !== permission_id)
            } else {
                this.role_has_permissions.push(permission_id )
            } */

            // Hace una petición a Laravel con el nuevo role_has_permissions
            axios.post(this.route('roles.permissions.toggle'), {
                permission_id: permission_id,
                role_id: this.role.id
            }).then(response => {
                console.log("response.data", response.data)
                this.role_has_permissions = response.data
            }).catch(error => {
                // Maneja el error de Laravel
            })
        }
    }
}
</script>

<style scoped>
</style>