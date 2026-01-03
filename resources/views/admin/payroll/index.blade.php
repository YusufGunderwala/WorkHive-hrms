@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Payroll</h2>

        <div class="card shadow-sm border-0">
            <div class="card-body py-2">
                <form action="{{ route('admin.payroll.generate') }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <label class="small fw-bold text-muted mb-0">Generate for:</label>
                    <input type="month" name="month" class="form-control form-control-sm" value="{{ date('Y-m') }}"
                        required>
                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                        <i class="fas fa-cog me-1"></i> Generate
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Month</th>
                            <th>Employee</th>
                            <th>Basic Salary</th>
                            <th>Overtime</th>
                            <th>Bonus</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $payroll->month }}</td>
                                <td class="fw-medium">{{ $payroll->user->name }}</td>
                                <td>₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                <td class="small">{{ $payroll->overtime_hours }} hrs</td>
                                <td class="text-success">+₹{{ number_format($payroll->bonus, 2) }}</td>
                                <td class="text-danger">-₹{{ number_format($payroll->deductions, 2) }}
                                    @if($payroll->late_instances >= 3)
                                        <div class="small text-muted" style="font-size: 0.7em;">
                                            ({{ floor($payroll->late_instances / 3) }} Day(s) Salary Cut)
                                        </div>
                                    @elseif($payroll->late_instances > 0)
                                        <div class="small text-muted" style="font-size: 0.7em;">
                                            ({{ $payroll->late_instances }} Late - No Cut Yet)
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">₹{{ number_format($payroll->net_salary, 2) }}</td>
                                <td>
                                    <form action="{{ route('admin.payroll.updateStatus', $payroll->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                            class="form-select form-select-sm {{ $payroll->status === 'paid' ? 'bg-success text-white' : 'bg-secondary text-white' }} border-0"
                                            onchange="this.form.submit()" style="width: 100px;">
                                            <option value="unpaid" class="bg-white text-dark" {{ $payroll->status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="paid" class="bg-white text-dark" {{ $payroll->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </form>
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