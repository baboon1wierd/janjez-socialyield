@extends('layouts.app')

@section('title', 'Blog Post - Janjez-Socio')

@section('content')
<div style="max-width:800px;margin:0 auto;background:white;border-radius:28px;padding:40px;box-shadow:0 10px 30px -12px rgba(0,0,0,0.04);border:1px solid #efebea;">
    <h1 style="font-size:32px;font-weight:800;margin-bottom:16px;">{{ ucwords(str_replace('-', ' ', $slug)) }}</h1>
    <p style="color:#4a4a4a;font-size:16px;line-height:1.8;">This is a placeholder for the blog post. In production, this would fetch the full article content from the database or CMS.</p>
</div>
@endsection
