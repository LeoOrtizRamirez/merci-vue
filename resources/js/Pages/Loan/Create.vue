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
                                        <label for="name">Cliente</label>
                                        <select name="customer_id" id="customer_id" class="block w-full" v-model="form.customer_id" required>
                                            <option value="">Selecciona un cliente</option>
                                            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{customer.name}}</option>
                                        </select>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="amount">Valor</label>
                                        <InputText v-model="form.amount" id="amount" type="number" @change="getBalance" required/>
                                    </div>
                                    
                                    <div class="field col-12 md:col-6">
                                        <label for="interest">Interes</label>
                                        <select name="interest" id="interest" class="block w-full" v-model="form.interest" @change="getBalance" required>
                                            <option value="">Selecciona un Interes</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="15">15%</option>
                                            <option value="20">20%</option>
                                        </select>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="balance">Saldo</label>
                                        <InputText v-model="form.balance" id="balance" type="balance" disabled/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="total_fee">Cuotas</label>
                                        <InputText v-model="form.total_fee" id="total_fee" type="total_fee" required/>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="way_to_pay">Forma de Pago</label>
                                        <select name="way_to_pay" id="way_to_pay" class="block w-full" v-model="form.way_to_pay" required>
                                            <option value="">Selecciona una Forma de Pago</option>
                                            <option value="1">Diario</option>
                                            <option value="7">Semanal</option>
                                            <option value="15">Quincenal</option>
                                            <option value="30">Mensual</option>
                                        </select>
                                    </div>
                                    <div class="field col-12 md:col-6">
                                        <label for="payment_date">Fecha del primer pago</label>
                                        <InputText v-model="form.payment_date" id="payment_date" type="date" required/>
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
            this.$inertia.post(route('loans.store'),this.form);
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