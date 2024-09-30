<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    //  get all interest rates data
    public function get(Request $request){
        if ($request->ajax()) {
            $interestRateData = Setting::select('id', 'key', 'value');
            return DataTables::of($interestRateData)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editInterestRate me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit setting"><i class="mdi mdi-pencil-outline"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('setting.index');
    }

    // store and update interest rate
    public function store(Request $request){
        $rules = [
            'key' => 'required',
            'value' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        if(!empty($request->setting_id)){
            $settingData = Setting::where('id', $request->setting_id)->first();
            if($settingData){
                $settingData->key = $request->key;
                $settingData->value = $request->value;
                if($settingData->save()){
                    return response()->json(['status' => true, 'message' => "Configuración actualizada con éxito."]);
                }
            }
            return response()->json(['status' => false, 'message' => __("translation.No data found.")]);
        }
        return response()->json(['status' => false, 'message' => __("translation.Something went wrong. Please try again later.")]);
    }

    //  get edit details
    public function edit(Request $request){
        if(!empty($request->id)){
            $settingsData = Setting::where('id', $request->id)->first();
            if($settingsData){
                return response()->json(['status' => true, 'message' => 'Success.', 'settingsData' => $settingsData]);
            }
            return response()->json(['status' => false, 'message' => __("translation.No data found.")]);
        }
        return response()->json(['status' => false, 'message' => __("translation.Something went wrong. Please try again later.")]);
    }
}
