@extends('backend.app')

@section('title')
    {{ config('app.name') }} || Profile Settings
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <h2 class="h3 mb-0">Adminstrator Profile Settings</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Profile Settings</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- Left Side: Profile Overview --}}
                    <div class="col-xl-4 col-lg-5">
                        <div class="card border-0 shadow-sm text-center p-4">
                            <div class="card-body">
                                <div class="position-relative d-inline-block mb-3">
                                    <img id="profile-display"
                                        src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                        class="rounded-circle border border-4 border-white shadow-sm"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <h4 class="mb-0">{{ $user->name }}</h4>
                                <p class="text-muted small">{{ ucfirst($user->role ?? 'N/A') }}</p>
                                <hr>

                                <div class="mt-4">
                                    {{-- Email Item --}}
                                    <div class="p-3 mb-2 bg-light rounded-3 border-0">
                                        <div class="row align-items-center">
                                            <div class="col-4 border-end">
                                                <span class="text-muted small fw-bold text-uppercase">Email</span>
                                            </div>
                                            <div class="col-8">
                                                <span class="text-dark small fw-semibold ps-2">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Phone Item --}}
                                    <div class="p-3 mb-2 bg-light rounded-3 border-0">
                                        <div class="row align-items-center">
                                            <div class="col-4 border-end">
                                                <span class="text-muted small fw-bold text-uppercase">Phone</span>
                                            </div>
                                            <div class="col-8">
                                                <span
                                                    class="text-dark small fw-semibold ps-2">{{ $user->phone ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Address Item --}}
                                    <div class="p-3 bg-light rounded-3 border-0">
                                        <div class="row align-items-center">
                                            <div class="col-4 border-end">
                                                <span class="text-muted small fw-bold text-uppercase">Address</span>
                                            </div>
                                            <div class="col-8">
                                                <span
                                                    class="text-dark small fw-semibold ps-2">{{ $user->address ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Update Form --}}
                    <div class="col-xl-8 col-lg-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Edit Personal Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('profile.update', $user->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-3">
                                        {{-- Name --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Full Name</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}" placeholder="Enter your name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Email Address</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}" placeholder="Enter email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Phone --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Phone Number</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">
                                        </div>

                                        {{-- Avatar --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Profile Picture</label>
                                            <input type="file" name="avatar" id="avatar-input"
                                                class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                            <small class="text-muted">Recommended: Square image, max 5MB</small>
                                            @error('avatar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Address --}}
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Address</label>
                                            <input type="address" name="address"
                                                class="form-control @error('address') is-invalid @enderror"
                                                value="{{ old('address', $user->address) }}" placeholder="Enter address">

                                        </div>

                                        <div class="col-12 mt-4">
                                            <h5 class="border-bottom pb-2">Change Password </h5>
                                            <p class="text-muted small">Leave the password field empty if you don't want to
                                                change the password</p>
                                        </div>

                                        {{-- Password --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">New Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="••••••••">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePassword('password', 'password-icon')">
                                                    <i class="fa-regular fa-eye" id="password-icon"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Confirm Password --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Confirm New Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control"
                                                    placeholder="••••••••">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePassword('password_confirmation', 'confirm-icon')">
                                                    <i class="fa-regular fa-eye" id="confirm-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <button type="reset" class="btn btn-light px-4 me-2">Reset</button>
                                        <button type="submit" class="btn btn-primary px-5">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    {{-- JavaScript for Image Preview --}}
    <script>
        document.getElementById('avatar-input').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('profile-display').src = URL.createObjectURL(file);
            }
        };

        // password show
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                //icon change check 
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = "password";
                //icon change check 
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
