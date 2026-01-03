@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <div>
                <h2 class="fw-bold text-dark mb-1">My Team</h2>
                <p class="text-muted mb-0">Colleagues in <span class="fw-bold text-primary">{{ $department }}</span>
                    department</p>
            </div>
            <div class="bg-white p-2 rounded-pill shadow-sm border">
                <span class="badge bg-primary rounded-pill px-3 py-2">{{ count($colleagues) }} Members</span>
            </div>
        </div>

        @if(count($colleagues) > 0)
            <div class="row g-4 animate__animated animate__fadeInUp">
                @foreach($colleagues as $colleague)
                    <div class="col-md-6 col-xl-4">
                        <div class="glass-card h-100 p-4 position-relative overflow-hidden group-hover-effect">
                            <div class="d-flex align-items-center">
                                <!-- Avatar -->
                                <div class="position-relative">
                                    @if($colleague->employee && $colleague->employee->profile_photo)
                                        <img src="{{ Storage::url($colleague->employee->profile_photo) }}" alt="{{ $colleague->name }}"
                                            class="rounded-circle border border-3 border-white shadow-sm object-fit-cover" width="80"
                                            height="80">
                                    @else
                                        <div class="rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center border border-3 border-white shadow-sm"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-user text-white fa-2x"></i>
                                        </div>
                                    @endif
                                    <!-- Online Status (Simulated) -->
                                    <span
                                        class="position-absolute bottom-0 end-0 p-2 bg-success border border-white rounded-circle"></span>
                                </div>

                                <div class="ms-3 flex-grow-1">
                                    <h5 class="fw-bold mb-1">{{ $colleague->name }}</h5>
                                    <p class="text-muted small mb-2">{{ $colleague->employee->designation ?? 'Team Member' }}</p>

                                    <div class="d-flex gap-2">
                                        <a href="mailto:{{ $colleague->email }}" class="btn btn-light btn-sm rounded-circle"
                                            data-bs-toggle="tooltip" title="Email">
                                            <i class="fas fa-envelope text-secondary"></i>
                                        </a>
                                        @if($colleague->employee->phone)
                                            <a href="tel:{{ $colleague->employee->phone }}" class="btn btn-light btn-sm rounded-circle"
                                                data-bs-toggle="tooltip" title="Call">
                                                <i class="fas fa-phone text-secondary"></i>
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <!-- Background Decoration -->
                            <div class="position-absolute top-0 end-0 opacity-10 p-3">
                                <i class="fas fa-users fa-5x"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass-card p-5 text-center animate__animated animate__fadeIn">
                <div class="mb-3">
                    <i class="fas fa-user-friends fa-4x text-muted opacity-50"></i>
                </div>
                <h4 class="fw-bold text-secondary">It's quiet here...</h4>
                <p class="text-muted">You are the only member in the {{ $department }} department currently.</p>
            </div>
        @endif
    </div>
@endsection