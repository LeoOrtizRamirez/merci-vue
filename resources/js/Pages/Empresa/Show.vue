<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="flex mb-2">
                        <Button v-permission="'empresa.edit'" icon="pi pi-pencil"
                            class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="edit(empresa.id)" />
                        <h4 class="m-0">Empresa</h4>
                    </div>
                    <div class="grid grid-cols-2 mx-4 my-4">
                        <div class="container_image">
                            <img :src="`/public/images/${empresa.logo}`" alt="" width="100">
                        </div>
                        <div>
                            <div class="flex justify-between items-center px-2 py-2">
                                <p class="m-0 font-bold">Nombre:</p>
                                <p class="ml-2">{{ empresa.name }}</p>
                            </div>
                            <div class="flex justify-between items-center px-2 py-2">
                                <p class="m-0 font-bold">NIT: </p>
                                <p class="ml-2"> {{ empresa.nit }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="title">
                        <Button v-permission="'acta.create'" icon="pi pi-fw pi-plus"
                            class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="this.$inertia.get('/actas/create?empresa_id=' + empresa.id);" />
                        
                        
                        <h4>
                            Actas
                            <i v-permission="'acta.import'" title="Descargar archivo con ejemplo de importación" class="pi pi-fw pi-download cursor-pointer" @click="descargarArchivo"></i>
                        </h4>
                        <!-- <Button icon="pi pi-fw pi-download" title="Descargar archivo con ejemplo de importación" class="mx-2 p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined" @click="descargarArchivo"/> -->
                    </div>
                    <div class="title" v-permission="'acta.import'">
                        <form @submit.prevent="importarArchivo">
                            <label for="file-upload" class="custom-file-upload">
                                <span class="pi pi-upload"></span>
                                <span>{{ fileName }}</span>
                                <input id="file-upload" name="file-upload" ref="archivo" type="file" class="input-file"
                                    @change="handleFileUpload">
                            </label>
                            <button v-if="fileName != 'Importar'" class="p-button p-component" type="submit">
                                <span class="p-button-label">Guardar</span>
                            </button>
                        </form>
                    </div>

                        <DataTable ref="dt" :value="actas" :lazy="true" data-key="id" :paginator="true" :rows="50" 
                            :loading="loading" :total-records="totalRecords"
                            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rows-per-page-options="[10, 25, 50]"
                            current-page-report-template="Mostrando del {first} al {last} de {totalRecords} resultados"
                            @page="onPage($event)" @sort="onSort($event)" @filter="onSort($event)">
                            <Column field="numero_sesion" header="# sesión">
                                <template #body="slotProps">
                                    {{ slotProps.data.numero_sesion }}
                                </template>
                            </Column>
                            <Column field="fecha" header="Fecha">
                                <template #body="slotProps">
                                    {{ slotProps.data.fecha }}
                                </template>
                            </Column>
                            <Column field="hora_inicio" header="Hora de inicio">
                                <template #body="slotProps">
                                    {{ slotProps.data.hora_inicio }}
                                </template>
                            </Column>
                            <Column field="hora_finalizacion" header="Hora de finalización">
                                <template #body="slotProps">
                                    {{ slotProps.data.hora_finalizacion }}
                                </template>
                            </Column>
                            <Column header="Acciones" class="acta-actions">
                                <template #body="slotProps">
                                    <!-- <Button v-permission="'acta.show'" icon="pi pi-calendar"
                                        class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                                        @click="showCronograma(slotProps.data.id)" /> -->
                                    <Button v-permission="'acta.show'" icon="pi pi-search"
                                        class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                                        @click="showActa(slotProps.data.id)" />
                                    <Button v-permission="'acta.edit'" icon="pi pi-pencil"
                                        class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                                        @click="editActa(slotProps.data.id)" />
                                    <Button v-permission="'acta.destroy'" icon="pi pi-trash"
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
                <Dropdown v-model="model.empresa" :options="empresas" optionLabel="name" placeholder="Selecciona un Usuario"
                    :class="{ 'p-invalid': submitted && !model.empresa }" required />
                <small class="p-invalid" v-if="submitted && !model.actividad">Usuario es requerido.</small>
            </div> -->
            <div class="field col-12 md:col-6">
                <label for="actividad">INDICADOR</label>
                <Dropdown v-model="model.indicador" :options="indicadores" optionLabel="name"
                    placeholder="Selecciona un Indicador" :class="{ 'p-invalid': submitted && !model.empresa }" required
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
        empresa: [],
        empresas: [],
        indicadores: [],
        actividades: [],
        estados: [],
        arbol: [],
        actas: []
    },
    data() {
        return {
            actas: this.actas,
            totalRecords: this.actas.length,
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
                empresa: this.empresa
            },
            submitted: false,
            loading: false,
            fileName: 'Importar'
        }
    },
    mounted() {
    },
    methods: {
        edit(id) {
            this.$inertia.get(this.route('empresas.edit', id));
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
            this.model = { ...this.model, empresa: this.empresa };
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
                axios.post(this.route('empresas.saveIndicador'), this.model)
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
                        this.actas = response.data
                        console.log("actas", this.actas)
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
        },
        async importarArchivo() {
            this.loading = true;
            let formData = new FormData();
            formData.append('file', this.$refs.archivo.files[0]);
            formData.append('empresa_id', this.empresa.id);

            let response = await axios.post('/importar-archivo', formData);

            console.log(response.data.data);
            this.$toast.add({
                severity: "success",
                summary: "Exitoso",
                detail: response.data.message,
                life: 3000,
            });

            this.actas = response.data.data
            this.actas.totalRecords = response.data.data.length;
            this.loading = false;
        },
        handleFileUpload(event) {
            const input = event.target;
            const fileName = input.files[0].name;
            this.fileName = fileName;
            // Handle file upload logic here
        },
        descargarArchivo() {
            const url = "/assets/cronograma.xlsx";
            const nombreArchivo = "importacion-cronograma.xlsx";
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", nombreArchivo);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        showActa(id) {
            this.$inertia.get(this.route('actas.show', id));
        },
        editActa(id) {
            this.$inertia.get(this.route('actas.edit', id));
        },
        showCronograma(id) {
            this.$inertia.get(this.route('actas.cronograma', id));
        },
    }
}
</script>

<style scoped>
.container_image {
    display: flex;
    align-items: center;
}
</style>