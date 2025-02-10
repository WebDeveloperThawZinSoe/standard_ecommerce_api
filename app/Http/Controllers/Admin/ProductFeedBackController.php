<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\CustomerFeedback;
use App\Models\Brand;
use App\Models\ProductVariants;
use App\Models\Order;
use App\Models\Goal;
use App\Models\ProductFeedBack;
use App\Models\GeneralSetting;

class ProductFeedBackController extends Controller
{
    //index
    public function index()
    {
        $productFeedbacks = ProductFeedBack::orderBy('id', 'desc')->get();
        return view('admin.product_feedback.index',compact("productFeedbacks"));
    }

    //show
    public function show($id){
        $productFeedback = ProductFeedBack::find($id);
        return view('admin.product_feedback.show',compact("productFeedback"));
    }


    //destroy 
    public function destroy($id){
        $productFeedback = ProductFeedBack::find($id);
        $productFeedback->delete();
        return redirect()->back()->with('success','Product Feedback Deleted Successfully');
    }

    //updateStatus
    public function updateStatus($id){
        $productFeedback = ProductFeedBack::find($id);
        if($productFeedback->status == 1){
            $productFeedback->status = 0;
        }else{
            $productFeedback->status = 1;
        }
        $productFeedback->save();
        //return redirect()->back()->with('success','Product Feedback Status Updated Successfully');
    }
}
