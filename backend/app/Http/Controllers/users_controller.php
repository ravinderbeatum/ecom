<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Hash;
use Auth;
use App\Http\Resources\User as UserResource;
class users_controller extends Controller
{
    public $response = array('success' => 0, 'msg' => array('text' => ''));

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }


        $input = $request->all();
        $input['role'] = 1;
        $input['password'] = Hash::make($input['password']);
        //echo '<pre>';print_r($input);die;
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['first_name'] =  $user->name;


        return response()->json(['success'=>$success]);
    }

    public function login(Request $request)
    {
        //echo '<pre>';print_r($request->all());die('hh');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $this->response['msg'] = $validator->messages();            
        }
        else {
            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)) {
                $user = Auth::user(); 
                $this->response['token'] =  $user->createToken('MyApp')->accessToken;
                $this->response['success'] = 1;
                $this->response['msg']['text'] = 'Logged in successfully.';
                $this->response['user_details'] = $user;
            }
            else {
                $this->response['msg']['text'] = 'Email or password is incorrect.';
            }
        }
        return $this->response;
    }

    ###### FUNCTION TO UPDATE USER PROFILE #######
    public function save_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/|max:255',
            //'last_name' => 'regex:/^[a-zA-Z ]+$/|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|min:10|digits:10',
        ]);

        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();           
        }
        else {
            $data = $request->all();
            if(Auth::user()->update($data)) {
                $this->response['msg']['text'] = 'User details has been updated successfully.';
                $this->response['success'] = 1;
            }    
            else {
                $this->response['msg']['text'] = 'An error occured while updating user details. Please try again later';
            }  
        } 
        return $this->response;
    }

    ###### FUNCTION TO CHANGE PASSWORD #######
    public function change_password(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'old_password' => 'required|max:255',
            'new_password' => 'required|same:new_password|max:255',
            'confirm_password' => 'required|same:new_password|max:255',
        ]);
        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();           
        }
        else {
            $data = $request->all();
            $current_password = Auth::user()->password;
            if(Hash::check($data['old_password'], $current_password))
            {         
                $admin_id = Auth::user()->id;
                $change_password['password'] = Hash::make($data['new_password']);
                if(Auth::user()->update($change_password)) {
                    $this->response['msg']['text'] = 'Password has been changed successfully.';
                    $this->response['success'] = 1;
                }    
                else {
                    $this->response['msg']['text'] = 'An error occured while changing password. Please try again later';
                } 
            }
            else
            {       
                $this->response['msg']['text'] = 'Old password is incorrect'; 
            }
        } 
        return $this->response;        
    }
}
