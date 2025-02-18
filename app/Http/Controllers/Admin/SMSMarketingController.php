<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class SMSMarketingController extends Controller
{
    //index 
    public function index(){
        return "SMS Marketing";
    }

    //phone_lists
    public function phone_lists(){
        $phoneLists = [];

        $phoneLists["register"] = User::whereNotNull("phone")
            ->select("phone") // Select only the phone column
            ->orderBy("id", "desc")
            ->distinct() // Ensure unique phone numbers
            ->get();
        
        $phoneLists["orders"] = Order::whereNotNull("phone")
            ->select("phone") // Select only the phone column
            ->orderBy("id", "desc")
            ->distinct() // Ensure unique phone numbers
            ->get();
        
        // Debugging (Uncomment only for testing)
        // dd($phoneLists);
        
        return view("admin.sms_marketing.phoneList", compact("phoneLists"));
        
    }
}
