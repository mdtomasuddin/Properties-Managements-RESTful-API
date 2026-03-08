@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Emergency Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Emergency Details</h2>
                                <p>Comprehensive details of the emergency report.</p>
                            </div>
                            <a href="{{ route('admin-emergency.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">General Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Category</th>
                                                <td>{{ $emergency->category->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Reported At</th>
                                                <td>{{ $emergency->created_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>User Type</th>
                                                <td><span
                                                        class="badge bg-light text-dark border">{{ ucfirst($emergency->user_type) }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Location & Description --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Location & Description</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted small text-uppercase">Location</label>
                                            <p class="mb-0">{{ $emergency->location ?? 'Not specified' }}</p>
                                        </div>
                                        <div>
                                            <label class="fw-bold text-muted small text-uppercase">Description</label>
                                            <p class="mb-0">{{ $emergency->description ?? 'No description provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Reporter Details --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Reporter Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                @if ($emergency->user && $emergency->user->avatar)
                                                    <img src="{{ $emergency->user->avatar }}" class="rounded-circle"
                                                        width="60" height="60" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-person text-secondary fs-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $emergency->user->name ?? 'Unknown User' }}</h6>
                                                <p class="mb-0 text-muted small">{{ $emergency->user->email ?? 'N/A' }}</p>
                                                <p class="mb-0 text-muted small">{{ $emergency->user->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Property Details --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Properties</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($emergency->property)
                                            <h6 class="mb-1">{{ $emergency->property->property_name }}</h6>
                                            <p class="mb-0 text-muted small">{{ $emergency->property->property_address }}
                                            </p>
                                            <hr class="my-2">
                                            <p class="mb-0 small text-muted"><strong>Owner:</strong>
                                                {{ $emergency->property->user->name ?? 'N/A' }}</p>
                                        @else
                                            <p class="text-muted italic">No property information linked.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
