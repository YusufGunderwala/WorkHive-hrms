@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Edit Employee: {{ $employee->user->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $employee->user->name }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email (Read Only)</label>
                                <input type="email" class="form-control bg-light" value="{{ $employee->user->email }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <select name="department" class="form-select" required>
                                    <option value="HR" {{ $employee->department == 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="IT" {{ $employee->department == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="Finance" {{ $employee->department == 'Finance' ? 'selected' : '' }}>Finance
                                    </option>
                                    <option value="Sales" {{ $employee->department == 'Sales' ? 'selected' : '' }}>Sales
                                    </option>
                                    <option value="Operations" {{ $employee->department == 'Operations' ? 'selected' : '' }}>
                                        Operations</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Designation</label>
                                <select name="designation" class="form-select" required>
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $d)
                                        <option value="{{ $d->name }}" {{ $employee->designation == $d->name ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Base Salary</label>
                                <input type="number" name="base_salary" class="form-control"
                                    value="{{ $employee->base_salary }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $employee->address }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection