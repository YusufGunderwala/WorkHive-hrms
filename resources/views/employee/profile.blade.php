@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-4">My Profile</h2>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 64px; height: 64px; font-size: 1.5rem;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">{{ Auth::user()->name }}</h4>
                            <p class="text-muted mb-0">{{ $employee->designation }} | {{ $employee->department }}</p>
                        </div>
                    </div>

                    <form action="{{ route('employee.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Employee ID</label>
                                <input type="text" class="form-control bg-light" value="{{ $employee->employee_id }}"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Joining Date</label>
                                <input type="text" class="form-control bg-light" value="{{ $employee->joining_date }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Email</label>
                                <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ $employee->address }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection