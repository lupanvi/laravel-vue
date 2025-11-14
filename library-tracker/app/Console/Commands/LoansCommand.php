<?php

namespace App\Console\Commands;

use App\Mail\NotifyUser;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LoansCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:loans-command';

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
        $loans = Loan::query()
            ->with(['user'])
            ->whereNull('returned_at')
            //->where('returned_at', null)
            ->where('due_at','<', now())
            ->get();

        foreach($loans as $loan){
            Mail::send(new NotifyUser($loan->user));
        }
    }
}
