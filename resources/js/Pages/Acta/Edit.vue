<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Acta</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6" :style="{ display: isAdmin ? 'unset' : 'none' }">
                                        <label for="user_id">Usuario</label>
                                        <Dropdown @change="getEmpresas()" v-model="form.user" :options="usuarios"
                                            optionLabel="name" placeholder="Selecciona un usuario" class="w-full"
                                            required />
                                    </div>
                                    <div class="field col-12 md:col-6" :style="{ display: isAdmin ? 'unset' : 'none' }">
                                        <label for="empresa_id">Empresa</label>
                                        <Dropdown v-model="form.empresa" :options="empresas" optionLabel="name"
                                            placeholder="Selecciona una empresa" class="w-full" required />
                                    </div>
                                    <div class="field col-12 md:col-4">
                                        <label for="fecha">Fecha</label>
                                        <InputText v-model="form.fecha" id="fecha" type="date" />
                                    </div>
                                    <div class="field col-12 md:col-4">
                                        <label for="hora_inicio">Hora de inicio</label>
                                        <InputText v-model="form.hora_inicio" id="hora_inicio" type="time" />
                                    </div>
                                    <div class="field col-12 md:col-4">
                                        <label for="hora_finalizacion">Hora de finalización</label>
                                        <InputText v-model="form.hora_finalizacion" id="hora_finalizacion" type="time" />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="name">Número de la sesión</label>
                                        <InputText v-model="form.numero_sesion" id="name" type="text" required />
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="modalidad_encuentro">Modalidad de encuentro</label>
                                        <Dropdown v-model="form.modalidad_encuentro" :options="modalidades"
                                            placeholder="Selecciona una modalidad" class="w-full" required />
                                    </div>
                                    <div class="field col-12 md:col-12">
                                        <label for="asistentes">Asistentes</label>
                                        <TextArea v-model="form.asistentes" rows="2" cols="30"
                                            class="w-full p-inputtextarea p-inputtext p-component" />
                                    </div>
                                    <div class="field col-12 md:col-12">
                                        <label for="temas">Temas tratados en la sesión</label>
                                        <TextArea v-model="form.temas" rows="3" cols="30"
                                            class="w-full p-inputtextarea p-inputtext p-component" />
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="backPage()" />
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
import TextArea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import axios from "axios";

export default {
    name: "Edit",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
        TextArea,
        Dropdown
    },
    props: {
        acta: [],
        errors: Object,
        current_empresa: "",
        current_user: "",
        usuarios: [],
        empresas: []
    },
    data() {
        return {
            form: {
                numero_sesion: this.acta.numero_sesion,
                fecha: this.acta.fecha,
                hora_inicio: this.acta.hora_inicio,
                hora_finalizacion: this.acta.hora_finalizacion,
                modalidad_encuentro: this.acta.modalidad_encuentro,
                asistentes: this.acta.asistentes,
                temas: this.acta.temas,
            },
            modalidades: ['Presencial', 'Virtual', 'Mixta'],
            isAdmin: false,
            empresas: this.empresas
        }
    },
    mounted() {
        const empresaSeleccionada = this.empresas.find(empresa => empresa.id === this.current_empresa.id);
        if (empresaSeleccionada) {
            this.form.empresa = empresaSeleccionada;
        }

        const usuarioSeleccionado = this.usuarios.find(usuario => usuario.id === this.current_user.id);
        if (usuarioSeleccionado) {
            this.form.user = usuarioSeleccionado;
        }

        if (this.$page.props.auth.roles[0] == "ADMIN") {
            this.isAdmin = true
        }
    },
    methods: {
        submit() {
            this.$inertia.put(route('actas.update', { 'acta': this.acta }), this.form);
        },
        getEmpresas() {
            axios.get('/users/empresas?user_id=' + this.form.user.id).then(response => {
                if (response.data) {
                    console.log(response.data)
                    this.empresas = response.data
                }
            })
        },
        backPage(){
            if(this.isAdmin){
                this.$inertia.get(this.route('actas.index'));
            }else{
                this.$inertia.get(this.route('empresas.show', this.current_empresa.id));
            }
        }
    }
}
</script>

<style scoped></style>