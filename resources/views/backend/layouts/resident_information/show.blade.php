@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Resident Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Resident Profile</h2>
                                <p>Comprehensive details of the resident and matched property owner.</p>
                            </div>
                            <a href="{{ route('resident-information.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        {{-- Resident Information Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                                    <div class="me-4">
                                        @php
                                            $avatar = $resident->user->avatar ?? null;
                                        @endphp
                                        @if ($avatar)
                                            <img src="{{ $avatar }}" class="rounded-circle shadow-sm"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                style="width: 100px; height: 100px;">
                                                <i class="bi bi-person text-secondary fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="mb-1 fw-bold">{{ $resident->user->name ?? 'N/A' }}</h3>
                                        <div class="text-muted d-flex gap-3">
                                            <span><i class="bi bi-envelope me-1"></i>
                                                {{ $resident->user->email ?? 'N/A' }}</span>
                                            <span><i class="bi bi-telephone me-1"></i>
                                                {{ $resident->user->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Resident Details</h6>
                                        <p class="mb-1"><strong>Property Owner Invitation Code:</strong> <span
                                                class="badge bg-info">{{ $resident->user->invitation_code ?? 'None' }}</span>
                                        </p>
                                        <p class="mb-1"><strong>Address:</strong> {{ $resident->user->address ?? 'N/A' }}
                                        </p>
                                        {{-- <p class="mb-1"><strong>Unit/Flat Number:</strong> <span
                                                class="text-primary fw-bold">{{ $resident->resident->unit_flat_number ?? 'Not Set' }}</span>
                                        </p> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Property & Owner Details (Matched by Invitation Code) --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Matched Property Information</h5>
                            </div>
                            <div class="card-body p-4">
                                @if ($resident->assigned_property)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Property Owner:</strong>
                                                {{ $resident->assigned_owner->name ?? 'N/A' }}</p>
                                            <p class="mb-2"><strong>Property ID:</strong>
                                                {{ $resident->assigned_property->id }}</p>
                                            <p class="mb-2"><strong>Property Name:</strong>
                                                {{ $resident->assigned_property->property_name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Property Address:</strong>
                                                {{ $resident->assigned_property->property_address }}</p>

                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bi bi-exclamation-circle text-warning fs-2"></i>
                                        <p class="mt-2 text-muted">No owner found with a matching invitation code.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
