<?php

namespace App\Http\Controllers;

use Auth;
Use App\Job;
use App\User;
use App\Role;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $message = "";
        $jobs = "";
        $user = Auth::user(); 
        if($user->email_verified_at){
            $jobs = Job::where('user_id', $user->id)
                   ->orderBy('id', 'desc')
                   ->take(10)
                   ->get();
        }
       
        return view('home',['message' => $message, 'jobs' => $jobs]);
    }
}
