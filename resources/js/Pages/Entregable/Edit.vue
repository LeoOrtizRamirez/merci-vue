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
        empresa: [],
        estados: [],
        errors: Object
    },
    data() {
        return {
            form: {
                name: this.empresa.name,
                nit: this.empresa.nit,
                estado: this.empresa.estado,
            },
        }
    },
    mounted() {
        const estadoSeleccionado = this.estados.find(estado => estado.id === this.empresa.estado_id);
        if (estadoSeleccionado) {
            this.form.estado = estadoSeleccionado;
        }
    },
    methods: {
        submit() {
            this.$inertia.put(route('empresas.update', { 'empresa': this.empresa }), this.form);
        },
    }
}
</script>

<style scoped></style>