@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Maintenance Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Maintenance Details</h2>
                                <p>Comprehensive details of the maintenance report.</p>
                            </div>
                            <a href="{{ route('admin-maintenance.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        <div class="row">
                            {{-- Basic Information & Category --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">General Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th style="width: 30%">Category ID</th>
                                                <td>{{ $maintenance->category->id ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Category Name</th>
                                                <td>
                                                    @if ($maintenance->category && $maintenance->category->icon)
                                                        <img src="{{ url($maintenance->category->icon) }}" width="25"
                                                            height="25" class="me-2 rounded-circle" alt="">
                                                    @endif
                                                    {{ $maintenance->category->name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Reported At</th>
                                                <td>{{ $maintenance->created_at->format('M d, Y h:i A') }}</td>
                                            </tr>

                                        </table>
                                        <div class="mt-3">
                                            <label class="fw-bold text-muted small text-uppercase">Description</label>
                                            <p class="mb-0">{{ $maintenance->description ?? 'No description provided' }}
                                            </p>
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
                                                @if ($maintenance->user && $maintenance->user->avatar)
                                                    <img src="{{ $maintenance->user->avatar }}" class="rounded-circle"
                                                        width="60" height="60"
                                                        style="object-fit: cover; border: 1px solid #eee;">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-person text-secondary fs-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $maintenance->user->name ?? 'Unknown User' }}</h6>
                                                <p class="mb-1 text-muted small"><strong>User ID:</strong>
                                                    {{ $maintenance->user->id ?? 'N/A' }}</p>
                                                <p class="mb-0 text-muted small"><i class="bi bi-envelope me-1"></i>
                                                    {{ $maintenance->user->email ?? 'N/A' }}</p>
                                                <p class="mb-0 text-muted small"><i class="bi bi-telephone me-1"></i>
                                                    {{ $maintenance->user->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Property Details --}}
                            <div class="col-md-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Property Details</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($maintenance->property)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th style="width: 40%">Property ID</th>
                                                            <td>{{ $maintenance->property->id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Property Name</th>
                                                            <td>{{ $maintenance->property->property_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Address</th>
                                                            <td>{{ $maintenance->property->property_address }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Number of Units</th>
                                                            <td>{{ $maintenance->property->number_of_units }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small text-uppercase">Property
                                                        Owner/Manager</label>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <div class="me-2">
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($maintenance->property->user->name ?? 'N', 0, 1)) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">
                                                                {{ $maintenance->property->user->name ?? 'N/A' }}</h6>
                                                            <p class="mb-0 text-muted small">Owner ID:
                                                                {{ $maintenance->property->user->id ?? 'N/A' }}</p>
                                                        </div>

                                                    </div>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <div class="me-2">
                                                            <i class="bi bi-envelope me-1"></i>
                                                            {{ $maintenance->property->user->email ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <div class="me-2">
                                                            <i class="bi bi-telephone me-1"></i>
                                                            {{ $maintenance->property->user->phone ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-muted italic">No property information linked.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if (!empty($maintenance->attachments))
                                <div class="col-12 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white py-3">
                                            <h5 class="mb-0">Attachments</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($maintenance->attachments as $attachment)
                                                    @php
                                                        $cleanPath = strtok($attachment, '?');
                                                        $extension = strtolower(
                                                            pathinfo($cleanPath, PATHINFO_EXTENSION),
                                                        );
                                                        $fileUrl = filter_var($attachment, FILTER_VALIDATE_URL)
                                                            ? $attachment
                                                            : asset('storage/' . $attachment);
                                                    @endphp

                                                    <div class="col-md-3 mb-3">
                                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'aif']) ||
                                                                str_contains($attachment, 'premium_photo') ||
                                                                str_contains($attachment, 'unsplash.com'))
                                                            <a href="{{ $fileUrl }}" target="_blank">
                                                                <img src="{{ $fileUrl }}"
                                                                    class="img-fluid rounded shadow-sm border"
                                                                    style="height:150px; width:100%; object-fit:cover;"
                                                                    onerror="this.onerror=null;this.src='https://placehold.co/300x150?text=Image+Not+Found';">
                                                            </a>
                                                        @elseif($extension == 'pdf')
                                                            <div class="border rounded p-2 text-center bg-light">
                                                                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                                <a href="{{ $fileUrl }}" target="_blank"
                                                                    class="btn btn-sm btn-primary d-block">View PDF</a>
                                                            </div>
                                                        @else
                                                            <div class="border rounded p-2 text-center bg-light">
                                                                <i class="fas fa-file fa-3x text-secondary mb-2"></i>
                                                                <a href="{{ $fileUrl }}" target="_blank"
                                                                    class="btn btn-sm btn-dark d-block">View File</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
