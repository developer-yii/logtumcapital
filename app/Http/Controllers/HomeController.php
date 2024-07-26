<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        if($user->role == 1){
            $totalEmployees = User::where('role', 4)->count();
            $totalCompanies = Company::count();
            $totalInvestors = Investment::count();
            return view('home', compact('totalEmployees', 'totalCompanies', 'totalInvestors'));
        }else if($user->role == 2){
            return redirect()->route('employee.getCompanyEmployees');
        }else if($user->role == 3){
            return redirect()->route('paymentcollector.getPaymentCollections');
        }else if($user->role == 4){
            return redirect()->route('employee.loanTerms');
        }
    }
}
