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
                                            placeholder="Selecciona los Indicadores" :maxSelectedLabels="3" class="w-full"
                                            required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="file-upload" class="file-title">Logo</label>
                                        <label for="file-upload" class="custom-file-upload import-entregable">
                                            <span class="pi pi-upload"></span>
                                            <span>{{ fileName }}</span>
                                            <input id="file-upload" name="image" ref="archivo" type="file"
                                                class="input-file" @change="handleFileUpload">
                                        </label>
                                        <br>
                                        <small class="p-invalid" v-if="submitted && !form.image">Logo es
                                            requerido.</small>
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('empresas.index'));" />
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
import MultiSelect from 'primevue/multiselect';
import axios from 'axios';

export default {
    name: "Edit",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        Dropdown,
        MultiSelect
    },
    props: {
        empresa: [],
        indicadores: [],
        current_empresa: [],
        empresa_indicadores_ids: [],
        estados: [],
        errors: Object
    },
    data() {
        return {
            form: {
                name: this.empresa.name,
                nit: this.empresa.nit,
                estado: this.empresa.estado,
                indicadores: this.current_empresa.indicadores,
                image: this.empresa.logo
            },
            fileName: this.empresa.logo,
            formData: new FormData(),
            submitted: false,
        }
    },
    mounted() {
        const estadoSeleccionado = this.estados.find(estado => estado.id === this.empresa.estado_id);
        if (estadoSeleccionado) {
            this.form.estado = estadoSeleccionado;
        }

        console.log()
        const indicadoresSeleccionados = this.indicadores.filter(indicador => this.empresa_indicadores_ids.includes(indicador.id));
        if (indicadoresSeleccionados) {
            this.form.indicadores = indicadoresSeleccionados;
        }
    },
    methods: {
        submit() {
            /* this.$inertia.put(route('empresas.update', { 'empresa': this.empresa }), this.form); */
            this.submitted = true
            if (this.form.estado && this.form.name && this.form.nit && this.form.image) {
                this.formData.append("id", this.empresa.id);
                this.formData.append("name", this.form.name);
                this.formData.append("nit", this.form.nit);
                this.formData.append("estado", this.form.estado.id);
                this.formData.append("indicadores", JSON.stringify(this.form.indicadores));
                axios.post('/api/update-image', this.formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.$toast.add({
                            severity: "success",
                            summary: "Exitoso!",
                            detail: "Empresa actualizada",
                            life: 3000,
                        });
                    this.$inertia.get(route('empresas.index'));
                }).catch(error => {
                    console.log(error.response.data);
                    this.$toast.add({
                        severity: "error",
                        summary: "Error",
                        detail: "La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif, svg",
                        life: 3000,
                    });
                    this.form.image = ""
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
    }
}
</script>

<style scoped></style>