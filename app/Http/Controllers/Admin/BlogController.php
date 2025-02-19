<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:204800',
            'content' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:204800',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();

        // Store thumbnail
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $thumbnailName = 'thumb_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('blog'), $thumbnailName);
            $data['thumbnail'] = 'blog/' . $thumbnailName;
        }

        // Store multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('blog'), $imageName);
                $imagePaths[] = 'blog/' . $imageName;
            }
        }
        $data['images'] = json_encode($imagePaths);

        Blog::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        // dd($request);
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_published' => 'boolean',
        ]);
    
        $data = $request->except(['thumbnail', 'images']);
    
        // Update thumbnail if a new one is uploaded
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($blog->thumbnail && File::exists(public_path($blog->thumbnail))) {
                File::delete(public_path($blog->thumbnail));
            }
    
            $file = $request->file('thumbnail');
            $thumbnailName = 'thumb_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('blog'), $thumbnailName);
            $data['thumbnail'] = 'blog/' . $thumbnailName;
        } else {
            // Keep the old thumbnail
            $data['thumbnail'] = $blog->thumbnail;
        }
    
        // Update images if new ones are uploaded
        if ($request->hasFile('images')) {
            // Delete old images if they exist
            if ($blog->images) {
                $oldImages = json_decode($blog->images, true);
                foreach ($oldImages as $oldImage) {
                    if (File::exists(public_path($oldImage))) {
                        File::delete(public_path($oldImage));
                    }
                }
            }
    
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('blog'), $imageName);
                $imagePaths[] = 'blog/' . $imageName;
            }
            $data['images'] = json_encode($imagePaths);
        } else {
            // Keep the old images
            $data['images'] = $blog->images;
        }
    
        $blog->update($data);
    
        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }
    

    public function destroy(Blog $blog)
    {
        // Delete thumbnail
        if ($blog->thumbnail && File::exists(public_path($blog->thumbnail))) {
            File::delete(public_path($blog->thumbnail));
        }

        // Delete images
        if ($blog->images) {
            $images = json_decode($blog->images, true);
            foreach ($images as $image) {
                if (File::exists(public_path($image))) {
                    File::delete(public_path($image));
                }
            }
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
