<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Gallery;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\FAQ;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Validation\ValidationException;


class HomeDataController extends Controller
{

    public function index()
    {
        $galleries = Gallery::orderBy("sort", "desc")->get();
        $gallery = [];
    
        // Get the full banner image URLs
        foreach ($galleries as $value) {
            $gallery[] = env("APP_URL") . "/" .$value->image;
        }
    
        // Get all general settings
        $settings = GeneralSetting::all();
        $general_settings = [];
    
        foreach ($settings as $setting) {
            switch ($setting->name) {
                case 'banner_image':
                    $general_settings[$setting->name] = env("APP_URL") . "/images/general_settings/" . $setting->value;
                    break;
                case 'about_image':
                    $general_settings[$setting->name] = env("APP_URL") . "/images/general_settings/" . $setting->value;
                    break;
                case 'contact_image':
                    $general_settings[$setting->name] = env("APP_URL") . "/images/general_settings/" . $setting->value;
                    break;
                case 'logo':
                    $general_settings[$setting->name] = env("APP_URL") . "/images/general_settings/" . $setting->value;
                    break;
                default:
                    $general_settings[$setting->name] = $setting->value; // Keep other values unchanged
                    break;
            }
        }
    
        return response()->json([
            'message' => 'Home data fetched successfully!',
            'home_page_banners' => $gallery,
            'general_settings' => $general_settings
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function getFaqs(){
        $fas = FAQ::get(["question","answer"]);
        return response()->json([
            'message' => 'Fas data fetched successfully!',
            'data' => $fas
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function getPrivacyPolicy(){
        $fas = FAQ::where("question","privacy policy")->get(["question","answer"]);
        return response()->json([
            'message' => 'Privacy Policy data fetched successfully!',
            'data' => $fas
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function getBrands(){
        $brands = Brand::get(["name","icon"]);
        foreach($brands as $brand){
            if($brand->icon != null){
                $brand->icon = env("APP_URL") . "/images/brands/" . $brand->icon;
            }
        }   
        return response()->json([
            'message' => 'Brands data fetched successfully!',
            'data' => $brands
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    // public function getBrandProducts($id){

    // }
    
    public function getCategories(){
        $categories = ProductCategory::get(["name","icon"]);
        foreach($categories as $category){
            if($category->icon != null){
                $category->icon = env("APP_URL") . "/images/categories/" . $category->icon;
            }
        }   
        return response()->json([
            'message' => 'Categories data fetched successfully!',
            'data' => $categories
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }



    
    
    public function getLatestProducts()
    {
        $products = Product::where("status",1)->orderBy("created_at", "desc")
            ->take(10)
            ->get([
                'name', 'price', 'stock', 'min_stock', 'image',
                'category_id', 'sub_category_id', 'brand_id',
                'status', 'short_description', 'description',
                'product_type', 'pre_order', 'discount_type',
                'discount_amount', 'images'
            ]);
    
        $data = [];
    
        foreach ($products as $product) {
            $item = $product->toArray();
    
            // Append the full image URL
            if (!empty($product->image)) {
                $item['image'] = env("APP_URL") .  $product->image;
            }
    
            // Handle multiple product images
            $decodedImages = [];
            if (is_string($product->images)) {
                $decodedImages = json_decode($product->images, true) ?? [];
            } elseif (is_array($product->images)) {
                $decodedImages = $product->images;
            }
    
            $item['images'] = array_map(
                fn($image) => env("APP_URL") .  $image,
                $decodedImages
            );
    
            // Append category and brand names if available
            $item['category_name'] = optional(ProductCategory::find($product->category_id))->name;
            $item['brand_name'] = optional(Brand::find($product->brand_id))->name;
    
            $data[] = $item;
        }
    
        return response()->json([
            'message' => 'Latest products data fetched successfully!',
            'data' => $data
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
    
    public function getProduct($id)
    {
        $product = Product::where("id", $id)
            ->first(['name', 'price', 'stock', 'min_stock', 'image',
                    'category_id', 'sub_category_id', 'brand_id',
                    'status', 'short_description', 'description',
                    'product_type', 'pre_order', 'discount_type',
                    'discount_amount', 'images']);
    
        // Convert the product to an array for easier manipulation
        $productArray = $product->toArray();
    
        // Prepend the base URL to the single 'image' field
        $productArray["image"] = env("APP_URL") . "/" . $productArray["image"];
    
        // Initialize the 'product_images' array
        $productArray["product_images"] = [];
    
        // Prepend the base URL to each image and append to the 'product_images' array
        foreach ($productArray["images"] as $image) {
            $productArray["product_images"][] = env("APP_URL") . "/" . $image;
        }
    
        return response()->json([
            'message' => 'Product data fetched successfully!',
            'product' => $productArray,
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
    
    

    public function trackOrder(Request $request)
    {
        $order_code = $request->order_code;
        if ($order_code == null) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => 'Order code is required!',
            ], 422);
        }
    
        $order = Order::where('order_number', $order_code)
            ->with('user', 'paymentMethod', 'orderDetails.productVaraints.product')
            ->first();
    
        if (!$order) {
            return response()->json([
                'error' => 'Error',
                'message' => 'Order Not Found',
            ], 422);
        }
    
        // Modify the order details to add full image URLs
        $baseImageUrl = env("APP_URL") . "/" ;
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->productVaraints) {
                $orderDetail->productVaraints->image = $baseImageUrl . $orderDetail->productVaraints->image;
                if ($orderDetail->productVaraints->product) {
                    $orderDetail->productVaraints->product->image = $baseImageUrl . $orderDetail->productVaraints->product->image;
                }
            }
        }
    
        return response()->json([
            'message' => 'Order data fetched successfully!',
            'order' => $order
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
    
    

}
