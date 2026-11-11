@extends('layouts.admin')

@section('title', 'Visitor Analytics & Reports')
@section('breadcrumb', 'Visitor Analytics')

@section('content')
<!-- Top Stat Cards (Sohoj Theme) -->
<div class="stat-cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));">
    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #EFF6FF; color: #2563EB;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div class="stat-card-sub">সর্বমোট ইউনিক ভিজিটর</div>
            <div class="stat-card-val" style="color: #2563EB;">{{ number_format($totalVisitors) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #ECFDF5; color: #059669;">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <div class="stat-card-sub">আজকের ভিজিটর (Today)</div>
            <div class="stat-card-val" style="color: #059669;">{{ number_format($todayVisitors) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #FFFBEB; color: #D97706;">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <div>
            <div class="stat-card-sub">গতকালের ভিজিটর</div>
            <div class="stat-card-val" style="color: #D97706;">{{ number_format($yesterdayVisitors) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #F3E8FF; color: #7C3AED;">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div>
            <div class="stat-card-sub">চলতি মাসের ভিজিটর</div>
            <div class="stat-card-val" style="color: #7C3AED;">{{ number_format($thisMonthVisitors) }}</div>
        </div>
    </div>

    <div class="stat-card-compact">
        <div class="stat-icon-wrap" style="background: #FDF2F8; color: #DB2777;">
            <i class="fa-solid fa-eye"></i>
        </div>
        <div>
            <div class="stat-card-sub">মোট পেজ ভিউ</div>
            <div class="stat-card-val" style="color: #DB2777;">{{ number_format($totalPageViews) }}</div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="padding: 16px 20px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 13px; font-weight: 700; color: #374151;">কুইক ফিল্টার:</span>
            <a href="{{ route('admin.reports.visitors', ['days' => 7]) }}" class="btn-outline-pill {{ $days == 7 && !$startDate ? 'active' : '' }}" style="padding: 5px 14px; font-size: 12px; {{ $days == 7 && !$startDate ? 'background: var(--admin-primary); color: white; border-color: var(--admin-primary);' : '' }}">বিগত ৭ দিন</a>
            <a href="{{ route('admin.reports.visitors', ['days' => 15]) }}" class="btn-outline-pill {{ $days == 15 && !$startDate ? 'active' : '' }}" style="padding: 5px 14px; font-size: 12px; {{ $days == 15 && !$startDate ? 'background: var(--admin-primary); color: white; border-color: var(--admin-primary);' : '' }}">বিগত ১৫ দিন</a>
            <a href="{{ route('admin.reports.visitors', ['days' => 30]) }}" class="btn-outline-pill {{ $days == 30 && !$startDate ? 'active' : '' }}" style="padding: 5px 14px; font-size: 12px; {{ $days == 30 && !$startDate ? 'background: var(--admin-primary); color: white; border-color: var(--admin-primary);' : '' }}">বিগত ৩০ দিন</a>
        </div>

        <form action="{{ route('admin.reports.visitors') }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 12px; font-weight: 700; color: #64748B;">শুরু:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" style="width: auto; padding: 5px 10px; font-size: 12.5px;">
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <label style="font-size: 12px; font-weight: 700; color: #64748B;">শেষ:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" style="width: auto; padding: 5px 10px; font-size: 12.5px;">
            </div>
            <button type="submit" class="btn-orange-add" style="height: 34px; padding: 0 16px; font-size: 12.5px;">
                <i class="fa-solid fa-filter"></i> ফিল্টার করুন
            </button>
            @if($startDate || $endDate || $days != 30)
                <a href="{{ route('admin.reports.visitors') }}" class="btn-outline-pill" style="padding: 5px 12px; font-size: 12px;">রিসেট</a>
            @endif
        </form>
    </div>
</div>

<!-- Daily Breakdown Report Table -->
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 class="card-title" style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-calendar-check" style="color: var(--admin-primary);"></i> দিনভিত্তিক ভিজিটর রিপোর্ট (Daily Visitor Breakdown)
        </h3>
        <span style="font-size: 12px; color: #64748B; font-weight: 600;">
            মোট {{ $dailyStats->total() }} দিনের তথ্য
        </span>
    </div>

    @if($dailyStats->isEmpty())
        <div style="text-align: center; padding: 40px 10px; color: #94A3B8;">
            <i class="fa-solid fa-chart-simple" style="font-size: 40px; margin-bottom: 12px; opacity: 0.3;"></i>
            <p style="font-size: 14px;">কোন ভিজিটর ডাটা পাওয়া যায়নি।</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">তারিখ (Date)</th>
                        <th style="width: 20%; text-align: center;">ইউনিক ভিজিটর (Visitors)</th>
                        <th style="width: 20%; text-align: center;">মোট পেজ ভিউ (Page Views)</th>
                        <th style="width: 25%; text-align: center;">ডিভাইস (Mobile vs Desktop)</th>
                        <th style="width: 10%; text-align: right;">অ্যাভারেজ ভিউ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStats as $stat)
                        @php
                            $cDate = \Carbon\Carbon::parse($stat->visit_date);
                            $isToday = $cDate->isToday();
                            $isYesterday = $cDate->isYesterday();
                            $totalV = max(1, $stat->page_views);
                            $mobPercent = round(($stat->mobile_views / $totalV) * 100);
                            $deskPercent = 100 - $mobPercent;
                        @endphp
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 6px; background: {{ $isToday ? '#DCFCE7' : '#F1F5F9' }}; color: {{ $isToday ? '#15803D' : '#475569' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                                        {{ $cDate->format('d') }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0F172A; font-size: 13.5px;">
                                            {{ $cDate->format('d M, Y') }}
                                            @if($isToday)
                                                <span style="background: #DCFCE7; color: #15803D; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-left: 4px; font-weight: 800;">আজ</span>
                                            @elseif($isYesterday)
                                                <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-left: 4px; font-weight: 800;">গতকাল</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 11.5px; color: #64748B;">{{ $cDate->format('l') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span style="display: inline-flex; align-items: center; gap: 5px; background: #EFF6FF; color: #1D4ED8; font-weight: 800; font-size: 13.5px; padding: 4px 14px; border-radius: 99px;">
                                    <i class="fa-solid fa-user" style="font-size: 11px;"></i> {{ number_format($stat->unique_visitors) }} জন
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-weight: 800; color: #0F172A; font-size: 14px;">
                                    {{ number_format($stat->page_views) }} ভিউ
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px; justify-content: center;">
                                    <div style="font-size: 11.5px; color: #475569;">
                                        <i class="fa-solid fa-mobile-screen" style="color: #2563EB;"></i> {{ $mobPercent }}%
                                    </div>
                                    <div style="width: 70px; height: 6px; background: #E2E8F0; border-radius: 99px; overflow: hidden; display: flex;">
                                        <div style="width: {{ $mobPercent }}%; background: #2563EB;"></div>
                                        <div style="width: {{ $deskPercent }}%; background: #059669;"></div>
                                    </div>
                                    <div style="font-size: 11.5px; color: #475569;">
                                        <i class="fa-solid fa-laptop" style="color: #059669;"></i> {{ $deskPercent }}%
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #64748B; font-size: 12.5px;">
                                {{ $stat->unique_visitors > 0 ? round($stat->page_views / $stat->unique_visitors, 1) : 0 }} /জন
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $dailyStats->links() }}
        </div>
    @endif
</div>

<!-- 2-Columns: Top Visited Pages & Devices -->
<div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- Top Visited Pages -->
    <div class="card">
        <h3 class="card-title" style="margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-fire" style="color: #EA580C;"></i> সর্বাধিক ভিজিট হওয়া পেইজ (Top Visited Pages)
        </h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>পেইজ URL</th>
                        <th style="text-align: center;">ইউনিক ভিজিটর</th>
                        <th style="text-align: right;">মোট ভিউ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPages as $page)
                        <tr>
                            <td>
                                <a href="{{ $page->page_url }}" target="_blank" style="color: #2563EB; font-weight: 600; font-size: 12.5px; text-decoration: none; word-break: break-all;">
                                    {{ Str::limit(str_replace(url('/'), '', $page->page_url) ?: '/', 45) }}
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px; opacity: 0.6;"></i>
                                </a>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: #0F172A;">
                                {{ number_format($page->unique_count) }}
                            </td>
                            <td style="text-align: right; font-weight: 800; color: var(--admin-primary);">
                                {{ number_format($page->views_count) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #94A3B8; padding: 20px;">কোন ডাটা নেই</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Device & Browser Distribution -->
    <div class="card">
        <h3 class="card-title" style="margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-mobile-screen-button" style="color: #7C3AED;"></i> ডিভাইস ও ব্রাউজার বিভাজন
        </h3>
        
        <div style="margin-bottom: 20px;">
            <div style="font-size: 12px; font-weight: 700; color: #64748B; margin-bottom: 8px; text-transform: uppercase;">ডিভাইস প্রকারভেদ:</div>
            @foreach($deviceStats as $d)
                @php
                    $dPercent = $totalPageViews > 0 ? round(($d->total / $totalPageViews) * 100) : 0;
                @endphp
                <div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 4px;">
                        <span style="font-weight: 700; color: #1E293B;">
                            <i class="{{ $d->device_type === 'Mobile' ? 'fa-solid fa-mobile-screen' : ($d->device_type === 'Tablet' ? 'fa-solid fa-tablet-screen-button' : 'fa-solid fa-desktop') }}" style="width: 18px; color: var(--admin-primary);"></i>
                            {{ $d->device_type }}
                        </span>
                        <span style="font-weight: 800; color: #475569;">{{ number_format($d->total) }} ({{ $dPercent }}%)</span>
                    </div>
                    <div style="height: 6px; background: #F1F5F9; border-radius: 99px; overflow: hidden;">
                        <div style="width: {{ $dPercent }}%; height: 100%; background: var(--admin-primary);"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <div style="font-size: 12px; font-weight: 700; color: #64748B; margin-bottom: 8px; text-transform: uppercase;">জনপ্রিয় ব্রাউজার:</div>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach($browserStats as $b)
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 6px; font-size: 12px; display: flex; align-items: center; gap: 6px;">
                        <strong style="color: #0F172A;">{{ $b->browser }}:</strong>
                        <span style="color: #64748B;">{{ number_format($b->total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Visitor Log -->
<div class="card">
    <h3 class="card-title" style="margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-list-ul" style="color: #0284C7;"></i> সাম্প্রতিক ভিজিটর অ্যাক্টিভিটি লগ (Recent Visitor Activity)
    </h3>
    <div class="table-responsive">
        <table class="data-table" style="font-size: 12.5px;">
            <thead>
                <tr>
                    <th>সময় (Time)</th>
                    <th>IP অ্যাড্রেস</th>
                    <th>ডিভাইস / OS</th>
                    <th>ব্রাউজার</th>
                    <th>ভিজিট করা পেইজ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentVisitors as $v)
                    <tr>
                        <td style="color: #64748B; white-space: nowrap;">
                            {{ $v->created_at->diffForHumans() }}
                        </td>
                        <td style="font-family: monospace; font-weight: 700; color: #0F172A;">
                            {{ Str::mask($v->ip_address, '*', -4) }}
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #334155;">
                                <i class="{{ $v->device_type === 'Mobile' ? 'fa-solid fa-mobile-screen' : 'fa-solid fa-desktop' }}" style="color: var(--admin-primary); margin-right: 4px;"></i>
                                {{ $v->device_type }} ({{ $v->platform }})
                            </span>
                        </td>
                        <td style="color: #475569;">
                            {{ $v->browser }}
                        </td>
                        <td>
                            <a href="{{ $v->page_url }}" target="_blank" style="color: #2563EB; text-decoration: none; word-break: break-all;">
                                {{ Str::limit(str_replace(url('/'), '', $v->page_url) ?: '/', 50) }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94A3B8; padding: 20px;">এখনো কোন অ্যাক্টিভিটি লগ নেই</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

