<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->month }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .payslip-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .company-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .table-borderless td {
            padding: 5px 0;
        }

        .earnings-table th,
        .deductions-table th {
            text-transform: uppercase;
            font-size: 0.8em;
            letter-spacing: 1px;
            background-color: #f8f9fa;
        }

        .net-pay-section {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .payslip-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="payslip-container">
            <!-- Header -->
            <div class="company-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary m-0">Dayflow INC.</h2>
                    <p class="text-muted small m-0">123 Business Park, Innovation Drive</p>
                    <p class="text-muted small m-0">Mumbai, India - 400001</p>
                </div>
                <div class="text-end">
                    <h1 class="fw-bold m-0" style="color: #ddd;">PAYSLIP</h1>
                    <p class="fw-bold text-dark m-0">{{ $payroll->month }}</p>
                </div>
            </div>

            <!-- Employee Details -->
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="text-uppercase text-muted fw-bold small">Employee Details</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" width="120">Name:</td>
                            <td class="fw-bold">{{ $payroll->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Employee ID:</td>
                            <td class="fw-bold">{{ $payroll->user->employee->employee_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Designation:</td>
                            <td class="fw-bold">{{ $payroll->user->employee->designation ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Department:</td>
                            <td class="fw-bold">{{ $payroll->user->employee->department ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <h6 class="text-uppercase text-muted fw-bold small">Pay Summary</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" width="120">Pay Date:</td>
                            <td class="fw-bold">{{ $payroll->created_at->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Month:</td>
                            <td class="fw-bold">{{ $payroll->month }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td><span class="badge bg-success text-uppercase">{{ $payroll->status }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Earnings & Deductions -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-header bg-transparent border-0 fw-bold text-success">
                            <i class="fas fa-arrow-up me-2"></i> EARNINGS
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0 earnings-table">
                                <thead>
                                    <tr>
                                        <th class="ps-3 border-0">Component</th>
                                        <th class="text-end pe-3 border-0">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-3">Basic Salary</td>
                                        <td class="text-end pe-3">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-3">
                                            Overtime Bonus
                                            <small class="text-muted d-block">({{ $payroll->overtime_hours }}
                                                hrs)</small>
                                        </td>
                                        <td class="text-end pe-3">₹{{ number_format($payroll->bonus, 2) }}</td>
                                    </tr>
                                    <!-- Add Allowance placeholders if any -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="ps-3 fw-bold">Total Earnings</td>
                                        <td class="text-end pe-3 fw-bold">
                                            ₹{{ number_format($payroll->basic_salary + $payroll->bonus, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-header bg-transparent border-0 fw-bold text-danger">
                            <i class="fas fa-arrow-down me-2"></i> DEDUCTIONS
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0 deductions-table">
                                <thead>
                                    <tr>
                                        <th class="ps-3 border-0">Component</th>
                                        <th class="text-end pe-3 border-0">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-3">
                                            Total Deductions
                                            @if($payroll->late_instances > 0)
                                                <small class="text-danger d-block">({{ $payroll->late_instances }} Late
                                                    Instances)</small>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">₹{{ number_format($payroll->deductions, 2) }}</td>
                                    </tr>
                                    <!-- Tax placeholders etc -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="ps-3 fw-bold">Total Deductions</td>
                                        <td class="text-end pe-3 fw-bold">₹{{ number_format($payroll->deductions, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Pay -->
            <div class="net-pay-section d-flex justify-content-between align-items-center shadow-sm">
                <div>
                    <h5 class="m-0 text-white-50">NET PAYABLE</h5>
                    <small>(Total Earnings - Total Deductions)</small>
                </div>
                <h1 class="fw-bold m-0">₹{{ number_format($payroll->net_salary, 2) }}</h1>
            </div>

            <p class="text-muted text-center small mt-5">This is a computer-generated document and does not require a
                signature.</p>

            <!-- Actions -->
            <div class="text-center mt-4 no-print gap-2 d-flex justify-content-center">
                <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
                    <i class="fas fa-print me-2"></i> Print / Save PDF
                </button>
                <button onclick="window.close()" class="btn btn-secondary btn-lg shadow">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>