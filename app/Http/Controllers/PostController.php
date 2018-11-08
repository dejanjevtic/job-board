<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Role;
use Illuminate\Http\Request;



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
		$this->role = $user->roles ? $user->roles->first()->name : 'No role';
		if($this->role!="ROLE_MODERATOR") $message="Your Job must be approved by moderator";
		else $message="";
    	
        return view('home',compact('message',$message));
    }
}
