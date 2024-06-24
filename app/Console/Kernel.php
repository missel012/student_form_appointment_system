<?php

namespace App\Console; // Declare the namespace for the Console kernel

use Illuminate\Console\Scheduling\Schedule; // Import the Schedule class from Illuminate\Console\Scheduling
use Laravel\Lumen\Console\Kernel as ConsoleKernel; // Import the ConsoleKernel class from Laravel\Lumen\Console and alias it as ConsoleKernel

class Kernel extends ConsoleKernel // Define the Kernel class extending the ConsoleKernel class
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Define an array to hold Artisan commands for the application, currently empty
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) // Define the schedule method which accepts a Schedule object
    {
        // This method is meant to define the application's scheduled tasks, currently empty
    }
}
