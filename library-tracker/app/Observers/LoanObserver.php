<?php

namespace App\Observers;

use App\Models\Loan;

class LoanObserver
{
    /**
     * Handle the Loan "created" event.
     */
    public function created(Loan $loan): void
    {
        $loan->due_at = $loan->loaned_at->addDays(15);
        $loan->update();
    }

    /**
     * Handle the Loan "updated" event.
     */
    public function updated(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "deleted" event.
     */
    public function deleted(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "restored" event.
     */
    public function restored(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "force deleted" event.
     */
    public function forceDeleted(Loan $loan): void
    {
        //
    }
}
