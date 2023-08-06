<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <h4>Cronograma</h4>
                    <DataTable :value="tareas" :paginator="false" v-model:filters="filters" filterDisplay="row">
                        <Column field="categoria_name" header="CATEGORIA">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
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
                        </Column>
                        <Column field="responsable" header="RESPONSABLE">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="fecha_inicio" header="FECHA INICIO">
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText type="text" v-model="filterModel.value" @input="filterCallback()"
                                    class="p-column-filter" />
                            </template>
                        </Column>
                        <Column field="fecha_fin" header="FECHA FIN">
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
                        <Column field="fecha_finalizacion" header="FECHA REAL DE FINALIZACION">
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
                    </DataTable>
                </div>
            </div>
        </div>
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
        tareas: [],
        actividades: [],
        categorias: [],
    },
    data() {
        return {
            filters: {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
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
        }
    },
    created() {
    },
    mounted() {
    },
    methods: {
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