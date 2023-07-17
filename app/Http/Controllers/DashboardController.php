<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $loans = 0;
        $investment = 0;
        $gain =  0;
        $current_balance = 0;

        $user = Auth::user();
        $indicadores = $user->indicadores;

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
        return Inertia::render('Dashboard', compact('chartVentasPresupuesto', 'chartTtlCotizaciones', 'chartEfectividadComercial', 'chartClientesNuevos', 'indicadores_ids'));
    }
}
