<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="title">
                        <Button v-permission="'acta.create'" icon="pi pi-fw pi-plus"
                            class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="this.$inertia.get(this.route('actas.create'));" />
                        <!-- <Button v-permission="'acta.create'" icon="pi pi-fw pi-file-import"
                            class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="this.$inertia.get(this.route('actas.create'));" /> -->
                        <h4  @click="descargarArchivo">Actas</h4>
                        <Button icon="pi pi-fw pi-download" title="Descargar archivo con ejemplo de importación" class="mx-2 p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined" @click="descargarArchivo">
                        </Button>
                    </div>
                    <div class="title">
                        <form @submit.prevent="importarArchivo">
                            <!-- <input id="file-upload" type="file" class="p-fileupload" ref="archivo" name="archivo">
                            <button type="submit" class="p-button p-component">Importar archivo</button> -->

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
                                <Button v-permission="'acta.show'" icon="pi pi-calendar"
                                    class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                                    @click="showCronograma(slotProps.data.id)" />
                                <Button v-permission="'acta.show'" icon="pi pi-search"
                                    class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                                    @click="show(slotProps.data.id)" />
                                <Button v-permission="'acta.edit'" icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                                    @click="edit(slotProps.data.id)" />
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
import axios from 'axios';

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
            fileName: 'Importar'
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
            this.datatableService.getData(this.route('actas.datatable'), this.datatable.lazyParams).then(data => {
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
            this.$inertia.get(this.route('actas.show', id));
        },
        showCronograma(id) {
            this.$inertia.get(this.route('actas.cronograma', id));
        },
        edit(id) {
            this.$inertia.get(this.route('actas.edit', id));
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
                        detail: "Acta Eliminada!",
                        life: 3000,
                    });
                }
            })
        },
        async importarArchivo() {
            this.datatable.loading = true;
            let formData = new FormData();
            formData.append('file', this.$refs.archivo.files[0]);

            let response = await axios.post('/importar-archivo', formData);

            console.log(response.data.data);
            this.$toast.add({
                severity: "success",
                summary: "Exitoso",
                detail: response.data.message,
                life: 3000,
            });

            this.datatable.data = response.data.data
            console.log(response.data.data.length)
            this.datatable.totalRecords = response.data.data.length;
            this.datatable.loading = false;
        },
        handleFileUpload(event) {
            const input = event.target;
            const fileName = input.files[0].name;
            this.fileName = fileName;
            // Handle file upload logic here
        },
        descargarArchivo() {
            const url = "http://127.0.0.1:8000/assets/cronograma.xlsx";
            const nombreArchivo = "importacion-cronograma.xlsx";
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", nombreArchivo);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}
</script>

<style scoped>
td.acta-actions {
    white-space: nowrap;
}

.input-file {
    opacity: 0;
    position: absolute;
    z-index: -1;
}

.custom-file-upload {
    display: inline-block;
    background-color: #6366F1;
    color: #fff;
    font-size: 1rem;
    padding: 6px 10px;
    cursor: pointer;
    border-radius: 4px;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    margin-right: 5px;
}

.custom-file-upload span {
    display: inline-block;
    max-width: 160px;
    margin-right: 8px;
    font-size: 0.9rem;
    vertical-align: middle;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>