<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Cliente</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <label for="name">Nombre</label>
                                        <InputText v-model="form.name" id="name" type="text" required/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="document">Número de documento</label>
                                        <InputText v-model="form.document" id="document" type="text"/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="phone">Teléfono</label>
                                        <InputText v-model="form.phone" id="phone" type="text"/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="email">E-mail</label>
                                        <InputText v-model="form.email" id="email" type="email"/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="address">Dirección</label>
                                        <InputText v-model="form.address" id="address" type="text"/>
                                    </div>
                                    <Button label="Submit" type="submit"></Button>
                                </div>
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
    name: "Edit",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
    },
    props:{
        customer:[],
        errors: Object
    },
    data() {
        return {
            form:{
                name:this.customer.name,
                email:this.customer.email,
                document:this.customer.document,
                address:this.customer.address,
                phone:this.customer.phone,
            },
        }
    },
    mounted() {
    },
    methods: {
        submit(){
            this.$inertia.put(route('customers.update',{'customer':this.customer}),this.form);
        },
    }
}
</script>

<style scoped>
</style>