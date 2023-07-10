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

export default {
    name: "Create",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        Dropdown
    },
    props:{
        estados: []
    },
    data() {
        return {
            form: {
                name: "",
                nit: "",
                estado: ""
            },
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.$inertia.post(route('empresas.store'), this.form);
        },
    }
}
</script>

<style scoped></style>