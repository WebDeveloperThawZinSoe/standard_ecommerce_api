<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
use App\Events\NewUserRegisterEvent;
use App\Models\User;
use App\Models\Theme;

class PageController extends Controller
{


    public function auth() {
        if (!Auth::check()) {
            return redirect('/login'); 
        }
    
        $role_id = Auth::user()->role;   
        if ($role_id == 1 ) {
            return redirect('/admin/dashboard');
        }elseif ($role_id == 3){
            return redirect('/admin/dashboard');
        
        } elseif ($role_id == 2) {
            return redirect('/dashboard');
        } else {
            return redirect('login');
        }
    }

    public function getTheme()
    {
        $theme = Theme::where("active", 1)->first("name");
        return $theme ? $theme->name : "default"; // Provide a default theme in case no active theme is found
    }
    
    //index
    public function index(){
        $theme_name = $this->getTheme();

        $Latest_products = Product::with(['category', 'subCategory', 'variants'])
        ->where("stock","!=",0)->where("status",1)->orderBy("id", "desc")
        ->limit(8)->get();
        $brands =   Brand::inRandomOrder()->limit(6)->get();
        return view("web.$theme_name.index",compact("Latest_products","brands"));
    }

    //category
    public function category($id){
        $theme_name = $this->getTheme();
        $data = [
            "ProductCategories" => ProductCategory::get(),
            "Category" =>ProductCategory::where("id",$id)->first(),
            "SubCategory" => SubCategory::where("category_id",$id)->get(),
            "Product" => Product::where("category_id",$id)->paginate(50),
            "category_id" => $id , 
            "sub_category_id" => null,
        ];
         
        return view("web.$theme_name.products")->with($data);
    }

    //subcategory
    public function subcategory($id){
        $theme_name = $this->getTheme();
        $SubCategory = SubCategory::where("id",$id)->first();
        $data = [
            "ProductCategories" => ProductCategory::get(),
            "SubCategory" => SubCategory::where("category_id",$SubCategory->category_id	)->get(),
            "Product" => Product::where("sub_category_id",$id)->paginate(50),
            "sub_category_id" => $id,
            "category_id" => $SubCategory->category_id	
        ];
         
        return view("web.$theme_name.products")->with($data);
    }

    //faq 
    public function faq(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.faq");
    }

    //member
    public function member(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.member");
    }

    //sellToUs
    public function sellToUs(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.sellToUs");
    }

    //contactUs
    public function contactUs(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.contactUs");
    }

    //checkout
    public function checkout(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.checkout");
    }

    //customerFeedback
    public function customerFeedback(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.customerFeedback");
    }

