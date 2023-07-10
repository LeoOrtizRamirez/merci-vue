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
                                        <InputText v-model="form.password" id="password" type="password" placeholder="Ingresa una contraseña" />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="indicadores">Indicadores</label>
                                        <MultiSelect v-model="form.indicadores" :options="indicadores" optionLabel="name"
                                            placeholder="Selecciona los Indicadores" :maxSelectedLabels="3" class="w-full" required/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="rol">Rol</label>
                                        <Dropdown v-model="form.rol" :options="roles" optionLabel="name"
                                            placeholder="Selecciona un Rol" class="w-full" required/>
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
import MultiSelect from 'primevue/multiselect';
import Dropdown from 'primevue/dropdown';

export default {
    name: "Edit",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        MultiSelect,
        Dropdown
    },
    props: {
        user: [],
        indicadores: [],
        empresas: [],
        roles: [],
        current_user: [],
        rol: [],
        user_indicadores_ids: [],
        errors: Object
    },
    data() {
        return {
            form: {
                id: this.current_user.id,
                name: this.current_user.name,
                indicadores: this.current_user.indicadores,
                password: this.current_user.password,
                email: this.current_user.email,
                empresa: this.current_user.empresa,
                rol: this.rol,
            },
        }
    },
    mounted() {
        const empresaSeleccionada = this.empresas.find(empresa => empresa.id === this.current_user.empresa_id);
        if (empresaSeleccionada) {
            this.form.empresa = empresaSeleccionada;
        }
        const rolSeleccionado = this.roles.find(rol => rol.name === this.rol);
        if (rolSeleccionado) {
            this.form.rol = rolSeleccionado;
        }
        const indicadoresSeleccionados = this.indicadores.filter(indicador => this.user_indicadores_ids.includes(indicador.id));
        if (indicadoresSeleccionados) {
            this.form.indicadores = indicadoresSeleccionados;
        }
    },
    methods: {
        submit() {
            this.$inertia.put(route('users.update', { 'user': this.user }), this.form);
        },
    }
}
</script>

<style scoped></style>