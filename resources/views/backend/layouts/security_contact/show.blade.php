@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Security Contact Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-11 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Security Contact Details</h2>
                                <p>Comprehensive information about this security contact.</p>
                            </div>
                            <a href="{{ route('admin-security-contact.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        {{-- Security Contact Info Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2 text-primary"></i>Contact
                                    Information</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Basic Info</h6>
                                        <div class="d-flex flex-column gap-2">
                                            <span><strong>Phone:</strong> {{ $securityContact->phone ?? 'N/A' }}</span>
                                            <span><strong>Category:</strong> {{ $securityContact->category->name ?? 'N/A' }}
                                                <small class="text-muted">(ID:
                                                    #{{ $securityContact->category_id }})</small></span>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Timestamps</h6>
                                        <div class="d-flex flex-column gap-2">
                                            <span><strong>Created At:</strong>
                                                {{ $securityContact->created_at ? $securityContact->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Description</h6>
                                        <div class="p-3 border rounded bg-light">
                                            {!! nl2br(e($securityContact->description)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Property Details Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>Property Details
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                @if ($securityContact->property)
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <span><strong>Property Name:</strong>
                                                    {{ $securityContact->property->property_name ?? 'N/A' }}</span>
                                                <span><strong>Address:</strong>
                                                    {{ $securityContact->property->property_address ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-2">
                                                <span><strong>Property ID:</strong>
                                                    #{{ $securityContact->property_id }}</span>
                                                <span><strong>Type:</strong>
                                                    {{ ucfirst(str_replace('_', ' ', $securityContact->property->property_type ?? 'N/A')) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No property information available.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Assigned Creator/User Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-person me-2 text-primary"></i>Assigned User</h4>
                            </div>
                            <div class="card-body p-4">
                                @if ($securityContact->user)
                                    <div class="d-flex align-items-center">
                                        <div class="me-4 text-center">
                                            @if ($securityContact->user->avatar)
                                                <img src="{{ $securityContact->user->avatar }}" alt="avatar"
                                                    class="rounded-circle shadow-sm"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="bi bi-person text-secondary fs-1"></i>
                                                </div>
                                            @endif
                                            <div class="mt-2 text-muted">
                                                <small>User ID: #{{ $securityContact->user_id }}</small>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="mb-1 fw-bold">{{ $securityContact->user->name ?? 'N/A' }}</h4>
                                            <div class="text-muted d-flex flex-column gap-1">
                                                <span><i class="bi bi-envelope me-2"></i>
                                                    {{ $securityContact->user->email ?? 'N/A' }}</span>
                                                <span><i class="bi bi-telephone me-2"></i>
                                                    {{ $securityContact->user->phone ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No user information available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
