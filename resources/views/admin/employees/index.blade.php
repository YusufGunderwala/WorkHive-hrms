@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Employee Management</h2>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add Employee
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $emp->employee_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light rounded-circle text-primary d-flex align-items-center justify-content-center me-2"
                                            style="width: 32px; height: 32px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="fw-medium">{{ $emp->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $emp->user->email }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $emp->department }}</span></td>
                                <td>{{ ucfirst($emp->user->role) }}</td>
                                <td>
                                    <a href="{{ route('admin.employees.edit', $emp->id) }}"
                                        class="btn btn-sm btn-outline-info me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $employees->links() }}
        </div>
    </div>
@endsection