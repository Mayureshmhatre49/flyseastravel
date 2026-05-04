@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="current">Dashboard</span>
@endsection

@section('admin-content')

<div class="page-head">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back. Here's what's happening with FlySeas Travels.</p>
    </div>
    <div class="page-head-actions">
        <a href="{{ route('admin.packages.create') }}" class="btn-primary-sm" style="text-decoration:none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Package
        </a>
    </div>
</div>

{{-- Stats Grid --}}
<div style="display:grid; grid-template-columns: repeat(4,1fr); gap:20px; margin-bottom:32px;">

    <div class="stat-card">
        <div class="stat-card-num">{{ $stats['packages'] }}</div>
        <div class="stat-card-label">Total Packages</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-num">{{ $stats['enquiries'] }}</div>
        <div class="stat-card-label">Total Enquiries</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-num" style="color:#1D4ED8;">{{ $stats['new_enquiries'] }}</div>
        <div class="stat-card-label">New Enquiries</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-num">{{ $stats['featured'] }}</div>
        <div class="stat-card-label">Featured Packages</div>
    </div>

</div>

{{-- Recent Enquiries --}}
<div class="form-card">
    <div class="form-card-head" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3>Recent Enquiries</h3>
            <p>Latest 5 customer enquiries</p>
        </div>
        <a href="{{ route('admin.enquiries.index') }}" style="font-size:13px; color:var(--fs-primary); text-decoration:none; font-weight:500;">
            View all →
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Destination</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_enquiries as $enquiry)
                <tr>
                    <td style="color:var(--fs-ink); font-weight:500;">{{ $enquiry->name }}</td>
                    <td>
                        <div>{{ $enquiry->phone }}</div>
                        <div style="font-size:12px; color:var(--fs-ink-soft);">{{ $enquiry->email }}</div>
                    </td>
                    <td>{{ $enquiry->destination ?? '—' }}</td>
                    <td>{{ $enquiry->package?->title ?? '—' }}</td>
                    <td>
                        @php
                            $badgeClass = match($enquiry->status ?? 'new') {
                                'new'       => 'badge-new',
                                'contacted' => 'badge-contacted',
                                'converted' => 'badge-converted',
                                'closed'    => 'badge-closed',
                                default     => 'badge-new',
                            };
                        @endphp
                        <span class="badge-status {{ $badgeClass }}">{{ ucfirst($enquiry->status ?? 'new') }}</span>
                    </td>
                    <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:var(--fs-ink-soft);">No enquiries yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
