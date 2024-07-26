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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Validator;

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
                        if($extension == 'pdf'){
                            return '<a class="btn custom-btn" src="'.$img.'" download>Click me</a>';
                        }
                        return "<img class='enlarge-image pe-auto' src='".$img."' height='50px' width='50px'>";
                    }else{
                        if($row->status == 2){
                            return "<button class='btn btn-primary upload-ioweyou' data-id='".$row->id."'>Upload IoweYou</button>";
                        }else{
                            return '-';
                        }
                    }
                })
                ->editColumn('status',function($row){
                    if($row->status == 1){
                        return "<select class='change-fund-request-status form-select w-auto' data-id='".$row->id."'>
                            <option value='1' selected>Pending</option>
                            <option value='2'>Accept</option>
                            <option value='3'>Reject</option>
                        </select>";
                    }elseif($row->status == 2){
                        return 'Approved';
                    }else{
                        return 'Rejected';
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

    // change fund request status
    public function changeFundRequestStatus(Request $request){
        $loanRequestData = LoanRequest::where('id', $request->requestId)->first();
        if(!empty($loanRequestData)){
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

            $loanRequestData->status = $request->status;
            if($loanRequestData->save()){
                $employeeMailData = [];
                $companyAdminMailData = [];

                //  fund request accepted
                if($request->status == 2){

                    // request accepted -> create loan
                    $responseLoanData = $this->createLoan($loanRequestData, $companyAdminData->id);

                    //  create installments
                    $this->createInstallments($responseLoanData);

                    if(!empty($responseLoanData->id)){
                        $loanRequestData->loan_id = $responseLoanData->id;
                        $loanRequestData->save();
                    }

                    $employeeMailData = [
                        'subject' => 'Loan Request Approved',
                        'message' => 'You request of fund amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been approved.'
                    ];

                    $companyAdminMailData = [
                        'subject' => 'Loan Request Approved',
                        'message' => 'Employee : ' . $fullName . ' has requested funds amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been approved.'
                    ];
                }
                // fund request rejected
                else if($request->status == 3){
                    $employeeMailData = [
                        'subject' => 'Loan Request Rejected',
                        'message' => 'You request of fund amounting to '.currencyFormatter($loanRequestData->amount).' for a duration of ' . $loanRequestData->duration .' '.$weekText.' has been rejected.'
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
                return response()->json(['status' => true, 'message' => 'Fund request status changed successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    }

    // create loan when fund request accepted
    private function createLoan($loanRequest, $companyAdminId){
        $loanData = Loan::where('user_id', $loanRequest->user_id)->first();
        if(empty($loanData)){
            $loanData = new Loan;
            $loanData->user_id = $loanRequest->user_id;
            $loanData->company_id = $loanRequest->company_id;
            $loanData->company_admin_id = $companyAdminId;
            $loanData->yearly_interest_rate =  getInterestRate();
            $loanData->weekly_interest_rate =  getInterestRate()/52;
            $loanData->status = 4;
        }
        $totalPaidInstallments = LoanInstallment::where('loan_id', $loanRequest->loan_id)->where('status', 2)->sum('capital');
        $totalLoanAmount = LoanInstallment::where('loan_id', $loanRequest->loan_id)->sum('capital');
        $finalAmount = (($totalLoanAmount - $totalPaidInstallments) + $loanRequest->amount);
        $finalDuration = $loanData->duration + $loanRequest->duration;
        $loanData->amount = $finalAmount;
        $loanData->duration = $finalDuration;
        $loanData->loan_interest_rate = (getInterestRate()/52) * $finalDuration;
        $loanData->first_installment_date = date('Y-m-d', strtotime(Carbon::now()->addWeek()));
        $loanData->last_installment_date = date('Y-m-d', strtotime(Carbon::now()->addWeeks($finalDuration)));
        $loanData->save();

        return $loanData;
    }

    // create installments
    public function createInstallments($loanData){
        $installments = LoanInstallment::where('loan_id',$loanData->id)->get();
        $installments->each(function ($installment) {
            $installment->delete();
        });
        $loanInterest = ($loanData->amount * $loanData->loan_interest_rate)/100;
        $totalAmountToPay = $loanData->amount + $loanInterest;
        $weeklyLoanInterestAmount = ($loanData->amount * $loanData->weekly_interest_rate)/100;
        $weeklyCapitalDeduct = ($loanData->amount / $loanData->duration);
        $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
        $installmentDate = $loanData->first_installment_date;

        for($i = 1; $i <= $loanData->duration; $i++){
            $loanInstallments = new LoanInstallment;
            $loanInstallments->loan_id = $loanData->id;
            $loanInstallments->user_id = $loanData->user_id;
            $loanInstallments->installment_date = $installmentDate;
            $loanInstallments->capital = $weeklyCapitalDeduct;
            $loanInstallments->interest = $weeklyLoanInterestAmount;
            $loanInstallments->payment = $installmentAmount;
            $loanInstallments->balance = $loanData->amount - ($weeklyCapitalDeduct * $i);
            $loanInstallments->save();
            $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
        }

        return true;
    }

    // upload ioweyou
    public function uploadIoweyou(Request $request){
        if($request->ajax()){
            $rules = [
                'ioweyou_document' => ['required','file','mimes:pdf,jpeg,png','max:5120'],
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            $loanRequestData = LoanRequest::where('id', $request->fund_request_id)->first();
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
                'loans.duration',
                'loans.created_at',
                'loans.status',
                'loans.ioweyou',
                'companies.name as company_name',
                DB::raw("CONCAT(users.first_name, ' ', IFNULL(CONCAT(users.middle_name, ' '), ''), users.last_name) as employee_name")
            )
            ->join('companies', 'loans.company_id', '=', 'companies.id')
            ->join('users', 'loans.user_id', '=', 'users.id');

            return DataTables::of($acceptedRequestLoans)
            ->addIndexColumn()
            ->editColumn('status',function($row){
                if($row->status == 2){
                    return "<select class='change-loan-status form-select w-auto' data-id='".$row->id."'>
                    <option value='2' selected>Accepted</option>
                    <option value='3'>Partial Disbursed</option>
                    <option value='4'>Disbursed</option>
                    </select>";
                }else if($row->status == 3){
                    return 'Partially Disbursed';
                }else{
                    return 'Disbursed';
                }
            })
            ->editColumn('created_at',function($row){
                return date('d-m-Y', strtotime($row->created_at));
            })
            ->addColumn('action', function($row){
                $btn = "<button class='btn btn-primary btn-sm view-loan-details' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='More details' data-id='".$row->id."'><i class='mdi mdi-eye fs-4'></i></button>";
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }
        return view('loan.pending_for_disbursed_list');
    }

    // change loan status
    public function changeLoanStatus(Request $request){
        $loanData = Loan::where('id', $request->requestId)->first();
        if(!empty($loanData)){
            $user = User::select('company_id', 'first_name', 'middle_name', 'last_name', 'email')->where('id', $loanData->user_id)->first();
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
            if($loanData->duration < 2){
                $weekText = 'week';
            }

            $loanData->status = $request->status;
            if($loanData->save()){
                $employeeMailData = [];
                $companyAdminMailData = [];

                if($request->status == 3){
                    $employeeMailData = [
                        'subject' => 'Loan Request Partially Disbursed',
                        'message' => 'You request of fund amounting to '.currencyFormatter($loanData->amount).' for a duration of ' . $loanData->duration .' '.$weekText.' has been partially disbursed.'
                    ];

                    $companyAdminMailData = [
                        'subject' => 'Loan Request Partially Disbursed',
                        'message' => 'Employee : ' . $fullName . ' has requested funds amounting to '.currencyFormatter($loanData->amount).' for a duration of ' . $loanData->duration .' '.$weekText.' has been partially disbursed.'
                    ];
                }else if($request->status == 4){
                    $employeeMailData = [
                        'subject' => 'Loan Request Disbursed',
                        'message' => 'You request of fund amounting to '.currencyFormatter($loanData->amount).' for a duration of ' . $loanData->duration .' '.$weekText.' has been disbursed.'
                    ];

                    $companyAdminMailData = [
                        'subject' => 'Loan Request Disbursed',
                        'message' => 'Employee : ' . $fullName . ' has requested funds amounting to '.currencyFormatter($loanData->amount).' for a duration of ' . $loanData->duration .' '.$weekText.' has been disbursed.'
                    ];
                }
                $installments = LoanInstallment::find($loanData->id);
                if ($installments) {
                    $installments->delete();
                }

                $loanInterest = ($loanData->amount * $loanData->loan_interest_rate)/100;
                $totalAmountToPay = $loanData->amount + $loanInterest;
                $weeklyLoanInterestAmount = ($loanData->amount * $loanData->weekly_interest_rate)/100;
                $weeklyCapitalDeduct = ($loanData->amount / $loanData->duration);
                $installmentAmount = $weeklyCapitalDeduct + $weeklyLoanInterestAmount;
                $installmentDate = $loanData->first_installment_date;

                for($i = 1; $i <= $loanData->duration; $i++){
                    $loanInstallments = new LoanInstallment;
                    $loanInstallments->loan_id = $loanData->id;
                    $loanInstallments->user_id = $loanData->user_id;
                    $loanInstallments->installment_date = $installmentDate;
                    $loanInstallments->capital = $weeklyCapitalDeduct;
                    $loanInstallments->interest = $weeklyLoanInterestAmount;
                    $loanInstallments->payment = $installmentAmount;
                    $loanInstallments->balance = $loanData->amount - ($weeklyCapitalDeduct * $i);
                    $loanInstallments->save();
                    $installmentDate = Carbon::parse($installmentDate)->addWeek()->format('Y-m-d');
                }

                Mail::to($user->email)->send(new SendFundRequestNotificationMail($employeeMailData));
                if(!empty($companyAdminEmail)){
                    Mail::to($companyAdminEmail)->send(new SendFundRequestNotificationMail($companyAdminMailData));
                }
                return response()->json(['status' => true, 'message' => 'Loan request status changed successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    }

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
}
