<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Empresa;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use stdClass;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $acta_id = 1;
        $total_actas = 0;
        $logo = "/images/logo-merci.png";
        if(isset($request->empresa_id)){
            $logo = "/images/" . Empresa::find($request->empresa_id)->logo;
            $actas = Acta::where('empresa_id', $request->empresa_id)
            ->where('user_id', Auth::user()->id)
            ->get();
            if(isset($actas[0])){
                $acta_id = $actas->last()->id;
                $total_actas = sizeof($actas);
            }
        }

        $user = Auth::user();
        $indicadores = $user->indicadores;

        $result = DB::table('tareas as t')
        ->join('actividades as a', 'a.id', '=', 't.actividad_id')
        ->join('categorias as c', 'c.id', '=', 'a.categoria_id')
        ->select(DB::raw('SUM(IF(t.estado_id = 6, 1, 0)) AS tareas_terminadas'), DB::raw('count(t.id) as tareas_totales'), 'c.name as categoria_name')
        ->where('acta_id', '=', $acta_id)
        ->groupBy('c.name')
        ->get();

        $dataVentasPresupuesto = [];
        $dataTtlCotizaciones = [];
        $dataEfectividadComercial = [];
        $dataClientesNuevos = [];

        $chartVentasPresupuesto = [];
        $chartTtlCotizaciones = [];
        $chartEfectividadComercial = [];
        $chartClientesNuevos = [];

        $indicadores_ids = [];
        foreach ($indicadores as $key => $item) {
            $indicadores_ids[] = $item->indicador->id;
            switch ($item->indicador->id) {
                case 1: //Ventas vs Presupuesto
                    $dataVentasPresupuesto = $item->datos;
                    $chartVentasPresupuesto = [
                        "labels" => array_column($dataVentasPresupuesto->toArray(), 'mes'),
                        "datasets" => [
                            [
                                "type" => "line",
                                "label" => "PRESUPUESTO",
                                "borderColor" => "#42A5F5",
                                "borderWidth" => 2,
                                "fill" => false,
                                "data" => array_column($dataVentasPresupuesto->toArray(), 'data_1')
                            ],
                            [
                                "type" => "bar",
                                "label" => "VENTAS",
                                "backgroundColor" => "#581dfc",
                                "data" => array_column($dataVentasPresupuesto->toArray(), 'data_2'),
                                "borderColor" => "white",
                                "borderWidth" => 2
                            ]
                        ]
                    ];
                    break;
                case 2: //Ventas vs Presupuesto
                    $dataTtlCotizaciones = $item->datos;
                    $chartTtlCotizaciones = [
                        "labels" => array_column($dataTtlCotizaciones->toArray(), 'mes'),
                        "datasets" => [
                            [
                                "type" => 'line',
                                "label" => 'N COTIZACIONES',
                                "borderColor" => '#42A5F5',
                                "borderWidth" => 2,
                                "fill" => false,
                                "data" => array_column($dataTtlCotizaciones->toArray(), 'data_1')
                            ],
                            [
                                "type" => 'bar',
                                "label" => 'TTL COTIZACIONES',
                                "backgroundColor" => '#581dfc',
                                "data" => array_column($dataTtlCotizaciones->toArray(), 'data_2'),
                                "borderColor" => 'white',
                                "borderWidth" => 2
                            ]
                        ]
                    ];
                    break;
                case 3: //Ventas vs Presupuesto
                    $dataEfectividadComercial = $item->datos;
                    $chartEfectividadComercial = [
                        'labels' => array_column($dataEfectividadComercial->toArray(), 'mes'),
                        'datasets' => [
                            [
                                'label' => 'VENTAS',
                                'data' => array_column($dataEfectividadComercial->toArray(), 'data_1'),
                                'fill' => true,
                                'borderColor' => '#581dfc',
                                'tension' => 0.4,
                                'backgroundColor' => '#581dfca6'
                            ]
                        ]
                    ];
                    break;
                case 4: //Ventas vs Presupuesto
                    $dataClientesNuevos = $item->datos;
                    $chartClientesNuevos = [
                        'labels' => array_column($dataClientesNuevos->toArray(), 'mes'),
                        'datasets' => [
                            [
                                'label' => 'CLIENTES',
                                'backgroundColor' => '#581dfc',
                                'data' => array_column($dataClientesNuevos->toArray(), 'data_1')
                            ]
                        ]
                    ];
                    break;
                default:
                    # code...
                    break;
            }
        }
        return Inertia::render('Dashboard', compact('chartVentasPresupuesto', 'chartTtlCotizaciones', 'chartEfectividadComercial', 'chartClientesNuevos', 'indicadores_ids', 'result', 'acta_id', 'total_actas', 'logo'));
    }
}
