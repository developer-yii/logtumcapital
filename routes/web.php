<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyAdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterestRateController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\PaymentCollectorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

Route::post('/store-company-details', [RegisterController::class, 'registerCompany'])->name('store-company-details');

Route::middleware(['checkrole:Super Admin'])->group(function () {
    Route::prefix('company')->group(function () {
        Route::get('/get', [CompanyController::class, 'get'])->name('company.get');
        Route::post('/store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
        Route::post('/delete', [CompanyController::class, 'delete'])->name('company.delete');
        Route::post('/change-status', [CompanyController::class, 'changeStatus'])->name('company.changeStatus');
    });
    Route::prefix('investment')->group(function () {
        Route::get('/get', [InvestmentController::class, 'get'])->name('investment.get');
        Route::post('/store', [InvestmentController::class, 'store'])->name('investment.store');
        Route::get('/edit/{id}', [InvestmentController::class, 'edit'])->name('investment.edit');
        Route::post('/delete', [InvestmentController::class, 'delete'])->name('investment.delete');
    });
    Route::prefix('company-admin')->group(function () {
        Route::get('/get', [CompanyAdminController::class, 'get'])->name('companyadmin.get');
        Route::post('/store', [CompanyAdminController::class, 'store'])->name('companyadmin.store');
        Route::get('/edit/{id}', [CompanyAdminController::class, 'edit'])->name('companyadmin.edit');
        Route::post('/delete', [CompanyAdminController::class, 'delete'])->name('companyadmin.delete');
    });
    Route::prefix('loan')->group(function () {
        Route::get('/requests', [LoanRequestController::class, 'getLoanRequests'])->name('loan.requests');
        Route::post('/reject-fund-request-status', [LoanRequestController::class, 'rejectFundRequestStatus'])->name('loan.rejectFundRequestStatus');
        Route::post('/upload-ioweyou', [LoanRequestController::class, 'uploadIoweyou'])->name('loan.uploadIoweyou');
        Route::get('/pending-for-disbursed', [LoanRequestController::class, 'pendingForDisbursed'])->name('loan.pendingForDisbursed');
        Route::post('/change-loan-status', [LoanRequestController::class, 'changeLoanStatus'])->name('loan.changeLoanStatus');
        Route::get('/get-loan-details', [LoanRequestController::class, 'getLoanDetails'])->name('loan.getLoanDetails');
    });
    Route::prefix('interest-rate')->group(function () {
        Route::get('/get', [InterestRateController::class, 'get'])->name('interestrate.get');
        Route::post('/store', [InterestRateController::class, 'store'])->name('interestrate.store');
        Route::get('/edit/{id}', [InterestRateController::class, 'edit'])->name('interestrate.edit');
    });
});


Route::middleware(['checkrole:Super Admin,Company Admin'])->group(function () {
    Route::prefix('employee')->group(function () {
        Route::get('/get', [EmployeeController::class, 'get'])->name('employee.get');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::post('/delete', [EmployeeController::class, 'delete'])->name('employee.delete');
    });
});
Route::middleware(['checkrole:Company Admin'])->group(function () {
    Route::prefix('employee')->group(function () {
        Route::get('/get-employees', [EmployeeController::class, 'getCompanyEmployees'])->name('employee.getCompanyEmployees');
        Route::post('/send-request-for-limit-change', [EmployeeController::class, 'sendRequestForLimitChange'])->name('employee.sendLimitForChangeRequest');
        Route::post('/change-employee-credit-limit', [EmployeeController::class, 'changeEmployeeCreditLimit'])->name('employee.changeEmployeeCreditLimit');
    });
    Route::prefix('payment-collector')->group(function () {
        Route::get('/get', [PaymentCollectorController::class, 'get'])->name('paymentcollector.get');
        Route::post('/store', [PaymentCollectorController::class, 'store'])->name('paymentcollector.store');
        Route::get('/edit/{id}', [PaymentCollectorController::class, 'edit'])->name('paymentcollector.edit');
        Route::post('/delete', [PaymentCollectorController::class, 'delete'])->name('paymentcollector.delete');
    });
});

Route::middleware(['checkrole:Employee'])->group(function () {
    Route::prefix('employee')->group(function () {
        Route::get('/loan-terms', [EmployeeController::class, 'loanTerms'])->name('employee.loanTerms');
        Route::get('/request-fund', [EmployeeController::class, 'requestFund'])->name('employee.requestFund');
        Route::post('/store-fund-request', [EmployeeController::class, 'storeFundRequest'])->name('employee.storeFundRequest');
    });
});

Route::middleware(['checkrole:Payment Collector'])->group(function () {
    Route::prefix('payment-collector')->group(function () {
        Route::get('/payment-collections', [PaymentCollectorController::class, 'getPaymentCollections'])->name('paymentcollector.getPaymentCollections');
        Route::post('/upload-bank-receipt', [PaymentCollectorController::class, 'storeBankReceipt'])->name('paymentcollector.storeBankReceipt');
    });
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
