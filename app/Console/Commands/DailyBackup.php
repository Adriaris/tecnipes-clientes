<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Exception;

class DailyBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a daily backup and keep only the latest 7 backups';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Ejecuta el comando de respaldo
            Artisan::call('backup:run', ['--only-db' => true]);

            // Obtener la ruta del archivo de respaldo generado
            $backupPath = storage_path('app/Laravel');  // Directorio donde se guardan los respaldos

            // Encuentra el archivo de respaldo más reciente
            $files = Storage::disk('local')->files('Laravel');
            $latestBackup = collect($files)->last();

            if (!$latestBackup || !Storage::disk('local')->exists($latestBackup)) {
                throw new Exception("No se encontró el archivo de respaldo.");
            }

            // Ruta completa del archivo de respaldo
            $backupFilePath = $backupPath . '/' . basename($latestBackup);

            // Genera el archivo zip en el directorio correcto
            $zip = new ZipArchive();
            $zipFileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.zip';
            $zipFilePath = storage_path('app/backups/' . $zipFileName);

            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
                throw new Exception("No se pudo crear el archivo zip en la ruta $zipFilePath.");
            }

            if (!$zip->addFile($backupFilePath, basename($backupFilePath))) {
                throw new Exception("No se pudo añadir el archivo al zip.");
            }

            $zip->close();

            // Eliminar el archivo de respaldo original para evitar duplicaciones
            Storage::disk('local')->delete($latestBackup);

            // Eliminar respaldos antiguos, mantener solo los últimos 7 en el directorio correcto
            $backups = Storage::disk('local')->files('backups');
            if (count($backups) > 7) {
                $oldBackups = collect($backups)->sortBy('timestamp')->slice(0, count($backups) - 7);
                foreach ($oldBackups as $oldBackup) {
                    Storage::disk('local')->delete($oldBackup);
                }
            }

            $this->info('Backup created successfully and old backups deleted.');
        } catch (Exception $e) {
            $this->error('Error creating backup: ' . $e->getMessage());
        }

        return 0;
    }
}
