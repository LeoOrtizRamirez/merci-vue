<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12" v-if="['ADMIN', 'CONSULTOR', 'CLIENTE'].includes(form.name)">
                        <div class="card">
                            <h5>Rol - {{form.name }}</h5>
                        </div>
                    </div>

                    <div class="col-12" v-else>
                        <div class="card">
                            <h5>Rol</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-12">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" required />
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('roles.index'));" />
                                <Button label="Actualizar" type="submit"></Button>
                            </form>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <h5>Permisos</h5>
                            <DataTable ref="dt" :value="datatable.data" :lazy="true" data-key="id" :paginator="true"
                                :rows="50" :loading="datatable.loading" :total-records="datatable.totalRecords"
                                v-model:filters="datatable.filters"
                                paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                :rows-per-page-options="[10, 25, 50]"
                                current-page-report-template="Mostrando del {first} al {last} de {totalRecords} resultados"
                                @page="onPage($event)" @sort="onSort($event)" @filter="onSort($event)">
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
            </div>
        </div>
        <DeleteDialog ref="deleteDialog" v-model:visible="deleteDialog" :loading="deletingModel" @delete="onDelete" />
        <Toast />
    </div>
</template>
<script>
import AppLayout from "../../Layouts/AppLayout";
import Button from "primevue/button";
import InputText from "primevue/inputtext";

import DataTable from "primevue/datatable";
import { FilterMatchMode } from "primevue/api";
import DatatableService from "../../Services/DatatableService";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import DeleteDialog from "../../Components/DeleteDialog";
import InputSwitch from 'primevue/inputswitch';
import axios from 'axios'
import Toast from 'primevue/toast';

export default {
    name: "Edit",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        Menubar,
        DataTable,
        Column,
        Button,
        DeleteDialog,
        InputSwitch,
        Toast
    },
    props: {
        role: [],
        role_has_permissions: [],
        errors: Object
    },
    data() {
        return {
            form: {
                name: this.role.name,
            },
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
        submit() {
            this.$inertia.put(route('roles.update', { 'role': this.role }), this.form);
        },
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
            axios.post(this.route('roles.permissions.toggle'), {
                permission_id: permission_id,
                role_id: this.role.id
            }).then(response => {
                this.role_has_permissions = response.data
                this.$toast.add({
                    severity: "success",
                    summary: "Exitoso",
                    detail: "Permiso Actualizado!",
                    life: 3000,
                });
            }).catch(error => {
                // Maneja el error de Laravel
            })
        }
    }
}
</script>

<style scoped></style>