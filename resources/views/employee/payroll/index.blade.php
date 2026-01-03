@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="fw-bold mb-4">My Salary Slips</h2>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Month</th>
                                    <th>Basic Salary</th>
                                    <th>Overtime</th>
                                    <th>Bonus</th>
                                    <th>Deductions</th>
                                    <th>Net Pay</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">{{ $payroll->month }}</td>
                                        <td>₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                        <td class="small">{{ $payroll->overtime_hours }} hrs</td>
                                        <td class="text-success">+₹{{ number_format($payroll->bonus, 2) }}</td>
                                        <td class="text-danger">-₹{{ number_format($payroll->deductions, 2) }}</td>
                                        <td class="fw-bold text-success">₹{{ number_format($payroll->net_salary, 2) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($payroll->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.payroll.download', $payroll->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i> Slip
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    {{ $payrolls->links() }}
                </div>
            </div>
@endsection