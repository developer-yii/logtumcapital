<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;
use Illuminate\Support\Facades\Storage;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class InvestmentController extends Controller
{
    // get all investments
    public function get(Request $request)
    {
        if ($request->ajax()) {
            $investments = Investment::whereNull('deleted_at');
            return Datatables::of($investments)
                ->addIndexColumn()
                ->editColumn('contributions', function($row){
                    return currencyFormatter($row->contributions);
                })
                ->editColumn('interest_rate', function($row){
                    return currencyFormatter($row->interest_rate, false).'%';
                })
                ->editColumn('interest_earnings', function($row){
                    return currencyFormatter($row->interest_earnings);
                })
                ->editColumn('total_amount', function($row){
                    return currencyFormatter($row->total_amount);
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editInvestment me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit investment details"><i class="mdi mdi-pencil-outline"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteInvestment" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete investment details"><i class="mdi mdi-delete-outline"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('investment.index');
    }

    // store investment details
    public function store(Request $request)
    {
        if($request->ajax()){
            $rules = [
                'name' => 'required|string|max:255',
                'contributions' => 'required|numeric',
                'interest_rate' => 'required|numeric|min:1|max:100',
                'interest_earnings' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'investment_contract' => 'required|max:5120',
                'note' => 'nullable',
            ];
            if(!empty($request->investment_id)){
                $rules['investment_contract'] = 'nullable|max:5120';
            }

            // Validate the request data
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 'error', 'message' => $validator->errors()]);
            }

            // Find existing investment or create a new instance
            $investment = Investment::find($request->investment_id) ?? new Investment();

            if ($request->hasFile('investment_contract')) {
                if ($investment->investment_contract) {
                    Storage::delete($investment->investment_contract);
                }
                $investmentDocument = $request->file('investment_contract')->store('investment_documents', 'public');
            } else {
                $investmentDocument = $investment->investment_contract;
            }

            // Prepare data for updating/creating the investment record
            $data = [
                'name' => $request->name,
                'contributions' => $request->contributions,
                'interest_rate' => $request->interest_rate,
                'interest_earnings' => $request->interest_earnings,
                'total_amount' => $request->total_amount,
                'note' => !empty($request->note)?$request->note:''
            ];

            if ($investmentDocument) {
                $data['investment_contract'] = $investmentDocument;
            }

            // Update or create the investment record
            $investment->fill($data);

            if ($investment->save()) {
                return response()->json(['status' => true, 'message' => __("translation.Investment details stored successfully.")]);
            }
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to store investment details.")]);
    }

    // edit investment details
    public function edit($id)
    {
        $investmentData = Investment::find($id);
        if(!empty($investmentData)){
            return response()->json(['status'=>true, 'message'=>'Success.', 'data'=>['investmentDetails' => $investmentData]]);
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to get investment details.")]);
    }

    // delete investment details
    public function delete(Request $request)
    {
        if($request->investmentId){
            $investmentData = Investment::find($request->investmentId);
            if (!$investmentData) {
                return response()->json(['status' => false, 'message' => __("translation.Investment not found.")]);
            }

            if ($investmentData->delete()) {
                return response()->json(['status' => true, 'message' => __("translation.Investment details deleted successfully.")]);
            }
        }
        return response()->json(['status' => false, 'message' => __("translation.Failed to delete investment details.")]);
    }
}
