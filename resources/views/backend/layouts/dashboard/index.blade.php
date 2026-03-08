@extends('backend.app')

@section('title')
    {{ config('app.name') }} || Dashboard
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <!-- Header Background -->
            <div class="bg-primary pt-10 pb-21 mt-n6 mx-n4"></div>

            <div class="container-fluid mt-n22">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <!-- Page header -->
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="mb-2 mb-lg-0">
                                <h3 class="mb-0 text-white font-weight-bold">Summary Dashboard</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <!-- Property Owners Card -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <div class="card h-100 card-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0 text-muted">Total Property Porichlok</h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-primary text-primary rounded-2">
                                        <i class="bi bi-person-badge fs-4"></i>
                                    </div>
                                </div>
                                <div class="lh-1">
                                    <h1 class="fw-bold mb-1">{{ $propertyOwnerUser }}</h1>
                                    <p class="mb-0 text-muted">Total registered</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resident Users Card -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <div class="card h-100 card-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0 text-muted">Resident Users</h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-success text-success rounded-2">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                </div>
                                <div class="lh-1">
                                    <h1 class="fw-bold mb-1">{{ $residentUser }}</h1>
                                    <p class="mb-0 text-muted">Total registered</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Maintenance Card -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <div class="card h-100 card-lift border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0 text-muted">Maintenance</h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-warning text-warning rounded-2">
                                        <i class="bi bi-tools fs-4"></i>
                                    </div>
                                </div>
                                <div class="lh-1">
                                    <h1 class="fw-bold mb-1">{{ $totalMaintenance }}</h1>
                                    <p class="mb-0 text-muted">Overall requests</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Properties Card -->
                    <div class="col-xl-3 col-lg-6 col-md-12 col-12 mb-5">
                        <div class="card h-100 card-lift border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h4 class="mb-0 text-muted">Properties</h4>
                                    </div>
                                    <div class="icon-shape icon-md bg-light-danger text-danger rounded-2">
                                        <i class="bi bi-building fs-4"></i>
                                    </div>
                                </div>
                                <div class="lh-1">
                                    <h1 class="fw-bold mb-1">{{ $totalProperties }}</h1>
                                    <p class="mb-0 text-muted">Managed assets</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Emergencies -->
                    <div class="col-xl-6 col-12 mb-5">
                        <div class="card h-100">
                            <div
                                class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                                <h4 class="mb-0">Recent Emergencies</h4>
                                <span class="badge bg-danger rounded-pill">New</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 text-nowrap table-centered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Location/Property</th>
                                            <th>Category</th>
                                            <th>User</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($emergencies as $emergency)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-0">
                                                            <h5 class="mb-0 text-dark">
                                                                {{ $emergency->property->property_name ?? 'Location' }}</h5>
                                                            <p class="mb-0 small text-muted">{{ $emergency->location }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $emergency->category->name ?? 'Default' }}</td>
                                                <td>{{ $emergency->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm rounded-circle"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#emergencyModal{{ $emergency->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Emergency Modal -->
                                            <div class="modal fade" id="emergencyModal{{ $emergency->id }}" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Emergency Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($emergency->user)
                                                                <div class="mb-4 border-bottom pb-3">
                                                                    <h6
                                                                        class="text-muted mb-3 small text-uppercase fw-bold">
                                                                        Reported By</h6>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="{{ !empty($emergency->user->avatar) ? $emergency->user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($emergency->user->name) . '&background=random' }}"
                                                                            class="rounded-circle border"
                                                                            style="width: 60px; height: 60px; object-fit: cover;"
                                                                            alt="">
                                                                        <div class="ms-3">
                                                                            <h5 class="mb-0 text-dark">
                                                                                {{ $emergency->user->name }} <span
                                                                                    class="text-muted small">(#{{ $emergency->user->id }})</span>
                                                                            </h5>
                                                                            <div class="small text-muted mb-1">
                                                                                <i
                                                                                    class="bi bi-envelope-fill me-1"></i>{{ $emergency->user->email }}
                                                                                |
                                                                                <i
                                                                                    class="bi bi-telephone-fill me-1"></i>{{ $emergency->user->phone }}
                                                                            </div>
                                                                            <span
                                                                                class="badge bg-soft-primary text-primary text-uppercase"
                                                                                style="font-size: 0.7rem; background: #e0e7ff;">{{ str_replace('_', ' ', $emergency->user->role) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="mb-3">
                                                                <label class="text-muted small">Property</label>
                                                                <p class="fw-bold mb-0 text-primary">
                                                                    {{ $emergency->property->property_name ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-muted small">Location
                                                                    Description</label>
                                                                <p class="mb-0">{{ $emergency->location }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-muted small">Category</label>
                                                                <p class="mb-0">
                                                                    {{ $emergency->category->name ?? 'N/A' }}
                                                                </p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-muted small">Description</label>
                                                                <p class="mb-0">{{ $emergency->description }}</p>
                                                            </div>
                                                            <div class="d-flex justify-content-between mt-4">
                                                                <span class="text-muted small">Reported At:
                                                                    {{ $emergency->created_at->format('M d, Y h:i A') }}</span>
                                                                <span
                                                                    class="badge bg-danger">{{ strtoupper($emergency->status) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">No emergencies reported</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Properties -->
                    <div class="col-xl-6 col-12 mb-5">
                        <div class="card h-100">
                            <div class="card-header bg-white border-bottom">
                                <h4 class="mb-0">Latest Properties</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 text-nowrap table-centered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Units</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentProperties as $prop)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $prop->property_name }}</td>
                                                <td>{{ $prop->property_type }}</td>
                                                <td><span
                                                        class="badge bg-light-primary text-primary">{{ $prop->number_of_units }}</span>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-outline-info btn-sm rounded-circle"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#propModal{{ $prop->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Property Modal -->
                                            <div class="modal fade" id="propModal{{ $prop->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title text-white">Property Information</h5>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            @if ($prop->user)
                                                                <div class="mb-4 border-bottom pb-3">
                                                                    <h6
                                                                        class="text-muted mb-3 small text-uppercase fw-bold">
                                                                        Owner / Manager</h6>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="{{ !empty($prop->user->avatar) ? $prop->user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($prop->user->name) . '&background=random' }}"
                                                                            class="rounded-circle border"
                                                                            style="width: 55px; height: 55px; object-fit: cover;"
                                                                            alt="">
                                                                        <div class="ms-3">
                                                                            <h5 class="mb-0 text-dark">
                                                                                {{ $prop->user->name }} <span
                                                                                    class="text-muted small">(#{{ $prop->user->id }})</span>
                                                                            </h5>
                                                                            <div class="small text-muted mb-1">
                                                                                <i
                                                                                    class="bi bi-envelope-fill me-1"></i>{{ $prop->user->email }}
                                                                                |
                                                                                <i
                                                                                    class="bi bi-telephone-fill me-1"></i>{{ $prop->user->phone }}
                                                                            </div>
                                                                            <span
                                                                                class="badge bg-soft-info text-info text-uppercase"
                                                                                style="font-size: 0.7rem; background: #e0f2fe;">{{ str_replace('_', ' ', $prop->user->role) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <h4 class="mb-3 text-primary">{{ $prop->property_name }}</h4>
                                                            <div class="row mb-3">
                                                                <div class="col-6">
                                                                    <label class="text-muted small">Type</label>
                                                                    <p class="mb-0 fw-bold">{{ $prop->property_type }}</p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="text-muted small">Total Units</label>
                                                                    <p class="mb-0 fw-bold">{{ $prop->number_of_units }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 text-secondary">
                                                                <label class="text-muted small">Address</label>
                                                                <p class="mb-0">{{ $prop->property_address }}</p>
                                                            </div>
                                                            <div class="mb-0 border-top pt-3 text-muted small">
                                                                Added On: {{ $prop->created_at->toFormattedDateString() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">No properties found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-5">
                            <div class="card-header bg-white border-bottom">
                                <h4 class="mb-0">Recent Announcements</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @forelse($recentAnnouncements as $announcement)
                                        <div class="col-md-4 mb-3">
                                            <div class="border p-3 rounded-2 shadow-sm h-100 d-flex flex-column">
                                                <h5 class="text-primary mb-2 line-clamp-1">{{ $announcement->title }}</h5>
                                                <p class="text-muted small mb-3 flex-grow-1">
                                                    {{ \Illuminate\Support\Str::limit($announcement->message, 120) }}
                                                </p>
                                                <div
                                                    class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                                    <span class="small text-muted"><i
                                                            class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($announcement->publish_date)->format('M d, Y') }}</span>
                                                    <button type="button"
                                                        class="btn btn-link btn-sm p-0 m-0 text-decoration-none"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#annModal{{ $announcement->id }}">View
                                                        More</button>
                                                </div>
                                            </div>

                                            <!-- Announcement Modal -->
                                            <div class="modal fade" id="annModal{{ $announcement->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg">
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-5 pt-2">
                                                            @if ($announcement->user)
                                                                <div class="mb-4 d-flex justify-content-center">
                                                                    <div
                                                                        class="text-center p-3 bg-light rounded-3 border w-100">
                                                                        <img src="{{ !empty($announcement->user->avatar) ? $announcement->user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($announcement->user->name) . '&background=random' }}"
                                                                            class="rounded-circle border mb-2 shadow-sm"
                                                                            style="width: 70px; height: 70px; object-fit: cover;"
                                                                            alt="">
                                                                        <h5 class="mb-1 text-dark">
                                                                            {{ $announcement->user->name }}</h5>
                                                                        <p class="mb-1 small text-muted">
                                                                            <i
                                                                                class="bi bi-envelope-fill me-1"></i>{{ $announcement->user->email }}
                                                                            |
                                                                            <i
                                                                                class="bi bi-telephone-fill me-1"></i>{{ $announcement->user->phone }}
                                                                        </p>
                                                                        <span class="badge bg-primary text-uppercase"
                                                                            style="font-size: 0.65rem;">{{ str_replace('_', ' ', $announcement->user->role) }}</span>
                                                                        <div class="mt-1 small text-muted">User ID:
                                                                            #{{ $announcement->user->id }}</div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="text-center mb-4">
                                                                <div class="avatar avatar-lg bg-light-primary text-primary rounded-circle mb-3 mx-auto"
                                                                    style="height: 60px; width: 60px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="bi bi-megaphone fs-2"></i>
                                                                </div>
                                                                <h2 class="fw-bold">{{ $announcement->title }}</h2>
                                                                <span
                                                                    class="text-muted">{{ \Carbon\Carbon::parse($announcement->publish_date)->toFormattedDateString() }}</span>
                                                            </div>
                                                            <div class="announcement-content border-top pt-3">
                                                                {!! nl2br(e($announcement->message)) !!}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 justify-content-center pb-4">
                                                            <button type="button" class="btn btn-secondary px-5"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <p class="text-muted">No recent announcements found.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/app.js'])
@endpush
