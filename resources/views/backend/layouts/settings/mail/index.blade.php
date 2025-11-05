@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Dashboard
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <!-- Container fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class=" col-xl-9 col-md-12 col-sm-12 col-12 ">
                        <!-- Content -->
                        <div class="">
                            <!-- validation -->
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div id="validation" class="mb-4">
                                        <h2 class="h3 mb-1">Email SMTP Settings</h2>
                                        <p>
                                            Please provide your email SMTP to activate your email functionality. Without
                                            this, the email sending feature will not work.
                                        </p>
                                    </div>
                                    <!-- Card -->
                                    <div class="mb-10 card">
                                        <!-- Tab content -->
                                        <div class="tab-content p-4" id="pills-tabContent-validation">
                                            <!-- Validation Form -->
                                            <form class="row g-3 needs-validation"
                                                action="{{ route('v1.setting.mail.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-md-4">
                                                    <label for="mail_mailer" class="form-label">Mail Mailer</label>
                                                    <input type="text" class="form-control" id="mail_mailer"
                                                        name="mail_mailer" value="{{ env('MAIL_MAILER') }}"
                                                        placeholder="smtp">
                                                    @error('mail_mailer')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="mail_host" class="form-label">Mail Host</label>
                                                    <input type="text" class="form-control" id="mail_host"
                                                        name="mail_host" value="{{ env('MAIL_HOST') }}"
                                                        placeholder="mail.domain.com">
                                                    @error('mail_host')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="mail_port" class="form-label">Mail Port</label>
                                                    <input type="text" class="form-control" id="mail_port"
                                                        name="mail_port" value="{{ env('MAIL_PORT') }}" placeholder="468">
                                                    @error('mail_port')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="mail_username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="mail_username"
                                                        name="mail_username" value="{{ env('MAIL_USERNAME') }}"
                                                        placeholder="mail_username">
                                                    @error('mail_username')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="mail_password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="mail_password"
                                                        name="mail_password" placeholder="**********"
                                                        value="{{ env('MAIL_PASSWORD') }}">
                                                    @error('mail_password')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="mail_encryption" class="form-label">Encryption</label>
                                                    <input type="text" class="form-control" id="mail_encryption"
                                                        name="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}"
                                                        placeholder="ssl">
                                                    @error('mail_encryption')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="mail_address" class="form-label">Mail Address</label>
                                                    <input type="text" class="form-control" id="mail_address"
                                                        name="mail_address" value="{{ env('MAIL_FROM_ADDRESS') }}"
                                                        placeholder="yourmail@mail.com">
                                                    @error('mail_address')
                                                        <div class="validation-error">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="condition" name="condition">
                                                        <label class="form-check-label" for="condition">
                                                            This SMTP is mine and saved to use
                                                        </label>
                                                        @error('condition')
                                                            <div class="validation-error">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- validation -->
                        </div>

                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12  d-none d-xl-block position-fixed end-0">
                        <!-- Sidebar nav fixed -->
                        <div class="sidebar-nav-fixed">
                            <span class="px-4 mb-2 d-block text-uppercase ls-md h3 fs-6">Contents</span>
                            <ul class="list-unstyled">
                                <li><a href="#validation">Validation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
