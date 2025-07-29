<?php
namespace App\Console\Commands;

use App\Models\Reserve;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnpaidReserves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserves:delete-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subMinutes(5);

        $deletedCount = Reserve::where('paymentStatus', 'pending')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("Deleted $deletedCount unpaid reserves older than 5 minutes.");

    }
}
