<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use view;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon;
use File;

class LoginController extends Controller
{
    
    public function store(Request $request)
    {
        $validator=Validator::make(Input::all(),
        array(
            'full_name' => 'required',
            'role' => 'required',
            'email_id' => 'required',
            'admin_code' => 'required',
            'mobile' => 'required',
            'isActive' => 'required'
        )
        );
        if ($validator->fails()) {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput(Input::all());
        } else{

            $admin_code = Input::get('admin_code');
            $full_name = Input::get('full_name');
            $role_id = Input::get('role');
            $email_id = Input::get('email_id');
            $mobile = Input::get('mobile');
            $isActive = Input::get('isActive');
            $password = rand(10000000,99999999);
            $address =  Input::get('address');
            $created_at=Carbon\Carbon::today()->format('Y-m-d');
            $updated_at=Carbon\Carbon::today()->format('Y-m-d');

            $admin=Admin::create(
                array(
                'role_id'=>$role_id,
                'admin_code'=>$admin_code,
                'full_name'=>$full_name,
                'mobile'=>$mobile,
                'email_id'=>$email_id,
                'password'=>$password,
                'address'=>$address,
                'isActive'=>$isActive,
                'created_at'=>$created_at,
                'updated_at'=>$updated_at
                )
            );
            $lastinsertid=$admin->id;
            if($admin){
                /*****Mail */
                /*$to = Input::get('buyer_email');
                $subject = 'Welcome to ShineIndia';
                $message = '
                <html>
                <head>
                <title>Welcome to ShineIndia</title>
                </head>
                <body style="font-size:20px;">

                <p style="padding-left:10px;">&nbsp;&nbsp;&nbsp;Welcome to ShineIndia &beta; Version,<br><br>
                    Dear '.Input::get('buyer_name').' ,<br>
                    Thank you for registeration, we wish for a long term engagement with you.
                    Your credentails :

                    your Email <b> '.Input::get('buyer_email').'</b> and
                    Password <b> '.Input::get('password').'<b><br>
                    <br><br>
                    for activate your account <strong><a href="https://shineindia.co.in/buyer_activate_acount/'.$active_token.'" target="_blank">Activate</a></strong>
                        <br><br>  

                    Note: Currently we have launched &beta; version very soon we will be upgrading.<br>

                    Thank you<br>
                    <a href="http://shineindia.co.in"><img src="https://shineindia.co.in/public/user/image/logo.png" style="width:200px;"></a>
                    <p>
                    <a href="https://www.facebook.com/ShineIndia-2083054048616143/"><img src="https://shineindia.co.in/public/social/fb.png" style="width:50px;height:50px;"></a>
                    <a href="https://plus.google.com/u/2/104088513464436174782"><img src="https://shineindia.co.in/public/social/GooglePlus-logos-02.png" style="width:45px;height:45px;"></a>
                    <a href=""><img src="https://shineindia.co.in/public/social/linkdin.png" style="width:55px;height:55px;"></a>
                    <a href="https://twitter.com/ShineIndia5"><img src="https://shineindia.co.in/public/social/twitter.jpg" style="width:45px;height:45px;"></a>
                    </p>
                    <br><br>
                    <span style="color:red;font-size:8px;">you have received this mail as you have registered to shineindia.co.in<br>
                    If you are not concerned with this mail please ignore. </span>
                    </p>
                </body>';

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <no_reply@shineindia.co.in>' . "\r\n";
                //$headers .= 'Cc: durgeshpandey1310@gmail.com' . "\r\n";
                mail($to,$subject,$message,$headers);*/
                /*****End Mail */
                return Redirect::back()
                    ->with('msg','Admin create Successfully');
            }
        }
    }

    public function update(Request $request, $id)
    {
        $admin_code = Input::get('admin_code');
        $full_name = Input::get('full_name');
        $role_id = Input::get('role');
        $email_id = Input::get('email_id');
        $mobile = Input::get('mobile');
        $address =  Input::get('address');
        $isActive = Input::get('isActive');

        Admin::where('admin_id', $id)->update(array(
            'role_id'=>$role_id,
            'full_name'=>$full_name,
            'mobile'=>$mobile,
            'email_id'=>$email_id,
            'address'=>$address,
            'isActive'=>$isActive,
            'updated_at' => Carbon\Carbon::today()->format('Y-m-d')
        ));
        return Redirect::back()
                ->with('msg','Admin Updated Successfull');
    }


    public function delete($id){
        Admin::where('admin_id', $id)->delete();
        return Redirect::back()
        ->with('msg', 'Admin delete successfull');
    }

    public function destroy($id)
    {
        Session::flush();
        return redirect('/');
    }

    public function authenticate(Request $request){
        $username = $request->input('txtEmail');
        $password = $request->input('txtPassword');

        $checkLogin = DB::select('select * from login where email="'.$username.'" and password="'.$password.'"
         and isActive="active"');
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