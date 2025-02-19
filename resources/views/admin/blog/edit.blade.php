@extends('layouts.admin')

@section('body')
<div class="container">
    <h1>Edit Blog</h1>

    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ $blog->title }}" required>
        </div>

        <div class="mb-3"  style="display:none !important;">
            <label for="author" class="form-label">Author</label>
            <input type="text" name="author" class="form-control" id="author"value="{{Auth::user()->id}}">
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control" id="thumbnail">
            @if($blog->thumbnail)
            <img src="{{ asset($blog->thumbnail) }}" width="50" alt="{{ $blog->title }}">
            @endif
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" class="form-control" id="content">{{ $blog->content }}</textarea>
        </div>
        <div class="mb-3">
    <label for="images" class="form-label">Images</label>
    <input type="file" name="images[]" class="form-control" id="images" multiple>
    @if($blog->images)
        @foreach(json_decode($blog->images, true) as $image)
            <img src="{{ asset($image) }}" width="100" alt="Blog Image">
        @endforeach
    @endif
</div>


    <br>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ $blog->is_published ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">
                Publish
            </label>
        </div>

        <br> 

        <button type="submit" class="btn btn-primary">Update Blog</button>
    </form>
</div>
@endsection
