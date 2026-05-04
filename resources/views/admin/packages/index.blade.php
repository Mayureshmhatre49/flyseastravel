@extends('layouts.admin')

@section('title', 'Packages')

@section('breadcrumb')
    <span class="current">Itineraries</span>
@endsection

@section('admin-content')

<div class="page-head">
    <div>
        <h1>Itineraries</h1>
        <p>{{ $packages->count() }} package{{ $packages->count() !== 1 ? 's' : '' }} in total</p>
    </div>
    <div class="page-head-actions">
        <a href="{{ route('admin.packages.create') }}" class="btn-primary-sm" style="text-decoration:none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Package
        </a>
    </div>
</div>

<div class="form-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $package)
                <tr>
                    <td>
                        <div style="font-weight:500; color:var(--fs-ink);">{{ $package->title }}</div>
                        <div style="font-size:12px; color:var(--fs-ink-soft); margin-top:2px;">{{ $package->days }}D/{{ $package->nights }}N &middot; {{ ucfirst($package->tier) }}</div>
                    </td>
                    <td>
                        <div>{{ $package->location }}</div>
                        <div style="font-size:12px; color:var(--fs-ink-soft);">{{ $package->country }}</div>
                    </td>
                    <td>
                        <span style="text-transform:capitalize;">{{ $package->category }}</span>
                    </td>
                    <td style="font-weight:500; color:var(--fs-ink);">
                        ₹{{ number_format($package->price_per_person) }}
                        <div style="font-size:12px; color:var(--fs-ink-soft); font-weight:400;">per person</div>
                    </td>
                    <td>
                        <span style="display:inline-flex; align-items:center; gap:4px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="var(--fs-accent)" stroke="var(--fs-accent-dark)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            {{ number_format($package->rating, 1) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            @if($package->is_active)
                                <span class="badge-status badge-converted">Active</span>
                            @else
                                <span class="badge-status badge-closed">Inactive</span>
                            @endif
                            @if($package->is_featured)
                                <span class="badge-status" style="background:#FEF3C7; color:#B45309;">Featured</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <a href="{{ route('admin.packages.edit', $package) }}" class="btn-secondary" style="text-decoration:none; height:34px; padding:0 14px; font-size:13px;">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.packages.destroy', $package) }}"
                                  onsubmit="return confirm('Delete \'{{ addslashes($package->title) }}\'? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="height:34px; padding:0 14px; font-size:13px;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:48px; color:var(--fs-ink-soft);">
                        No packages yet.
                        <a href="{{ route('admin.packages.create') }}" style="color:var(--fs-primary); font-weight:500; margin-left:6px;">Create your first package →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
