@extends('layouts.app')

@section('title', $page->title . ' | ' . \App\Models\Setting::get('site_name', 'Meghduti Paridhaan'))

@push('styles')
<style>
    .page-hero-banner {
        background: #F8F9FA;
        border-bottom: 1px solid #E5E7EB;
        padding: 40px 0 30px;
        text-align: center;
        margin-bottom: 30px;
    }
    .page-breadcrumb {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 8px;
    }
    .page-breadcrumb a {
        color: #111827;
        text-decoration: none;
        font-weight: 600;
    }
    .page-breadcrumb a:hover {
        color: #B5876D;
    }
    .page-main-title {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .page-content-wrapper {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 35px 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        margin-bottom: 60px;
        line-height: 1.8;
        font-size: 15px;
        color: #374151;
    }
    .page-content-wrapper h2, 
    .page-content-wrapper h3, 
    .page-content-wrapper h4 {
        color: #111827;
        font-weight: 800;
        margin-top: 24px;
        margin-bottom: 12px;
        letter-spacing: -0.3px;
    }
    .page-content-wrapper p {
        margin-bottom: 16px;
    }
    .page-content-wrapper ul, 
    .page-content-wrapper ol {
        margin-bottom: 16px;
        padding-left: 24px;
    }
    .page-content-wrapper li {
        margin-bottom: 8px;
    }
    .page-content-wrapper a {
        color: #B5876D;
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .page-content-wrapper {
            padding: 24px 20px;
        }
        .page-main-title {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-hero-banner">
    <div class="container">
        <div class="page-breadcrumb">
            <a href="{{ route('home') }}">Home</a> &nbsp;/&nbsp; <span>{{ $page->title }}</span>
        </div>
        <h1 class="page-main-title">{{ $page->title }}</h1>
    </div>
</div>

<!-- Page Content Container -->
<div class="container">
    <div class="page-content-wrapper">
        {!! $page->description !!}
    </div>
</div>
@endsection
