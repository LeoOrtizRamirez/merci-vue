<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <div class="title">
                        <Button v-permission="'entregable.create'" icon="pi pi-fw pi-plus"
                            class="p-button-primary p-button-sm mr-1 p-button-rounded p-button-outlined"
                            @click="this.$inertia.get('/entregables/create?empresa_id=' + empresa_id + '&view=single');" />
                        <h4>Entregables</h4>
                    </div>
                    <DataTable :value="entregables" :paginator="false">
                        <Column field="name" header="Nombre">
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column field="extension" header="Tipo">
                            <template #body="slotProps">
                                <img v-if="excelExtension.includes(slotProps.data.extension.toUpperCase())" src='/public/images/documentos/Excel.svg' alt="" height="30">
                                <img v-if="slotProps.data.extension.toUpperCase() == 'PDF'" src='/public/images/documentos/PDF.svg' alt="" height="30">
                                <img v-if="slotProps.data.extension.toUpperCase() == 'WORD'" src='/public/images/documentos/Word.svg' alt="" height="30">
                                <img v-if="slotProps.data.extension.toUpperCase() == 'PNG'" src='/public/images/documentos/png.png' alt="" height="30">
                                <img v-if="desconocidasExtensiones.includes(slotProps.data.extension.toUpperCase())" src='/public/images/documentos/Desconocido.svg' alt="" height="30">
                            </template>
                        </Column>
                        <Column field="url" header="Documento">
                            <template #body="slotProps">
                                <a :href="`/public/images/entregables/${empresa_id}/${slotProps.data.url}`" target="_blank">{{ slotProps.data.url }}</a>
                            </template>
                        </Column>
                        <Column header="Acciones" class="actions">
                            <template #body="slotProps">
                                <Button v-permission="'entregable.edit'" icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1 p-button-rounded p-button-outlined"
                                    @click="edit(slotProps.data.id)" />
                                <Button v-permission="'entregable.destroy'" icon="pi pi-trash"
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
    props:{
        user:[],
        entregables: [],
        empresa_id: ''
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
                    'empresa_id': { value: this.user.id, matchMode: FilterMatchMode.EQUALS },
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
            excelExtension:['XLSX','XLS','XLSM','XLSB','XLTM','XLTX'],
            desconocidasExtensiones:['123', '3G2', '3GP', '7Z', 'AAC', 'AC3', 'ACCDB', 'AIFF', 'AMR', 'ASF', 'AVI', 'BMP', 'C', 'CLASS', 'CPP', 'CR2', 'CSS', 'CSV', 'CUE', 'DAT', 'DB', 'DBF', 'DDS', 'DNG', 'DOC', 'DOCX', 'DWG', 'DXF', 'EPS', 'EXE', 'FLAC', 'FLV', 'GIF', 'GZ', 'H', 'HTML', 'ICS', 'IFF', 'INDD', 'ISO', 'JAR', 'JAVA', 'JPEG', 'JPG', 'JS', 'JSON', 'JSP', 'KEY', 'LOG', 'M4A', 'M4B', 'M4P', 'M4V', 'MAX', 'MDB', 'MID', 'MKV', 'MOV', 'MP3', 'MP4', 'MPA', 'MPEG', 'MPG', 'MSG', 'NC', 'NES', 'NUMBERS', 'OBJ', 'ODP', 'ODS', 'ODT', 'OGG', 'OTF', 'PAGES', 'PDB', 'PEF', 'PHP', 'PNG', 'PPT', 'PPTX', 'PRPROJ', 'PS', 'PSD', 'PY', 'RAR', 'RAW', 'RM', 'ROM', 'RPM', 'RTF', 'RW2', 'RWL', 'SH', 'SLN', 'SRT', 'SVG', 'SWF', 'TAR', 'TBZ2', 'TGA', 'TGZ', 'TIF', 'TIFF', 'TORRENT', 'TTF', 'TXT', 'VOB', 'WAV', 'WEBM', 'WMA', 'WMV', 'WPD', 'WPS', 'XLR', 'XML', 'YUV', 'ZIP']
        }
    },
    created() {
    },
    mounted() {
    },
    methods: {
        edit(id) {
            this.$inertia.get('/entregables/' + id + '/edit?view=single');
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('entregables.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    //this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                    this.$toast.add({
                        severity: "success",
                        summary: "Exitoso",
                        detail: "Entregable Eliminado!",
                        life: 3000,
                    });
                }
            })
        },
    }
}
</script>

<style scoped></style>