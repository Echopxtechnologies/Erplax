@extends('ecommerce::public.layout')

@section('title', ucfirst($slug) . ' - ' . ($settings->site_name ?? 'Website'))
@section('description', $settings->meta_description ?? '')

@section('styles')
<style>
    .page-content-wrapper {
        max-width: 100%;
        margin: 0 auto;
    }
    
    .page-content {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text-dark);
    }
    
    .page-content h1, .page-content h2, .page-content h3 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: var(--text-dark);
    }
    
    .page-content p {
        margin-bottom: 20px;
    }
    
    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .page-content ul, .page-content ol {
        margin-bottom: 20px;
        padding-left: 30px;
    }
    
    .page-content li {
        margin-bottom: 8px;
    }
    
    .page-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 20px;
        margin: 20px 0;
        font-style: italic;
        color: var(--text-light);
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 28px;
        }
    }
</style>
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        {!! $content !!}
    </div>
</div>
@endsection
