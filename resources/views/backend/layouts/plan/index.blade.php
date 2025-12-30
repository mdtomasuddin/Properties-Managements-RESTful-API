@extends('backend.app')

@section('title')
    {{ env('APP_NAME') }} || Pricing Plans Management
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .plan-card {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: #ffffff;
            position: relative;
            z-index: 1;
            overflow: hidden;
            border: 1px solid #e3e6f0;
        }

        /*Gradient effect coming from the side*/
        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            /* Will be on the left at the beginning */
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(13, 110, 253, 0.04), rgba(102, 16, 242, 0.04));
            transition: all 0.6s ease;
            z-index: -1;
        }

        /* The gradient will come in when you hover. */
        .plan-card:hover::before {
            left: 0;
        }

        /* The gradient will come in when you hover. */
        .plan-card:hover .ri-checkbox-circle-fill {
            transform: scale(1.2);
            transition: 0.3s;
        }

        /* Card header text hover */
        .plan-card:hover h4 {
            color: #0d6efd;
            transition: 0.3s;
        }
    </style>
@endpush

@section('content')
    <div id="app-content">
        <div class="app-content-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-10 col-md-12 col-12 text-center text-xl-start">
                        {{-- Header Section --}}
                        <div id="validation" class="mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="h3 mb-1">Pricing Plans</h2>
                                <p class="text-muted">Manage and update your subscription plans.</p>
                            </div>
                        </div>

                        {{-- Plans Grid --}}
                        <div class="row g-4 ms-5">
                            @foreach ($plans as $plan)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 plan-card shadow-sm border-0 position-relative">
                                        {{-- Status Badge --}}
                                        <div class="position-absolute top-0 fs-4 end-0 mt-3 me-3">
                                            <span
                                                class="badge rounded-pill bg-{{ $plan->status === 'active' ? 'success' : 'danger' }}-soft text-{{ $plan->status === 'active' ? 'success' : 'danger' }} px-3">
                                                {{ ucfirst($plan->status) }}
                                            </span>
                                        </div>

                                        <div class="card-body p-4 d-flex flex-column">
                                            <div class="mb-3">
                                                <h4 class="fw-bold mb-1">{{ $plan->name }}</h4>
                                                <div class="d-flex align-items-baseline">
                                                    <h3 class="fw-bold mb-0">
                                                        {{ $plan->currency }} {{ number_format($plan->price, 2) }}</h3>
                                                    <span class="text-muted ms-1 small">/
                                                        {{ str_replace('_', ' ', $plan->billing_interval) }}</span>
                                                </div>
                                                <p class="text-muted mt-2 small">
                                                    {{ $plan->description ?: 'No description provided.' }}
                                                </p>
                                            </div>

                                            <hr class="my-3 opacity-10">

                                            {{-- Features List --}}
                                            <div class="mb-4">
                                                <h4 class="text-uppercase text-primary mb-3 fw-bold">Plan Included Features:
                                                </h4>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($plan->features ?? [] as $feature)
                                                        <li class="d-flex align-items-center small">
                                                            <i
                                                                class="ri-checkbox-circle-fill text-success me-2 fs-5 mt-1"></i>
                                                            <span class="text-secondary">{{ $feature }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            {{-- Actions --}}
                                            <div class="mt-auto d-grid gap-2">
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $plan->id }}">
                                                    <i class="ri-edit-line me-1"></i> Edit Plan
                                                </button>

                                                <form action="{{ route('plan.toggle-status', $plan->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm w-100 {{ $plan->status === 'active' ? 'btn-light text-danger' : 'btn-light text-success' }}">
                                                        {{ $plan->status === 'active' ? 'Deactivate Plan' : 'Activate Plan' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $plan->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form method="POST" action="{{ route('plan.update', $plan->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold">upgrade Plan: {{ $plan->name }}</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Plan Name</label>
                                                            <input type="text" name="name"
                                                                value="{{ old('name', $plan->name) }}" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Billing Interval</label>
                                                            <select name="billing_interval" class="form-select"
                                                                {{ $plan->price ? 'disabled' : '' }}>
                                                                <option value="monthly" @selected($plan->billing_interval === 'monthly')>Monthly
                                                                </option>

                                                            </select>
                                                            @if ($plan->price)
                                                                <input type="hidden" name="billing_interval"
                                                                    value="{{ $plan->billing_interval }}">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Price</label>
                                                            <input type="number" step="0.01" name="price"
                                                                class="form-control"
                                                                value="{{ old('price', $plan->price) }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Currency</label>
                                                            <input type="text" name="currency" class="form-control"
                                                                value="{{ old('currency', $plan->currency) }}"
                                                                {{ $plan->price ? 'readonly' : '' }}>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option value="active" @selected($plan->status === 'active')>Active
                                                                </option>
                                                                <option value="inactive" @selected($plan->status === 'inactive')>
                                                                    Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control" rows="2">{{ old('description', $plan->description) }}</textarea>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label fw-semibold">Features (comma
                                                                separated):</label>
                                                            <input type="text" name="features" class="form-control"
                                                                value="{{ is_array($plan->features) ? implode(', ', $plan->features) : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-0">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary px-4">Save
                                                        Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-xl-2 d-none d-xl-block">
                        <div class="sticky-top" style="top: 2rem;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
