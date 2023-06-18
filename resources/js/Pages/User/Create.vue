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
                User
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mt-4">
                                <JetLabel for="name" value="Nombre" />
                                <input-error :message="errors.name"/>
                                <JetInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autocomplete="current-name"/>
                            </div>
                            <div class="mt-4">
                                <JetLabel for="email" value="Correo electrónico" />
                                <input-error :message="errors.email"/>
                                <JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autocomplete="current-email"/>
                            </div>
                            <div class="mt-4">
                                <JetLabel for="password" value="Contraseña" />
                                <input-error :message="errors.password"/>
                                <JetInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="current-password"/>
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
        errors: Object
    },
    data(){
        return{
            form:{
                name:"",
                email:"",
                password:""
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
            Inertia.post(route('user.store'),this.form);
        },
    }
}
</script>
