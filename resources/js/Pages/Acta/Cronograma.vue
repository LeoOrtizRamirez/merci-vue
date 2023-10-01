<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <h4>Cronograma</h4>
                    <DataTable :value="tareas" :paginator="false" v-model:filters="filters" filterDisplay="row">
                        <Column field="acta_id" header="ACTA">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <!-- <Column field="categoria_name" header="CATEGORIA">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column> -->
                        <Column field="actividad_name" header="ACTIVIDAD">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="descripcion" header="TAREAS">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                            <template #body="slotProps">
                                <div class="large-text" v-html="slotProps.data.descripcion"></div>
                            </template>
                        </Column>
                        <Column field="responsable" header="RESPONSABLE">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="fecha_inicio" header="INICIO">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="fecha_fin" header="FIN">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="estado_name" header="ESTADO">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                            <template #body="slotProps">
                                <Tag :style="{ background: slotProps.data.estado_backgroundColor }">
                                    <span style="font-size: 11px;">{{ slotProps.data.estado_name }}</span>
                                </Tag>
                            </template>
                        </Column>
                        <Column field="fecha_finalizacion" header="FINALIZACIÓN">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="desviacion" header="DESVIACIÓN" class="text-center">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column header="Acciones" class="actions">
                            <template #body="slotProps">
                                <Button v-permission="'tarea.edit'" icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                                    @click="editModel(slotProps.data)" />
                                <Button v-permission="'tarea.destroy'" icon="pi pi-trash"
                                    class="p-button-sm p-button-danger p-button-rounded p-button-outlined"
                                    @click="showDeleteDialog(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="modelEditDialog" :style="{ width: '750px' }" header="Editar Tarea" :modal="true"
        class="p-fluid">
        <div class="p-fluid formgrid grid">
            <div class="field col-12 md:col-12">
                <label for="name">Tarea</label>
                <Editor v-model="model.descripcion" editorStyle="height: 100px" />
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
                <InputText id="responsable" v-model.trim="model.responsable" autofocus />
            </div>
            <div class="field col-12 md:col-4">
                <label for="fecha_inicio">Fecha Inicio</label>
                <InputText id="fecha_inicio" v-model.trim="model.fecha_inicio" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_inicio }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_inicio">Fecha Inicio es requerida.</small>
            </div>
            <div class="field col-12 md:col-4">
                <label for="fecha_fin">Fecha Fin</label>
                <InputText id="fecha_fin" v-model.trim="model.fecha_fin" required="true" autofocus
                    :class="{ 'p-invalid': submitted && !model.fecha_fin }" type="date" />
                <small class="p-invalid" v-if="submitted && !model.fecha_fin">Fecha Fin es requerida.</small>
            </div>
            <div class="field col-12 md:col-4">
                <label for="fecha_finalizacion">Fecha finalización</label>
                <InputText id="fecha_finalizacion" v-model.trim="model.fecha_finalizacion" autofocus type="date" />
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
import Dropdown from 'primevue/dropdown';
import axios from "axios";
import Tag from 'primevue/tag';
import Editor from 'primevue/editor';

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
        Tag,
        Editor
    },
    props: {
        tareas: [],
        actividades: [],
        estados: [],
        categorias: [],
    },
    data() {
        return {
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
                acta_id: { value: null, matchMode: FilterMatchMode.CONTAINS },
                categoria_name: { value: null, matchMode: FilterMatchMode.CONTAINS },
                actividad_name: { value: null, matchMode: FilterMatchMode.CONTAINS },
                descripcion: { value: null, matchMode: FilterMatchMode.CONTAINS },
                responsable: { value: null, matchMode: FilterMatchMode.CONTAINS },
                fecha_inicio: { value: null, matchMode: FilterMatchMode.CONTAINS },
                fecha_fin: { value: null, matchMode: FilterMatchMode.CONTAINS },
                estado_name: { value: null, matchMode: FilterMatchMode.CONTAINS },
                fecha_finalizacion: { value: null, matchMode: FilterMatchMode.CONTAINS },
                desviacion: { value: null, matchMode: FilterMatchMode.CONTAINS },
            },
            modelEditDialog: false,
            tareas: this.tareas,
            model: {}
        }
    },
    created() {
    },
    mounted() {
    },
    methods: {
        editModel(model) {
            var actividad = this.actividades.filter(actividad => actividad.name == model.actividad_name)[0]
            var estado = this.estados.filter(estado => estado.name == model.estado_name)[0]

            this.model = model
            this.model.actividad = actividad
            this.model.estado = estado

            this.submitted = false;
            this.modelEditDialog = true;
        },
        hideEditDialog() {
            this.modelEditDialog = false;
            this.submitted = false;
        },
        updateModel() {
            this.submitted = true;
            if (this.model && this.model.actividad && this.model.fecha_inicio && this.model.fecha_fin) {
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
                        this.tareas = response.data
                        this.modelEditDialog = false;
                        this.model = {};
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
        },
    }
}
</script>

<style scoped>
.Table {
    display: table;
}

.Title {
    display: table-caption;
    text-align: center;
    font-weight: bold;
    font-size: larger;
}

.Heading {
    display: table-row;
    font-weight: bold;
    text-align: center;
}

.Row {
    display: table-row;
}

.Cell {
    display: table-cell;
    border: solid;
    border-width: thin;
    padding-left: 5px;
    padding-right: 5px;
}

.Row.actividades div {
    border: none !important;
    padding: 0px !important;
}

.Row.tareas div {
    border: none !important;
    padding: 0px !important;
}
</style>