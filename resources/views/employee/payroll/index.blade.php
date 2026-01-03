@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12"> <!-- Expanded width for better table view -->
            <x-employee-header 
                title="My Salary Slips" 
                description="View and download your monthly salary slips." 
            />

            <!-- Glass Table Card -->
            <div class="glass-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-dark text-white">
                            <tr style="border-bottom: 2px solid rgba(0,0,0,0.05);">
                                <th class="ps-4 py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Month</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Basic Salary</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Overtime</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Bonus</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Deductions</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Net Pay</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Status</th>
                                <th class="py-3 fw-semibold text-uppercase small" style="letter-spacing: 0.5px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white bg-opacity-50">
                            @foreach($payrolls as $payroll)
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 fw-bold text-dark py-3">{{ $payroll->month }}</td>
                                    <td class="text-secondary">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                    <td class="small text-muted">
                                        @if($payroll->overtime_hours > 0)
                                            <span class="text-success fw-bold">{{ $payroll->overtime_hours }} hrs</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-success fw-bold">+₹{{ number_format($payroll->bonus, 2) }}</td>
                                    <td class="text-danger fw-bold">-₹{{ number_format($payroll->deductions, 2) }}</td>
                                    <td class="fw-bold text-success fs-6">₹{{ number_format($payroll->net_salary, 2) }}</td>
                                    <td>
                                        @if($payroll->status == 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-10">Paid</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 border border-secondary border-opacity-10">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('employee.payroll.download', $payroll->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none">
                                            <i class="fas fa-download me-1"></i> Slip
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="p-3 border-top border-light border-opacity-50">
                    {{ $payrolls->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection