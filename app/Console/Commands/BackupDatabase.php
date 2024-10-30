<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database and store it in the storage directory';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Configuración de la base de datos
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $database = config('database.connections.mysql.database');

            // Ruta de mysqldump
            $mysqldumpPath = 'C:/xampp/mysql/bin/mysqldump.exe'; // Ajusta esta ruta según donde esté instalado mysqldump

            // Nombre del archivo de respaldo
            $backupFile = storage_path('app/Laravel') . '/backup_' . date('Y-m-d_H-i-s') . '.sql';

            // Comando de mysqldump
            $command = "\"$mysqldumpPath\" --host=$host --port=$port --user=$username --password=$password $database > $backupFile";

            // Ejecutar el comando
            exec($command, $output, $returnVar);

            // Verificar si el comando se ejecutó correctamente
            if ($returnVar !== 0) {
                throw new Exception("Error ejecutando mysqldump: " . implode("\n", $output));
            }

            $this->info("Backup created successfully: $backupFile");
        } catch (Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
