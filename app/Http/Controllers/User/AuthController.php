<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Project\DevelopmentProgress;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);
        
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        
        $tokenResult = $user->createToken('tokens');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function user(Request $request){
        $user = $request->user();
        $status = "await";

        $customerLot = $user->customer->customerLot;
        if($customerLot != null){
            $generalStatus = $customerLot->generalStatus;
            if($generalStatus != null){
                $status = ($generalStatus->key == 'approved') ? 'accept' : 'decline';
            }
        }

        $user['submission'] = $status;
        // if($user->customer->developmentProgress->count() == 0){
        //     $user['submission'] = "await";
        // }else{
        //     $user['submission'] = ($user->customer->developmentProgress[$user->customer->developmentProgress->count()-1]->customer_approval)
        //         ? 'accept'
        //         : 'decline';
        // }
        
        $user['customer_id'] = $user->customer->id;
        unset($user['customer']);
        return response()->json($user);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout success'
        ]);

    }

    public function setting(Request $request){
        $user = $request->user();
        
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $user->name = $request->name;
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json(['status'=> 'success']);

    }
}
