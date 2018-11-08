<?php

namespace App\Http\Controllers;

use Auth;
Use App\Job;
use App\User;
use App\Role;
use App\VerifyUser;
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
        $job->approved = 0;
        
		
		$this->role = $user->roles ? $user->roles->first()->name : 'No role';
		if($this->role!="ROLE_MODERATOR") {
            $message="Your Job must be approved by moderator";
            $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);

        Mail::to($user->email)->send(new VerifyMail($user, $job));

        
        }
		else {
            $message="";
            $job->approved = 1;
    	}
        $job->save();
        return view('home',compact('message',$message));
    }
}
