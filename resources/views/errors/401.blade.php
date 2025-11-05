@extends('errors.app')

@section('title')
    {{ env('APP_NAME') }} || 401
@endsection

@section('content')
    <div class="text-center">
        <div class="mb-3">
            <!-- img -->
            <img src="{{asset('assets/custom/svg/401.svg')}}" alt="Image" class="img-fluid">
        </div>
        <!-- text -->
        <h1 class="display-4 ">Oops! you are not authorized.</h1>
        <p class="mb-4">Or simply leverage the expertise of our consultation
            team.</p>
        <!-- button -->
        <a href="{{route("dashboard")}}" class="btn btn-primary">Go Home</a>
    </div>
@endsection
