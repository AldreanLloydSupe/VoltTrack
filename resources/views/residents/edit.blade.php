@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Breadcrumb -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Residents</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Account</li>
            </ol>
        </nav>
    </div>

    <!-- Success Message -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Profile Section -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <strong>Profile</strong>
            </h5>

            <form action="{{ route('residents.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label text-uppercase text-muted small">
                            <strong>First Name</strong>
                        </label>
                        <input type="text"
                               class="form-control @error('first_name') is-invalid @enderror"
                               id="first_name"
                               name="first_name"
                               value="{{ old('first_name', $user->first_name) }}"
                               required>
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label text-uppercase text-muted small">
                            <strong>Last Name</strong>
                        </label>
                        <input type="text"
                               class="form-control @error('last_name') is-invalid @enderror"
                               id="last_name"
                               name="last_name"
                               value="{{ old('last_name', $user->last_name) }}"
                               required>
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label text-uppercase text-muted small">
                            <strong>Phone Number</strong>
                        </label>
                        <input type="tel"
                               class="form-control @error('phone_number') is-invalid @enderror"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number', $user->phone_number) }}"
                               required>
                        @error('phone_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="unit_id" class="form-label text-uppercase text-muted small">
                            <strong>Unit ID</strong>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="unit_id"
                               value="{{ $user->property ? $user->property->property_unit_id : 'N/A' }}"
                               readonly>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="email" class="form-label text-uppercase text-muted small">
                            <strong>Email Address</strong>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-uppercase text-muted small">
                            <strong>Gender</strong>
                        </label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gender') is-invalid @enderror"
                                       type="radio"
                                       name="gender"
                                       id="gender_male"
                                       value="Male"
                                       {{ old('gender', $user->gender) === 'Male' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_male">
                                    Male
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gender') is-invalid @enderror"
                                       type="radio"
                                       name="gender"
                                       id="gender_female"
                                       value="Female"
                                       {{ old('gender', $user->gender) === 'Female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_female">
                                    Female
                                </label>
                            </div>
                            @error('gender')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">Apply Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Meter Network Section -->
    @if ($meters->count() > 0 || ($user->property && $user->property->meters()->count() > 0))
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <strong>Meter Network</strong>
                </h5>

                <div class="row">
                    @forelse ($meters as $meter)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 position-relative"
                                 style="border-left: 4px solid {{ $meter->utility_type === 'Electricity' ? '#FF6B5B' : '#4A90E2' }}">
                                <div class="text-uppercase text-muted small mb-2">
                                    <strong>{{ $meter->utility_type }}</strong>
                                </div>
                                <div class="mb-2">
                                    <strong>{{ $meter->hardware_meter_number ?? $meter->serial_number }}</strong>
                                </div>
                                <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                    <a href="#" class="text-danger" style="text-decoration: none; font-size: 18px;">
                                        🗑️
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">No meters assigned to this property.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        color: #666;
        font-size: 12px;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .btn-primary {
        background-color: #003366;
        border-color: #003366;
    }

    .btn-primary:hover {
        background-color: #002244;
        border-color: #002244;
    }
</style>
@endsection
