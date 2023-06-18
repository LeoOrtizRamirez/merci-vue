<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="grid">

                    <div class="col-12">
                        <div class="card">
                            <h5>Prestamos</h5>
                            <form @submit.prevent="submit">
                                <div class="p-fluid formgrid grid">
                                    <div class="field col-12 md:col-6">
                                        <select name="user_id" id="user_id" class="mt-1 block w-full" v-model="form.user_id">
                                            <option value="">Selecciona un Usuario</option>
                                            <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
                                        </select>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="amount">Valor</label>
                                        <InputText v-model="form.amount" id="amount" type="number" @change="getBalance" required/>
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
    name: "Create",
    layout: AppLayout,
    components: {
        AppLayout,
        Button,
        InputText,
    },
    props:{
        errors: Object,
        customers:[],
    },
    props:{
        errors: Object,
        users: []
    },
    data() {
        return {
            form:{
                user_id:"",
                customer_id:"",
                amount:"",
                interest:"",
                balance:"",
                total_fee:"",
                way_to_pay:"",
                payment_date:""
            },
            
        }
    },
    mounted() {
    },
    methods: {
        submit(){
            this.$inertia.post(route('investments.store'),this.form);
        },
        getBalance:function(){
            var amount = parseInt(this.form.amount)
            var interest = parseInt(this.form.interest)
            this.form.balance = amount + (amount * interest / 100)
        },
    }
}
</script>

<style scoped>

</style>



<!--
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from "@inertiajs/inertia";

import InputError from "@/Jetstream/InputError";
import JetInput from '@/Jetstream/Input';
import JetLabel from '@/Jetstream/Label';
import JetButton from '@/Jetstream/Button';

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inversión
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mt-4">
                                <JetLabel for="user_id" value="Usuario" />
                                <input-error :message="errors.user_id"/>
                                <select name="user_id" id="user_id" class="mt-1 block w-full" v-model="form.user_id">
                                    <option value="">Selecciona un Usuario</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <JetLabel for="amount" value="Valor" />
                                <input-error :message="errors.amount"/>
                                <JetInput id="amount" v-model="form.amount" type="number" class="mt-1 block w-full" required autocomplete="current-amount"/>
                            </div>
                            <div class="mt-4">
                                <JetButton class="mt-4" type="submit">Enviar</JetButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
export default {
    props:{
        errors: Object,
        users: []
    },
    data(){
        return{
            form:{
                user_id:"",
                amount:""
            },
        };
    },
    components:{
        AppLayout,
        InputError,
        JetInput,
        JetLabel,
        JetButton
    },
    methods:{
        submit(){
            Inertia.post(route('investment.store'),this.form);
        },
    }
}
</script>
-->