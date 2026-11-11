@extends('layouts.admin')

@section('title', 'Landing Pages')
@section('breadcrumb', 'Landing Pages')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-purple">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <div>
            <div class="stat-card-sub">Total Landing Pages</div>
            <div class="stat-card-val">{{ \App\Models\LandingPage::count() }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">Active Campaigns</div>
            <div class="stat-card-val">{{ \App\Models\LandingPage::where('is_active', true)->count() }}</div>
        </div>
    </div>
</div>

<div class="filter-card-bar">
    <div style="font-weight: 700; font-size: 13px; color: #111827;">
        <i class="fa-solid fa-wand-magic-sparkles" style="color: var(--admin-primary); margin-right: 6px;"></i> ক্যাম্পেইন ও ল্যান্ডিং পেজ তালিকা
    </div>
    <a href="{{ route('admin.landing.create') }}" class="btn-orange-add">
        <i class="fa-solid fa-plus"></i> নতুন ল্যান্ডিং পেজ
    </a>
</div>

<!-- Compact Landing Pages Table (Exact Sohoj Excel-like Layout) -->
<div class="table-card-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 45px; text-align: center;">#</th>
                <th>পেজের শিরোনাম ও লিঙ্ক</th>
                <th style="text-align: center;">সংযুক্ত প্রোডাক্ট</th>
                <th>স্ট্যাটাস</th>
                <th>তৈরির তারিখ</th>
                <th style="text-align: right; width: 100px;">অ্যাকশন</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $index => $page)
                <tr>
                    <td style="text-align: center; color: #9CA3AF; font-weight: 600;">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: 700; color: #111827; margin-bottom: 2px;">{{ $page->title }}</div>
                        <a href="{{ route('landing.show', $page->slug) }}" target="_blank" class="invoice-orange" style="font-size: 11px;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> /landing/{{ $page->slug }}
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge-status badge-info">
                            {{ $page->products_count }} Products
                        </span>
                    </td>
                    <td>
                        <span class="badge-status {{ $page->is_active ? 'badge-success' : 'badge-neutral' }}">
                            {{ $page->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td style="color: #64748B; font-size: 12px;">{{ $page->created_at->format('d M, Y') }}</td>
                    <td style="text-align: right;">
                        <div class="action-btns-wrap">
                            <a href="{{ route('landing.show', $page->slug) }}" target="_blank" class="btn-tbl-action btn-tbl-view" title="Live Preview">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.landing.edit', $page->id) }}" class="btn-tbl-action btn-tbl-edit" title="Edit Campaign">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.landing.destroy', $page->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Delete this landing page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-tbl-action btn-tbl-delete" title="Delete Page">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #9CA3AF; padding: 36px;">
                        কোন ল্যান্ডিং পেজ তৈরি করা হয়নি।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($pages->hasPages())
        <div style="padding: 12px 18px; border-top: 1px solid var(--admin-border); background: #FFFFFF;">
            {{ $pages->links() }}
        </div>
    @endif
</div>
@endsection
