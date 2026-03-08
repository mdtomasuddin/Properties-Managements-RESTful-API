@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Properties Details
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Properties Owner Profile</h2>
                                <p>Comprehensive details of the property owner and their assigned properties.</p>
                            </div>
                            <a href="{{ route('property-owner.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>

                        {{-- User Information Card --}}
                        <div class="card mb-4 border-0 shadow-sm" id="owner-info">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                                    <div class="me-4">
                                        @if ($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="avatar" class="rounded-circle shadow-sm"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                                                style="width: 100px; height: 100px;">
                                                <i class="bi bi-person text-secondary fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="mb-1 fw-bold">
                                            Name: {{ $user->name ?? 'N/A' }}
                                        </h3>
                                        <div class="text-muted d-flex align-items-center gap-3">
                                            <span>
                                                <i class="bi bi-envelope me-1"></i>
                                                <a href="mailto:{{ $user->email }}">
                                                    {{ $user->email ?? 'N/A' }}
                                                </a>
                                            </span>
                                            <span>
                                                <i class="bi bi-telephone me-1"></i>
                                                <a href="tel:{{ $user->phone }}">
                                                    {{ $user->phone ?? 'N/A' }}
                                                </a>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Account Details</h6>
                                        <div class="d-flex flex-column gap-1">
                                            <span><strong>Joined Date:</strong>
                                                {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}</span>
                                            <span><strong>Referral Code:</strong> {{ $user->reffer_code ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-uppercase text-muted fw-semibold mb-2">Location</h6>
                                        <p class="mb-0"><i
                                                class="bi bi-geo-alt me-2 text-primary"></i>{{ $user->address ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="text-muted  mb-2">Subscription</h6>
                                        @if ($user->subscription)
                                            <span><strong>Yes</span>
                                        @else
                                            <span><strong>No</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- User Properties Card --}}
                        <div class="card border-0 shadow-sm" id="owner-properties">
                            <div class="card-header bg-white border-bottom p-4">
                                <h4 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>Assigned Properties
                                </h4>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered w-100 table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Property Name</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>Units</th>
                                            <th>Security Contact</th>
                                            <th>Additional Details</th>
                                            <th>Created_at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($user->properties as $index => $property)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $property->property_name ?? 'N/A' }}</td>
                                                <td class="text-capitalize">
                                                    {{ str_replace('_', ' ', $property->property_type) }}</td>
                                                <td>{{ $property->property_address ?? 'N/A' }}</td>
                                                <td class="text-center">{{ $property->number_of_units ?? 0 }}</td>
                                                <td>{{ $property->security_contact ?? 'N/A' }}</td>
                                                <td>{{ Str::limit($property->additional_details ?? 'N/A', 30) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $property->created_at ? $property->created_at->format('d M, Y') : 'N/A' }}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info text-white"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#propertyModal{{ $property->id }}"
                                                        title="View Full Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">No properties
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Property Modals --}}
                        @foreach ($user->properties as $property)
                            <div class="modal fade" id="propertyModal{{ $property->id }}" tabindex="-1"
                                aria-labelledby="propertyModalLabel{{ $property->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="propertyModalLabel{{ $property->id }}">Property:
                                                {{ $property->property_name ?? 'N/A' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <strong>Property ID:</strong> {{ $property->id }}
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Type:</strong> <span
                                                        class="text-capitalize">{{ str_replace('_', ' ', $property->property_type) }}</span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Address:</strong> {{ $property->property_address ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Units:</strong> {{ $property->number_of_units ?? 0 }}
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Security Contact:</strong>
                                                    {{ $property->security_contact ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Status:</strong>
                                                    <span
                                                        class="badge {{ $property->status == 'active' ? 'bg-success' : 'bg-primary' }}">
                                                        {{ ucfirst($property->status) }}
                                                    </span>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <strong>Additional Details:</strong>
                                                    <p class="mt-1 border p-2 rounded bg-light">
                                                        {{ $property->additional_details ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <strong>Documents:</strong><br>
                                                    @if (is_array($property->documents) && count($property->documents) > 0)
                                                        <div class="d-flex gap-3 mt-2 flex-wrap">
                                                            @foreach ($property->documents as $doc)
                                                                @php
                                                                    $urlPath = parse_url($doc, PHP_URL_PATH) ?? '';
                                                                    $ext = pathinfo($urlPath, PATHINFO_EXTENSION);
                                                                    $isImage =
                                                                        preg_match(
                                                                            '/\.(jpg|jpeg|png|gif|webp|svg)(\?.*)?$/i',
                                                                            $doc,
                                                                        ) ||
                                                                        str_contains(strtolower($doc), 'image') ||
                                                                        str_contains(strtolower($doc), 'photo') ||
                                                                        in_array(strtolower($ext), [
                                                                            'jpg',
                                                                            'jpeg',
                                                                            'png',
                                                                            'gif',
                                                                            'webp',
                                                                            'svg',
                                                                        ]);
                                                                @endphp

                                                                @if ($isImage)
                                                                    <div class="position-relative text-center">
                                                                        <img src="{{ $doc }}" alt="Document"
                                                                            class="img-thumbnail shadow-sm"
                                                                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border-radius: 8px;"
                                                                            onclick="openImageModal('{{ $doc }}')">
                                                                    </div>
                                                                @else
                                                                    <div class="text-center">
                                                                        <a href="{{ $doc }}" target="_blank"
                                                                            class="btn btn-outline-info shadow-sm d-flex flex-column align-items-center justify-content-center"
                                                                            style="width: 80px; height: 80px; border-radius: 8px;">
                                                                            <i class="bi bi-file-earmark-pdf fs-2"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No documents available.</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $property->created_at ? $property->created_at->format('d M, Y H:i A') : 'N/A' }}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

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
                    <h5 class="modal-title" id="imageViewerModalLabel"><i class="bi bi-image me-2"></i> Document Viewer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-light">
                    <div style="padding: 20px;">
                        <img id="viewerImage" src="" alt="Document Preview" class="img-fluid rounded shadow-sm"
                            style="max-height: 75vh;">
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
