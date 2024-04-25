<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class ForgetPasswordController extends Controller
{
    public function forgetPassword($token)
    {
        $data['token'] = $token;
        $data['title'] = "Change Password";
        // abort(404);
        return view('emails.change_password', $data);
    }
    public function submitForgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $user = User::where(['email_token'=>$request->token,'token_valid'=>1])->first();
        if($user)
        {
            $user->token_valid = 0;
            $user->password = bcrypt($request->password);
            $user->save();
            return back()->with('success','Password changes successfully');
        }
        return back()->with('error','Your link has been expired. Please try again.');
    }
}
