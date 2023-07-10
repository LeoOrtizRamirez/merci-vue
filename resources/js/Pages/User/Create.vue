<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Usuario</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" placeholder="Ingresa el nombre" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="email">Correo electrónico</label>
                                        <InputText v-model="form.email" id="email" type="email" placeholder="Ingresa el correo electrónico" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="password">Contraseña</label>
                                        <InputText v-model="form.password" id="password" type="password" placeholder="Ingresa una contraseña" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="indicadores">Indicadores</label>
                                        <MultiSelect v-model="form.indicadores" :options="indicadores" optionLabel="name"
                                            placeholder="Selecciona los Indicadores" :maxSelectedLabels="3" class="w-full" required/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="rol">Rol</label>
                                        <Dropdown v-model="form.rol" :options="roles" optionLabel="name"
                                            placeholder="Selecciona una Empresa" class="w-full" required/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="empresa">Empresa</label>
                                        <Dropdown v-model="form.empresa" :options="empresas" optionLabel="name"
                                            placeholder="Selecciona una Empresa" class="w-full" required/>
                                    </div>

                                    <!-- <div class="field col-12 md:col-6">
                                        <label for="rol">Rol</label>
                                        <input type="file" name="imagen" @change="cargarImagen">
                                    </div> -->
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('users.index'));" />
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
import FileUpload from 'primevue/fileupload';

export default {
    name: "Create",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        MultiSelect,
        Dropdown,
        FileUpload
    },
    props: {
        indicadores: [],
        empresas: [],
        roles: [],
        errors: Object
    },
    data() {
        return {
            selectedIndicadores: [],
            selectedEstado: null,
            form: {
                name: "",
                indicadores: "",
                password: "",
                rePassword: "",
                email: "",
                empresa: "",
                rol: "",
            },
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.$inertia.post(route('users.store'), this.form);
        },
    }
}
</script>

<style scoped></style>