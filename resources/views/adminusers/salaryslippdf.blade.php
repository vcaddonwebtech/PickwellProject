<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Salary Slip' }}</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        .heading {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .h2 {
          color: #eb1f1fff;  
        }
        .center {
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-10 col-sm-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    
                </div>
                <div class="card-body">
                    <div class="center"><img src="{{ public_path('images/brand-logos/desktop-logo.png') }}" style="height: 80px; width: 250px; max-width: 200px;">
                </div>
                    <div class="center"><br><strong style="color: #eb1f1fff; font-weight: bold;">SAISTAR IMPEX PRIVATE LIMITED</strong><br>
                        7-8-9, 1st, 3rd, 5th Floor, Somnath Industrial Park, Devadh, Mangrol, Surat-394210, Gujarat
                        <br>
                        {{'Salary Slip for the Month of ' . ($monthName ?? 'June') . ' ' . ($year ?? '2025') }}
                    </div>

                    <p class="section-title">Employee Details</p>
                    <table>
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{ $userData->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td>{{ $userData->roles->first()->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>PAN</td>
                                <td>{{ $userPayroll->panno ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>Working Days</td>
                                <td>{{ $userSalary->working_days ?? 30 }}</td>
                            </tr>
                            <tr>
                                <td>Present Days</td>
                                <td>{{ $userSalary->pdays ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Leave Taken</td>
                                <td>{{ $userData->leaves ?? 2 }}</td>
                            </tr>
                           
                        </tbody>
                    </table>

                    <p class="section-title">Salary Details</p>
                    <table>
                        <thead>
                            <tr>
                                <td class="heading">EARNING</td>
                                <td class="heading">AMOUNT (Rs)</td>
                                <td class="heading">DEDUCTION</td>
                                <td class="heading">AMOUNT (Rs)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BASIC</td>
                                <td>{{ $userPayroll->basic_sal ?? 8500 }}</td>
                                <td>PF</td>
                                <td>{{ $userData->pf ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>HRA</td>
                                <td>{{ $userPayroll->hra ?? 0 }}</td>
                                <td>ESIC</td>
                                <td>{{ $userData->esic ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Misc. Allowance</td>
                                <td>{{ $userPayroll->msc_allow ?? 2500 }}</td>
                                <td>PT</td>
                                <td>{{ $userPayroll->pt ?? 200 }}</td>
                            </tr>
                            <tr>
                                <td>Petrol Allowance</td>
                                <td>{{ $userPayroll->ptrl_allow ?? 2000 }}</td>
                                <td>TDS</td>
                                <td>{{ $userData->tds ?? 0 }}</td>
                            </tr>
                            <tr class="heading">
                                <td>Total Earning</td>
                                <td>{{ $userPayroll->basic_sal + $userPayroll->hra + $userPayroll->msc_allow + $userPayroll->ptrl_allow}}</td>
                                <td>Total Deduction</td>
                                <td>{{ $userPayroll->pt ?? 200 }}</td>
                            </tr>
                            <tr class="heading">
                                <td colspan="2">NET SALARY</td>
                                <td colspan="2">{{ $userSalary->total_salary ?? 27000 }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 20px;">Prepared By: Vikash Chaudhry</p>
                    <p>Received By: {{ $userData->name ?? '' }}</p>
                    <p><strong>Dhiraj Patil - CEO</strong></p>
                    <p style="font-size: 12px; color: #555;">This payslip is computer generated and doesn’t require any signature.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>