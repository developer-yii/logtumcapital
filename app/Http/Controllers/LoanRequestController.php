<?php

namespace App\Http\Controllers;

use App\Mail\SendFundRequestNotificationMail;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use App\Models\LoanCollection;
class LoanRequestController extends Controller
{
    // get loan requests
    public function getLoanRequests(Request $request){
        if ($request->ajax()) {
            $loanRequestAccepted = LoanRequest::select(
                'loan_requests.id',
                'loan_requests.amount',
                'loan_requests.duration',
                'loan_requests.created_at',
                'loan_requests.status',
                'loan_requests.ioweyou',
                'loan_requests.bank_name',
                'loan_requests.account_number',
                'companies.name as company_name',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(CONCAT(users.middle_name, ' '), ''), users.last_name) as employee_name")
            )
            ->join('companies', 'loan_requests.company_id', '=', 'companies.id')
            ->join('users', 'loan_requests.user_id', '=', 'users.id');

            return DataTables::of($loanRequestAccepted)
                ->addIndexColumn()
                ->editColumn('amount',function($row){
                    return currencyFormatter($row->amount);
                })
                ->editColumn('ioweyou',function($row){
                    if(!empty($row->ioweyou)){
                        $extension = strtolower(pathinfo($row->ioweyou, PATHINFO_EXTENSION));
                        $img= asset('storage').'/'.$row->ioweyou;
                        // if($extension == 'pdf'){
                            return '<a class="btn custom-btn" href="'.$img.'" download>Download</a>';
                        // }
                        // return "<img class='enlarge-image pe-auto' href='".$img."' height='50px' width='50px'>";
                    }else{
                        return '-';
                    }
                })
                ->editColumn('status',function($row){
                    if($row->status == 1){
                        return "<select class='change-fund-request-status form-select w-auto' data-id='".$row->id."'>
                            <option value='1' selected>Pending</option>
                            <option value='2'>Accept</option>
                            <option value='3'>Reject</option>
                        </select>";
                    }else{
                        return LoanRequest::getLoanRequestStatusName($row->status);
                    }
                })
                ->editColumn('created_at',function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->rawColumns(['ioweyou','status'])
                ->make(true);
        }
        return view('loan.request_fund_list');
    }

    // change Loan request status
    private function changeFundRequestStatus($loanRequestData, $status, $disbursement_date=null){
        if(!empty($loanRequestData) && !empty($status)){
            $user = User::select('company_id', 'first_name', 'middle_name', 'last_name', 'email')->where('id', $loanRequestData->user_id)->first();
            $fullName = '';
            $companyAdminData = '';
            $companyAdminEmail = '';
            if(!empty($user)){
                $companyAdminData = getCompanyAdminData($user->company_id);

                if(!empty($companyAdminData)){
                    $companyAdminEmail = $companyAdminData->email;
                }

                if($user->middle_name){
                    $fullName = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                }else{
                    $fullName = $user->first_name.' '.$user->last_name;

                }
            }

            $weekText = 'weeks';
            if($loanRequestData->duration < 2){
                $weekText = 'week';
            }

            $employeeMailData = [];
            $companyAdminMailData = [];

            //  Loan request accepted
            if($status == 2){

                // request accepted -> create loan
                $responseLoanData = $this->createLoan($loanRequestData, $companyAdminData->id, $disbursement_date);

                //  create installments
                $installmentResponse = $this->createInstallments($loanRequestData, $responseLoanData, $disbursement_date);
                if(!$installmentResponse){
                    return ['status' => false, 'message' => 'Something went wrong. Please try again later.'];
                }

                if(!empty($responseLoanData->id)){
                    $loanRequestData->status = $status;
                    $loanRequestData->loan_id = $responseLoanData->id;
                    $loanRequestData->save();
                }

                $employeeMailData = [
                    'subject' => 'Loan Request Approved',
                    'message' => 'Your request of fund amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been approved.'
                ];

                $companyAdminMailData = [
                    'subject' => 'Loan Request Approved',
                    'message' => 'Employee : ' . $fullName . ' has requested funds amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been approved.'
                ];
            }
            // Loan request rejected
            elseif($status == 3){
                $loanRequestData->status = $status;
                $loanRequestData->save();
                $employeeMailData = [
                    'subject' => 'Loan Request Rejected',
                    'message' => 'Your request of fund amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been rejected.'
                ];

                $companyAdminMailData = [
                    'subject' => 'Loan Request Rejected',
                    'message' => 'Employee : ' . $fullName . ' has requested funds amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been rejected.'
                ];
            }

            Mail::to($user->email)->send(new SendFundRequestNotificationMail($employeeMailData));
            if(!empty($companyAdminEmail)){
                Mail::to($companyAdminEmail)->send(new SendFundRequestNotificationMail($companyAdminMailData));
            }
            return ['status' => true, 'message' => 'Loan request status changed successfully.'];
        }
        return ['status' => false, 'message' => 'Something went wrong. Please try again later.'];
    }

