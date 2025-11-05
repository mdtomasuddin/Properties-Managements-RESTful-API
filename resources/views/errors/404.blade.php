@extends('errors.app')

@section('title')
    {{ env('APP_NAME') }} || 404
@endsection

@section('content')
    <div class="text-center">
        <div class="mb-3">
            <!-- img -->
            <img src="{{asset('assets/backend/images/error/404-error-img.png')}}" alt="Image" class="img-fluid">
        </div>
        <!-- text -->
        <h1 class="display-4 ">Oops! the page not found.</h1>
        <p class="mb-4">Or simply leverage the expertise of our consultation
            team.</p>
        <!-- button -->
        <a href="{{route("dashboard")}}" class="btn btn-primary">Go Home</a>
    </div>
@endsection
