<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Entregable</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" />
                                        <small class="p-invalid" v-if="submitted && !form.name">Nombre es
                                            requerido.</small>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="file-upload" class="file-title">Archivo</label>
                                        <label for="file-upload" class="custom-file-upload import-entregable">
                                            <span class="pi pi-upload"></span>
                                            <span>{{ fileName }}</span>
                                            <input id="file-upload" name="image" ref="archivo" type="file"
                                                class="input-file" @change="handleFileUpload">
                                        </label>
                                        <br>
                                            <small class="p-invalid" v-if="submitted && !form.image">Archivo es
                                            requerido.</small>
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="back()" />
                                <Button label="Guardar" type="submit"></Button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from 'primevue/dropdown';
import axios from 'axios';
import Toast from 'primevue/toast';

export default {
    name: "Create",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        Dropdown,
        Toast
    },
    props: {
        estados: [],
        empresa_id: "",
        view: ""
    },
    data() {
        return {
            form: {
                name: "",
                empresa_id: this.empresa_id,
                image: ""
            },
            fileName: 'Seleccionar archivo',
            submitted: false,
            formData: new FormData()
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.submitted = true
            if (this.form.name && this.form.image) {
                this.formData.append("name", this.form.name);
                this.formData.append("empresa_id", this.form.empresa_id);
                axios.post('/api/upload-entregable', this.formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.$toast.add({
                        severity: "success",
                        summary: "Exitoso!",
                        detail: "Entregable creado",
                        life: 3000,
                    });
                    this.back()
                }).catch(error => {
                    console.log(error.response.data);
                    this.$toast.add({
                        severity: "error",
                        summary: "Error",
                        detail: error.response.data,
                        life: 3000,
                    });
                });
            }
        },

        handleFileUpload(event) {
            const input = event.target;
            const fileName = input.files[0].name;
            this.fileName = fileName;
            this.form.image = fileName

            this.formData.append('image', event.target.files[0]);
        },
        back() {
            if (this.view == "") {
                this.$inertia.get(route('empresas.show', this.form.empresa_id));
            } else {
                this.$inertia.get('/entregables-v2/' + this.form.empresa_id);
            }
        }
    }
}
</script>

<style scoped></style>