    private function createLoan($loanRequest, $companyAdminId, $disbursement_date){
        $loanData = Loan::where('user_id', $loanRequest->user_id)->first();

        if(empty($loanData)){
            $loanData = new Loan;
            $loanData->user_id = $loanRequest->user_id;
            $loanData->company_id = $loanRequest->company_id;
            $loanData->company_admin_id = $companyAdminId;
            $loanData->status = 2;
            $loanData->save();
        }

        $totalPaidInstallments = LoanInstallment::where('loan_id', $loanData->id)
            ->where('status', 2)
            ->sum('capital');

        $totalInstallmentAfterLoanDisbursed = LoanInstallment::whereDate('installment_date', '>', $disbursement_date)
            ->where('status', 1)
            ->count();

        $totalLoanAmount = $loanData->amount;
        $finalAmount = !empty($totalLoanAmount)?$totalLoanAmount + $loanRequest->amount: $loanRequest->amount;
        // $finalAmount = $loanRequest->amount;
        $finalDuration = !empty($loanData->duration)? (($loanData->duration - $totalInstallmentAfterLoanDisbursed) + $loanRequest->duration) : $loanRequest->duration;

        $loanData->amount = $finalAmount;
        $loanData->duration = $finalDuration;
        $loanData->yearly_interest_rate =  getInterestRate();
        $loanData->weekly_interest_rate =  getInterestRate()/52;
        $loanData->loan_interest_rate = (getInterestRate()/52) * $loanRequest->duration;
        $loanData->first_installment_date = Carbon::parse($disbursement_date)->copy()->addWeek(1)->format('Y-m-d');
        $loanData->last_installment_date = Carbon::parse($loanData->first_installment_date)->copy()->addWeeks(($loanRequest->duration - 1))->format('Y-m-d');
        $loanData->save();

        return $loanData;
    }
    // create installments
    // private function createInstallments($loanData, $disbursement_date){
    //     $installments = LoanInstallment::where('loan_id',$loanData->id)->whereDate('created_at', '>', $disbursement_date)->get();
    //     $installments->each(function ($installment) {
    //         $installment->delete();
    //     });
    //     $loanInterest = ($loanData->amount * $loanData->loan_interest_rate)/100;
    //     $totalAmountToPay = $loanData->amount + $loanInterest;
    //     $weeklyLoanInterestAmount = ($loanData->amount * $loanData->weekly_interest_rate)/100;
    //     $weeklyCapitalDeduct = ($loanData->amount / $loanData->duration);
    //     $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
    //     $installmentDate = $loanData->first_installment_date;

