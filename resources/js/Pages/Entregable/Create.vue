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
                                        <InputText v-model="form.name" id="name" type="text" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="file-upload" class="custom-file-upload import-entregable">
                                            <span class="pi pi-upload"></span>
                                            <span>{{ fileName }}</span>
                                            <input id="file-upload" name="image" ref="archivo" type="file"
                                                class="input-file" @change="handleFileUpload">
                                        </label>
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('empresas.show', entregable.empresa_id));" />
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
        empresa_id: ""
    },
    data() {
        return {
            form: {
                name: "",
                empresa_id: this.empresa_id,
            },
            fileName: 'Seleccionar archivo'
        }
    },
    mounted() {
    },
    methods: {
        submit() {
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
                this.$inertia.get(route('empresas.show', this.form.empresa_id));
            }).catch(error => {
                console.log(error.response.data);
            });
        },

        handleFileUpload(event) {
            const input = event.target;
            const fileName = input.files[0].name;
            this.fileName = fileName;

            this.formData = new FormData();
            this.formData.append('image', event.target.files[0]);
        },
    }
}
</script>

<style scoped></style>