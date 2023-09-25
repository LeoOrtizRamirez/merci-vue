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
            </div>
        </div>
    </div>

    <DeleteDialog ref="deleteDialog" v-model:visible="deleteDialog" :loading="deletingModel" @delete="onDelete" />

    <Dialog v-model:visible="modelDialog" :style="{ width: '750px' }" v-model:header="header" :modal="true"
        class="p-fluid">
        <div class="p-fluid formgrid grid">
            <div class="field col-12 md:col-6">
                <label for="actividad">INDICADOR</label>
                <Dropdown v-model="model.indicador" :options="indicadores" optionLabel="name"
                    placeholder="Selecciona un Indicador" :class="{ 'p-invalid': submitted && model.indicador == '' }" required
                    v-on:change="clearData" />
                <small class="p-invalid" v-if="submitted && model.indicador == ''">INDICADOR ES REQUERIDO.</small>
            </div>
            <div class="field col-12 md:col-6">
                <label for="mes">MES</label>
                <InputText type="month" id="mes" v-model.trim="model.mes" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.mes == ''}" />
                <small class="p-invalid" v-if="submitted && model.mes == ''">MES ES REQUERIDO.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 1">
                <label for="data_1">VENTAS</label>
                <InputNumber id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.data_1 == ''}" />
                <small class="p-invalid" v-if="submitted && model.data_1 == ''">VENTAS ES REQUERIDO.</small>
            </div>
            <div class="field col-12 md:col-12" v-if="model.indicador.id == 1">
                <label for="data_1">PRESUPUESTO</label>
                <InputNumber id="data_2" v-model.trim="model.data_2" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.data_2 == ''}" />
                <small class="p-invalid" v-if="submitted && model.data_2 == ''">PRESUPUESTO ES REQUERIDO.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 2">
                <label for="data_1">TTL COTIZACIONES</label>
                <InputNumber id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.data_1 == ''}" />
                <small class="p-invalid" v-if="submitted && model.data_1 == ''">TTL COTIZACIONES ES REQUERIDO.</small>
            </div>
            <div class="field col-12 md:col-12" v-if="model.indicador.id == 2">
                <label for="data_1">N COTIZACIONES</label>
                <InputNumber id="data_2" v-model.trim="model.data_2" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.data_2 == ''}" />
                <small class="p-invalid" v-if="submitted && model.data_2 == ''">N COTIZACIONES ES REQUERIDO.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 3">
                <label for="data_1">PORCENTAJE</label>
                <InputNumber id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.data_1 == ''}" />
                <small class="p-invalid" v-if="submitted && model.data_1 == ''">PORCENTAJE ES REQUERIDO.</small>
            </div>

            <div class="field col-12 md:col-12" v-if="model.indicador.id == 4">
                <label for="data_1">CLIENTES</label>
                <InputNumber id="data_1" v-model.trim="model.data_1" required="true" autofocus
                    :class="{ 'p-invalid': submitted && model.data_1 == ''}" />
                <small class="p-invalid" v-if="submitted && model.data_1 == ''">CLIENTES ES REQUERIDO.</small>
            </div>
        </div>
        <template #footer>
            <Button label="Cancelar" icon="pi pi-times" class="p-button-danger p-button-raised mx-2" @click="hideDialog" />
            <Button label="Guardar" icon="pi pi-check" class="" @click="saveModel()" />
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
                id: "",
                mes: "",
                data_1: "",
                data_2: "",
                indicador: "",
                user: this.user
            },
            submitted: false,
            arbol: this.arbol,
            header: "Crear Indicador"
        }
    },
    mounted() {
    },
    methods: {
        edit(id) {
            this.$inertia.get(this.route('users.edit', id));
        },
        onDelete() {
            this.deletingModel = true;
            this.submitted = true;
            if (this.model) {
                axios.post('/users/indicadores-delete/' + this.selectedModel)
                    .then((response) => {
                        //this.model = response.data.data;
                        this.$toast.add({
                            severity: "success",
                            summary: "Exitoso!",
                            detail: "Indicador Eliminado",
                            life: 3000,
                        });
                        this.arbol = response.data
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
            this.clearModel()
            this.deletingModel = false;
            this.deleteDialog = false;
            this.$refs.deleteDialog.onClose();
        },
        hideDialog() {
            this.clearModel()
            this.submitted = false;
            this.modelDialog = false;
        },
        openNew() {
            this.clearModel()
            this.submitted = false;
            this.header = "Crear Indicador"
            this.modelDialog = true;
        },
        editModel(model) {
            var actividad = this.actividades.filter(actividad => actividad.name == model.actividad_name)[0]
            var estado = this.estados.filter(estado => estado.name == model.estado_name)[0]

            this.model = model
            this.model.actividad = actividad
            this.model.estado = estado

            this.submitted = false;
            this.modelEditDialog = true;
        },
        clearData() {
            this.model.data_1 = ""
            this.model.data_2 = ""
        },
        showDeleteDialog(item) {
            this.selectedModel = item.id;
            this.deleteDialog = true;
        },
    }
}
</script>

<style scoped></style>