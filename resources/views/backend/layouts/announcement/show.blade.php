@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Announcement Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Announcement Details</h2>
                                <p>Comprehensive details of the announcement.</p>
                            </div>
                            <a href="{{ route('admin-announcement.index') }}" class="btn btn-primary">
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
                                                <th style="width: 30%">Title</th>
                                                <td>{{ $announcement->title ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Category</th>
                                                <td>{{ $announcement->category->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @php
                                                        $badgeClass = match ($announcement->status) {
                                                            'published', 'active' => 'bg-success',
                                                            'draft' => 'bg-warning',
                                                            'inactive', 'cancelled' => 'bg-danger',
                                                            default => 'bg-secondary',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge {{ $badgeClass }}">{{ ucfirst($announcement->status) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td>
                                                    @if (is_string($announcement->created_at))
                                                        {{ \Carbon\Carbon::parse($announcement->created_at)->format('M d, Y h:i A') }}
                                                    @else
                                                        {{ $announcement->created_at->format('M d, Y h:i A') }}
                                                    @endif
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <th>Publish Date</th>
                                                <td>{{ $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->format('M d, Y') : 'N/A' }}</td>
                                            </tr> --}}
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Announcement Content</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted small text-uppercase">Message</label>
                                            <div class="mt-2">
                                                {!! nl2br(e($announcement->message)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Author Details --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Author Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                @if ($announcement->user && $announcement->user->avatar)
                                                    <img src="{{ $announcement->user->avatar }}" class="rounded-circle"
                                                        width="60" height="60" style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-person text-secondary fs-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $announcement->user->name ?? 'Unknown User' }}</h6>
                                                <p class="mb-0 text-muted small"><strong>User ID:</strong>
                                                    {{ $announcement->user->id ?? 'N/A' }}</p>
                                                <p class="mb-0 text-muted small"><strong>Email:</strong>
                                                    {{ $announcement->user->email ?? 'N/A' }}</p>
                                                <p class="mb-0 text-muted small"><strong>Phone:</strong>
                                                    {{ $announcement->user->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Property Details --}}
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0">Linked Property</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($announcement->property)
                                            <h6 class="mb-1">{{ $announcement->property->property_name }}</h6>
                                            <p class="mb-0 text-muted small">
                                                {{ $announcement->property->property_address }}</p>
                                            <hr class="my-2">
                                            <p class="mb-0 small text-muted"><strong>Owner:</strong>
                                                {{ $announcement->property->user->name ?? 'N/A' }}</p>
                                        @else
                                            <p class="text-muted italic">No property information linked.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Attachments --}}
                            @if (!empty($announcement->attachments))
                                <div class="col-12 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white py-3">
                                            <h5 class="mb-0">Attachments</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($announcement->attachments as $attachment)
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
                                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) || str_contains($attachment, 'premium_photo'))
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
