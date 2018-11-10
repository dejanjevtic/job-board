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

    /**
     * Show the application job form
     *     
     * @return \Illuminate\Http\Response
     */
    public function index()    {
		$message="";
        $user = Auth::user();
        return view('post',['message' => $message, 'mail' => $user->email]);
    }

    /**
     * Save job, sending mail to moderator and HR, 
     *
     * @param $request
     * @return mix
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /*posted job*/
        $job = new Job();
        $job->user_id = $user->id;
    	$job->title = $request->title;
        $job->description = $request->description;
        $job->email = $request->email;
        if($user->email_verified_at) {
            $job->approved = 1;
            $job->spam = 0;
        }
        else {
            $job->approved = 0;
        }
         
		/* User's role */
        if($user->hasAnyRole(["ROLE_MODERATOR","ROLE_HR"])) { 
            $this->role = $user->roles->first()->name;              
        }         
        else {
            $this->role = 'No role';
        }
        
        /* Last uncertain job set by user */
        $jobSetbyUser = Job::where('user_id', $user->id)
                            ->where('approved', false)
                            ->where('spam', false)
                            ->orderBy('id', 'desc')
                            ->first(); 

        /* Sending mail to moderator if this is first user's job */
		if(!$user->hasRole("ROLE_MODERATOR") && !$user->email_verified_at && !$jobSetbyUser) {
            $status = "Your Job must be approved by moderator";
            $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
            ]);

            /* Sending mail to HR if this is first HR's job */
            if($user->hasRole("ROLE_HR")) {
                    Mail::to($user->email)->send(new HRMail($user, 'Your submission is in moderation'));
            }

            // Moderator e-mail        
            $moderator_role_id = User::find(1)
                                    ->roles()->where('name', 'ROLE_MODERATOR')
                                    ->first()
                                    ->id;
            $moderator_email = Role::find(1)
                                    ->users()
                                    ->where('role_id', $moderator_role_id)
                                    ->first()
                                    ->email;

            Mail::to($moderator_email)->send(new VerifyMail($user, $job));       
            $job->save();
        }
		else if(!$user->hasRole("ROLE_MODERATOR") && !$user->email_verified_at && is_object($jobSetbyUser)){
            $status = "Your must be firstly approved by moderator";                       
    	}
        else {
            $status = "Saved Job"; 
            $job->save();
        }
       
        return redirect('/home')->with('status', $status);
    }

    /**
     * Verify User for his first job by moderator
     *
     * @param $token
     * @return home
     */
    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;

            if(!$user->email_verified_at) {
                $verifyUser->user->email_verified_at = 1; 
                $verifyUser->user->save();
                $status = "Your e-mail is verified and job is published.";
            }else{
                $status = "Your e-mail is already verified.";
            }
        }else{
            return redirect('/home')->with('warning', "Sorry your email cannot be identified.");
        }
       
        $job = Job::where('user_id', $user->id)
                    ->orderBy('id', 'desc')
                    ->first(); 
        $job->approved = 1;
        $job->save();

        return redirect('/home')->with('status', $status);
    }

    /**
     * Mark as spam user's first job by moderator
     *
     * @param $token
     * @return home
     */
    public function spamUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            $status = "Your Job is spam";            
        }else{
            return redirect('/home')->with('warning', "Sorry your email cannot be identified.");
        }
        
        $job = Job::where('user_id', $user->id)
                    ->orderBy('id', 'desc')
                    ->first(); 
        $job->spam = 1;
        $job->save();

        return redirect('/home')->with('status', $status);
    }
}