    //     for($i = 1; $i <= $loanData->duration; $i++){
    //         $loanInstallments = new LoanInstallment;
    //         $loanInstallments->loan_id = $loanData->id;
    //         $loanInstallments->user_id = $loanData->user_id;
    //         $loanInstallments->installment_date = $installmentDate;
    //         $loanInstallments->capital = $weeklyCapitalDeduct;
    //         $loanInstallments->interest = $weeklyLoanInterestAmount;
    //         $loanInstallments->payment = $installmentAmount;
    //         $loanInstallments->balance = $loanData->amount - ($weeklyCapitalDeduct * $i);
    //         $loanInstallments->save();
    //         $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
    //     }

    //     return true;
    // }
    // private function createInstallments($loanData, $disbursement_date)
    // {
    //     $pendingInstallments = [];
    //     $j = 1;
    //     $oldInstallmentTotal = 0;
    //     $newInstallments = [];
    //     $beforeDisbursedPaidInstallments = LoanInstallment::where('loan_id', $loanData->id)
    //         ->whereDate('installment_date', '<=', $disbursement_date)
    //         ->where('status', 2)
    //         ->delete();
    //     // Fetch existing installments created after the disbursement date
    //     $beforeDisbursedInstallments = LoanInstallment::where('loan_id', $loanData->id)
    //         ->whereDate('installment_date', '<=', $disbursement_date)
    //         ->where('status', 1)
    //         ->get();

    //     $oldInstallmentCapitalTotal = 0;

    //     foreach($beforeDisbursedInstallments as $installment){
    //         $pendingInstallments[] = [
    //             'loan_id' => $installment->loan_id,
    //             'user_id' => $installment->user_id,
    //             'installment_date' => $installment->installment_date,
    //             'capital' => $installment->capital,
    //             'interest' => $installment->interest,
    //             'payment' => $installment->payment,
    //             'balance' => ($loanData->amount - ($installment->capital * $j)),
    //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //             'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         ];
    //         $oldInstallmentCapitalTotal += $installment->capital;
    //         $j++;
    //         $installment->delete();
    //     }

    //     $afterInstallments = LoanInstallment::where('loan_id', $loanData->id)
    //         ->whereDate('installment_date', '>', $disbursement_date)
    //         ->delete();

    //     if (!empty($pendingInstallments)) {
    //         Log::info('array till disbursement date : '.json_encode($pendingInstallments));
    //         LoanInstallment::insert($pendingInstallments);
    //     }

    //     // Calculate interest and installment amounts
    //     $finalAmount = $loanData->amount - $oldInstallmentCapitalTotal;
    //     $loanInterest = ($finalAmount * $loanData->loan_interest_rate) / 100;
    //     $totalAmountToPay = $finalAmount + $loanInterest;
    //     $weeklyLoanInterestAmount = ($finalAmount * $loanData->weekly_interest_rate) / 100;
    //     $weeklyCapitalDeduct = $finalAmount / $loanData->duration;
    //     $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
    //     $installmentDate = $loanData->first_installment_date;

    //     // Create new installments
    //     for ($i = 1; $i <= $loanData->duration; $i++) {
    //         $newInstallments[] = [
    //             'loan_id' => $loanData->id,
    //             'user_id' => $loanData->user_id,
    //             'installment_date' => $installmentDate,
    //             'capital' => $weeklyCapitalDeduct,
    //             'interest' => $weeklyLoanInterestAmount,
    //             'payment' => $installmentAmount,
    //             'balance' => $finalAmount - (($weeklyCapitalDeduct * $i) + $oldInstallmentTotal),
    //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //             'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
    //         ];
    //         // Increment the installment date by one week
    //         $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
    //     }

    //     // Insert pending installments into the database
    //     if (!empty($newInstallments)) {
    //         Log::info('new installment array : '.json_encode($newInstallments));
    //         LoanInstallment::insert($newInstallments);
    //     }

    //     // Return true or the pending installments array, as needed
    //     return true;
    // }

    // another version
    // private function createInstallments($loanData, $disbursement_date)
    // {
    //     $pendingInstallments = [];
    //     $oldInstallmentCapitalTotal = 0;
    //     $newInstallments = [];
    //     $j = 1;

