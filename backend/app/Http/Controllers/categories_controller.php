<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Validator;
use Auth;
use App\Http\Resources\Category as CategoryResource;
class categories_controller extends Controller
{
    public $response = array('success' => 0, 'msg' => array('text' => ''), 'data' => array());

    ######### FUNCTION TO ADD PRODUCT CATEGORY #######
    function add_category(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();           
        }
        else {
            $data['category_name'] = $request->category_name;
            $data['user_id'] = Auth::user()->id;
           if(Category::create($data)) {
                $this->response['msg']['text'] = 'Category has been added successfully.';
                $this->response['success'] = 1;
           } 
           else {
                $this->response['msg']['text'] = 'An error occured while adding category.';
           }
        }
        return $this->response;
    }

    ######### FUNCTION TO ADD PRODUCT CATEGORY #######
    function categories() {
        $user_id = Auth::user()->id;
        $this->response['data'] = Category::where('user_id', $user_id)->get();
        $this->response['success'] = 1;
        return $this->response;
    }

    ######### FUNCTION TO ADD PRODUCT CATEGORY #######
    function update_category_status(Request $request) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|numeric|in:0,1',
            'category_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();   
        }
        else {
            if($request->status) {
                $request->status = 0;
            }
            else {
                $request->status = 1;
            }
            $user_id = Auth::user()->id;
            if(Category::where('user_id', $user_id)->where('id', $request->category_id)->update(['status' => $request->status])) {
                $this->response['success'] = 1; 
                $this->response['msg']['text'] = 'Category status has been updated successfully.';   
            }
            else {
                $this->response['msg']['text'] = 'An error occured while updating category status.';
            }    
        }
        return $this->response;
    }

    ######### FUNCTION TO ADD PRODUCT CATEGORY #######
    function get_category(Request $request, $category_id) {
        $data['category_id'] = $category_id;
        $validator = Validator::make($data, [
            'category_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();   
        }
        else {
            $user_id = Auth::user()->id;
            $this->response['data'] = Category::where('user_id', $user_id)->where('id', $category_id)->first();
            if(empty($this->response['data'])) {
                $this->response['msg']['text'] = 'Category not found.';
            }
            else {  
                $this->response['success'] = 1;
            }
        }
        return $this->response;
    }

    ######### FUNCTION TO UPDATE PRODUCT CATEGORY #######
    function update_category(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|max:255',
            'category_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();           
        }
        else {
            $data['category_name'] = $request->category_name;
            $user_id = Auth::user()->id;
           if(Category::where('user_id', $user_id)->where('id', $request->category_id)->update($data)) {
                $this->response['msg']['text'] = 'Category has been updated successfully.';
                $this->response['success'] = 1;
           } 
           else {
                $this->response['msg']['text'] = 'An error occured while updating category.';
           }
        }
        return $this->response;
    }

    ######### FUNCTION TO ADD PRODUCT CATEGORY #######
    function get_categories() {
        $user_id = Auth::user()->id;
        $this->response['data'] = Category::select('category_name', 'id')->where('status', 1)->where('user_id', $user_id)->get();
        $this->response['success'] = 1;
        return $this->response;
    }
}
