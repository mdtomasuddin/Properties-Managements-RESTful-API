@extends('backend.app')


@section('title')
    {{ env('APP_NAME') }} || Profile
@endsection

@push('styles')
    <style>
        .dt-info {
            display: flex;
            justify-content: end;
            padding-bottom: 10px;
            padding-right: 25px;
        }

        .paging_full_numbers {
            display: flex;
            justify-content: end;
            padding-bottom: 10px;
            padding-right: 20px;
        }
    </style>
@endpush

@section('content')
    <div id="overlay"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.5); z-index:9999;">
    </div>
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                        <!-- Bg -->
                        <div class="pt-20 rounded-top"
                            style="background: url({{ asset('assets/backend/images/background/profile-bg.jpg') }}) no-repeat; background-size: cover;">
                        </div>
                        <div class="card rounded-bottom rounded-0 smooth-shadow-sm mb-5">
                            <div class="d-flex align-items-center justify-content-between pt-4 pb-6 px-4">
                                <div class="d-flex align-items-center">
                                    <!-- avatar -->
                                    <div
                                        class="avatar-xxl me-2 position-relative d-flex justify-content-end align-items-end mt-n10">
                                        <img src="{{ $user->avatar }}" class="avatar-xxl rounded-circle border border-2 "
                                            alt="Image">
                                        <div class="position-absolute top-0 right-0 me-2">
                                            @if ($user->status)
                                                <img src="{{ asset('assets/backend/images/svg/checked-mark.svg') }}"
                                                    alt="Image" class="icon-sm">
                                            @endif
                                        </div>
                                    </div>
                                    <!-- text -->
                                    <div class="lh-1">
                                        <h2 class="mb-0">
                                            {{ $user->first_name ?? 'N/A' }} {{ $user->last_name ?? 'N/A' }}
                                        </h2>
                                        <p class="mb-0 d-block">@i{{ $user->handle ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div>
                                    {{-- <a href="#!" class="btn btn-outline-primary d-none d-md-block">Edit Profile</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- content --}}
                <div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-xl-6 col-lg-12 col-md-12 col-12 mb-5">
                            <!-- card -->
                            <div class="card h-100">
                                <!-- card body -->
                                <div class="card-header">
                                    <h4 class="mb-0">About Me</h4>
                                </div>
                                <div class="card-body">
                                    <!-- row -->
                                    <div class="row">
                                        <div class="col-12 mb-5">
                                            <!-- text -->
                                            <h5 class="text-uppercase">Email</h5>
                                            <p class="mb-0">{{ $user->email }}</p>
                                        </div>
                                        <div class="col-6 mb-5">
                                            <h5 class="text-uppercase">Phone</h5>
                                            <p class="mb-0">{{ $user->profile->phone ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-6 mb-5">
                                            <h5 class="text-uppercase">
                                                Date of Birth
                                            </h5>
                                            <p class="mb-0">{{ $user->profile->date_of_birth ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-uppercase">Gender</h5>
                                            <p class="mb-0">{{ $user->profile->gender ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-uppercase">Role</h5>
                                            <p class="mb-0">{{ $user->role->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-12">
                                            <h5 class="text-uppercase">Stripe Customer ID</h5>
                                            <p class="mb-0">{{ $user->role->stripe_customer_id ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-uppercase">Created At</h5>
                                            <p class="mb-0">
                                                {{ $user->role->created_at ? $user->role->created_at . ' (' . $user->role->created_at->diffForHumans() . ')' : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-uppercase">Last Updated AT</h5>
                                            <p class="mb-0">
                                                {{ $user->role->updated_at ? $user->role->updated_at . ' (' . $user->role->updated_at->diffForHumans() . ')' : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 col-md-12 col-12 mb-5">
                            <!-- card -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="mb-0">Properties</h4>
                                </div>
                                <!-- card body -->
                                <div class="card-body">
                                    <!-- card title -->
                                        <div class="d-md-flex justify-content-between align-items-center mb-4">
                                            <div class="d-flex align-items-center gap-10">
                                                <!-- text -->
                                                <div class="ms-3">
                                                    <h5 class="mb-1">
                                                        <a class="text-inherit"></a>
                                                    </h5>
                                                    <p class="mb-0 fs-5 text-muted">
                                                    </p>
                                                </div>
                                                <div class="ms-3">
                                                    <h5 class="mb-1">
                                                        <a class="text-inherit"></a>
                                                    </h5>
                                                    <p class="mb-0 fs-5 text-muted">

                                                    </p>
                                                </div>
                                                <div class="ms-3">
                                                    <h5 class="mb-1">
                                                        <a class="text-inherit"></a>
                                                    </h5>
                                                    <p class="mb-0 fs-5 text-muted">

                                                    </p>
                                                </div>
                                                <div class="ms-3">
                                                    <h5 class="mb-1">
                                                        <a class="text-inherit">Subscription: </a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- {{ $properties->links() }} --}}
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
@endpush
