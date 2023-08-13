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
                                    @click="this.$inertia.get(this.route('entregables.index'));" />
                                <Button label="Actualizar" type="submit"></Button>
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
import axios from "axios";

export default {
    name: "Edit",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        Dropdown
    },
    props: {
        entregable: [],
        errors: Object
    },
    data() {
        return {
            form: {
                id: this.entregable.id,
                name: this.entregable.name,
            },
            fileName: this.entregable.url,
            formData: new FormData()
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.formData.append("id", this.form.id);
            this.formData.append("name", this.form.name);
            axios.post('/api/update-entregable', this.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$inertia.get(route('entregables.index'));
            }).catch(error => {
                console.log(error.response.data);
            });
        },

        handleFileUpload(event) {
            const input = event.target;
            const fileName = input.files[0].name;
            this.fileName = fileName;
            this.formData.append('image', event.target.files[0]);
        },
    }
}
</script>

<style scoped></style>