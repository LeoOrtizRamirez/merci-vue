<?php

namespace App\Http\Controllers;

use App\Datatables\ActaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Acta;
use App\Models\Actividade;
use App\Models\Categoria;
use App\Models\Estado;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\Inertia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;

class ActaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Acta/Index');
    }

    public function datatable(
        Request       $request,
        ActaDatatable $datatable
    ): JsonResponse {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Acta/Create');
    }

    public function store(Request $request)
    {
        $acta = new Acta;
        $acta->numero_sesion = $request->numero_sesion;
        $acta->fecha = $request->fecha;
        $acta->hora_inicio = $request->hora_inicio;
        $acta->hora_finalizacion = $request->hora_finalizacion;
        $acta->modalidad_encuentro = $request->modalidad_encuentro;
        $acta->asistentes = $request->asistentes;
        $acta->temas = $request->temas;
        $acta->save();
        return redirect()->route('actas.show', $acta->id);
    }

    public function show(Acta $acta)
    {
        $estados = Estado::where('tipo', 2)->get();
        $actividades = Actividade::all();
        return Inertia::render('Acta/Show', compact('acta', 'actividades', 'estados'));
    }

    public function edit(Acta $acta)
    {
        return Inertia::render('Acta/Edit', compact('acta'));
    }

    public function update(Request $request, Acta $acta)
    {
        $acta = Acta::find($acta->id);
        $acta->numero_sesion = $request->numero_sesion;
        $acta->fecha = $request->fecha;
        $acta->hora_inicio = $request->hora_inicio;
        $acta->hora_finalizacion = $request->hora_finalizacion;
        $acta->modalidad_encuentro = $request->modalidad_encuentro;
        $acta->asistentes = $request->asistentes;
        $acta->temas = $request->temas;
        $acta->save();
        return redirect()->route('actas.show', $acta->id);
    }

    public function destroy(Acta $acta)
    {
        //Buscar tareas de Acta y eliminar
        $tareas = Tarea::where("acta_id", $acta->id)->get();
        foreach ($tareas as $key => $tarea) {
            $tarea->delete();
        }
        $acta->delete();
        return redirect()->back();
    }

    public function cronograma($id)
    {
        $acta = Acta::with('tareas')->find($id);
        $tareas = $acta->tareas;

        $categorias_ids = [];
        $actividades_ids = [];

        $categorias = [];
        $actividades = [];

        foreach ($tareas as $key => $tarea) {
            if (!in_array($tarea->actividad_id, $actividades_ids)) {
                $actividades[] = $tarea->actividad;
                $actividades_ids[] = $tarea->actividad_id;
            }
            if (!in_array($tarea->actividad->categoria_id, $categorias_ids)) {
                $categorias[] = $tarea->actividad->categoria;
                $categorias_ids[] = $tarea->actividad->categoria_id;
            }
        }

        return Inertia::render('Acta/Cronograma', compact('acta', 'tareas', 'actividades', 'categorias'));
    }

    public function import(Request $request)
    {
        // Obtenemos el archivo enviado
        $file = $request->file('file');

        // Leemos el archivo Excel
        $data = Excel::toArray([], $file);

        //Crear Acta
        $acta = new Acta;
        $acta->numero_sesion = $data[0][1][0];
        $acta->fecha = $data[0][1][1];
        $acta->hora_inicio = $data[0][1][2];
        $acta->hora_finalizacion = $data[0][1][3];
        $acta->modalidad_encuentro = $data[0][1][4];
        $acta->asistentes = $data[0][1][5];
        $acta->temas = $data[0][1][6];
        $acta->save();

        // Procesamos los datos del archivo
        foreach ($data[0] as $index => $row) {
            if ($index > 3) {
                //dd($row);
                //Buscar Categoria
                $categoria = Categoria::where('name', $row[0])->first();
                if (is_null($categoria)) {
                    $categoria = new Categoria;
                    $categoria->name = $row[0];
                    $categoria->save();
                }
                //dd($categoria);
                //Buscar Actividad
                $actividad = Actividade::where('name', $row[1])->first();
                if (is_null($actividad)) {
                    $actividad = new Actividade;
                    $actividad->name = $row[0];
                    $actividad->categoria_id = $categoria->id;
                    $actividad->save();
                }
                //dd($actividad);
                //Crear Tarea
                $tarea = new Tarea;
                $tarea->descripcion = $row[2];
                $tarea->responsable = $row[3];
                $tarea->fecha_inicio = $row[4];
                $tarea->fecha_fin = $row[5];

                switch ($row[6]) {
                    case 'Sin iniciar':
                        $tarea->estado_id = 4;
                        break;
                    case 'Progreso':
                        $tarea->estado_id = 5;
                        break;
                    case 'Terminada':
                        $tarea->estado_id = 6;
                        break;
                    case 'Vencida':
                        $tarea->estado_id = 7;
                        break;
                    default:
                        $tarea->estado_id = 4;
                        break;
                }

                $tarea->fecha_finalizacion = $row[7];
                $tarea->actividad_id = $actividad->id;
                $tarea->acta_id = $acta->id;
                $tarea->save();
            }
        }

        $actas = Acta::all();
        // Retornamos una respuesta
        return response()->json([
            'message' => 'Archivo importado con éxito',
            'data' => $actas,
        ]);
    }
}
