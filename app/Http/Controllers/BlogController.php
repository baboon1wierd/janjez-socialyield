<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = [];
        
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = [
            'title' => 'Blog Post Title',
            'content' => 'Blog content here...',
            'published_at' => now(),
            'author' => 'Janjez-Socio Team'
        ];
        
        return view('blog.show', compact('post', 'slug'));
    }
}