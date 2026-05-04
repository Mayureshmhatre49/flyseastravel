@extends('layouts.admin')

@section('title', 'Enquiries')

@section('breadcrumb')
    <span class="current">Enquiries</span>
@endsection

@section('admin-content')

<div class="page-head">
    <div>
        <h1>Enquiries</h1>
        <p>{{ $enquiries->total() }} total enquir{{ $enquiries->total() !== 1 ? 'ies' : 'y' }}</p>
    </div>
</div>

<div class="form-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Destination</th>
                    <th>Dates</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $enquiry)
                <tr>
                    <td>
                        <div style="font-weight:500; color:var(--fs-ink);">{{ $enquiry->name }}</div>
                        @if($enquiry->message)
                            <div style="font-size:12px; color:var(--fs-ink-soft); margin-top:2px; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                                 title="{{ $enquiry->message }}">
                                {{ $enquiry->message }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $enquiry->phone }}</td>
                    <td>
                        <a href="mailto:{{ $enquiry->email }}"
                           style="color:var(--fs-primary); text-decoration:none;">
                            {{ $enquiry->email }}
                        </a>
                    </td>
                    <td>{{ $enquiry->destination ?? '—' }}</td>
                    <td style="white-space:nowrap;">{{ $enquiry->travel_dates ?? '—' }}</td>
                    <td>
                        @if($enquiry->package)
                            <a href="{{ route('admin.packages.edit', $enquiry->package) }}"
                               style="color:var(--fs-primary); text-decoration:none; font-size:13px;">
                                {{ Str::limit($enquiry->package->title, 30) }}
                            </a>
                        @else
                            <span style="color:var(--fs-ink-faint);">—</span>
                        @endif
                    </td>
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
                    <td style="white-space:nowrap; color:var(--fs-ink-soft);">{{ $enquiry->created_at->format('d M Y') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.enquiries.status', $enquiry) }}"
                              style="display:flex; gap:6px; align-items:center;">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="field-select"
                                    style="height:34px; font-size:12px; padding:0 10px; width:120px;">
                                @foreach(['new','contacted','converted','closed'] as $status)
                                    <option value="{{ $status }}"
                                        {{ ($enquiry->status ?? 'new') === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary-sm"
                                    style="height:34px; padding:0 12px; font-size:12px;">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:48px; color:var(--fs-ink-soft);">
                        No enquiries yet. They'll appear here when customers submit the enquiry form.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($enquiries->hasPages())
    <div style="margin-top:24px;">
        {{ $enquiries->links() }}
    </div>
@endif

@endsection
