<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Empresa</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="nit">NIT</label>
                                        <InputText v-model="form.nit" id="nit" type="text" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="estado">Estado</label>
                                        <Dropdown v-model="form.estado" :options="estados" optionLabel="name"
                                            placeholder="Selecciona un Estado" class="w-full" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="indicadores">Indicadores</label>
                                        <MultiSelect v-model="form.indicadores" :options="indicadores" optionLabel="name"
                                            placeholder="Selecciona los Indicadores" :maxSelectedLabels="3" class="w-full" required/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="estado">Logo</label>
                                        <input type="file" name="image" @change="handleFileUpload">
                                    </div>

                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('empresas.index'));" />
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
import MultiSelect from 'primevue/multiselect';
import Dropdown from 'primevue/dropdown';
import axios from 'axios';

export default {
    name: "Create",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        Dropdown,
        MultiSelect
    },
    props: {
        estados: [],
        indicadores: [],
    },
    data() {
        return {
            selectedIndicadores: [],
            form: {
                name: "",
                nit: "",
                estado: "",
                indicadores: "",
            },
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.formData.append("name", this.form.name);
            this.formData.append("nit", this.form.nit);
            this.formData.append("estado", this.form.estado.id);
            axios.post('/api/upload-image', this.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                this.$inertia.get(route('empresas.index'));
            }).catch(error => {
                console.log(error.response.data);
            });
        },

        handleFileUpload(event) {
            this.formData = new FormData();
            this.formData.append('image', event.target.files[0]);
        },
    }
}
</script>

<style scoped></style>