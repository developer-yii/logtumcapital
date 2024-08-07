<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\InterestRate;
use App\Models\Investment;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanRequest;
use App\Models\User;

if (! function_exists('pr')) {
    function pr($data){
        echo "<pre>";
        print_r($data);
        exit();
    }
}
if (! function_exists('cacheclear')) {
    function cacheclear()
    {
        return time();
    }
}
if (! function_exists('viewBalanceFormatted')) {
    function viewBalanceFormatted($balance = 0){
        return ($balance)? number_format($balance, 2, '.', ','):0;
    }
}
if (! function_exists('getDateFormateView')) {
    function getDateFormateView($date){
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
    }
}
if (! function_exists('addPageJsLink')) {
    function addPageJsLink($link){
        return asset('js/pages')."/".$link.'?'.time();
    }
}
if (! function_exists('getFooterCopyrightText')) {
    function getFooterCopyrightText(){
        $appName = config('app.name');
        return '© '.date('Y').' '.$appName.'. All rights reserved';
    }
}
if (!function_exists('isActiveRouteMain')) {
    function isActiveRouteMain($routeNames = "")
    {
        if($routeNames){
            if (is_array($routeNames)) {
                foreach ($routeNames as $routeName) {
                    if (request()->routeIs($routeName)) {
                        return 'menuitem-active';
                    }
                }
            } else {
                if (request()->routeIs($routeNames)) {
                    return 'menuitem-active';
                }
            }
        }
        return '';
    }
}
if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routeNames = "")
    {
        if($routeNames){
            if (is_array($routeNames)) {
                foreach ($routeNames as $routeName) {
                    if (request()->routeIs($routeName)) {
                        return 'active';
                    }
                }
            } else {
                if (request()->routeIs($routeNames)) {
                    return 'active';
                }
            }
        }
        return '';
    }
}
if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return Auth::user()->id==1 ? true : false;
    }
}
if (!function_exists('currencyFormatter')) {
    function currencyFormatter($amount, $prefix = true)
    {
        // Check if the decimal part is zero
        if (floor($amount) == $amount) {
            // No decimal part, format without decimals
            $formattedAmount = number_format($amount, 0, '.', ',');
        } else {
            // Decimal part exists, format with two decimal places
            $formattedAmount = number_format($amount, 2, '.', ',');
        }

        if ($prefix) {
            return '$' . $formattedAmount;
        }
        return $formattedAmount;
    }
}
if (!function_exists('getCompanyData')) {
    function getCompanyData($companyId)
    {
        $companyData = Company::select('name','authorized_credit_limit')->where('id', $companyId)->first();
        return (!empty($companyData))?$companyData:'';
    }
}
if (!function_exists('getCompanyAdminData')) {
    function getCompanyAdminData($companyId){
        $companyAdminData = User::where('company_id', $companyId)->where('role', 2)->first();
        if(!empty($companyAdminData)){
            return $companyAdminData;
        }
        return '';
    }
}
if (!function_exists('getCompanyCreditsData')) {
    function getCompanyCreditsData($id){
        if($id){
            $creditData = Company::select('name', 'authorized_credit_limit')->where('id', $id)->first();
            $companyName = !empty($creditData->name)?$creditData->name:'';
            $usedCredits = User::where('company_id', $id)->sum('authorized_credit_limit');
            return ['credit' => $creditData->authorized_credit_limit, 'usedCredit' => $usedCredits, 'availableCredit' => ($creditData->authorized_credit_limit - $usedCredits), 'companyName' => $companyName];
        }
        return '';
    }
}
if (!function_exists('getEmployeeCreditsData')) {
    function getEmployeeCreditsData($id){
        if($id){
            $creditData = User::select('authorized_credit_limit')->where('id', $id)->first();
            $pendingFundRequests = LoanRequest::where('user_id', $id)->where('status', 1)->sum('amount');
            $pendingInstallments = LoanInstallment::where('user_id', $id)->where('status',1)->sum('capital');
            $usedCredits = $pendingFundRequests + $pendingInstallments;
            return ['credit' => $creditData->authorized_credit_limit, 'usedCredit' => $usedCredits, 'availableCredit' => ($creditData->authorized_credit_limit - $usedCredits)];
        }
        return '';
    }
}
if (! function_exists('getInterestRate')) {
    function getInterestRate(){
        $interestRateData = InterestRate::select('interest_rate')->first();
        if(!empty($interestRateData->interest_rate)){
            return $interestRateData->interest_rate;
        }
        return config('app.interest_rate');
    }
}
if(! function_exists('getAvailableSuperAdminCredit')){
    function getAvailableSuperAdminCredit($id = null){
        $totalInvestment = Investment::sum('contributions');
        $totalCreditSpendToCompanies = Company::sum('authorized_credit_limit');
        if($id){
            $totalCreditSpendToCompanies = Company::where('id', '!=', $id)->sum('authorized_credit_limit');
        }
        return ($totalInvestment - $totalCreditSpendToCompanies);
    }
}
?>
