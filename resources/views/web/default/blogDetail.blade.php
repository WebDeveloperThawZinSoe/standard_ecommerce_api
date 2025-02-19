@extends('web.default.master')
@section('body')
<style>
/* General styles for images */
.gift-media img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
    margin: 20px auto;
    border-radius: 10px; /* Add rounded corners for a more polished look */
}

/* Blog post description section */
.description {
    width: 70%;
    margin: 40px auto;
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444; /* Subtle text color */
    font-family: 'Roboto', sans-serif; /* Modern and clean font */
    text-align: justify; /* Justified text for better readability */
}

.description h2 {
    font-size: 1.5rem;
    margin-top: 20px;
    font-weight: bold;
    color: #333;
}

/* Adjust styles for smaller screens */
@media (max-width: 768px) {
    .description {
        width: 90%; /* Slightly more width on smaller screens */
    }

    .gift-media img {
        height: 200px; /* Adjust image height for smaller screens */
    }

    h1.title {
        font-size: 1.8rem; /* Reduce title font size on small screens */
    }
}

/* Title and image section styles */
.center-content {
    text-align: center;
    margin-bottom: 40px;
}

.center-content img {
    width: 80%; /* Slightly reduce image size */
    max-width: 300px;
    border-radius: 10px; /* Rounded corners for images */
}

.center-content h1 {
    font-size: 2.5rem;
    margin-top: 20px;
    color: #333;
    font-family: 'Open Sans', sans-serif; /* Clear and modern title font */
}

/* Image Gallery Styling */
.image-gallery .row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Center the images in the row */
}

.image-item {
    width: 30%; /* Set width to 30% for 3 images per row */
    box-sizing: border-box;
    margin-bottom: 20px;
    position: relative;
}

.image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px; /* Add rounded corners for images */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add subtle shadow for gallery effect */
}

/* Responsive adjustments for smaller screens */
@media (max-width: 768px) {
    .image-item {
        width: 48%; /* Adjust to 2 images per row on tablets */
    }
}

@media (max-width: 480px) {
    .image-item {
        width: 100%; /* Full width on mobile */
    }
}
</style>

<div class="page-content">
    <section class="content-inner main-faq-content">
        <div class="container">
            <div class="row faq-head">
                <section class="content-inner">
                    <div class="container">
                        <div class="row">
                            <div class="center-content">
                                <img src="{{ asset($blog->thumbnail) }}" class="img-center" loading="lazy" alt="">
                                <h1 class="title wow fadeInUp" data-wow-delay="0.1s">{{ $blog->title }}</h1>
                            </div>
                            <p class="description wow fadeInUp" data-wow-delay="0.2s">
                                {!! $blog->content !!}
                            </p>
                            <div class="image-gallery">
                                <div class="row">
                                    @if($blog->images)
                                        @foreach(json_decode($blog->images, true) as $image)
                                            <div class="image-item">
                                                <a href="{{ asset($image) }}" target="_blank"><img src="{{ asset($image) }}" alt="Blog Image" class="img-fluid rounded mb-4"></a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

@endsection
