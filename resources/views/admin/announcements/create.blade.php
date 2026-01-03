@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light me-3 shadow-sm rounded-circle"
                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">Create Announcement</h2>
            </div>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i> New Announcement Details</h5>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <form action="{{ route('admin.announcements.store') }}" method="POST">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small text-muted">Title</label>
                            <input type="text" name="title" class="form-control form-control-lg bg-light border-0"
                                placeholder="e.g., Office Holiday Party" required>
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase small text-muted">Content</label>
                            <textarea name="content" rows="4" class="form-control bg-light border-0"
                                placeholder="Write your announcement here..." required></textarea>
                        </div>

                        <div class="row">
                            <!-- Department -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-uppercase small text-muted">Target Audience</label>
                                <select name="department" class="form-select form-select-lg bg-light border-0" required>
                                    <option value="All">All Departments (Global)</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }} Department</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type/Priority -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-uppercase small text-muted">Announcement Type</label>
                                <select name="type" class="form-select form-select-lg bg-light border-0" required>
                                    <option value="info">ℹ️ General Info</option>
                                    <option value="warning">⚠️ Warning / Important</option>
                                    <option value="urgent">🔥 Urgent / Critical</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Dates -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-uppercase small text-muted">Publish Date</label>
                                <input type="date" name="start_date" class="form-control form-control-lg bg-light border-0"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-uppercase small text-muted">Expiry Date</label>
                                <input type="date" name="end_date" class="form-control form-control-lg bg-light border-0"
                                    required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Publish Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection