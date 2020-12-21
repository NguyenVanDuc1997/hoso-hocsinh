<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class PagesController extends Controller
{
	public function autocomplete_login(Request $request){
        $q = $request->q;
        $u = User::where('username','LIKE','%'.$q.'%')->select('username','last_name')->get();
        return $u;
    }
    public function getHome()
    {
		//if(Auth::guest()){
		        return view('auth.login');
		//}else{
		//	 return view('layouts.auth', ['category' => 'James']);
		//}
    }
}
