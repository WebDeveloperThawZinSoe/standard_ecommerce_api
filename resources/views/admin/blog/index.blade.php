@extends('layouts.admin')

@section('body')
<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-primary alert-dismissible fade show">
            <strong>Success!</strong>  {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> Please check the form below for errors.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <button class="btn btn-primary btn-tone m-r-5" data-toggle="modal" data-target="#createModal">
            <i class="anticon anticon-plus"></i> Create Blog
        </button>

        <div class="m-t-25">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                      
                        <th>Thumbnail</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $key=>$blog)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $blog->title }}</td>
                       
                        <td>
                            @if($blog->thumbnail)
                                <img src="{{ asset($blog->thumbnail) }}" width="50" alt="{{ $blog->title }}">
                            @endif
                        </td>
                        <td>
                            @if($blog->is_published)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-danger">Draft</span>
                            @endif
                        </td>
                        <td>{{ $blog->created_at->format('F j, Y, g:i a') }}</td>
                        <td>
                            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-warning">
                                <i class="anticon anticon-edit"></i>
                            </a>
                            <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure to delete this blog?')" class="btn btn-danger">
                                    <i class="anticon anticon-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Create Blog Modal -->
        <div class="modal fade" id="createModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Blog</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="anticon anticon-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title <span style="color:gold"> * </span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group" style="display:none !important;">
                                <label for="author">Author</label>
                                <input type="text" class="form-control" id="author" value="{{Auth::user()->id}}" name="author" placeholder="Author">
                            </div>
                            <div class="form-group">
                                <label for="thumbnail">Thumbnail</label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content" placeholder="Content">{{ old('content') }}</textarea>
                            </div>
                            <div class="form-group">
    <label for="images">Images</label>
    <input type="file" class="form-control" id="images" name="images[]" multiple>
</div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1">
                                <label class="form-check-label" for="is_published">
                                    Publish
                                </label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
