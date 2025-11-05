@extends('backend.layouts.auth.app')

@section('title')
    {{ env('APP_NAME') }} || Verify Email
@endsection

@section('main')
    <main class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center g-0
        min-vh-100">
            <div class="col-12 col-md-8 col-lg-6 col-xxl-4 py-8 py-xl-0">
                <a href="#" class="form-check form-switch theme-switch btn btn-light btn-icon rounded-circle d-none ">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault"></label>

                </a>
                <!-- Card -->
                <div class="card smooth-shadow-md">
                    <!-- Card body -->
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <a href="../index-2.html"><img src="{{ asset('assets/backend/images/brand/logo/logo-2.svg') }}"
                                    class="mb-2  text-inverse" alt="Image"></a>
                            <p class="mb-6">Don't worry, we'll send you an email to reset your password.
                            </p>
                            @if (session('status') == 'verification-link-sent')
                                <div class="mb-4 font-medium text-sm text-success">
                                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                </div>
                            @endif
                        </div>
                        <!-- Resend Verification Email -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div class="mb-3 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Resend Verification Email
                                </button>
                            </div>
                        </form>

                        <!-- logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="mb-3 d-grid">
                                <button type="submit" class="btn btn-secondary">
                                    Logout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
