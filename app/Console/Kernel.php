use App\Console\Commands\SendReturnReminder;

protected $commands = [
    SendReturnReminder::class,
];

protected function schedule(Schedule $schedule)
{
    // Jadwalkan pengingat setiap hari jam 9 pagi
    $schedule->command('reminder:send-return')->dailyAt('09:00');
    
    // Atau setiap 6 jam
    // $schedule->command('reminder:send-return')->everySixHours();
}