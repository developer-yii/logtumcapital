<?php

namespace App\Http\Controllers;

use App\Mail\SendFundRequestNotificationMail;
use App\Models\Company;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    // get all employees
    public function get(Request $request)
    {
        $companyId = !empty($request->cid) ? $request->cid : '';
        $companiesData = Company::select('id', 'name')->where('status', 2)->get();
        if ($request->ajax()) {
            if (!empty($request->cid) && isSuperAdmin()) {
                $employees = User::select('users.first_name', 'users.middle_name', 'users.last_name', 'users.email', 'users.phone_number', 'users.authorized_credit_limit', 'companies.name as company_name')
                    ->join('companies', 'users.company_id', '=', 'companies.id')
                    ->where('users.role', 4)
                    ->where('users.company_id', $request->cid)
                    ->get();
            } else {
                $loginUserCompanyId = !empty(auth()->user()->company_id) ? auth()->user()->company_id : '';
                $query = User::select('users.*', 'companies.name as company_name')
                    ->join('companies', 'users.company_id', '=', 'companies.id')
                    ->where('users.role', 4);

                if (!empty($loginUserCompanyId)) {
                    $query->where('users.company_id', $loginUserCompanyId);
                }

                $employees = $query->get(); // Execute the query
            }
            return Datatables::of($employees)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->editColumn('authorized_credit_limit', function($row){
                    return currencyFormatter($row->authorized_credit_limit);
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editEmployee me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit employee details"><i class="mdi mdi-pencil-outline"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('employee.index', compact('companyId', 'companiesData'));
    }

    // get specific company employees
    public function getCompanyEmployees(Request $request)
    {
        $user = auth()->user();
        if ($request->ajax()) {
            $employees = User::where('role', 4)
            ->where('users.company_id', $user->company_id)
            ->leftJoin('loans', 'users.id', '=', 'loans.user_id')
            ->select(
                'users.id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.email',
                'users.phone_number',
                'loans.user_id',
                DB::raw('COALESCE(SUM(loans.amount), 0) as used_credit_limit'),
                DB::raw('(users.authorized_credit_limit - COALESCE(SUM(loans.amount), 0)) as available_credit_limit'),
                'users.authorized_credit_limit',
                'users.ine',
                'users.proof_of_address'
            )
            ->groupBy(
                'users.id',
                'loans.user_id',
            );
            return Datatables::of($employees)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->editColumn('authorized_credit_limit', function($row){
                    return currencyFormatter($row->authorized_credit_limit);
                })
                ->editColumn('used_credit_limit', function($row){
                    return currencyFormatter($row->used_credit_limit);
                })
                ->editColumn('available_credit_limit', function($row){
                    return currencyFormatter($row->available_credit_limit);
                })
                ->editColumn('available_credit_limit', function($row){
                    return currencyFormatter($row->available_credit_limit);
                })
                ->editColumn('ine', function($row){
                    $file = asset('storage').'/'.$row->ine;

                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff'];

                    if (in_array($extension, $imageExtensions)) {
                        return '<img class="enlarge-image pe-auto" style="height:50px; width:50px;" src="' . $file . '">';
                    } else {
                        return '<a href="' . $file . '" class="pe-auto" download><i class="mdi mdi-download fs-2"></i></a>';
                    }
                })
                ->editColumn('proof_of_address', function($row){
                    $file = asset('storage').'/'.$row->proof_of_address;

                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff'];

                    if (in_array($extension, $imageExtensions)) {
                        return '<img class="enlarge-image pe-auto" style="height:50px; width:50px;" src="' . $file . '">';
                    } else {
                        return '<a href="' . $file . '" class="pe-auto" download><i class="mdi mdi-download fs-2"></i></a>';
                    }
                })
                ->rawColumns(['ine','proof_of_address'])
                ->make(true);
        }
        $loginUserCompanyId = !empty($user->company_id) ? $user->company_id : '';
        $creditData = getCompanyCreditsData($loginUserCompanyId);
        $companyData = getCompanyData($user->company_id);
        $employeeData = User::select('id', 'email')->where('role', 4)->where('company_id', $user->company_id)->get();
        if(!empty($companyData)){
            $companyName = $companyData->name;
            $companyTotalCredit = $companyData->authorized_credit_limit;
            return view('employee.company_employees', compact('creditData', 'companyName', 'companyTotalCredit', 'employeeData'));
        }
        return view('employee.company_employees', compact('creditData', 'employeeData'));
    }

    // store employee details
    public function store(Request $request)
    {
        $user = auth()->user();
        if($request->ajax()){
            if($user->role == 1){
                $rules = [
                    'company' => 'required|string|max:255',
                    'first_name' => 'required|string|max:255',
                    'middle_name' => 'nullable|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                    'authorized_credit_limit' => 'required|numeric|min:0'
                ];
                if(!empty($request->employee_id)){
                    $rules['email'] = ['required','email','max:255','unique:users,email,'.$request->employee_id];
                    $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:users,phone_number,'.$request->employee_id];
                    $rules['password'] = ['nullable','min:8','max:255'];
                    $rules['confirm_password'] = ['same:password'];
                    $rules['proof_of_address'] = ['nullable','file','mimes:pdf,jpeg,jpg,png','max:5120'];
                    $rules['ine_document'] = ['nullable','file','mimes:pdf,jpeg,png','max:5120'];
                }else{
                    $rules['email'] = ['required','email','max:255','unique:users,email'];
                    $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:users,phone_number'];
                    $rules['password'] = ['required','min:8','max:255'];
                    $rules['confirm_password'] = ['required','same:password'];
                    $rules['proof_of_address'] = ['required','file','mimes:pdf,jpeg,jpg,png','max:5120'];
                    $rules['ine_document'] = ['required','file','mimes:pdf,jpeg,png','max:5120'];
                }
            }else{
                $rules = [
                    'authorized_credit_limit' => 'required|numeric|min:0'
                ];
            }

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            // Find existing employee or create a new instance
            $employee = User::find($request->employee_id) ?? new User();

            if($user->role == 1){
                // Check and delete old documents if new ones are uploaded
                if ($request->hasFile('proof_of_address')) {
                    if ($employee->proof_of_address) {
                        Storage::delete($employee->proof_of_address);
                    }
                    $proofOfAddress = $request->file('proof_of_address')->store('employee_documents', 'public');
                } else {
                    $proofOfAddress = $employee->proof_of_address;
                }

                if ($request->hasFile('ine_document')) {
                    if ($employee->ine_document) {
                        Storage::delete($employee->ine_document);
                    }
                    $ineDocument = $request->file('ine_document')->store('employee_documents', 'public');
                } else {
                    $ineDocument = $employee->ine_document;
                }

                if ($request->hasFile('ine_document')) {
                    if ($employee->ine_document) {
                        Storage::delete($employee->ine_document);
                    }
                    $ineDocument = $request->file('ine_document')->store('employee_documents', 'public');
                } else {
                    $ineDocument = $employee->ine_document;
                }

                // Prepare data for updating/creating the employee record
                $data = [
                    'company_id' => $request->company,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'authorized_credit_limit' => $request->authorized_credit_limit,
                    'role'=>4,
                ];

                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                // Only add document fields to $data if new files are uploaded
                if ($proofOfAddress) {
                    $data['proof_of_address'] = $proofOfAddress;
                }
                if ($ineDocument) {
                    $data['ine'] = $ineDocument;
                }
            }else{
                $data['authorized_credit_limit'] = $request->authorized_credit_limit;
            }

            // Update or create the employee record
            $employee->fill($data);

            if ($employee->save()) {
                return response()->json(['status' => true, 'message' => 'Employee details stored successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to store employee details.']);
    }

    // edit employee details
    public function edit($id)
    {
        $employeeData = User::find($id);
        if(!empty($employeeData)){
            return response()->json(['status'=>true, 'message'=>'Success.', 'data'=>['employeeDetails' => $employeeData]]);
        }
        return response()->json(['status' => false, 'message' => 'Failed to get employee details.']);
    }

    // delete employee details
    public function delete(Request $request)
    {
        if($request->employeeId){
            $employeeData = User::find($request->employeeId);
            if (!$employeeData) {
                return response()->json(['status' => false, 'message' => 'Employee not found.']);
            }

            if ($employeeData->delete()) {
                return response()->json(['status' => true, 'message' => 'Employee details deleted successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to delete employee details.']);
    }

    // show installment details
    public function loanTerms(Request $request){
        $userId = auth()->id();
        if(isset($request->uid) && isset($request->lid)){
            $userId = $request->uid;
            $loanId = $request->lid;
            $loanData = Loan::select('first_installment_date', 'amount', 'status')->where('id', $loanId)->where('user_id', $userId)->where('status', 6)->withTrashed()->first();
            $totalLoanAmountAsOfToday = LoanRequest::where('user_id', $userId)->where('loan_id', $loanId)->where('status',6)->sum('amount');
            $loanStatusName = '';
            $loanInstallments = [];
            if($loanData){
                $loanStatusName = Loan::getLoanStatusName($loanData->status);
                $loanInstallments = LoanInstallment::where('loan_id', $loanId)->where('user_id', $userId)->get();
            }
            return view('employee.loan_terms', compact('loanInstallments','loanData', 'loanStatusName', 'totalLoanAmountAsOfToday'));
        }else if(isset($request->uid)){
            $userId = $request->uid;
        }
        $loanData = Loan::select('first_installment_date', 'amount', 'status')->where('user_id', $userId)->where('status', 4)->first();
        $totalLoanAmountAsOfToday = LoanRequest::where('user_id', $userId)->whereIn('status',[2,4,5])->sum('amount');
        $loanStatusName = '';
        $loanInstallments = [];
        if($loanData){
            $loanStatusName = Loan::getLoanStatusName($loanData->status);
            $loanInstallments = LoanInstallment::where('user_id', $userId)->get();
        }
        return view('employee.loan_terms', compact('loanInstallments','loanData', 'loanStatusName', 'totalLoanAmountAsOfToday'));
    }

    // show request fund page
    public function requestFund(Request $request){
        $loanData = Loan::select('first_installment_date', 'amount')->where('user_id', auth()->id())->whereNotIn('status', [3,6])->first();
        $loanInstallments = LoanInstallment::where('user_id', auth()->id())->get();
        if ($request->ajax()) {
            $user = auth()->user();
            $fundRequestedData = LoanRequest::where('user_id', $user->id);
            return Datatables::of($fundRequestedData)
                ->addIndexColumn()
                ->editColumn('amount', function($row){
                    return currencyFormatter($row->amount);
                })
                ->editColumn('created_at', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->editColumn('status', function($row){
                    return Loan::getLoanStatusName($row->status);
                })
                ->rawColumns([])
                ->make(true);
        }
        return view('employee.request_fund', compact('loanInstallments','loanData'));
    }

    // store fund request
    public function storeFundRequest(Request $request){
        if($request->ajax()){
            $user = auth()->user();

            $rules = [
                'bank_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'amount' => ['required', 'numeric', 'min:1', function ($attribute, $value, $fail) use($user){
                        $employeeCreditData = getEmployeeCreditsData($user->id);
                        $availableEmployeeCredit = $employeeCreditData['availableCredit'];
                        if ($value > $availableEmployeeCredit) {
                            $fail('The ' . $attribute . ' must not exceed your credit limit.');
                        }
                    },
                ],
                'duration' => 'required|numeric|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            $saveLoanRequest = new LoanRequest;
            if(!empty($user->middle_name)){
                $fullName = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
            }else{
                $fullName = $user->first_name.' '.$user->last_name;
            }
            $saveLoanRequest->user_id = $user->id;
            $saveLoanRequest->company_id = $user->company_id;
            $saveLoanRequest->interest_rate = getInterestRate();
            $saveLoanRequest->amount = $request->amount;
            $saveLoanRequest->duration = $request->duration;
            $saveLoanRequest->bank_name = $request->bank_name;
            $saveLoanRequest->account_number = $request->account_number;
            $saveLoanRequest->status = 1;
            if($saveLoanRequest->save()){
                $superAdminMail = config('app.super_admin_mail');
                $companyAdmin = getCompanyAdminData($user->company_id);
                $companyAdminMail = $companyAdmin->email;
                $companyInfo = getCompanyData($user->company_id);
                $weekText = '';
                if($saveLoanRequest->duration == 1){
                    $weekText = 'week';
                }else{
                    $weekText = 'weeks';
                }
                $companyAdminMailData = [
                    'subject' => 'New Fund Request',
                    'message' => $fullName.' requested funds amounting to '.currencyFormatter($saveLoanRequest->amount).' for a duration of ' . $saveLoanRequest->duration .' '. $weekText .'.'
                ];

                $superAdminMailData = [
                    'subject' => 'New Fund Request',
                    'message' => 'Employee : ' . $fullName . ' from company : ' . $companyInfo->name . ' requested funds amounting to '.currencyFormatter($saveLoanRequest->amount).' for a duration of ' . $saveLoanRequest->duration .' '.$weekText.'.'
                ];
                Mail::to($companyAdminMail)->send(new SendFundRequestNotificationMail($companyAdminMailData));
                Mail::to($superAdminMail)->send(new SendFundRequestNotificationMail($superAdminMailData));
                return response()->json(['status' => true, 'message' => 'Your fund request received successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    }

    // send request of company limit change
    public function sendRequestForLimitChange(Request $request){
        if($request->ajax()){
            $rules = [
                'authorized_credit_limit' => 'required|numeric|min:0'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }
            $superAdminMailData = [
                'subject' => 'Change Authorized Credit Limit Request',
                'message' => $request->company_name . ' requested to update the authorized credit limit from the current limit of ' . currencyFormatter($request->current_limit) . ' to the new limit of ' . currencyFormatter($request->authorized_credit_limit) . '.'
            ];
            Mail::to(config('app.super_admin_mail'))->send(new SendFundRequestNotificationMail($superAdminMailData));
            return response()->json(['status' => true, 'message' => 'Your request of increase credit limit sent successfully.']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    }

    // change employee credit limit
    public function changeEmployeeCreditLimit(Request $request){
        if($request->ajax()){
            $user = auth()->user();
            $rules = [
                'employee' => 'required',
                'authorized_credit_limit' => ['required', 'numeric', 'min:0', function ($attribute, $value, $fail) use($user, $request){
                    if($user->company_id){
                        $companyCreditData = getCompanyCreditsData($user->company_id);
                        $availableCredit = $companyCreditData['availableCredit'];
                        if($value > $availableCredit){
                            $fail("You cannot exceed the company's total credit limit of ".currencyFormatter($availableCredit).'.');
                        }
                    }

                    if($request->employee){
                        $employeeCreditData = getEmployeeCreditsData($request->employee);
                        $usedEmployeeCredit = $employeeCreditData['usedCredit'];
                        if($value < $usedEmployeeCredit){
                            $fail('You cannot set the credit limit lower than '.currencyFormatter($usedEmployeeCredit).'.');
                        }
                    }
                }]
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            $updateUserData = User::where('id', $request->employee)->update(['authorized_credit_limit' => $request->authorized_credit_limit]);

            return response()->json(['status' => true, 'message' => 'Credit limit of employee updated successfully.']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong. Please try again later.']);
    }
}
