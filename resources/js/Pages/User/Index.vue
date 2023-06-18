<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

import JetDialogModal from "@/Jetstream/DialogModal";
import JetButton from "@/Jetstream/Button";

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
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200 overflow-x-auto">
                        <Link v-permission="'user.create'" :href="route('user.create')" class="btn btn-sm btn-primary mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </Link>
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="p-3">Nombre</th>
                                    <th class="p-3">Email</th>
                                    <th class="p-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="u in users" :key="u.id">
                                    <td class="p-3 border">{{ u.name }}</td>
                                    <td class="p-3 border">{{ u.email }}</td>
                                    <td class="p-3 border">
                                        <Link v-permission="'user.show'" :href="route('user.show', { person: u})" class="btn btn-sm btn-primary mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link v-permission="'user.edit'" :href="route('user.edit', { person: u})" class="btn btn-sm btn-success mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>
                                        <button v-permission="'user.destroy'" @click="modalOpen=true;selectUser=u" class="btn btn-sm btn-danger mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    <jet-dialog-modal :show="modalOpen">
        <template v-slot:title>
            <h1>Eliminar Usuario</h1>
        </template> 
        <template v-slot:content>
            <p v-if="selectUser">¿Seguro que quieres eliminar el usuario: <strong>{{ selectUser.name }}</strong>?</p>
        </template> 
        <template v-slot:footer>
            <jet-button @click="modalOpen=false">Cerrar</jet-button>
            <jet-button class="bg-red-500 hover:bg-red-800" @click="deletePerson()">Eliminar</jet-button>
        </template>    
    </jet-dialog-modal> 



    </AppLayout>
</template>
<script>
export default {
    props:{
        users:[],
    },
    data(){
        return{
            modalOpen:false,
            selectUser:Object
        }
    },
    components:{
        AppLayout,
        Link,
        JetDialogModal,
        JetButton
    },
    methods:{
        deletePerson: function(){
            //if(!confirm("¿Seguro que quieres eliminar el usuario?"));
            Inertia.delete(route("user.destroy", {person : this.selectUser}))
            this.modalOpen = false
        }
    }
}
</script>
