<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use DB;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Str;
use Mail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $this->loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $this->loginValue = $request->email;
       // print_r($this->loginValue);die();
        $request->merge([$this->loginField => $this->loginValue]);
        $userPassword = User::where($request->only($this->loginField))->first();
        // echo "<pre>"; print_r($userPassword);die();
        if(!empty($userPassword)){
            $token = Str::random(64);
            DB::table('password_resets')->where('email',$userPassword->email)->delete();            
            DB::table('password_resets')->insert(['email'=>$userPassword->email,'token'=>$token]);
            
            $url = url('reset-password/?token='.$token);

            $mailInfo = [
                'email'     => $request->email,
                'token'     => $request->_token,
                'url'       => $url
            ];

            $details = ['type'=>'ResetPasswordTemplate','email' => $userPassword->email,'mailInfo' => $mailInfo];
            SendEmailJob::dispatchNow($details);
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            // $status = Password::sendResetLink(
            //    $request->only('email')
            // );
            //print_r($status);die();
            return  redirect()->back()->with('status','An email sent to your email to reset password');
        }else{
            return redirect()->back()->with('error', 'You are not register with us');
        } 
    }
}
