<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanCollection;
use App\Models\LoanInstallment;
use App\Models\LoanInstallmentBkp;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PaymentCollectorController extends Controller
{
    // get payment collections details
    public function upcomingPaymentCollections(Request $request){
        return view('payment_collector.index');
    }
    // get all payment collectors
    public function get(Request $request)
    {
        $paymentCollectors = User::select('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'company_id')
        ->where('role', 3)
        ->where('company_id', auth()->user()->company_id);

        if ($request->ajax()) {
            return Datatables::of($paymentCollectors)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm editPaymentCollector me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit payment collector details"><i class="mdi mdi-pencil-outline"></i></a>';
                    // $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deletePaymentCollector" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete company admin details"><i class="mdi mdi-delete-outline"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('payment_collector.index');
    }

    // store payment collector details
    public function store(Request $request)
    {
        $user = auth()->user();
        if($request->ajax()){
            $rules = [
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ];
            if(!empty($request->id)){
                $rules['email'] = ['required','email','max:255','unique:users,email,'.$request->id];
                $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:users,phone_number,'.$request->id];
                $rules['password'] = ['nullable','min:8','max:255'];
                $rules['confirm_password'] = ['same:password'];
            }else{
                $rules['email'] = ['required','email','max:255','unique:users,email'];
                $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:users,phone_number'];
                $rules['password'] = ['required','min:8','max:255'];
                $rules['confirm_password'] = ['required','same:password'];
            }

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            // Find existing payment collector or create a new instance
            $paymentCollector = User::find($request->id) ?? new User();

            // Prepare data for updating/creating the payment collector record
            $data = [
                'company_id' => $user->company_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'role'=>3,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }


            // Update or create the payment collector record
            $paymentCollector->fill($data);

            if ($paymentCollector->save()) {
                return response()->json(['status' => true, 'message' => 'Payment collector details stored successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to store payment collector details.']);
    }

    // edit payment collector details
    public function edit($id)
    {
        $paymentCollectorData = User::find($id);
        if(!empty($paymentCollectorData)){
            return response()->json(['status'=>true, 'message'=>'Success.', 'data'=>['paymentCollectorDetails' => $paymentCollectorData]]);
        }
        return response()->json(['status' => false, 'message' => 'Failed to get payment collector details.']);
    }

    // // delete payment collector details
    public function delete(Request $request)
    {
        if($request->paymentCollectorId){
            $paymentCollectorData = User::find($request->paymentCollectorId);
            if (!$paymentCollectorData) {
                return response()->json(['status' => false, 'message' => 'Payment collector not found.']);
            }

            if ($paymentCollectorData->delete()) {
                return response()->json(['status' => true, 'message' => 'Payment collector details deleted successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to delete payment collector details.']);
    }

    // get upcoming payment collections
    public function getPaymentCollections(Request $request){
        $user = auth()->user();
        $userIds = User::where('company_id', $user->company_id)->where('role', 4)->pluck('id');
        $fridayDate = isset($request->selectCollection)?date('Y-m-d', strtotime($request->selectCollection)):$this->getUpcomingFriday();
        $collectionData = LoanInstallment::select('users.first_name', 'users.middle_name', 'users.last_name', 'loan_installments.installment_date', 'loan_installments.payment', 'loan_installments.status')
        ->leftjoin('users', 'loan_installments.user_id','=','users.id')
        ->whereIn('loan_installments.user_id', $userIds)
        ->whereNull('users.deleted_at')
        ->where('loan_installments.installment_date', $fridayDate);

        if ($request->ajax()) {
            return Datatables::of($collectionData)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->editColumn('installment_date', function($row){
                    return date('d-m-Y', strtotime($row->installment_date));
                })
                ->editColumn('payment', function($row){
                    return currencyFormatter($row->payment);
                })
                ->editColumn('status', function($row){
                    return LoanInstallment::getLoanInstallmentStatusName($row->status);
                })
                // ->addColumn('action', function($row){
                //     $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm editPaymentCollector me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit payment collector details"><i class="mdi mdi-pencil-outline"></i></a>';
                //     // $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deletePaymentCollector" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete company admin details"><i class="mdi mdi-delete-outline"></i></a>';
                //     return $btn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }
        return view('payment_collector.payment_collections', compact('fridayDate'));
    }

    // get upcoming friday date
    private function getUpcomingFriday(){
        $today = date('Y-m-d');
        if (date('N', strtotime($today)) == 5) {
            return $today;
        } else {
            return date('Y-m-d', strtotime('next Friday', strtotime($today)));
        }
    }

    // store bank receipt
    public function storeBankReceipt(Request $request){
        $user = auth()->user();
        $rules = [
            'amount' => 'required|numeric',
            'bank_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        if ($request->hasFile('bank_receipt')) {
            $file = $request->file('bank_receipt');
            $path = $file->store('bank_receipts', 'public');

            $storeLoanInstallmentCollection = new LoanCollection();
            $storeLoanInstallmentCollection->company_id = $user->company_id;
            $storeLoanInstallmentCollection->collector_id = $user->id;
            $storeLoanInstallmentCollection->bank_receipt = $path;
            $storeLoanInstallmentCollection->amount = $request->amount;

            if ($storeLoanInstallmentCollection->save()) {
                $installmentDate = !empty($request->installment_date) ? date('Y-m-d', strtotime($request->installment_date)) : date('Y-m-d');
                $updateLoanInstallments = LoanInstallment::where('installment_date', $installmentDate)->update(['status' => 2, 'loan_collection_id' => $storeLoanInstallmentCollection->id]);

                // store loan installments backup
                $installmentBkpResponse = $this->storeLoanInstallmentBkp($installmentDate);
                if(!$installmentBkpResponse){
                    Log::info('Something went wrong while saving loan installment backup for date : '.$installmentDate);
                }

                $loanInstallments = LoanInstallment::select(
                    'loan_id',
                    DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as pendingInstallmentCount'),
                    DB::raw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completedInstallmentCount')
                )
                ->groupBy('loan_id')
                ->get();

                foreach($loanInstallments as $installment){
                    if ($installment->completedInstallmentCount == 1) {
                        $this->updateLoanRequestStatus($installment->loan_id, 5);
                    }
                    if ($installment->pendingInstallmentCount <= 0) {
                        $loan = Loan::where('id',$installment->loan_id)->first();
                        if($loan){
                            $loan->status = 6;
                            $loan->save();
                            $loan->delete();
                        }
                        $this->updateLoanRequestStatus($installment->loan_id, 6);
                    }
                }
                return response()->json(['status' => true, 'message' => 'Bank receipt uploaded successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to upload bank receipt.']);
    }

    // store backup of loan installments
    private function storeLoanInstallmentBkp($installmentDate){
        $paidLoanInstallments = LoanInstallment::where('installment_date', $installmentDate)->get();
        foreach($paidLoanInstallments as $installment){
            $storeInstallmentBkp = new LoanInstallmentBkp();
            $storeInstallmentBkp->user_id = $installment->user_id;
            $storeInstallmentBkp->loan_id = $installment->loan_id;
            $storeInstallmentBkp->loan_collection_id = $installment->loan_collection_id;
            $storeInstallmentBkp->installment_date = $installmentDate;
            $storeInstallmentBkp->capital = $installment->capital;
            $storeInstallmentBkp->interest = $installment->interest;
            $storeInstallmentBkp->payment = $installment->payment;
            $storeInstallmentBkp->balance = $installment->balance;
            $storeInstallmentBkp->status = $installment->status;
            $storeInstallmentBkp->save();
        }
        return true;
    }

    // update loan requets status
    private function updateLoanRequestStatus($loanId, $status){
        $loanRequestData = LoanRequest::where('loan_id', $loanId)->update(['status' => $status]);

        return true;
    }
}
