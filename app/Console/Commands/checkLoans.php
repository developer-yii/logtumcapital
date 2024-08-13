<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Loan;
use App\Models\LoanRequest;
use Illuminate\Support\Facades\Log;

class CheckLoans extends Command
{
    protected $signature = 'loans:check';
    protected $description = 'Check loans and update statuses based on the first installment date.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Loans and loan requests status check started.');

        // Get all loans with a status of 2
        $loans = Loan::where('status', 2)->get();

        foreach ($loans as $loan) {

            $disbursementDate = Carbon::parse($loan->first_installment_date)->subWeek()->format('Y-m-d');
            $todayDate = Carbon::now()->format('Y-m-d');

            if ($disbursementDate == $todayDate) {
                // Update all loan requests status
                $loanRequests = LoanRequest::where('loan_id', $loan->id)->get();


                // If there is only one request, update the loan status as well
                if ($loanRequests->count() == 1) {
                    $loan->status = 4;
                    $loan->save();

                    foreach ($loanRequests as $request) {
                        $request->status = 4;
                        $request->save();
                    }
                }else{
                    $lastRequest = $loanRequests->sortByDesc('created_at')->first();
                    if ($lastRequest) {
                        $lastRequest->status = 4;
                        $lastRequest->save();
                    }
                }
            }
        }

        Log::info('Loans and loan requests status check ended.');
    }
}
