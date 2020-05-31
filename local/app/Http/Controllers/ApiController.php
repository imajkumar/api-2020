<?php

namespace App\Http\Controllers;

use App\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function register(Request $request)
    {





        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;
        $address = $request->address;
        $gender = $request->gender;
       
        $dob = $request->dob;
        $role_name = $request->role_name;

        $credentials = $request->only('avatar','first_name', 'last_name', 'email', 'password', 'photo', 'phone');

        $rules = [
            'first_name' => 'required|max:255',
            'password' => 'required',
            'email' => 'required|email|max:255|unique:users'
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $fileName="";
        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            $fileName = 'avatar' . rand() . "_" . date('dmyhis') . "." . $file->getClientOriginalExtension();

            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
            
        }

        DB::table('users')->insert(
            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'dob' => $dob,
                'password' => Hash::make($password),                
                'avatar' =>$fileName ,
                'gender' =>$gender, 
                'address' =>$address, 
                'role_name' =>$role_name 
            ]
        );




        // $user = User::create(['avatar'=>$photo,'first_name'=>$first_name,'last_name' =>$last_name,'phone' => $phone, 'email' => $email, 'password' => Hash::make($password)]);
        //return $this->setSuccessResponse($user,"Registedred Saved succesfully",'oo');
        echo "dd5";
    }
    //
}
