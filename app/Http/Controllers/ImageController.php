<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
            
        return view('images.index', compact('images'));
    }

    public function create()
    {
        return view('images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
            'tags' => 'nullable|array'
        ]);

        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('images', $filename, 'public');

        Image::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_url' => Storage::url($path),
            'tags' => $request->tags,
            'status' => 'draft'
        ]);

        return redirect()->route('images.index')
            ->with('success', 'Image uploaded successfully!');
    }

    public function show(Image $image)
    {
        return view('images.show', compact('image'));
    }

    public function edit(Image $image)
    {
        return view('images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array'
        ]);

        $image->update([
            'title' => $request->title,
            'description' => $request->description,
            'tags' => $request->tags
        ]);

        return redirect()->route('images.show', $image)
            ->with('success', 'Image updated successfully!');
    }

    public function destroy(Image $image)
    {
        Storage::delete($image->image_url);
        $image->delete();

        return redirect()->route('images.index')
            ->with('success', 'Image deleted successfully!');
    }

    public function aiGenerate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
            'style' => 'nullable|string',
            'size' => 'nullable|string'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'AI image generation started'
        ]);
    }
}