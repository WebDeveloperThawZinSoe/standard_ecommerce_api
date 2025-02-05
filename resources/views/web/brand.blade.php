@extends('web.master')
@section('body')
<style>
.gift-media img {
    width: 100%;
    height: 150px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
}

.description {
    width: 70%;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .description {
        width: 100%; /* Full width on smaller screens */
    }
}
</style>
<div class="page-content">
    <section class="content-inner main-faq-content" style="background-image:url({{asset('web/images/background/bg-shape.jpg')}});">
        <div class="container">
            <div class="row faq-head">
                <div class="col-12 text-center">
                    <h1 class="title wow fadeInUp" data-wow-delay="0.1s">Our Brands</h1>
                    <p class="description wow fadeInUp" data-wow-delay="0.2s">
                        Discover our trusted brands offering a range of high-quality protein supplements to support your
                        health and fitness goals. Whether you're looking to build muscle, recover faster, or simply
                        maintain a balanced diet, our brands provide premium products to meet your needs.
                    </p>
                </div>
                <section class="content-inner">
                    <div class="container">
                        <div class="row">
                            @foreach($brands as $brand)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay="0.1s">
                                <a href="/brands/{{$brand->id}}">
                                    <div class="gift-bx">
                                        <div class="gift-media">
                                            <img src="{{ asset('images/brands/' . $brand->icon) }}" loading="lazy" alt="">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
@endsection