    //     // Delete installments with status 2 (paid) on or before the disbursement date
    //     LoanInstallment::where('loan_id', $loanData->id)
    //         ->whereDate('installment_date', '<=', $disbursement_date)
    //         ->where('status', 2)
    //         ->delete();

    //     // Fetch and process unpaid installments created on or before the disbursement date
    //     $beforeDisbursedInstallments = LoanInstallment::where('loan_id', $loanData->id)
    //         ->whereDate('installment_date', '<=', $disbursement_date)
    //         ->where('status', 1)
    //         ->get();

    //     foreach ($beforeDisbursedInstallments as $installment) {
    //         $pendingInstallments[] = [
    //             'loan_id' => $installment->loan_id,
    //             'user_id' => $installment->user_id,
    //             'installment_date' => $installment->installment_date,
    //             'capital' => $installment->capital,
    //             'interest' => $installment->interest,
    //             'payment' => $installment->payment,
    //             'balance' => $loanData->amount - ($installment->capital * $j),
    //             'created_at' => Carbon::now(),
    //             'updated_at' => Carbon::now(),
    //         ];
    //         $oldInstallmentCapitalTotal += $installment->capital;
    //         $j++;
    //         $installment->delete();
    //     }

    //     // Delete installments created after the disbursement date
    //     LoanInstallment::where('loan_id', $loanData->id)
    //         ->whereDate('installment_date', '>', $disbursement_date)
    //         ->delete();

    //     // Insert the pending installments into the database
    //     if (!empty($pendingInstallments)) {
    //         Log::info('Array till disbursement date: ' . json_encode($pendingInstallments));
    //         LoanInstallment::insert($pendingInstallments);
    //     }

    //     // Calculate interest and installment amounts for new installments
    //     $finalAmount = $loanData->amount - $oldInstallmentCapitalTotal;
    //     $loanInterest = ($finalAmount * $loanData->loan_interest_rate) / 100;
    //     $totalAmountToPay = $finalAmount + $loanInterest;
    //     $weeklyLoanInterestAmount = ($finalAmount * $loanData->weekly_interest_rate) / 100;
    //     $weeklyCapitalDeduct = $finalAmount / $loanData->duration;
    //     $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
    //     $installmentDate = $loanData->first_installment_date;

    //     // Create new installments
    //     for ($i = 1; $i <= $loanData->duration; $i++) {
    //         $newInstallments[] = [
    //             'loan_id' => $loanData->id,
    //             'user_id' => $loanData->user_id,
    //             'installment_date' => $installmentDate,
    //             'capital' => $weeklyCapitalDeduct,
    //             'interest' => $weeklyLoanInterestAmount,
    //             'payment' => $installmentAmount,
    //             'balance' => $finalAmount - (($weeklyCapitalDeduct * $i) + $oldInstallmentCapitalTotal),
    //             'created_at' => Carbon::now(),
    //             'updated_at' => Carbon::now(),
    //         ];
    //         $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
    //     }

    //     // Insert the new installments into the database
    //     if (!empty($newInstallments)) {
    //         Log::info('New installment array: ' . json_encode($newInstallments));
    //         LoanInstallment::insert($newInstallments);
    //     }

    //     return true;
    // }

