@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Create FAQ
@endsection

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-9 col-md-12 col-sm-12 col-12">
                        <div class="row">
                            <div class="col-xl-12">
                                <div id="validation" class="mb-4">
                                    <h2 class="h3 mb-1">Create FAQ</h2>
                                    <p>This form will allow you to create a FAQ entry</p>
                                </div>

                                <div class="card mb-10">
                                    <div class="tab-content p-4">
                                        <form class="row g-3" action="{{ route('faq.store') }}" method="POST">
                                            @csrf
                                            <div class="col-md-12">
                                                <label for="question" class="form-label">Question*</label>
                                                <input type="text"
                                                    class="form-control @error('question') is-invalid @enderror"
                                                    id="question" name="question" placeholder="Enter your question"
                                                    value="{{ old('question') }}" required>
                                                @error('question')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <label for="answer" class="form-label">Answer*</label>
                                                <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer"
                                                    placeholder="Enter your answer" rows="4" required>{{ old('answer') }}</textarea>
                                                @error('answer')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <button class="btn btn-primary" type="submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar (optional) --}}
                    <div class="col-xl-2 d-none d-xl-block position-fixed end-0">
                        <div class="sidebar-nav-fixed">
                            <span class="px-4 mb-2 d-block text-uppercase ls-md h3 fs-6">Contents</span>
                            <ul class="list-unstyled">
                                <li><a href="#validation">FAQ Form</a></li>
                                <li><a href="{{ route('faq.index') }}">FAQ Back</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Add any additional JS here --}}
@endpush
