<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <h4>Acta</h4>
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
                                    {{ slotProps.data.descripcion }}
                                </template>
                            </Column>
                            <Column field="responsable" header="Responsable">
                                <template #body="slotProps">
                                    {{ slotProps.data.responsable }}
                                </template>
                            </Column>
                            <Column field="fecha_inicio" header="Fecha Inicio">
                                <template #body="slotProps">
                                    {{ slotProps.data.fecha_inicio }}
                                </template>
                            </Column>
                            <Column field="fecha_fin" header="Fecha Finalización">
                                <template #body="slotProps">
                                    {{ slotProps.data.fecha_fin }}
                                </template>
                            </Column>
                            <Column field="estado" header="Estado">
                                <template #body="slotProps">
                                    <Tag :style="{ background: slotProps.data.estado_backgroundColor }">
                                        <span>{{ slotProps.data.estado_name }}</span>
                                    </Tag>
                                </template>
                            </Column>
                            <Column field="fecha_finalizacion" header="Fecha real de Finalización">
                                <template #body="slotProps">
                                    {{ slotProps.data.fecha_finalizacion }}
                                </template>
                            </Column>
                            <Column header="Acciones" style="width: 150px;">
                                <template #body="slotProps">
                                    <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2"
                                        @click="editModel(slotProps.data)" />
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
            <div class="field col-12 md:col-6">
                <label for="estado">Estado</label>
                <Dropdown v-model="model.estado" :options="estados" optionLabel="name" placeholder="Selecciona un Estado"
                    :class="{ 'p-invalid': submitted && !model.estado }" required />
                <small class="p-invalid" v-if="submitted && !model.estado">Responsable es requerido.</small>
            </div>
            <div class="field col-12 md:col-6">
                <label for="actividad">Actividad</label>
                <Dropdown v-model="model.actividad" :options="actividades" optionLabel="name"
                    placeholder="Selecciona una Actividad" :class="{ 'p-invalid': submitted && !model.actividad }"
                    required />
                <small class="p-invalid" v-if="submitted && !model.actividad">Responsable es requerido.</small>
            </div>
        </div>
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Guardar" icon="pi pi-check" class="p-button-text" @click="saveModel" />
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
            modelDialog: false,
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
            this.$inertia.delete(this.route('actas.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                    this.$toast.add({
                        severity: "success",
                        summary: "Exitoso",
                        detail: "Categoria Eliminada!",
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
        saveModel() {
            this.submitted = true;
            if (this.model) {

                /* var token = document.querySelector('meta[name="csrf-token"]')
                fetch(this.route('tareas.store'), {
                    method: 'POST',
                    body: this.model,
                    headers: {
                        'X-CSRF-TOKEN': token.content // Replace with your CSRF token
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        this.model = data.data.data;
                        this.models.unshift(this.model);
                        this.$toast.add({
                            severity: "success",
                            summary: "Successful",
                            detail: "Permission Created",
                            life: 3000,
                        });
                        this.datatable.data = response.data
                        console.log("datatable.data", this.datatable.data)
                    })
                    .catch(error => {
                        console.error('Error uploading image', error);
                        this.$toast.add({
                            severity: "error",
                            summary: "Error",
                            detail: "Permission not created",
                            life: 3000,
                        });
                    }); */


                axios
                    .post(this.route('tareas.store'), this.model)
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
    }
}
</script>

<style scoped></style>