<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyAdminController extends Controller
{
    // get all companyAdmins
    public function get(Request $request)
    {
        $companyId = !empty($request->cid) ? $request->cid : '';
        $companiesData = Company::select('id', 'name')->where('status', 2)->get();
        if ($request->ajax()) {
            if (!empty($companyId)) {
                $companyAdmins = User::where('role', 2)->where('company_id', $companyId);
            } else {
                $companyAdmins = User::where('role', 2);
            }
            return Datatables::of($companyAdmins)
                ->addIndexColumn()
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm editCompanyAdmin me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit company admin details"><i class="mdi mdi-pencil-outline"></i></a>';
                    // $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteCompanyAdmin" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete company admin details"><i class="mdi mdi-delete-outline"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company_admin.index', compact('companyId', 'companiesData'));
    }

    // store company admin details
    public function store(Request $request)
    {
        if($request->ajax()){
            $rules = [
                'company' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ];
            if(!empty($request->company_admin_id)){
                $rules['email'] = ['required','email','max:255','unique:users,email,'.$request->company_admin_id];
                $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:users,phone_number,'.$request->company_admin_id];
                $rules['password'] = ['nullable','min:8','max:255'];
                $rules['confirm_password'] = ['same:password'];
            }else{
                $rules['email'] = ['required','email','max:255','unique:users,email'];
                $rules['phone_number'] = ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:users,phone_number'];
                $rules['password'] = ['required','min:8','max:255'];
                $rules['confirm_password'] = ['required','same:password'];
            }

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            // Find existing user or create a new instance
            $companyAdmin = User::find($request->company_admin_id) ?? new User();

            // Prepare data for updating/creating the companyAdmin record
            $data = [
                'company_id' => $request->company,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'role'=>2,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Update or create the companyAdmin record
            $companyAdmin->fill($data);

            if ($companyAdmin->save()) {
                return response()->json(['status' => true, 'message' => 'Company admin details stored successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to store company admin details.']);
    }

    // edit company admin details
    public function edit($id)
    {
        $companyAdminData = User::find($id);
        if(!empty($companyAdminData)){
            return response()->json(['status'=>true, 'message'=>'Success.', 'data'=>['companyAdminDetails' => $companyAdminData]]);
        }
        return response()->json(['status' => false, 'message' => 'Failed to get company admin details.']);
    }

    // delete company admin details
    public function delete(Request $request)
    {
        if($request->companyAdminId){
            $companyAdminData = User::find($request->companyAdminId);
            if (!$companyAdminData) {
                return response()->json(['status' => false, 'message' => 'Company admin not found.']);
            }

            if ($companyAdminData->delete()) {
                return response()->json(['status' => true, 'message' => 'Company admin details deleted successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to delete company admin details.']);
    }
}
