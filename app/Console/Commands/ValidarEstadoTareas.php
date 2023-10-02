<?php

namespace App\Console\Commands;

use App\Models\Tarea;
use Illuminate\Console\Command;

class ValidarEstadoTareas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:validar-estado-tareas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $current_date = date('Y-m-d');
        $tareas = Tarea::all();
        foreach ($tareas as $key => $tarea) {
            if($current_date > $tarea->fecha_fin){
                $tarea->estado_id = 7;
                $tarea->save();
            }
        }
        $this->info('Los estados de las tareas se han actualizado correctamente.');
    }
}
