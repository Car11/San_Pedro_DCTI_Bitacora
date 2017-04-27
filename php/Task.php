<?php

namespace App\Console;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            DB::table('recent_users')->delete();
        })->daily();
    }
}

exit;

?>


/*
create event selectVisitantes
on SCHEDULE EVERY 1 HOUR 
DO
UPDATE bitacora 
set SALIDA= now() , detalle= detalle+ '. SALIDA AUTOMATICA' 
WHERE  SALIDA IS NULL AND((time_to_sec(timediff(NOW(), ENTRADA )) /3600)>1 )
*/