<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="flex mb-2">
                        <Button v-permission="'acta.edit'" icon="pi pi-pencil"
                            class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="edit(acta.id)" />
                        <h4 class="m-0">Acta</h4>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Número de la sesión:</p>
                        <p class="ml-2">{{ acta.numero_sesion }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Fecha: </p>
                        <p class="ml-2"> {{ acta.fecha }} </p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Hora de inicio:</p>
                        <p class="ml-2">{{ acta.hora_inicio }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Hora de finalización:</p>
                        <p class="ml-2">{{ acta.hora_finalizacion }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Modalidad de encuentro:</p>
                        <p class="ml-2">{{ acta.modalidad_encuentro }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Asistentes:</p>
                        <p class="ml-2">{{ acta.asistentes }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Temas tratados en la sesión:</p>
                        <p class="ml-2">{{ acta.temas }}</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="flex mb-2">
                            <Button v-permission="'acta.create'" icon="pi pi-fw pi-plus"
                                class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                                @click="openNew" />
                            <h4 class="m-0">Tareas</h4>
                        </div>
                        <DataTable ref="dt" :value="datatable.data" :lazy="true" data-key="id" :paginator="true" :rows="10"
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
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <DeleteDialog ref="deleteDialog" v-model:visible="deleteDialog" :loading="deletingModel" @delete="onDelete" />

    <Dialog v-model:visible="modelDialog" :style="{ width: '750px' }" header="Crear Tarea" :modal="true" class="p-fluid">
        <div class="p-fluid formgrid grid">
            <div class="field col-12 md:col-12">
                <label for="name">Tarea</label>
                <InputText id="descripcion" v-model.trim="model.descripcion" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.descripcion }" />
                <small class="p-invalid" v-if="submitted && !model.descripcion">Descripción es requerida.</small>
            </div>
            <div class="field col-12 md:col-12">
                <label for="actividad">Actividad</label>
                <Dropdown v-model="model.actividad" :options="actividades" optionLabel="name"
                    placeholder="Selecciona una Actividad" :class="{ 'p-invalid': submitted && !model.actividad }"
                    required />
                <small class="p-invalid" v-if="submitted && !model.actividad">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-12">
                <label for="responsable">Responsable</label>
                <InputText id="responsable" v-model.trim="model.responsable" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.responsable }" />
                <small class="p-invalid" v-if="submitted && !model.responsable">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-6">
                <label for="fecha_inicio">Fecha Inicio</label>
                <InputText id="fecha_inicio" v-model.trim="model.fecha_inicio" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_inicio }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_inicio">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-6">
                <label for="fecha_fin">Fecha Fin</label>
                <InputText id="fecha_fin" v-model.trim="model.fecha_fin" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_fin }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_fin">Responsable es requerido.</small>
            </div>
            <!-- <div class="field col-12 md:col-4">
                <label for="fecha_finalizacion">Fecha finalización</label>
                <InputText id="fecha_finalizacion" v-model.trim="model.fecha_finalizacion" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_finalizacion }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_finalizacion">Responsable es requerido.</small>
            </div> -->
            <!-- <div class="field col-12 md:col-6">
                <label for="estado">Estado</label>
                <Dropdown v-model="model.estado" :options="estados" optionLabel="name" placeholder="Selecciona un Estado"
                    :class="{ 'p-invalid': submitted && !model.estado }" required />
                <small class="p-invalid" v-if="submitted && !model.estado">Responsable es requerido.</small>
            </div> -->
        </div>
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Guardar" icon="pi pi-check" class="p-button-text" @click="saveModel" />
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
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideEditDialog" />
            <Button label="Guardar" icon="pi pi-check" class="p-button-text" @click="updateModel" />
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
import Dropdown from 'primevue/dropdown';
import axios from "axios";
import Tag from 'primevue/tag';

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
        Dropdown,
        Tag
    },
    props: {
        acta: [],
        actividades: [],
        estados: [],
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
                    'acta_id': { value: this.acta.id, matchMode: FilterMatchMode.EQUALS },
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
            modelDialog: false,
            modelEditDialog: false,
            model: {
                descripcion: "des",
                responsable: "des",
                fecha_inicio: "2023-01-01",
                fecha_finalizacion: "2023-01-01",
                fecha_fin: "2023-01-01",
                estado: {
                    id: 4, name: "Sin Iniciar", color: "#FFFFFF", backgroundColor: "#FFBF00", tipo: 2, created_at: null, updated_at: null
                },
                actividad: {
                    id: 4, name: "Cronograma", categoria_id: 1, created_at: null, updated_at: null
                }

            },
            submitted: false,
        }
    },
    datatableService: null,
    created() {
        this.datatableService = new DatatableService();
    },
    mounted() {
        console.log(this.actividades[0])
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
        edit(id) {
            this.$inertia.get(this.route('actas.edit', id));
        },
        loadLazyData() {

            this.datatable.loading = true;
            this.datatableService.getData(this.route('tareas.datatable'), this.datatable.lazyParams).then(data => {
                this.datatable.data = data.data;
                this.datatable.totalRecords = data.total;
                this.datatable.loading = false;
            });
            console.log("datatable.data", this.datatable.data)
        },
        onPage(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
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
            this.model = { ...this.model, acta: this.acta.id };
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
                axios.post(this.route('tareas.store'), this.model)
                    .then((response) => {
                        this.model = response.data.data;
                        /* this.models.unshift(this.model); */
                        this.$toast.add({
                            severity: "success",
                            summary: "Exitoso!",
                            detail: "Tarea creada",
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
            this.modelDialog = false;
            this.model = {};
        },
        updateModel() {
            this.submitted = true;
            if (this.model) {
                console.log("model", this.model)
                console.log(typeof miVariable)
                axios.put(this.route('tareas.update',  this.model), this.model)
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
    }
}
</script>

<style scoped></style>