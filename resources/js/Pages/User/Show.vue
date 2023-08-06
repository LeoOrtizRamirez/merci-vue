<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="flex mb-2">
                        <Button v-permission="'user.edit'" icon="pi pi-pencil"
                            class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="edit(user.id)" />
                        <h4 class="m-0">Usuario</h4>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Nombre:</p>
                        <p class="ml-2">{{ user.name }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Correo electrónico: </p>
                        <p class="ml-2"> {{ user.email }} </p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Rol:</p>
                        <p class="ml-2">{{ user.role_name }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2" v-if="user.empresas.length <= 1">
                        <p class="m-0 font-bold">Empresa:</p>
                        <p class="m-0 ml-2" v-for="empresa in user.empresas" :key="empresa">{{ empresa }}</p><br>
                    </div>
                    <div class="px-2 py-2" v-if="user.empresas.length > 1">
                        <p class="m-0 font-bold">Empresa:</p>
                        <p class="m-0 ml-2" v-for="empresa in user.empresas" :key="empresa">{{ empresa }}</p><br>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="flex mb-2">
                            <Button v-permission="'user.create'" icon="pi pi-fw pi-plus"
                                class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                                @click="openNew" />
                            <h4 class="m-0">Indicadores</h4>
                        </div>

                        <TreeTable :value="arbol">
                            <Column field="name" header="Nombre" expander></Column>
                            <Column field="size" header=""></Column>
                            <Column field="type" header=""></Column>
                        </TreeTable>


                        <!-- <DataTable ref="dt" :value="datatable.data" :lazy="true" data-key="id" :paginator="true" :rows="50"
                            :loading="datatable.loading" :total-records="datatable.totalRecords"
                            v-model:filters="datatable.filters"
                            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rows-per-page-options="[10, 25, 50]"
                            current-page-report-template="Mostrando del {first} al {last} de {totalRecords} resultados"
                            @page="onPage($event)" @sort="onSort($event)" @filter="onSort($event)">
                            <Column field="acta_id" header="Acta">
                                <template #body="slotProps">
                                    {{ slotProps.data.acta_id }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <InputText type="text" v-model="filterModel.value" class="p-column-filter"
                                        placeholder="Search by name" />
                                </template>
                            </Column>
                            <Column field="descripcion" header="Tarea">
                                <template #body="slotProps">
                                    <span class="large-text">{{ slotProps.data.descripcion }}</span>
                                </template>
                            </Column>
                            <Column field="categoria" header="Categoria">
                                <template #body="slotProps">
                                    {{ slotProps.data.categoria_name }}
                                </template>
                            </Column>
                            <Column field="actividad" header="Actividad">
                                <template #body="slotProps">
                                    {{ slotProps.data.actividad_name }}
                                </template>
                            </Column>
                            <Column field="responsable" header="Responsable">
                                <template #body="slotProps">
                                    <span class="large-text">{{ slotProps.data.responsable }}</span>
                                </template>
                            </Column>
                            <Column field="fecha_inicio" header="Fecha Inicio" class="date">
                                <template #body="slotProps">
                                    <span class="date">{{ slotProps.data.fecha_inicio }}</span>
                                </template>
                            </Column>
                            <Column field="fecha_fin" header="Fecha Fin" class="date">
                                <template #body="slotProps">
                                    <span class="date">{{ slotProps.data.fecha_fin }}</span>
                                </template>
                            </Column>
                            <Column field="estado" header="Estado">
                                <template #body="slotProps">
                                    <Tag :style="{ background: slotProps.data.estado_backgroundColor }">
                                        <span style="font-size: 11px;">{{ slotProps.data.estado_name }}</span>
                                    </Tag>
                                </template>
                            </Column>
                            <Column field="fecha_finalizacion" header="Fecha Finalización" class="date">
                                <template #body="slotProps">
                                    <span class="date">{{ slotProps.data.fecha_finalizacion }}</span>
                                </template>
                            </Column>
                            <Column header="Acciones" style="min-width: 150px;">
                                <template #body="slotProps">
                                    <Button v-permission="'tarea.edit'" icon="pi pi-pencil"
                                        class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                                        @click="editModel(slotProps.data)" />
                                    <Button v-permission="'tarea.destroy'" icon="pi pi-trash"
                                        class="p-button-sm p-button-danger p-button-rounded p-button-outlined"
                                        @click="showDeleteDialog(slotProps.data)" />
                                </template>
                            </Column>
                            <template #empty>
                                Sin registros.
                            </template>
                        </DataTable> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <DeleteDialog ref="deleteDialog" v-model:visible="deleteDialog" :loading="deletingModel" @delete="onDelete" />

    <Dialog v-model:visible="modelDialog" :style="{ width: '750px' }" header="Crear Indicador" :modal="true"
        class="p-fluid">
        <div class="p-fluid formgrid grid">
            <div class="field col-12 md:col-6">
                <label for="mes">MES</label>
                <InputText type="month" id="mes" v-model.trim="model.mes" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.mes }" />
                <small class="p-invalid" v-if="submitted && !model.mes">MES es requerido.</small>
            </div>
            <!-- <div class="field col-12 md:col-6">
                <label for="actividad">USUARIO</label>
                <Dropdown v-model="model.user" :options="users" optionLabel="name" placeholder="Selecciona un Usuario"
                    :class="{ 'p-invalid': submitted && !model.user }" required />
                <small class="p-invalid" v-if="submitted && !model.actividad">Usuario es requerido.</small>
            </div> -->
            <div class="field col-12 md:col-6">
                <label for="actividad">INDICADOR</label>
                <Dropdown v-model="model.indicador" :options="indicadores" optionLabel="name"
                    placeholder="Selecciona un Indicador" :class="{ 'p-invalid': submitted && !model.user }" required
                    v-on:change="clearData" />
                <small class="p-invalid" v-if="submitted && !model.indicador">Indicador es requerido.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 1">
                <label type="number" for="data_1">VENTAS</label>
                <InputNumber id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.data_1 }" />
                <small class="p-invalid" v-if="submitted && !model.data_1">Ventas es requerido.</small>
            </div>
            <div class="field col-12 md:col-12" v-if="model.indicador.id == 1">
                <label type="number" for="data_1">PRESUPUESTO</label>
                <InputNumber id="data_2" v-model.trim="model.data_2" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.data_2 }" />
                <small class="p-invalid" v-if="submitted && !model.data_2">Ventas es requerido.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 2">
                <label type="number" for="data_1">TTL COTIZACIONES</label>
                <InputNumber id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.data_1 }" />
                <small class="p-invalid" v-if="submitted && !model.data_1">TTL COTIZACIONES es requerido.</small>
            </div>
            <div class="field col-12 md:col-12" v-if="model.indicador.id == 2">
                <label for="data_1">N COTIZACIONES</label>
                <InputNumber type="number" id="data_2" v-model.trim="model.data_2" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.data_2 }" />
                <small class="p-invalid" v-if="submitted && !model.data_2">N COTIZACIONES es requerido.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 3">
                <label for="data_1">PORCENTAJE</label>
                <InputNumber type="number" id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.data_1 }" />
                <small class="p-invalid" v-if="submitted && !model.data_1">PORCENTAJE es requerido.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 4">
                <label for="data_1">CLIENTES</label>
                <InputNumber type="number" id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.data_1 }" />
                <small class="p-invalid" v-if="submitted && !model.data_1">CLIENTES es requerido.</small>
            </div>
        </div>
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-danger p-button-raised mx-2" @click="hideDialog" />
            <Button label="Guardar" icon="pi pi-check" class="" @click="saveModel" />
        </template>
    </Dialog>

    <Dialog v-model:visible="modelEditDialog" :style="{ width: '750px' }" header="Editar Tarea" :modal="true"
        class="p-fluid">
        <div class="p-fluid formgrid grid">
            <div class="field col-12 md:col-12">
                <label for="name">Tarea</label>
                <InputText id="descripcion" v-model.trim="model.descripcion" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.descripcion }" />
                <small class="p-invalid" v-if="submitted && !model.descripcion">Descripción es requerida.</small>
            </div>
            <div class="field col-12 md:col-6">
                <label for="actividad">Actividad</label>
                <Dropdown v-model="model.actividad" :options="actividades" optionLabel="name"
                    placeholder="Selecciona una Actividad" :class="{ 'p-invalid': submitted && !model.actividad }"
                    required />
                <small class="p-invalid" v-if="submitted && !model.actividad">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-6">
                <label for="estado">Estado</label>
                <Dropdown v-model="model.estado" :options="estados" optionLabel="name" placeholder="Selecciona un Estado"
                    :class="{ 'p-invalid': submitted && !model.estado }" required />
                <small class="p-invalid" v-if="submitted && !model.estado">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-12">
                <label for="responsable">Responsable</label>
                <InputText id="responsable" v-model.trim="model.responsable" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.responsable }" />
                <small class="p-invalid" v-if="submitted && !model.responsable">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-4">
                <label for="fecha_inicio">Fecha Inicio</label>
                <InputText id="fecha_inicio" v-model.trim="model.fecha_inicio" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_inicio }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_inicio">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-4">
                <label for="fecha_fin">Fecha Fin</label>
                <InputText id="fecha_fin" v-model.trim="model.fecha_fin" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_fin }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_fin">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-4">
                <label for="fecha_finalizacion">Fecha finalización</label>
                <InputText id="fecha_finalizacion" v-model.trim="model.fecha_finalizacion" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_finalizacion }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_finalizacion">Responsable es requerido.</small>
            </div>
        </div>
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-danger p-button-raised mx-2"
                @click="hideEditDialog" />
            <Button label="Guardar" icon="pi pi-check" class="" @click="updateModel" />
        </template>
    </Dialog>
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
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import axios from "axios";
import Tag from 'primevue/tag';
import TreeTable from 'primevue/treetable';

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
        Toast,
        Dialog,
        InputText,
        InputNumber,
        Dropdown,
        Tag,
        TreeTable
    },
    props: {
        user: [],
        users: [],
        indicadores: [],
        actividades: [],
        estados: [],
        arbol: []
    },
    data() {
        return {
            importDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
            selectedModel: null,
            deleteDialog: false,
            deletingModel: false,
            modelDialog: false,
            modelEditDialog: false,
            model: {
                mes: "",
                data_1: "",
                data_2: "",
                indicador: "",
                user: this.user
            },
            submitted: false,
        }
    },
    mounted() {
        console.log("this.users" + this.user)
    },
    methods: {
        edit(id) {
            this.$inertia.get(this.route('users.edit', id));
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('tareas.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                    this.$toast.add({
                        severity: "success",
                        summary: "Exitoso",
                        detail: "Tarea Eliminada!",
                        life: 3000,
                    });
                }
            })
        },
        hideDialog() {
            this.modelDialog = false;
            this.submitted = false;
        },
        openNew() {
            this.model = { ...this.model, user: this.user };
            this.submitted = false;
            this.modelDialog = true;
        },
        editModel(model) {
            var actividad = this.actividades.filter(actividad => actividad.name == model.actividad_name)[0]
            var estado = this.estados.filter(estado => estado.name == model.estado_name)[0]

            this.model = model
            this.model.actividad = actividad
            this.model.estado = estado

            console.log(this.model)
            this.submitted = false;
            this.modelEditDialog = true;
        },
        hideEditDialog() {
            this.modelEditDialog = false;
            this.submitted = false;
        },
        saveModel() {
            this.submitted = true;
            if (this.model) {
                axios.post(this.route('users.saveIndicador'), this.model)
                    .then((response) => {
                        this.model = response.data.data;
                        /* this.models.unshift(this.model); */
                        this.$toast.add({
                            severity: "success",
                            summary: "Exitoso!",
                            detail: "Indicador creada",
                            life: 3000,
                        });
                        console.log("arbol", response.data)
                        this.$arbol = response.data
                    })
                    .catch((error) => {
                        this.$toast.add({
                            severity: "error",
                            summary: "Error",
                            detail: error,
                            life: 3000,
                        });
                    });
            }
            this.modelDialog = false;
            this.model = {};
        },
        updateModel() {
            this.submitted = true;
            if (this.model) {
                console.log("model", this.model)
                console.log(typeof miVariable)
                axios.put(this.route('tareas.update', this.model), this.model)
                    .then((response) => {
                        this.model = response.data.data;
                        /* this.models.unshift(this.model); */
                        this.$toast.add({
                            severity: "success",
                            summary: "Exitoso!",
                            detail: "Tarea actualizada",
                            life: 3000,
                        });
                        this.datatable.data = response.data
                        console.log("datatable.data", this.datatable.data)
                    })
                    .catch((error) => {
                        this.$toast.add({
                            severity: "error",
                            summary: "Error",
                            detail: error,
                            life: 3000,
                        });
                    });
            }
            this.modelEditDialog = false;
            this.model = {};
        },
        clearData() {
            this.model.data_1 = ""
            this.model.data_2 = ""
        }
    }
}
</script>

<style scoped></style>