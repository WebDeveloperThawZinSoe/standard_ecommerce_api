@php
$logo = App\Models\GeneralSetting::where("name","logo")->first();
$generalSettings = App\Models\GeneralSetting::whereIn('name', [
'about_us', 'how_to_sell_us', 'phone_number_1', 'phone_number_2', 'phone_number_3',
'email_1', 'email_2', 'email_3', 'facebook', 'telegram', 'discord' , 'viber' , 'skype','address'
])->pluck('value', 'name');

@endphp
<footer class="site-footer footer-dark style-1">
    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="widget widget_about me-2">
                        <div class="footer-logo logo-white">

                        </div>
                        <ul class="widget-address">
                            @if(!empty($generalSettings->get('address')))
                            <li>
                                <p><span>Address</span>: {{ $generalSettings->get('address') }}</p>
                            </li>
                            @endif

                            @if(!empty($generalSettings->get('email_1')))
                            <li>
                                <p><span>E-mail</span>: {{ $generalSettings->get('email_1') }}</p>
                            </li>
                            @endif

                            @if(!empty($generalSettings->get('phone_number_1')))
                            <li>
                                <p><span>Phone</span>: {{ $generalSettings->get('phone_number_1') }}</p>
                            </li>
                            @endif


                        </ul>
                        <div class="dz-social-icon">
                            <ul>
                                @php
                                $socials = App\Models\SocialAccount::get();
                                @endphp
                                <div class="dz-social-icon" bis_skin_checked="1">
                                    <ul>
                                    @if($socials->isNotEmpty())
                                    @foreach($socials as $social)
                                        <li><a href="{{ $social->social_link }}" target="_blank">
                                        <img src="{{asset($social->icon)}}" alt="" style="width:30px;height:30px;">
                                    </a></li>
                                        
                                                @endforeach
                                @endif
                                    </ul>
                                </div>
                               
                            
                                

                             
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="widget widget_about me-2">
                        <h5 class="footer-title">Our Teach</h5>
                        <ul class="widget-address">

                            @php
                            $blogs = App\Models\Blog::where("is_published",1)->take(5)->get();
                            @endphp

                            @if($blogs->isNotEmpty())
                            <ul>
                                @foreach($blogs as $blog)
                                <li>
                                    <p>

                                        <a href="/our-teach/{{ $blog->id }}" target="_blank">{{ $blog->title }}</a>
                                        <hr>
                                    </p>
                                </li>
                                @endforeach
                            </ul>
                            @endif

                        </ul>

                    </div>
                </div>
                <div class="col-xl-3 col-md-3 col-sm-4 col-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="widget widget_services">
                        <h5 class="footer-title">Categories</h5>
                        <ul>
                            @php
                            $categories = App\Models\ProductCategory::orderBy("id","asc")->limit(6)->get();
                            @endphp
                            @foreach($categories as $category)
                            <li><a href="/products/category/{{$category->id}}">{{$category->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-md-3 col-sm-4 col-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="widget widget_services">
                        <h5 class="footer-title">Pages</h5>
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="/products">Products</a></li>
                            <li><a href="/brand">Brand</a></li>
                            <li><a href="/about-us">About Us</a></li>
                            <li><a href="/faq">FAQ</a></li>
                            <li><a href="/contact-us">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <!--<div class="col-xl-2 col-md-3 col-sm-4 col-4 wow fadeInUp" data-wow-delay="0.3s">-->
                <!--	<div class="widget widget_services">-->
                <!--		<h5 class="footer-title">Pages</h5>-->
                <!--		<ul>-->
                <!--			<li><a href="/">Home</a></li>-->
                <!--			<li><a href="/products">Products</a></li>-->
                <!--			<li><a href="/brand">Brand</a></li>-->
                <!--			<li><a href="/about-us">About Us</a></li>-->
                <!--			<li><a href="/faq">FAQ</a></li>-->
                <!--			<li><a href="/contact-us">Contact Us</a></li>-->
                <!--		</ul>   -->
                <!--	</div>-->
                <!--</div>-->
                <!--<div class="col-xl-4 col-md-3 col-sm-4 col-12 wow fadeInUp" data-wow-delay="0.4s">-->
                <!--	<div class="widget widget_services">-->
                <!--	<h5 class="footer-title"></h5>-->
                <!--		<p style="color:white">-->

                <!--		</p>-->
                <!--	</div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <!-- Footer Top End -->

    <!-- Footer Bottom -->
    <div class="footer-bottom" style="background-color: #000;">
        <div class="container">
            <div class="row fb-inner wow fadeInUp" data-wow-delay="0.1s">
                <div class="col-lg-6 col-md-12 text-start">
                    <p class="copyright-text" style="color: #fff;">© {{ env('APP_NAME') }} {{ now()->year }} - All
                        rights reserved.</p>
                </div>
                <div class="col-lg-6 col-md-12 text-end">
                    <div
                        class="d-flex align-items-center justify-content-center justify-content-md-center justify-content-xl-end">
                        <span class="me-3" style="color: #fff;">We Accept: </span>
                        <img src="{{asset('web/images/footer-img.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Bottom End -->

</footer>