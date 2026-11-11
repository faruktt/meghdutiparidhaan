@extends('layouts.admin')

@section('title', 'Edit Page')
@section('breadcrumb', 'Pages / Edit')

@section('content')
<form action="{{ route('admin.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card" style="padding: 20px; border-radius: 10px; max-width: 900px;">
        <div style="margin-bottom: 16px; border-bottom: 1px solid #E2E8F0; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="margin: 0; font-size: 14.5px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-pen-to-square" style="color: var(--admin-primary);"></i> Edit Page: {{ $page->title }}
            </h3>
            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn-admin-secondary" style="font-size: 11px; padding: 4px 10px;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Preview
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Page Title / Name <span style="color: red;">*</span></label>
                <input type="text" name="title" id="pageTitle" value="{{ $page->title }}" class="form-control" style="font-size: 12.5px; padding: 7px 10px;" required>
            </div>
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">URL Slug</label>
                <input type="text" name="slug" id="pageSlug" value="{{ $page->slug }}" class="form-control" style="font-size: 12.5px; padding: 7px 10px;" required>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Description / Content <span style="color: red;">*</span></label>
            <textarea name="description" rows="12" class="form-control" style="font-size: 13px; line-height: 1.6; padding: 10px;" required>{{ $page->description }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; align-items: center;">
            <div>
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: #374151; margin-bottom: 4px;">Sort Order</label>
                <input type="number" name="sort_order" value="{{ $page->sort_order }}" class="form-control" style="font-size: 12.5px; padding: 6px 10px;">
            </div>
            <div style="padding-top: 18px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} style="width: 16px; height: 16px;">
                    Active (Show link in Frontend Footer)
                </label>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #E2E8F0; padding-top: 14px;">
            <a href="{{ route('admin.pages.index') }}" class="btn-admin-secondary" style="font-size: 12px; padding: 6px 14px;">Cancel</a>
            <button type="submit" class="btn-orange-search" style="font-size: 12px; padding: 6px 18px;">
                <i class="fa-solid fa-check"></i> Update Page
            </button>
        </div>
    </div>
</form>
@endsection
