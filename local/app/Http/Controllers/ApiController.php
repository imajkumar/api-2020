<?php

namespace App\Http\Controllers;

use App\User;
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
    public function generalDetails(Request $request){
        $name = $request->name;
        $father_name = $request->father_name;
        $email = $request->email;
        $phone = $request->phone;
        $dob = $request->dob;
        $address = $request->address;
        $state = $request->state;        
        $pincode = $request->pincode;       
        $aadharno = $request->aadharno;
        $pandcardno = $request->pandcardno;
        $aadharno_1="";
        $aadharno_2="";
        $pandcardno_1="";
        $pandcardno_2="";
        if ($request->hasFile('aadharno_1')) {

            $file = $request->file('aadharno_1');

            $fileName = 'aadharno_1' . rand() . "_" . date('dmyhis') . "." . $file->getClientOriginalExtension();

            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
            $aadharno_1=$fileName;
        }

        if ($request->hasFile('aadharno_2')) {

            $file = $request->file('aadharno_2');

            $fileName = 'aadharno_2' . rand() . "_" . date('dmyhis') . "." . $file->getClientOriginalExtension();

            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
            $aadharno_2=$fileName;
        }

        if ($request->hasFile('pandcardno_1')) {

            $file = $request->file('pandcardno_1');

            $fileName = 'pandcardno_1' . rand() . "_" . date('dmyhis') . "." . $file->getClientOriginalExtension();

            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
            $pandcardno_1=$fileName;
        }

        if ($request->hasFile('pandcardno_2')) {

            $file = $request->file('pandcardno_2');

            $fileName = 'pandcardno_2' . rand() . "_" . date('dmyhis') . "." . $file->getClientOriginalExtension();

            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
            $pandcardno_2=$fileName;
        }






        $result = DB::table('details')->insert(
            [
                'name' => $name,
                'father_name' => $father_name,
                'email' => $email,
                'phone' => $phone,
                'dob' => $dob,        
                'address' => $address,
                'state' => $state,
                'pincode' => $pincode,
                'aadharno' => $aadharno,
                'pandcardno' => $pandcardno,
                'aadharno_1' => $aadharno_1,
                'aadharno_2' => $aadharno_2,
                'pandcardno_1' => $pandcardno_1,
                'pandcardno_2' => $pandcardno_2,

            ]
        );
        $id = DB::getPdo()->lastInsertId();
       
        $users = DB::table('details')->where('id',$id)->get();

        return $this->setSuccessResponse($users, "Details saved ", 'oo');



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

        $credentials = $request->only('avatar', 'first_name', 'last_name', 'email', 'password', 'photo', 'phone');

        $rules = [
            'first_name' => 'required|max:255',
            'password' => 'required',
            'email' => 'required|email|max:255|unique:users'
        ];

        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $fileName = "";
        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            $fileName = 'avatar' . rand() . "_" . date('dmyhis') . "." . $file->getClientOriginalExtension();

            $destinationPath = 'uploads';
            $file->move($destinationPath, $fileName);
        }

        $result = DB::table('users')->insert(
            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'dob' => $dob,
                'password' => Hash::make($password),
                'avatar' => $fileName,
                'gender' => $gender,
                'address' => $address,
                'role_name' => $role_name
            ]
        );

        $id = DB::getPdo()->lastInsertId();

        $result = User::find($id);

        return $this->setSuccessResponse($result, "Registedred Saved succesfully", 'oo');


        // $user = User::create(['avatar'=>$photo,'first_name'=>$first_name,'last_name' =>$last_name,'phone' => $phone, 'email' => $email, 'password' => Hash::make($password)]);
        //return $this->setSuccessResponse($user,"Registedred Saved succesfully",'oo');

    }
    //

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;


        try {
            $credentials = $request->only('email', 'password');

            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $validator = Validator::make($credentials, $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' => $validator->messages()]);
            }

            // $hashedPassword = DB::table('users')->where('email', $email)->first();
            $hashedPassword = User::where('email', $request->email)->first();
            //print_r($hashedPassword);
            //die;


            if (Hash::check($password, $hashedPassword->password)) {
                return $this->setSuccessResponse($hashedPassword, "Login succesfully", 'oo');
            } else {
                return $this->setErrorResponse([], "Failed-LOGIN", $hashedPassword);
            }
        } catch (\Exception $ex) {
            return $this->setErrorResponse($ex->getMessage());
        }
    }
}
