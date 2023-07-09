<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Actividad</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="categoria">Categoria</label>
                                        <Dropdown v-model="form.categoria" :options="categorias" optionLabel="name"
                                            placeholder="Selecciona un Categoria" class="w-full" required />
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('actividades.index'));" />
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
    props: {
        categorias: []
    },
    data() {
        return {
            form: {
                name: "",
                categoria: "",
            },
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.$inertia.post(route('actividades.store'), this.form);
        },
    }
}
</script>

<style scoped></style>