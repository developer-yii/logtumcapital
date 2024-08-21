<?php

namespace App\Console\Commands;

use App\Mail\SendFundRequestNotificationMail;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckCompanyIoweyouExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company-ioweyou-expiry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check company ioweyou document expiry date and notify superadmin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("check company expiry date of ioweyou document started.");
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $companyIoweyouData = Company::select('name', 'ioweyou_expiry_date')->where('ioweyou_expiry_date', $tomorrow)->get();

        $html = '';
        if(!empty($companyIoweyouData)){
            $html .= '<table border="1" cellpadding="8" cellspacing="0">';
            $html .= '<thead><tr><th>Company Name</th><th>Expiry Date</th></tr></thead>';
            $html .= '<tbody>';
            foreach($companyIoweyouData as $data){
                $html .= '<tr><td>'.$data->name.'</td><td>'.date('d-m-Y', strtotime($data->ioweyou_expiry_date)).'</td></tr>';
            }
            $html .= '</tbody></table>';
        }

        if(!empty($html)){
            $superAdminMailData = [
                'subject' => 'Reminder: Company IOweYou Document is Expiring Tomorrow',
                'message' => $html,
                'ioweyou_expiry_check' => true
            ];
            $superAdminMail = config('app.super_admin_mail');
            Mail::to($superAdminMail)->send(new SendFundRequestNotificationMail($superAdminMailData));
        }

        Log::info("check company expiry date of ioweyou document ended.");
    }
}
