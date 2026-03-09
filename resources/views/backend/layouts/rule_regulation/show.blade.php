@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Rule Regulation Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Rule Regulation Details</h2>
                                <p>Detailed information about the rule regulation.</p>
                            </div>
                            <a href="{{ route('admin-rule-regulation.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        {{-- Rule Regulation Information Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i>Rule Information
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Title</h6>
                                        <p class="mb-0 fw-bold fs-5">{{ $ruleRegulation->title ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Description</h6>
                                        <div class="p-3 border rounded bg-light">
                                            {!! nl2br(e($ruleRegulation->description)) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Property Details</h6>
                                        <div class="d-flex flex-column gap-2">
                                            <span><strong>Property Name:</strong>
                                                {{ $ruleRegulation->property->property_name ?? 'N/A' }}</span>
                                            <span><strong>Location:</strong>
                                                {{ $ruleRegulation->property->property_address ?? 'N/A' }}</span>

                                            <span><strong>Property Type:</strong>
                                                {{ $ruleRegulation->property->property_type ?? 'N/A' }}</span>

                                            <span><strong>Number of Units:</strong>
                                                {{ $ruleRegulation->property->number_of_units ?? 'N/A' }}</span>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Timestamps</h6>
                                        <div class="d-flex flex-column gap-2">
                                            <span><strong>Created At:</strong>
                                                {{ $ruleRegulation->created_at ? $ruleRegulation->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Creator Information Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Creator
                                    Information</h4>
                            </div>
                            <div class="card-body p-4">
                                @if ($ruleRegulation->user)
                                    <div class="d-flex align-items-center">
                                        <div class="me-4 text-center">
                                            @if ($ruleRegulation->user->avatar)
                                                <img src="{{ $ruleRegulation->user->avatar }}" alt="avatar"
                                                    class="rounded-circle shadow-sm"
                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                    style="width: 100px; height: 100px;">
                                                    <i class="bi bi-person text-secondary fs-1"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="mb-1 fw-bold">{{ $ruleRegulation->user->name ?? 'N/A' }}</h3>
                                            <div class="text-muted d-flex flex-column gap-2 mt-3">
                                                <span><i class="bi bi-envelope me-2"></i> <a
                                                        href="mailto:{{ $ruleRegulation->user->email }}">{{ $ruleRegulation->user->email ?? 'N/A' }}</a></span>
                                                <span><i class="bi bi-telephone me-2"></i> <a
                                                        href="tel:{{ $ruleRegulation->user->phone }}">{{ $ruleRegulation->user->phone ?? 'N/A' }}</a></span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No creator information available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
