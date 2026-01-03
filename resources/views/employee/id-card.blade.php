@extends('layouts.app')

@section('content')
    <div class="container animate__animated animate__zoomIn">
        <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="text-center">

                <div id="id-card" class="bg-white shadow-lg overflow-hidden position-relative mx-auto mb-4"
                    style="width: 350px; height: 550px; border-radius: 20px;">
                    <!-- Header Design -->
                    <div class="bg-primary position-absolute top-0 w-100"
                        style="height: 150px; border-bottom-left-radius: 50% 30px; border-bottom-right-radius: 50% 30px;">
                    </div>

                    <div class="position-relative z-1 pt-5">
                        <!-- Profile Photo -->
                        <div class="bg-white p-1 rounded-circle shadow mx-auto mb-3 d-flex justify-content-center align-items-center"
                            style="width: 130px; height: 130px;">
                            <div class="bg-primary align-items-center d-flex justify-content-center rounded-circle text-white fw-bold display-3"
                                style="width: 120px; height: 120px;">
                                {{ substr($employee->user->name, 0, 1) }}
                            </div>
                        </div>

                        <h3 class="fw-bold text-dark mb-1">{{ $employee->user->name }}</h3>
                        <p class="text-secondary text-uppercase fw-bold small letter-spacing-2">{{ $employee->designation }}
                        </p>

                        <hr class="w-50 mx-auto my-4 opacity-10">

                        <div class="px-5 text-start">
                            <div class="mb-3">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">ID
                                    Number</small>
                                <div class="fw-bold">{{ $employee->employee_id }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted text-uppercase fw-bold"
                                    style="font-size: 0.65rem;">Department</small>
                                <div class="fw-bold">{{ $employee->department }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Join
                                    Date</small>
                                <div class="fw-bold">{{ $employee->joining_date }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="position-absolute bottom-0 w-100 bg-dark text-white py-3">
                        <small class="fw-bold letter-spacing-2">DAYFLOW SYSTEMS</small>
                    </div>
                </div>

                <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="fas fa-print me-2"></i> Print ID Card
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #id-card,
            #id-card * {
                visibility: visible;
            }

            #id-card {
                position: absolute;
                left: 0;
                top: 0;
            }

            .sidebar,
            .main-content {
                margin: 0;
                padding: 0;
            }
        }
    </style>
@endsection