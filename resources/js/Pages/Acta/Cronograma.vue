<template>
    <div>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <h4>Acta</h4>
                    <!-- <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Número de la sesión:</p>
                        <p class="ml-2">{{ acta.numero_sesion }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Fecha: </p>
                        <p class="ml-2"> {{ acta.fecha }} </p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Hora de inicio:</p>
                        <p class="ml-2">{{ acta.hora_inicio }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Hora de finalización:</p>
                        <p class="ml-2">{{ acta.hora_finalizacion }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Modalidad de encuentro:</p>
                        <p class="ml-2">{{ acta.modalidad_encuentro }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Asistentes:</p>
                        <p class="ml-2">{{ acta.asistentes }}</p>
                    </div>
                    <div class="flex justify-between items-center px-2 py-2">
                        <p class="m-0 font-bold">Temas tratados en la sesión:</p>
                        <p class="ml-2">{{ acta.temas }}</p>
                    </div> -->

                    <table class="border-1" summary="Análisis de ventas anuales">
                        <thead>
                            <tr>
                                <th class="border-1" rowspan="2" scope="col">AÑO</th>
                                <th class="border-1" colspan="4" scope="col">Expansión de ventas</th>
                            </tr>
                            <tr>
                                <th class="border-1" scope="col">Producto A</th>
                                <th class="border-1" scope="col">Producto B</th>
                                <th class="border-1" scope="col">Producto C</th>
                                <th class="border-1" scope="col">Producto D</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th class="border-1" rowspan="2" scope="col">AÑO</th>
                                <th class="border-1" scope="col">Producto A</th>
                                <th class="border-1" scope="col">Producto B</th>
                                <th class="border-1" scope="col">Producto C</th>
                                <th class="border-1" scope="col">Producto D</th>
                            </tr>
                            <tr>
                                <th class="border-1" colspan="4" scope="col">Expansión de ventas</th>
                            </tr>
                        </tfoot>

                        <tbody>
                            <tr>
                                <th class="border-1" scope="row">N-3</th>
                                <td class="border-1">-</td>
                                <td class="border-1">-</td>
                                <td class="border-1">-</td>
                                <td class="border-1">-</td>
                            </tr>
                            <tr>
                                <th scope="row">N-2</th>
                                <td class="border-1">3</td>
                                <td class="border-1">5</td>
                                <td class="border-1">8</td>
                                <td class="border-1">4</td>
                            </tr>
                            <tr>
                                <th scope="row">N-1</th>
                                <td class="border-1">4</td>
                                <td class="border-1">4</td>
                                <td class="border-1">7</td>
                                <td class="border-1">3</td>
                            </tr>
                            <tr>
                                <th scope="row">N</th>
                                <td class="border-1">5</td>
                                <td class="border-1">7</td>
                                <td class="border-1">6</td>
                                <td class="border-1">2</td>
                            </tr>
                        </tbody>
                    </table>


                    <div class="Table">
                        <div class="Heading">
                            <div class="Cell">
                                <p>Categoría</p>
                            </div>
                            <div class="Cell">
                                <p>Actividades</p>
                            </div>
                            <div class="Cell">
                                <p>Tareas</p>
                            </div>
                        </div>
                        <div class="Row" v-for="(categoria, categoriaIndex) in categorias" :key="categoriaIndex">
                            <div class="Cell categorias">{{ categoria.name }}</div>
                            <div class="Cell actividades">
                                <div class="Row actividades"
                                    v-for="actividad in actividades.filter(a => a.categoria_id === categoria.id)"
                                    :key="actividad.id">
                                    <div class="Cell">{{ actividad.name }}</div>
                                </div>
                            </div>
                            <div class="Cell tareas">
                                <div v-for="actividad in actividades.filter(a => a.categoria_id === categoria.id)">
                                    <div class="Row tareas" v-for="tarea in tareas" :key="tarea.id">
                                        <div v-if="tarea.actividad_id == actividad.id">
                                            <div class="Cell">{{ tarea.descripcion }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <table>
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Actividades</th>
                                <th>Tareas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(categoria, categoriaIndex) in categorias" :key="categoriaIndex">
                                <div class="Cell">{{ categoria.name }}</div>
                                <div class="Cell">
                                    <table>
                                        <tbody>
                                            <tr v-for="actividad in actividades.filter(a => a.categoria_id === categoria.id)"
                                                :key="actividad.id">
                                                <div class="Cell">{{ actividad.name }}</div>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="Cell">
                                    <table>
                                        <tbody>
                                            <div
                                                v-for="actividad in actividades.filter(a => a.categoria_id === categoria.id)">
                                                <tr v-for="tarea in tareas" :key="tarea.id">
                                                    <div v-if="tarea.actividad_id == actividad.id">
                                                        <div class="Cell">{{ tarea.descripcion }}</div>
                                                    </div>
                                                </tr>
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </tr>
                        </tbody>
                    </table>

                    <table class="border-collapse border-slate-400">
                        <thead>
                            <tr>
                                <th>CATEGORÍA</th>
                                <th>ACTIVIDAD - TÁCTICA</th>
                                <th>TAREAS</th>
                                <th>RESPONSABLE</th>
                                <th>FECHA INICIO</th>
                                <th>FECHA FIN</th>
                                <th>ESTADO</th>
                                <th>FECHA REAL DE FINALIZACIÓN</th>
                                <th>DESVIACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoria in categorias">
                                <div class="Cell">
                                    {{ categoria.name }}
                                </div>
                                <div class="Cell">
                                    <div v-for="categoria in categorias">
                                        <div v-for="actividad in actividades">
                                            <div v-if="categoria.id == actividad.categoria_id">
                                                {{ actividad.name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="Cell">
                                    <div v-for="tarea in tareas">
                                        {{ tarea.descripcion }}
                                    </div>
                                </div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import DataTable from "primevue/datatable";
import { FilterMatchMode } from "primevue/api";
import DatatableService from "../../Services/DatatableService";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import Button from "primevue/button";
import DeleteDialog from "../../Components/DeleteDialog";
import Toast from 'primevue/toast';
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Dropdown from 'primevue/dropdown';
import axios from "axios";
import Tag from 'primevue/tag';

export default {
    name: "Index",
    layout: AppLayout,
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        Button,
        DeleteDialog,
        Toast,
        Dialog,
        InputText,
        Dropdown,
        Tag
    },
    props: {
        acta: [],
        tareas: [],
        actividades: [],
        categorias: [],
    },
    data() {
        return {
            rowSpanCategorias: 0
        }
    },
    created() {
        console.log("categorias", this.categorias[0])
        console.log("actividades", this.actividades[0])
        console.log("tareas", this.tareas[0])
    },
    mounted() {
    },
    methods: {
    }
}
</script>

<style scoped>
.Table {
    display: table;
}

.Title {
    display: table-caption;
    text-align: center;
    font-weight: bold;
    font-size: larger;
}

.Heading {
    display: table-row;
    font-weight: bold;
    text-align: center;
}

.Row {
    display: table-row;
}

.Cell {
    display: table-cell;
    border: solid;
    border-width: thin;
    padding-left: 5px;
    padding-right: 5px;
}

.Row.actividades div {
    border: none !important;
    padding: 0px !important;
}

.Row.tareas div {
    border: none !important;
    padding: 0px !important;
}</style>