<!-- Libs CSS -->
<link href="{{ asset('assets/backend/libs/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/backend/libs/%40mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/backend/libs/simplebar/dist/simplebar.min.css') }}" rel="stylesheet">

<!-- Theme CSS -->
<link rel="stylesheet" href="{{ asset('assets/backend/css/theme.min.css') }}">



{{-- development ................................................. --}}
{{-- toastr --}}
<link rel="stylesheet" href="{{ asset('assets/custom/css/toastr.css') }}">
{{-- push --}}
</script>


{{-- dropify --}}
<link rel="stylesheet" href="{{ asset('assets/custom/css/dropify.min.css') }}">


{{-- sweet alert --}}
<style>
    .my-popup-class {
        background-color: #fffefe;
        border-radius: 10px;
        border: 2px solid #f5f7fa;
    }

    .my-title-class {
        color: #e5780b;
        font-size: 24px;
    }

    .my-content-class {
        color: #003cc7 !important;
        font-size: 16px;
    }

    .my-confirm-button-class {
        background-color: #25b003;
        color: black;
        border-radius: 5px;
        border: none;
    }
</style>

@stack('styles')



{{-- custom style --}}
<style>
    :root {
        --danger: #dc3545;
    }

    .validation-error {
        width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--danger);
    }
</style>
