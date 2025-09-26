<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Message;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Borrar permanentemente mensajes que han estado en la papelera por más de 7 días
        $schedule->call(function () {
            // Elimina permanentemente mensajes con soft delete que tienen 7 días de antigüedad
            Message::onlyTrashed()
                ->where('deleted_at', '<=', now()->subDays(7))
                ->forceDelete();
        })->daily()->at('01:00'); // Ejecutar todos los días a la 01:00 AM
    }
}