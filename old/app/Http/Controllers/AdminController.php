<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use view;
use DB;
use Session;
use AngelBroking\SmartApi;
use KiteConnect\KiteConnect;

// require_once __DIR__ . '../../vendor/autoload.php';

class AdminController extends Controller
{
    //
    public function logout(Request $request){
        Session::flush();
        return redirect('/');
    }

    public function authenticate(Request $request){
        $username = $request->txtEmail;
        $password = $request->txtPassword;
        $checkLogin = DB::select('select * from login where email="'.$username.'" and password="'.$password.'"
         and is_active="active"');
        if(count($checkLogin)  > 0)
        {
            foreach ($checkLogin as $checkLogins) {
                # code...
            }
            
            Session::put('admin_id',$checkLogins->id);
            Session::put('email',$username);
             return  Redirect::to('/dashboard');
        }
        else
        {
            return Redirect::to('/')->with('msg','Invalid Login Try Again...');
        }
    }
}
