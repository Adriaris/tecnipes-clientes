<?php

namespace App\Console\Commands;

use App\Models\ClienteVisitado;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LimpiarClientesVistos extends Command
{
    protected $signature = 'limpiar:clientes_vistos';
    protected $description = 'Eliminar registros de clientes vistos cada 24 horas';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Calcula la fecha y hora hace 24 horas
        $fechaLimite = Carbon::now()->subHours(24);

        // Elimina los registros de clientes_vistos más antiguos de hace 24 horas
        ClienteVisitado::where('created_at', '<', $fechaLimite)->delete();

        $this->info('Registros de clientes vistos eliminados con éxito.');
    }
}
