@extends('layouts.admin')

@section('title', 'Create Package')

@section('breadcrumb')
    <a href="{{ route('admin.packages.index') }}">Itineraries</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    <span class="current">Create</span>
@endsection

@section('admin-content')

<div class="page-head">
    <div>
        <h1>Create Package</h1>
        <p>Add a new travel itinerary to your catalogue.</p>
    </div>
    <div class="page-head-actions">
        <a href="{{ route('admin.packages.index') }}" class="btn-secondary" style="text-decoration:none;">
            Cancel
        </a>
    </div>
</div>

@if($errors->any())
    <div style="background:#FCEEEC; border:1px solid #C0392B; border-radius:var(--fs-r-md); padding:16px 20px; margin-bottom:20px; font-size:14px; color:#C0392B;">
        <strong>Please fix the following errors:</strong>
        <ul style="margin-top:8px; padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-grid">

        {{-- Left: main fields --}}
        <div>

            {{-- Trip Basics --}}
            <div class="form-card">
                <div class="form-card-head">
                    <h3>Trip Basics</h3>
                    <p>Core identification and classification</p>
                </div>
                <div class="form-card-body">
                    <div class="field">
                        <div class="field-label">
                            <label for="title">Title <span class="req">*</span></label>
                        </div>
                        <input type="text" id="title" name="title" class="field-input"
                               value="{{ old('title') }}" placeholder="e.g. Romantic Maldives Getaway" required>
                    </div>

                    <div class="field">
                        <div class="field-label">
                            <label for="slug">Slug <span class="req">*</span></label>
                            <span class="hint">Auto-generated from title</span>
                        </div>
                        <input type="text" id="slug" name="slug" class="field-input"
                               value="{{ old('slug') }}" placeholder="romantic-maldives-getaway" required>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <div class="field-label"><label for="location">Location <span class="req">*</span></label></div>
                            <input type="text" id="location" name="location" class="field-input"
                                   value="{{ old('location') }}" placeholder="Maldives" required>
                        </div>
                        <div class="field">
                            <div class="field-label"><label for="country">Country <span class="req">*</span></label></div>
                            <input type="text" id="country" name="country" class="field-input"
                                   value="{{ old('country') }}" placeholder="India" required>
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="field">
                            <div class="field-label"><label for="category">Category <span class="req">*</span></label></div>
                            <select id="category" name="category" class="field-select" required>
                                <option value="">Select category</option>
                                @foreach(['honeymoon','group','family','adventure','international','college'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <div class="field-label"><label for="badge">Badge <span class="req">*</span></label></div>
                            <select id="badge" name="badge" class="field-select" required>
                                <option value="">Select badge</option>
                                @foreach(['bestseller','new','limited','none'] as $b)
                                    <option value="{{ $b }}" {{ old('badge') === $b ? 'selected' : '' }}>
                                        {{ ucfirst($b) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row-3">
                        <div class="field">
                            <div class="field-label"><label for="days">Days <span class="req">*</span></label></div>
                            <input type="number" id="days" name="days" class="field-input" min="1"
                                   value="{{ old('days') }}" placeholder="5" required>
                        </div>
                        <div class="field">
                            <div class="field-label"><label for="nights">Nights <span class="req">*</span></label></div>
                            <input type="number" id="nights" name="nights" class="field-input" min="1"
                                   value="{{ old('nights') }}" placeholder="4" required>
                        </div>
                        <div class="field">
                            <div class="field-label"><label for="tier">Tier <span class="req">*</span></label></div>
                            <select id="tier" name="tier" class="field-select" required>
                                <option value="">Select tier</option>
                                <option value="standard" {{ old('tier') === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="premium"  {{ old('tier') === 'premium'  ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="form-card">
                <div class="form-card-head">
                    <h3>Pricing</h3>
                </div>
                <div class="form-card-body">
                    <div class="row-2">
                        <div class="field">
                            <div class="field-label"><label for="price_per_person">Price per Person (₹) <span class="req">*</span></label></div>
                            <input type="number" id="price_per_person" name="price_per_person" class="field-input" min="0"
                                   value="{{ old('price_per_person') }}" placeholder="45000" required>
                        </div>
                        <div class="field">
                            <div class="field-label"><label for="rating">Rating <span class="req">*</span></label></div>
                            <input type="number" id="rating" name="rating" class="field-input" min="0" max="5" step="0.1"
                                   value="{{ old('rating') }}" placeholder="4.8" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="form-card">
                <div class="form-card-head">
                    <h3>Content</h3>
                    <p>Description shown on listing and detail pages</p>
                </div>
                <div class="form-card-body">
                    <div class="field">
                        <div class="field-label"><label for="description">Short Description</label></div>
                        <textarea id="description" name="description" class="field-textarea" rows="3"
                                  placeholder="A brief, enticing summary of the package...">{{ old('description') }}</textarea>
                    </div>
                    <div class="field">
                        <div class="field-label"><label for="overview">Overview</label></div>
                        <textarea id="overview" name="overview" class="field-textarea" rows="5"
                                  placeholder="Detailed overview of the trip experience...">{{ old('overview') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Media --}}
            <div class="form-card">
                <div class="form-card-head">
                    <h3>Media</h3>
                    <p>First image is the hero — drag to reorder</p>
                </div>
                <div class="form-card-body">
                    @include('admin.packages._image-uploader', ['existing' => []])
                </div>
            </div>

            {{-- Inclusions / Exclusions --}}
            <div class="form-card">
                <div class="form-card-head">
                    <h3>Inclusions &amp; Exclusions</h3>
                    <p>Enter one item per line</p>
                </div>
                <div class="form-card-body">
                    <div class="row-2">
                        <div class="field">
                            <div class="field-label"><label for="inclusions">Inclusions</label></div>
                            <textarea id="inclusions" name="inclusions" class="field-textarea" rows="6"
                                      placeholder="Flight tickets&#10;Hotel accommodation&#10;Breakfast daily">{{ old('inclusions') }}</textarea>
                        </div>
                        <div class="field">
                            <div class="field-label"><label for="exclusions">Exclusions</label></div>
                            <textarea id="exclusions" name="exclusions" class="field-textarea" rows="6"
                                      placeholder="Personal expenses&#10;Travel insurance&#10;Visa fees">{{ old('exclusions') }}</textarea>
                        </div>
                    </div>
                    <div class="field" style="margin-top:16px;">
                        <div class="field-label">
                            <label for="highlights">Highlights</label>
                            <span class="hint">One per line</span>
                        </div>
                        <textarea id="highlights" name="highlights" class="field-textarea" rows="4"
                                  placeholder="Sunset cruise&#10;Snorkelling at coral reef&#10;Beachfront dining">{{ old('highlights') }}</textarea>
                    </div>
                    <div class="field">
                        <div class="field-label">
                            <label for="includes_icons">Includes Icons</label>
                            <span class="hint">Icon names, one per line</span>
                        </div>
                        <textarea id="includes_icons" name="includes_icons" class="field-textarea" rows="3"
                                  placeholder="plane&#10;hotel&#10;camera">{{ old('includes_icons') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Status sidebar --}}
        <div>
            <div class="status-card">
                <h4>Publish Settings</h4>
                <div class="toggle-row">
                    <div class="toggle-row-text">
                        <h5>Active</h5>
                        <p>Show on public site</p>
                    </div>
                    <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               style="width:16px; height:16px; accent-color:var(--fs-primary);">
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-row-text">
                        <h5>Featured</h5>
                        <p>Show on homepage</p>
                    </div>
                    <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured') == '1' ? 'checked' : '' }}
                               style="width:16px; height:16px; accent-color:var(--fs-primary);">
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-primary-sm" style="width:100%; justify-content:center; height:44px; font-size:15px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Package
            </button>

            <a href="{{ route('admin.packages.index') }}" class="btn-secondary"
               style="width:100%; justify-content:center; margin-top:10px; text-decoration:none; height:44px;">
                Cancel
            </a>
        </div>

    </div>
</form>

<script>
    const titleInput = document.getElementById('title');
    const slugInput  = document.getElementById('slug');
    let slugManuallyEdited = false;

    slugInput.addEventListener('input', function() {
        slugManuallyEdited = true;
    });

    titleInput.addEventListener('input', function() {
        if (!slugManuallyEdited) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    });
</script>

@endsection
