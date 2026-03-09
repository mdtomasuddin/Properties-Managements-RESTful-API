@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Property Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Property Details</h2>
                                <p>Comprehensive information about property: <strong>{{ $property->property_name }}</strong></p>
                            </div>
                            <a href="{{ route('admin-property.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        {{-- Property Information Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>Property Information</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Basic Info</h6>
                                        <div class="d-flex flex-column gap-2">
                                            <span><strong>Property Name:</strong> {{ $property->property_name ?? 'N/A' }}</span>
                                            <span><strong>Property Type:</strong> <span class="text-capitalize">{{ str_replace('_', ' ', $property->property_type) }}</span></span>
                                            <span><strong>Address:</strong> {{ $property->property_address ?? 'N/A' }}</span>
                                            <span><strong>Number of Units:</strong> {{ $property->number_of_units ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Status & Security</h6>
                                        <div class="d-flex flex-column gap-2">
                                            <span><strong>Status:</strong> 
                                                <span class="badge {{ $property->status == 'active' ? 'bg-success' : 'bg-primary' }}">
                                                    {{ ucfirst($property->status) }}
                                                </span>
                                            </span>
                                            <span><strong>Security Contact:</strong> {{ $property->security_contact ?? 'N/A' }}</span>
                                            <span><strong>Created At:</strong> {{ $property->created_at ? $property->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Additional Details</h6>
                                        <p class="mb-0 border p-3 rounded bg-light">{{ $property->additional_details ?? 'No additional details provided.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Owner Information Card --}}
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-person me-2 text-primary"></i>Owner Information</h4>
                            </div>
                            <div class="card-body p-4">
                                @if($property->user)
                                <div class="d-flex align-items-center mb-4">
                                    <div class="me-4">
                                        @if ($property->user->avatar)
                                            <img src="{{ $property->user->avatar }}" alt="avatar" class="rounded-circle shadow-sm"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                style="width: 80px; height: 80px;">
                                                <i class="bi bi-person text-secondary fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="mb-1 fw-bold">{{ $property->user->name ?? 'N/A' }}</h3>
                                        <div class="text-muted d-flex align-items-center gap-3">
                                            <span><i class="bi bi-envelope me-1"></i> <a href="mailto:{{ $property->user->email }}">{{ $property->user->email ?? 'N/A' }}</a></span>
                                            <span><i class="bi bi-telephone me-1"></i> <a href="tel:{{ $property->user->phone }}">{{ $property->user->phone ?? 'N/A' }}</a></span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <p class="text-muted mb-0">No owner assigned to this property.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Documents Card --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Documents</h4>
                            </div>
                            <div class="card-body p-4">
                                @if (is_array($property->documents) && count($property->documents) > 0)
                                    <div class="d-flex gap-3 mt-2 flex-wrap">
                                        @foreach ($property->documents as $doc)
                                            @php
                                                $urlPath = parse_url($doc, PHP_URL_PATH) ?? '';
                                                $ext = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
                                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                                                $isImage = in_array($ext, $imageExtensions) || 
                                                           str_contains(strtolower($doc), 'unsplash.com') || 
                                                           str_contains(strtolower($doc), 'images.unsplash.com');
                                                $isPdf = $ext == 'pdf';
                                            @endphp

                                            @if ($isImage)
                                                <div class="position-relative text-center">
                                                    <img src="{{ $doc }}" alt="Document"
                                                        class="img-thumbnail shadow-sm"
                                                        style="width: 100px; height: 100px; object-fit: cover; cursor: pointer; border-radius: 8px;"
                                                        onclick="openImageModal('{{ $doc }}')">
                                                </div>
                                            @elseif ($isPdf)
                                                <div class="text-center">
                                                    <a href="{{ $doc }}" target="_blank"
                                                        class="btn btn-outline-info shadow-sm d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 100px; height: 100px; border-radius: 8px;">
                                                        <i class="bi bi-file-earmark-pdf fs-1"></i>
                                                        <small class="mt-1">View PDF</small>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <a href="{{ $doc }}" target="_blank"
                                                        class="btn btn-outline-secondary shadow-sm d-flex flex-column align-items-center justify-content-center"
                                                        style="width: 100px; height: 100px; border-radius: 8px;">
                                                        <i class="bi bi-file-earmark fs-1"></i>
                                                        <small class="mt-1">View File</small>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No documents available for this property.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Global Image Viewer Modal --}}
    <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-labelledby="imageViewerModalLabel"
        aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-bottom-0">
                    <h5 class="modal-title" id="imageViewerModalLabel"><i class="bi bi-image me-2"></i> Document Viewer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-light">
                    <div style="padding: 20px;">
                        <img id="viewerImage" src="" alt="Document Preview" class="img-fluid rounded shadow-sm" style="max-height: 75vh;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openImageModal(url) {
            var viewerElem = document.getElementById('imageViewerModal');
            document.getElementById('viewerImage').src = url;
            var imageModal = new bootstrap.Modal(viewerElem);
            imageModal.show();
        }
    </script>
@endpush
