@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Apply for Leave</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('employee.leaves.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Leave Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="sick">Sick Leave (Medical)</option>
                                    <option value="casual">Casual Leave</option>
                                    <option value="earned">Earned Leave (Privilege)</option>
                                    <option value="unpaid">Unpaid Leave (Loss of Pay)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employee.leaves.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection