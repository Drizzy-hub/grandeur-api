<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PosterRefund;
use App\Models\PosterUploads;
use Illuminate\Support\Facades\Auth;
use Validator;

class PosterRefundController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        $refunds = PosterRefund::all();

        return response()->json([
            'success' => true,
            'data' => $refunds
        ]);
    }

    public function getRefund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'desc' => 'required|string',
            'file_id' => 'required',
            'paycode' => 'required|string',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->Json([
                'success' => false,
                'message' => $validator->errors()
            ], 401);
        }

        $file = PosterUploads::where('file_id', $request->file_id)->first();
        // check if the file id is valid
        if(is_null($file)){
            return response()->Json([
                'success' => false,
                'message' => "Invalid file id."
            ]);
        }
        $user = auth('poster')->user();
        // check if the requester is the owner of the file
        if($user->email != $file->email){
            return response()->Json([
                'success' => false,
                'message' => "UnAuthorized access to file."
            ]);
        }

        $data = new PosterRefund();
        $data->email = $user->email;
        $data->matricNo = $user->matricNo;
        $data->subject = $request->subject;
        $data->file_id = $request->file_id;
        $data->desc = $request->desc;
        $data->paycode = $request->paycode;
        $data->amount = $request->amount;
        $data->save();

        
        return response()->Json([
            'success' => true,
            'message' => "Refund request log successfully"
        ]);
        
    }

    public function getMyRefunds(){
        $user = auth('poster')->user();
        $refunds = PosterRefund::where('email', $user->email);

        if (is_null($refunds)) {
            return response()->json([
                'success' => true,
                'data' => 'No data found for user'
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $refunds
        ]);
    }
}