    private function createInstallments($loanRequestData, $loanData, $disbursement_date)
    {
        $newInstallments = [];

        $totalLoanAmountAsOfToday = LoanRequest::where('user_id', auth()->id())->whereIn('status',[2,4,5])->sum('amount');

        $afterDisburesdDateInstallmentsTotal = LoanInstallment::where('loan_id', $loanData->id)
            ->whereDate('installment_date', '>', $disbursement_date)
            ->sum('capital');

        $afterInstallments = LoanInstallment::where('loan_id', $loanData->id)
            ->whereDate('installment_date', '>', $disbursement_date)
            ->delete();

        // Calculate interest and installment amounts
        $finalAmount = $loanRequestData->amount + $afterDisburesdDateInstallmentsTotal;
        $loanInterest = ($finalAmount * $loanData->loan_interest_rate) / 100;
        $totalAmountToPay = $finalAmount + $loanInterest;
        $weeklyLoanInterestAmount = ($finalAmount * $loanData->weekly_interest_rate) / 100;
        $weeklyCapitalDeduct = $finalAmount / $loanRequestData->duration;
        $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
        $installmentDate = $loanData->first_installment_date;

        // Create new installments
        for ($i = 1; $i <= $loanRequestData->duration; $i++) {
            $newInstallments[] = [
                'loan_id' => $loanData->id,
                'user_id' => $loanData->user_id,
                'installment_date' => $installmentDate,
                'capital' => $weeklyCapitalDeduct,
                'interest' => $weeklyLoanInterestAmount,
                'payment' => $installmentAmount,
                'balance' => $finalAmount - ($weeklyCapitalDeduct * $i),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            // Increment the installment date by one week
            $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
        }

        // Insert pending installments into the database
        if (!empty($newInstallments)) {
            LoanInstallment::insert($newInstallments);
        }

        // Return true or the pending installments array, as needed
        return true;
    }

    // upload ioweyou
    public function uploadIoweyou(Request $request){
        if($request->ajax()){
            $rules = [
                'disbursement_date' => ['required', 'date'],
                'ioweyou_document' => ['required','mimes:pdf,jpeg,png','max:5120'],
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            $loanRequestData = LoanRequest::where('id', $request->fund_request_id)->first();
            $request->disbursement_date = \Carbon\Carbon::parse($request->disbursement_date)->format('Y-m-d');
            $changeFundRequestResponse = $this->changeFundRequestStatus($loanRequestData, $request->status, $request->disbursement_date);
            if ($request->hasFile('ioweyou_document')) {
                if ($loanRequestData->ioweyou) {
                    Storage::delete($loanRequestData->ioweyou);
                }
                $ioweyouDocument = $request->file('ioweyou_document')->store('loan_documents', 'public');
            } else {
                $ioweyouDocument = $loanRequestData->ioweyou;
            }

            $loanRequestData->ioweyou = $ioweyouDocument;
            if($loanRequestData->save()){
                $loanData = Loan::where('id', $loanRequestData->loan_id)->first();
                $loanData->ioweyou = $loanRequestData->ioweyou;
                $loanData->save();
                if(isset($changeFundRequestResponse['status'])){
                    return response()->json($changeFundRequestResponse);
                }
                return response()->json(['status'=>true, 'message'=>'Ioweyou document uploaded successfully.']);
            }
        }
        return response()->json(['status'=>false, 'message'=>'Something went wrong. Please try again later.']);
    }

    // pending for disbursed loan request list
    public function pendingForDisbursed(Request $request){
        if ($request->ajax()) {
            $acceptedRequestLoans = Loan::select(
                'loans.id',
                'loans.amount',
                'loans.user_id',
                'loans.duration',
                'loans.created_at',
                'loans.status',
                'loans.ioweyou',
                'loans.first_installment_date',
                'companies.name as company_name',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(CONCAT(users.middle_name, ' '), ''), users.last_name) as employee_name")
            )
            ->join('companies', 'loans.company_id', '=', 'companies.id')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->where('loans.status', 4);

            return DataTables::of($acceptedRequestLoans)
            ->addIndexColumn()
            ->editColumn('amount', function($row){
                return currencyFormatter($row->amount);
            })
            ->editColumn('status',function($row){
                return Loan::getLoanStatusName($row->status);
            })
            ->editColumn('first_installment_date',function($row){
                return Carbon::parse($row->first_installment_date)->subWeek(1)->format('d-m-Y');
            })
            ->addColumn('action', function($row){
                $btn = "<button class='btn btn-primary btn-sm view-loan-details' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='More details' data-id='".$row->id."'><i class='mdi mdi-eye fs-4'></i></button><a href=".route('employee.loanTerms').'?uid='.$row->user_id." class='btn btn-primary btn-sm view-loan-installments ms-2' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Loan Installments details' data-id='".$row->user_id."' target='_blank'><i class='mdi mdi-bank-transfer-in fs-4'></i></a>";
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }
        return view('loan.pending_for_disbursed_list');
    }

    // change loan status
    // public function changeLoanStatus(Request $request){
    //     $loanData = Loan::where('id', $request->requestId)->first();
    //     if(!empty($loanData)){
    //         $user = User::select('company_id', 'first_name', 'middle_name', 'last_name', 'email')->where('id', $loanData->user_id)->first();
    //         $fullName = '';
    //         $companyAdminData = '';
    //         $companyAdminEmail = '';
    //         if(!empty($user)){
    //             $companyAdminData = getCompanyAdminData($user->company_id);

    //             if(!empty($companyAdminData)){
    //                 $companyAdminEmail = $companyAdminData->email;
    //             }

    //             if($user->middle_name){
    //                 $fullName = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
    //             }else{
    //                 $fullName = $user->first_name.' '.$user->last_name;

    //             }
    //         }

    //         $weekText = 'weeks';
    //         if($loanData->duration < 2){
    //             $weekText = 'week';
    //         }

    //         $loanData->status = $request->status;
    //         if($loanData->save()){
    //             $employeeMailData = [];
    //             $companyAdminMailData = [];

    //             if($request->status == 4){
    //                 $employeeMailData = [
    //                     'subject' => 'Loan Request Disbursed',
    //                     'message' => 'You request of fund amounting to '.currencyFormatter($loanData->amount).' for a duration of ' . $loanData->duration .' '.$weekText.' has been disbursed.'
    //                 ];

    //                 $companyAdminMailData = [
    //                     'subject' => 'Loan Request Disbursed',
    //                     'message' => 'Employee : ' . $fullName . ' has requested funds amounting to '.currencyFormatter($loanData->amount).' for a duration of ' . $loanData->duration .' '.$weekText.' has been disbursed.'
    //                 ];
    //             }
    //             $installments = LoanInstallment::find($loanData->id);
    //             if ($installments) {
    //                 $installments->delete();
    //             }

    //             $loanInterest = ($loanData->amount * $loanData->loan_interest_rate)/100;
    //             $totalAmountToPay = $loanData->amount + $loanInterest;
    //             $weeklyLoanInterestAmount = ($loanData->amount * $loanData->weekly_interest_rate)/100;
    //             $weeklyCapitalDeduct = ($loanData->amount / $loanData->duration);
    //             $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
    //             $installmentDate = $loanData->first_installment_date;

    //             for($i = 1; $i <= $loanData->duration; $i++){
    //                 $loanInstallments = new LoanInstallment;
    //                 $loanInstallments->loan_id = $loanData->id;
    //                 $loanInstallments->user_id = $loanData->user_id;
    //                 $loanInstallments->installment_date = $installmentDate;
    //                 $loanInstallments->capital = $weeklyCapitalDeduct;
    //                 $loanInstallments->interest = $weeklyLoanInterestAmount;
    //                 $loanInstallments->payment = $installmentAmount;
    //                 $loanInstallments->balance = $loanData->amount - ($weeklyCapitalDeduct * $i);
    //                 $loanInstallments->save();
    //                 $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
    //             }

    //             Mail::to($user->email)->send(new SendFundRequestNotificationMail($employeeMailData));
    //             if(!empty($companyAdminEmail)){
    //                 Mail::to($companyAdminEmail)->send(new SendFundRequestNotificationMail($companyAdminMailData));
    //             }
    //             return response()->json(['status' => true, 'message' => 'Loan request status changed successfully.']);
    //         }
    //     }
    //     return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    // }

    // get more details of loan
    public function getLoanDetails(Request $request){
        if(isset($request->id)){
            $getLoanDetails = Loan::select('first_installment_date', 'last_installment_date', 'yearly_interest_rate', 'loan_interest_rate', 'weekly_interest_rate')->where('id', $request->id)->first();
            if(!empty($getLoanDetails)){
                return response()->json(['status' => true, 'message' => 'Success.', 'loanDetails' => $getLoanDetails]);
            }
            return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    // handle change request status with reject status
    public function rejectFundRequestStatus(Request $request){
        if(!empty($request->requestId) && !empty($request->status)){
            $loanRequestData = LoanRequest::where('id', $request->requestId)->first();
            $response = $this->changeFundRequestStatus($loanRequestData, $request->status, null);
            if(!empty($response['status']) && $response['status'] == true){
                return response()->json($response);
            }
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    }

    // completed loans
    public function completedLoans(Request $request){
        if ($request->ajax()) {
            $completedLoans = Loan::select(
                'loans.*',
                'companies.name as company_name',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(CONCAT(users.middle_name, ' '), ''), users.last_name) as employee_name")
            )
            ->join('companies', 'loans.company_id', '=', 'companies.id')
            ->join('users', 'loans.user_id', '=', 'users.id')
            ->orderBy('created_at', 'desc')
            ->where('loans.status', 6)
            ->withTrashed();

            return DataTables::of($completedLoans)
                ->addIndexColumn()
                ->editColumn('amount',function($row){
                    return currencyFormatter($row->amount);
                })
                ->editColumn('ioweyou',function($row){
                    if(!empty($row->ioweyou)){
                        $extension = strtolower(pathinfo($row->ioweyou, PATHINFO_EXTENSION));
                        $img= asset('storage').'/'.$row->ioweyou;
                        // if($extension == 'pdf'){
                            return '<a class="btn custom-btn" href="'.$img.'" download>Download</a>';
                        // }
                        // return "<img class='enlarge-image pe-auto' href='".$img."' height='50px' width='50px'>";
                    }else{
                        return '-';
                    }
                })
                ->addColumn('action', function($row){
                    $btn = "<a href=".route('employee.loanTerms').'?uid='.$row->user_id.'&lid='.$row->id." class='btn btn-primary btn-sm view-loan-installments ms-2' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Loan Installments details' data-id='".$row->user_id."' target='_blank'><i class='mdi mdi-bank-transfer-in fs-4'></i></a>";
                    return $btn;
                })
                ->rawColumns(['ioweyou', 'action'])
                ->make(true);
        }
        return view('loan.completed_loans');
    }

    // loan installment collections
    public function loanInstallmentCollection(Request $request){
        if ($request->ajax()) {
            $installmentCollectionData = LoanCollection::select(
                    'loan_collections.*',
                    'companies.name as company_name',
                    DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name) as collector_name")
                )
                ->join('companies', 'loan_collections.company_id', '=', 'companies.id')
                ->join('users', 'loan_collections.collector_id', '=', 'users.id') // Fixed the join condition
                ->orderBy('loan_collections.created_at', 'desc')
                ->withTrashed();

            return DataTables::of($installmentCollectionData)
                ->addIndexColumn()
                ->editColumn('amount', function($row){
                    return currencyFormatter($row->amount);
                })
                ->editColumn('bank_receipt', function($row) {
                    if ($row->bank_receipt) {
                        return '<a href="'.asset('storage/'.$row->bank_receipt).'" target="_blank" download>Download</a>';
                    } else {
                        return '<a href="javascript:void(0)" target="_blank">Download</a>';
                    }
                })
                ->editColumn('note', function($row){
                    if($row->note){
                        return '<button class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'.$row->note.'"><i class="mdi mdi-information-outline fs-4"></i></button>';
                    }else{
                        return '-';
                    }
                })
                ->editColumn('created_at', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->rawColumns(['bank_receipt', 'note'])
                ->make(true);
        }
        return view('loan.installment_collections');
    }
}
