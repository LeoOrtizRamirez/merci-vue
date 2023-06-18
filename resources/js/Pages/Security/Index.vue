<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

import JetDialogModal from "@/Jetstream/DialogModal";
import JetButton from "@/Jetstream/Button";

import LaraDashTable from '@/Components/Table';

</script>

<template>
    <AppLayout title="Permisos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Permisos
            </h2>
            {{ props }}
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">


                        <LaraDashTable>
                            <template #col>
                                <th class="px-4 py-3 text-xs">ID</th>
                                <th class="px-4 py-3 text-xs">Nombre</th>
                            </template>
                            <template #row>
                                <tr v-for="p in permissions" :key="p.id" class="text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-300">
                                    <td px-4 py-1 text-xs>{{ p.id }}</td>
                                    <td px-4 py-1 text-xs>{{ p.name }}</td>
                                    <td px-4 py-1 text-xs>{{ p.name }}</td>
                                    <td px-4 py-1 text-xs>{{ p.name }}</td>
                                    <td px-4 py-1 text-xs>{{ p.name }}</td>
                                </tr>
                            </template>
                        </LaraDashTable>


                    </div>
                </div>
            </div>
        </div>



    <jet-dialog-modal :show="modalOpen">
        <template v-slot:title>
            <h1>Eliminar Permiso</h1>
        </template> 
        <template v-slot:content>
            <p v-if="selectPermission">¿Seguro que quieres eliminar el registro: <strong>{{ selectPermission.name }}</strong>?</p>
        </template> 
        <template v-slot:footer>
            <jet-button @click="modalOpen=false">Cerrar</jet-button>
            <jet-button class="bg-red-500 hover:bg-red-800" @click="deletePermission()">Eliminar</jet-button>
        </template>    
    </jet-dialog-modal> 



    </AppLayout>
</template>
<script>
export default {
    props:{
        permissions:[]
    },
    data(){
        return{
            modalOpen:false,
            selectPermission:Object
        }
    },
    components:{
        AppLayout,
        Link,
        JetDialogModal,
        JetButton
    },
    methods:{
        deletePermission: function(){
            //if(!confirm("¿Seguro que quieres eliminar el usuario?"));
            Inertia.delete(route("permission.destroy", {permission : this.selectPermission}))
            this.modalOpen = false
        }
    }
}
</script>
