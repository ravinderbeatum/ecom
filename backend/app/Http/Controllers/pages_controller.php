<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use Validator;
class pages_controller extends Controller
{
    public $response = array('success' => 0, 'msg' => array('text' => ''), 'data' => array());

    ######### FUNCTION TO HET LIST OF ALL ACTIVE PRODUCTS #########
    function get_products() {
        $response['data'] = Product::where('status', 1)->get();
        $response['success'] = 1;
        //echo '<pre>';print_r($response);die;
        return $response;
    }

    ######### FUNCTION TO GET LIST OF ALL ACTIVE CATEGORIES #########
    function get_categories() {
        $response['data'] = Category::select('category_name', 'id')->with(['products' => function ($query) {
            $query->where('status', 1);
        },])->where('status', 1)->get();
        $response['success'] = 1;
        //echo '<pre>';print_r($response);die;
        return $response;
    }


    ######### FUNCTION TO GET PRODUCT AND RELATED PRODUCTS #########
    function get_product(Request $request, $product_id) {
        $data['product_id'] = $product_id;
        $validator = Validator::make($data, [
            'product_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $this->response['msg']['text'] = $validator->messages();   
        }
        else {
            $this->response['data']['product'] = Product::where('id', $product_id)->first();
            if(!empty($this->response['data']['product'])) {
                $this->response['data']['related_products'] = Product::where('category_id', $this->response['data']['product']['category_id'])->where('id', '!=', $this->response['data']['product']['id'])->get();
                $this->response['success'] = 1;
            }    
            else {  
                $this->response['msg']['text'] = 'Product not found.';
            }
        }
        return $this->response;
    }
}
