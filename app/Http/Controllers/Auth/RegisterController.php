<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CompanyRegisteredMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // register a company
    public function registerCompany(Request $request){
        if($request->ajax()){
            $rules = [
                'name' => 'required|string|max:255',
                'phone_number' => ['required','string','max:20','regex:/^\+\d{1,3}\d{9,14}$/','unique:companies,phone_number'],
                'address' => 'required|string|max:255',
                'consetitutive_act_document' => ['required','file','mimes:pdf,jpeg,jpg,png','max:5120'],
                'ine_document' => ['required','file','mimes:pdf,jpeg,png','max:5120'],
            ];

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            $company = new Company;

            if ($request->hasFile('consetitutive_act_document')) {
                $constitutiveActDocument = $request->file('consetitutive_act_document')->store('company_documents', 'public');
            }

            if ($request->hasFile('ine_document')) {
                $ineDocument = $request->file('ine_document')->store('company_documents', 'public');
            }

            $data = [
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'status' => $request->status,
            ];

            if ($constitutiveActDocument) {
                $data['consetutive_act'] = $constitutiveActDocument;
            }
            if ($ineDocument) {
                $data['ine'] = $ineDocument;
            }

            $company->fill($data);

            if ($company->save()) {
                $mailData = ['subject' => 'New Company Registered', 'message' => $company->name.' has registered on your platform. Please verify the documents and approve the joining request of the company to proceed with further processes.'];
                Mail::to(config('app.super_admin_mail'))->send(new CompanyRegisteredMail($mailData));
                return response()->json(['status' => true, 'message' => 'Company details stored successfully.']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Failed to store company details.']);
    }
}
