<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    // get all companies
    public function get(Request $request)
    {
        if ($request->ajax()) {
            $companies = Company::whereNull('deleted_at');
            return Datatables::of($companies)
                ->addIndexColumn()
                ->editColumn('authorized_credit_limit', function($row){
                    return currencyFormatter($row->authorized_credit_limit);
                })
                ->editColumn('ioweyou_expiry_date', function($row){
                    return date('d-m-Y', strtotime($row->ioweyou_expiry_date));
                })
                ->editColumn('status', function($row){
                    if($row->status == 1){
                        return "<select class='change-status form-select w-auto' data-id='".$row->id."'>
                            <option value='1' ".($row->status == 1 ? 'selected' : '').">Pending</option>
                            <option value='2' ".($row->status == 2 ? 'selected' : '').">Approved</option>
                            <option value='3' ".($row->status == 3 ? 'selected' : '').">Rejected</option>
                        </select>";
                    }else{
                        if($row->status == 2){
                            return 'Approved';
                        }else{
                            return 'Rejected';
                        }
                    }
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('employee.get').'?cid='.$row->id.'" data-id="'.$row->id.'" class="btn btn-primary btn-sm viewEmployees me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View employees"><i class="uil uil-users-alt"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editCompany me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit company details"><i class="mdi mdi-pencil-outline"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteCompany" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete company details"><i class="mdi mdi-delete-outline"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('company.index');
    }

    // store company details
    public function store(Request $request)
    {
        if($request->ajax()){
            $rules = [
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone_number' => ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:companies,phone_number'],
                'ioweyou_expiry_date' => ['required','date']
            ];
            if(!empty($request->company_id)){
                $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:companies,phone_number,'.$request->company_id];
                $rules['consetitutive_act_document'] = ['nullable','mimes:pdf,jpeg,jpg,png','max:5120'];
                $rules['ine_document'] = ['nullable','mimes:pdf,jpeg,png','max:5120'];
                $rules['ioweyou_document'] = ['nullable','mimes:pdf,jpeg,png','max:5120'];
                $rules['authorized_credit_limit'] = [
                    'required',
                    'numeric',
                    'min:1',
                    // function($attribute, $value, $fail) use($request){
                    //     $availableCredit = getAvailableSuperAdminCredit($request->company_id);
                    //     if ($value > $availableCredit) {
                    //         $fail('Current available credit limit is : '.$availableCredit.'.');
                    //     }
                    // }
                ];
            }else{
                $rules['consetitutive_act_document'] = ['required','mimes:pdf,jpeg,jpg,png','max:5120'];
                $rules['ine_document'] = ['required','mimes:pdf,jpeg,png','max:5120'];
                $rules['ioweyou_document'] = ['required','mimes:pdf,jpeg,png','max:5120'];
                $rules['authorized_credit_limit'] = [
                    'required',
                    'numeric',
                    'min:1',
                    // function($attribute, $value, $fail) {
                    //     $availableCredit = getAvailableSuperAdminCredit();
                    //     if ($value > $availableCredit) {
                    //         $fail('Current available credit limit is : '.$availableCredit.'.');
                    //     }
                    // }
                ];
            }

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            // Find existing company or create a new instance
            $company = Company::find($request->company_id) ?? new Company();

            // Check and delete old documents if new ones are uploaded
            if ($request->hasFile('consetitutive_act_document')) {
                if ($company->consetitutive_act_document) {
                    Storage::delete($company->consetitutive_act_document);
                }
                $constitutiveActDocument = $request->file('consetitutive_act_document')->store('company_documents', 'public');
            } else {
                $constitutiveActDocument = $company->consetitutive_act_document;
            }

            if ($request->hasFile('ine_document')) {
                if ($company->ine_document) {
                    Storage::delete($company->ine_document);
                }
                $ineDocument = $request->file('ine_document')->store('company_documents', 'public');
            } else {
                $ineDocument = $company->ine_document;
            }

            if ($request->hasFile('ioweyou_document')) {
                if ($company->ioweyou_document) {
                    Storage::delete($company->ioweyou_document);
                }
                $ioweyouDocument = $request->file('ioweyou_document')->store('company_documents', 'public');
            } else {
                $ioweyouDocument = $company->ioweyou_document;
            }

            // Prepare data for updating/creating the company record
            $data = [
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'authorized_credit_limit' => $request->authorized_credit_limit,
                'status' => $request->status,
                'ioweyou_expiry_date' => $request->ioweyou_expiry_date
            ];

            // Only add document fields to $data if new files are uploaded
            if ($constitutiveActDocument) {
                $data['consetutive_act'] = $constitutiveActDocument;
            }
            if ($ineDocument) {
                $data['ine'] = $ineDocument;
            }
            if ($ioweyouDocument) {
                $data['ioweyou'] = $ioweyouDocument;
            }

            // Update or create the company record
            $company->fill($data);

            if ($company->save()) {
                return response()->json(['status' => true, 'message' => __("translation.Company details stored successfully.")]);
            }
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to store company details.")]);
    }

    // edit compnay details
    public function edit($id)
    {
        $companyData = Company::find($id);
        if(!empty($companyData)){
            return response()->json(['status'=>true, 'message'=>'Success.', 'data'=>['companyDetails' => $companyData]]);
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to get company details.")]);
    }

    // delete company details
    public function delete(Request $request)
    {
        if($request->companyId){
            $companyData = Company::find($request->companyId);
            if (!$companyData) {
                return response()->json(['status' => false, 'message' => __("translation.Company not found.")]);
            }

            if ($companyData->delete()) {
                return response()->json(['status' => true, 'message' => __("translation.Company details deleted successfully.")]);
            }
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to delete company details.")]);
    }

    // change status of company
    public function changeStatus(Request $request){
        if($request->companyId){
            $companyData = Company::find($request->companyId);
            if (!$companyData) {
                return response()->json(['status' => false, 'message' => __("translation.Company not found.")]);
            }

            if (!empty($companyData->status)) {
                $companyData->status = $request->status;
                if($companyData->save()){
                    return response()->json(['status' => true, 'message' => __("translation.Status of the company changed successfully.")]);
                }
            }
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to change status of the company.")]);
    }
}
