<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Categoria</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-12">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" required />
                                    </div>
                                </div>
                                <Button class="p-button p-component p-button-danger p-button-raised mx-2" label="Cancelar"
                                    @click="this.$inertia.get(this.route('categorias.index'));" />
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

export default {
    name: "Create",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
    },
    data() {
        return {
            form: {
                name: "",
            },
        }
    },
    mounted() {
    },
    methods: {
        submit() {
            this.$inertia.post(route('categorias.store'), this.form);
        },
    }
}
</script>

<style scoped></style>