    public function customerFeedbackStore(Request $request) {
        $theme_name = $this->getTheme();
        // Validate input fields
        $request->validate([
            'title' => 'required|string|max:255',
            'about' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle the file upload if it exists
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/feedback'), $imageName);
        } else {
            $imageName = null; // In case no image is uploaded
        }
    
        // Create new feedback record
        CustomerFeedback::create([
            'user_id' => auth()->id(), // Assuming user authentication
            'title' => $request->title,
            'about' => $request->about,
            'image' => $imageName,
        ]);
    
        // Redirect or return response after storing
        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }

    public function privacyPolicy(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.privacy_policy");
    }

    //products
    public function products()
    {
        $theme_name = $this->getTheme();
        $data = [
            "ProductCategories" => ProductCategory::inRandomOrder()->limit(10)->get(),
            "brands" => Brand::inRandomOrder()->limit(10)->get(),
            "products" => Product::with(['category', 'subCategory', 'variants'])->where("stock","!=",0)->where("status",1)
                ->orderBy("name", "asc")
                ->paginate(18) // Paginate with 18 items per page
        ];        
        return view("web.$theme_name.products")->with($data);
    }
    

    //products
    public function brands(){
        $theme_name = $this->getTheme();
        $brands = Brand::orderBy("id", "desc")->get();
        return view("web.$theme_name.brand",compact("brands"));
    }

    //brandDetail
    public function brandDetail($id){
        $theme_name = $this->getTheme();
        $data = [
            "ProductCategories" => ProductCategory::inRandomOrder()->limit(10)->get(),
            "brands" => Brand::inRandomOrder()->limit(10)->get(),
            "brands_detail" => Brand::find($id),
            "products" => Product::with(['category', 'subCategory', 'variants'])
                ->where("brand_id",$id)->where("stock","!=",0)->where("status",1)
                ->orderBy("id", "desc")
                ->paginate(18) // Paginate with 18 items per page
        ];        
        return view("web.$theme_name.brandDetail")->with($data);
    }

    //productsCategory
    public function productsCategory($id){
        $theme_name = $this->getTheme();
        $data = [
            "ProductCategories" => ProductCategory::inRandomOrder()->limit(10)->get(),
            "ProductCategory_detail" => ProductCategory::find($id),
            "brands" => Brand::inRandomOrder()->limit(10)->get(),
            "products" => Product::with(['category', 'subCategory', 'variants'])
                ->where("category_id",$id)->where("stock","!=",0)->where("status",1)
                ->orderBy("name", "asc")
                ->paginate(18) // Paginate with 18 items per page
        ];        
        return view("web.$theme_name.categoryDetail")->with($data);
    }

    public function productDetail($id)
    {
        $theme_name = $this->getTheme();
        $detail_product = Product::with(['category', 'subCategory', 'variants'])->findOrFail($id);
    
        // Fetch 4 random products from the same category
        $suggest_products = Product::where("category_id", $detail_product->category_id)
            ->where("id", "!=", $id)  // Exclude the current product
            ->inRandomOrder()
            ->limit(4)
            ->get();
    
        return view("web.$theme_name.productDetail", compact('detail_product', 'suggest_products'));
    }

    public function orderTrack(){
        $theme_name = $this->getTheme();
        return view("web.$theme_name.order_track");
    }
    
    public function searchOrder(Request $request)
    {
        $theme_name = $this->getTheme();
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $order = Order::where('order_number', $request->order_number)->with('user', 'paymentMethod', 'orderDetails.productVaraints.product')->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        return view("web.$theme_name.order_track", compact('order'));
    }

    public function search(Request $request)
    {
        $theme_name = $this->getTheme();
        $query = $request->input('query');
    
       
    
        // Fetch products matching the query in name, price, short_description, or description
        $products = Product::with(['category', 'subCategory', 'variants'])
            ->where("stock", "!=", 0)
            ->where("status", 1)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('price', 'LIKE', "%{$query}%")
                    ->orWhere('short_description', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->orderBy("name", "asc")
            ->paginate(18); // Use pagination with 18 items per page
    
        $data = [
            "ProductCategories" => ProductCategory::inRandomOrder()->limit(10)->get(),
            "brands" => Brand::inRandomOrder()->limit(10)->get(),
            "products" => $products,
            "query" => $query,
        ];
        return view("web.$theme_name.search_products")->with($data);
        // return view('web.search_products', compact('products', 'query', 'data'));
    }


    //goalsPage
    public function goalsPage($name){
        $theme_name = $this->getTheme();
       // Retrieve all goals with the given name
        $goals = Goal::where("name", $name)->get();

        // Extract all unique product_category_id values from the goals
        $productCategoryIds = $goals->pluck('product_category_id')->unique();

        $data = [
            "ProductCategories" => ProductCategory::inRandomOrder()->limit(10)->get(),
            "brands" => Brand::inRandomOrder()->limit(10)->get(),
            "products" => Product::with(['category', 'subCategory', 'variants'])
                ->whereIn("category_id", $productCategoryIds) // Filter by category_ids
                ->where("stock", "!=", 0)
                ->where("status", 1)
                ->orderBy("name", "asc")
                ->paginate(18),
            "goal_name" => $name
        ];

        return view("web.$theme_name.goals")->with($data);
    }
    
    //preOrder
    public function preOrder(){
        $theme_name = $this->getTheme();
        $data = [
            "ProductCategories" => ProductCategory::inRandomOrder()->limit(10)->get(),
            "brands" => Brand::inRandomOrder()->limit(10)->get(),
            "products" => Product::with(['category', 'subCategory', 'variants'])->where("status",1)->where("pre_order",1)
                ->orderBy("name", "asc")
                ->paginate(18) // Paginate with 18 items per page
        ];        
        return view("web.$theme_name.products_pre_order")->with($data);
    }


    // submitReview
    public function submitReview(Request $request)
    {
        $theme_name = $this->getTheme();
        // Validate request data
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
        ]);

        // Fetch general settings
        $defaultPendingStatus = GeneralSetting::where("name", "customer_feedback_system_default_pending")->value("value");
        $status = ($defaultPendingStatus == "on") ? 1 : 0;
        

        $customerFeedbackSystemOrder = GeneralSetting::where("name", "customer_feedback_system_order")->value("value");
        // dd($customerFeedbackSystemOrder);

        // Check if customer must purchase before review
        if ($customerFeedbackSystemOrder == "on" ) {
            if(!Auth::check()){
                return redirect()->back()->with('error', 'You need to login first and need to buy this product first!');
            }
            $orders = Order::where("user_id", Auth::id())->where("status", "2")->get();

            // $purchasedProductIds = $orders->flatMap(fn($order) => $order->orderDetails->pluck('product_variant_id'))->unique();

            if ($customerFeedbackSystemOrder == "on" && Auth::check()) {
                $orders = Order::where("user_id", Auth::id())->where("status", "2")->get();
            
                // $purchasedProductIds = $orders->flatMap(fn($order) => $order->orderDetails->pluck('product_variant_id'))->unique();
                // Get the list of product IDs through product variants in the order details
                $purchasedProductIds = $orders->flatMap(function ($order) {
                    return $order->orderDetails->map(function ($orderDetail) {
                        return $orderDetail->productVaraints->product_id ?? null; // Safeguard against null values
                    });
                })->filter()->unique(); // Remove null values and ensure unique product IDs

            
                if (!$purchasedProductIds->contains($request->product_id)) {
                    return redirect()->back()->with('error', 'You need to buy this product first!');
                }
            }

            

            if (!$purchasedProductIds->contains($request->product_id)) {
                return redirect()->back()->with('error', 'You need to buy this product first!');
            }
        }
        // Initialize feedback instance
        $feedback = new ProductFeedBack([
            'product_id' => $request->product_id,
            'review_star' => $request->rating,
            'message' => $request->message,
            'status' => $status,
        ]);

        if (Auth::check()) {
            $feedback->user_id = Auth::id();
        } else {
            // Capture user information for guests
            $userInfo = [
                'IP' => $request->ip(),
                'User-Agent' => $request->header('User-Agent'),
                'Accept-Language' => $request->header('Accept-Language'),
            ];
            $feedback->user_information_data = json_encode($userInfo);
        }

        // Save feedback and return response
        $feedback->save();

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }


    public function pusherTest()
    {
        return view('web.pusherTest');
    }

    public function pushterTestPost()
    {
        $user = User::find(1); // or create/register a user instance
        if ($user) {
            NewUserRegisterEvent::dispatch($user);
        }
        return response()->json(['status' => 'Event dispatched']);
    }
        
}