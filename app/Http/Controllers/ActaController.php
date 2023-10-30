<?php

namespace App\Http\Controllers;

use App\Datatables\ActaDatatable;
use App\Http\Controllers\Controller;
use App\Models\Acta;
use App\Models\Actividade;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Estado;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;

class ActaController extends Controller
{

    public function indexV2($empresa_id, Request $request): Response
    {
        return Inertia::render('Acta/IndexV2', compact('empresa_id'));
    }

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

    public function create(Request $request): Response
    {
        if(isset($request->empresa_id)){
            $current_empresa = Empresa::find($request->empresa_id);
        }else{
            $current_empresa = Empresa::find(1);
        }
        
        $current_user = Auth::user();
        $usuarios = User::all();

        $user = Auth::user();
        $empresas_ids = $user->empresas->pluck('empresa_id')->toArray();
        $empresas = Empresa::whereIn('id', $empresas_ids)->get();
        /* $empresas = Empresa::all(); */
        return Inertia::render('Acta/Create', compact('current_empresa','current_user','usuarios', 'empresas'));
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
        $acta->user_id = $request->user["id"];
        $acta->empresa_id = $request->empresa["id"];
        $acta->save();
        return redirect()->route('actas.show', $acta->id);
    }

    public function show(Acta $acta)
    {
        $acta = Acta::where('id', $acta->id)->with('empresa')->first();
        $estados = Estado::where('tipo', 2)->get();
        $actividades = Actividade::all();
        return Inertia::render('Acta/Show', compact('acta', 'actividades', 'estados'));
    }

    public function edit(Acta $acta)
    {
        $current_empresa = Empresa::find($acta->empresa_id);
        $current_user = User::find($acta->user_id);
        
        $usuarios = User::all();

        /* $user = Auth::user();
        $empresas_ids = $user->empresas->pluck('empresa_id')->toArray();
        $empresas = Empresa::whereIn('id', $empresas_ids)->get(); */
        $empresas = Empresa::all();
        return Inertia::render('Acta/Edit', compact('acta', 'current_empresa','current_user','usuarios', 'empresas'));
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
        $acta->user_id = $request->user["id"];
        $acta->empresa_id = $request->empresa["id"];
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

    public function cronograma(Request $request)
    {
        $user = Auth::user();

        $actas = Acta::where('empresa_id', $request->empresa_id)
                ->get();
        /* if($user->getRoleNames()[0] == "CLIENTE"){
            $actas = Acta::where('empresa_id', $request->empresa_id)
            ->get();
        }else if(isset($request->empresa_id)){
            $actas = Acta::where('empresa_id', $request->empresa_id)
            ->where('user_id', $user->id)
            ->get();
        }else{
            $actas = Acta::where('user_id', $user->id)->get();
        } */
        
        $actas_ids = [];
        if($actas){
            $actas_ids = $actas->pluck('id')->toArray();
        }

        $tareas = Tarea::whereIn('acta_id', $actas_ids)->get();

        foreach ($tareas as $key => $tarea) {
            $tarea->estado_name = $tarea->estado->name;
            $tarea->estado_backgroundColor = $tarea->estado->backgroundColor;
            $tarea->actividad_name = $tarea->actividad->name;
            $tarea->categoria_name = $tarea->actividad->categoria->name;

            $segundos = strtotime($tarea->fecha_finalizacion) - strtotime($tarea->fecha_fin);
            $dias = $segundos / 86400;
            $tarea->desviacion = $dias;
        }

        $estados = Estado::where('tipo', 2)->get();
        $actividades = Actividade::all();
        return Inertia::render('Acta/Cronograma', compact('tareas', 'estados', 'actividades'));
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
        $acta->empresa_id = $request->empresa_id;
        $acta->user_id = Auth::user()->id;
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

        // Retornamos una respuesta
        return response()->json([
            'message' => 'Archivo importado con éxito',
            'data' => $acta->id,
        ]);
    }
}
