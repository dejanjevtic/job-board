<?php

namespace App\Http\Controllers;

use Auth;
use App\Job;
use App\User;
use App\Role;
use App\VerifyUser;
use App\Mail\HRMail;
use App\Mail\VerifyMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class PostController extends Controller
{
	public $role;

	public function __construct()
    {
        $this->middleware('auth');                
    }

    public function index()    {
		$message="";
        return view('post',compact('message',$message));

    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $job = new Job();
        $job->user_id = $user->id;
    	$job->title = $request->title;
        $job->description = $request->description;
        $job->email = $request->email;
        if($user->email_verified_at) $job->approved = 1;
        else $job->approved = 0;
        
		// Moderator e-mail
        
        $moderator_role_id = User::find(1)->roles()->where('name', 'ROLE_MODERATOR')->first()->id;
        $moderator_email = Role::find(1)->users()->where('role_id', $moderator_role_id)->first()->email;

		$this->role = $user->roles ? $user->roles->first()->name : 'No role'; 
		if($this->role!="ROLE_MODERATOR" && !$user->email_verified_at) {
            $message="Your Job must be approved by moderator";
            $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
            ]);


            if($this->role=="ROLE_HR") {
                Mail::to($user->email)->send(new HRMail($user, 'Your submission is in moderation'));
            }
            Mail::to($moderator_email)->send(new VerifyMail($user, $job));
            
        }
		else {
            $message="";
            $job->approved = 1;
    	}
        $job->save();
       
        return redirect('/home')->with('message', $message);
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;

            if(!$user->email_verified_at) {
                $verifyUser->user->email_verified_at = 1; 
                $verifyUser->user->save();
                $status = "Your e-mail is verified.";
            }else{
                $status = "Your e-mail is already verified.";
            }
        }else{
            return redirect('/home')->with('warning', "Sorry your email cannot be identified.");
        }

        return redirect('/home')->with('status', $status);
    }
}
