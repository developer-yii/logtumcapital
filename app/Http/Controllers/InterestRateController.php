<?php

namespace App\Http\Controllers;

use App\Models\InterestRate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class InterestRateController extends Controller
{
    //  get all interest rates data
    public function get(Request $request){
        if ($request->ajax()) {
            $interestRateData = InterestRate::select('id', 'company_name', 'interest_rate');
            return Datatables::of($interestRateData)
            ->addIndexColumn()
            ->editColumn('interest_rate', function($row){
                return $row->interest_rate.'%';
            })
            ->addColumn('action', function($row){
                return '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editInterestRate me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit interest rate"><i class="mdi mdi-pencil-outline"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('interest_rate.index');
    }

    // store and update interest rate
    public function store(Request $request){
        $rules = [
            'interest_rate' => 'required|numeric|gt:0|max:100'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        if(!empty($request->interest_rate_id)){
            $interestRateData = InterestRate::where('id', $request->interest_rate_id)->first();
            if($interestRateData){
                $interestRateData->company_name = $request->name;
                $interestRateData->sub_title = $request->tag_line;
                $interestRateData->interest_rate = $request->interest_rate;
                if($interestRateData->save()){
                    return response()->json(['status' => true, 'message' => __("translation.Interest rate updated successfully.")]);
                }
            }
            return response()->json(['status' => false, 'message' => __("translation.No data found.")]);
        }
        return response()->json(['status' => false, 'message' => __("translation.Something went wrong. Please try again later.")]);
    }

    //  get edit details
    public function edit(Request $request){
        if(!empty($request->id)){
            $interestRateData = InterestRate::where('id', $request->id)->first();
            if($interestRateData){
                return response()->json(['status' => true, 'message' => 'Success.', 'interestRateData' => $interestRateData]);
            }
            return response()->json(['status' => false, 'message' => __("translation.No data found.")]);
        }
        return response()->json(['status' => false, 'message' => __("translation.Something went wrong. Please try again later.")]);
    }
}
