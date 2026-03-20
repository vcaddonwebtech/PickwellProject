@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="">
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-1 mb-md-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('attendap-today-report') }}">Attendence</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div style="padding:15px;">
                        <div class="row card-title align-items-end">
                            @if ($userAttendance->isNotEmpty())
                                <div class="col-xl-2 card-title">
                                    {{ $userAttendance->first()?->user?->name }}
                                </div>
                            @endif
                            <div class="col-xl-1">
                                Total Days: {{ $totalstaff }}
                            </div>
                            <div class="col-xl-1">
                                Present: <span class="text-success fw-bold"> {{ $present }} </span>
                            </div>
                            <div class="col-xl-1">
                                Absent: <span class="text-danger fw-bold"> {{ $absent }} </span>
                            </div>
                            <div class="col-xl-1">
                                Leave: <span class="text-danger fw-bold"> {{ $leave }} </span>
                            </div>
                            <div class="col-xl-1">
                                Pending Approval: <span class="text-danger fw-bold"> {{ $pending }} </span>
                            </div>

                            
                            <!-- Form Section -->
                            <div class="col-xl-6">
                                <form id="search-form">
                                    <input type="hidden" id="engineer_id" value="{{ $user }}">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-xl-2">
                                            <select name="month" class="form-control" id="month">
                                                <option value="0" selected>Month</option>
                                                @foreach (App\Models\Months::get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- <div class="col-auto">
                                                    <input type="text"
                                                        class="form-control"
                                                        placeholder="yyyy-mm-dd"
                                                        name="date"
                                                        id="date">
                                                </div> -->
                                        <div class="col-auto">
                                            <button type="button" id="search-button" class="btn btn-primary">
                                                Filter
                                            </button>

                                        </div>
                                        <div class="col-auto">
                                            <div id="exportapsum"> </div>
                                        </div>
                                        <div class="col-auto"><a class="btn btn-primary"
                                                href="{{ url('user-monthly-attendence-list', $user) }}">Clear Filter</a>
                                            {{-- <div class="d-flex justify-content-end mb-2"> --}}
                                                <button class="btn btn-success" id="approveBtn">
                                                    <i class="fa fa-check" aria-hidden="true"></i> Approve
                                                </button>
                                            {{-- </div> --}}
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table text-nowrap table-bordered" id="complaint-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th><input type="checkbox" class="form-check-input" id="selectAll"></th>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>in Selfie</th>
                                        <th>A/P</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Working Hrs</th>
                                        <th>Late Hrs</th>
                                        <th>Early Going Hrs</th>
                                        <th>In Address</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>


                        {{-- {{ $dataTable->table() }} --}}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}

    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#month').select2();
            $('#role_id').select2();
            $('#department').select2();
            var table = $('#complaint-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('user-monthly-attendence-list', $user) }}",
                    type: 'GET',
                    data: function(d) {
                        //d.date = $('#date').val();
                        //d.attn = $('#attn').val();
                        d.month = $('#month').val();
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="form-check-input row-checkbox" value="' +
                                row.id + '">';
                        }
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'in_date',
                        name: 'in_date',
                    },
                    {
                        data: 'in_selfie',
                        name: 'in_selfie',
                        render: function(data, type, row) {
                            // Change text color based on status
                            if (row.in_selfie != null && row.in_selfie !== '') {
                                let baseUrl = "{{ url('user-daily-attendence-details', $user) }}";
                                return '<a href="' + baseUrl + '/' + row.in_date +
                                    '" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile"><img src="https://pickwell.addonwebtech.com/atdselfie/' +
                                    row.in_selfie +
                                    '" height="60" width="60" style="border-radius:50%; object-fit:cover;"></a>';
                            }

                            return '';
                        }
                    },
                    {
                        data: 'ap',
                        name: 'ap',
                    },
                    {
                        data: 'in_time',
                        name: 'in_time',
                    },
                    {
                        data: 'out_time',
                        name: 'out_time',
                    },
                    {
                        data: 'working_hrs',
                        name: 'working_hrs',
                    },
                    {
                        data: 'late_hrs',
                        name: 'late_hrs',
                    },
                    {
                        data: 'earligoing_hrs',
                        name: 'earligoing_hrs',
                    },
                    {
                        data: 'in_address',
                        name: 'in_address',
                    },
                ],
            });

            $('#search-button').click(function() {
                table.draw();
            })

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });
            $('#search-button').click(function() {
                let fmonth = $('#month').val();
                let engineer_id = $('#engineer_id').val();
                //let main_machine_type = $('#main_machine_type').val();
                if (fmonth === '0') {
                    const currentDate = new Date();
                    fmonth = currentDate.getMonth() + 1; // JS months are 0-indexed
                }
                $('#exportapsum').html(
                    `<a href="https://pickwell.addonwebtech.com/export-engineer-ap-summary/${fmonth}/${engineer_id}" class="btn btn-primary mt-3"><i class="fa fa-download" aria-hidden="true"></i></a>`
                );
                table.draw();
            });
            $('#date').datepicker({
                dateFormat: "yy-mm-dd", // Change format to d-m-Y (day-month-year)
            });
        });

        $('#selectAll').on('click', function() {
            $('.row-checkbox').prop('checked', this.checked);
        });

        // Uncheck "Select All" if any checkbox is unchecked
        $('#complaint-table').on('change', '.row-checkbox', function() {
            if (!this.checked) {
                $('#selectAll').prop('checked', false);
            } else {
                // Check if all checkboxes are checked
                if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                }
            }
        });

        // Approve selected attendance records
        $('#approveBtn').on('click', function() {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one record to approve.',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }

            Swal.fire({
                title: 'Approve Attendance?',
                text: "Are you sure you want to approve " + selectedIds.length + " record(s)?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Approve!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we approve the records.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('approve-attendance') }}",
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message ||
                                        'Attendance records approved successfully.',
                                    confirmButtonColor: '#28a745',
                                }).then(() => {
                                    $('#selectAll').prop('checked', false);
                                    table.draw(); // Refresh table
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong. Please try again.',
                                    confirmButtonColor: '#dc3545',
                                });
                            }
                        },
                        error: function(xhr) {
                            var errorMessage = 'An error occurred. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat()
                                    .join('\n');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage,
                                confirmButtonColor: '#dc3545',
                            });
                        }
                    });
                }
            });
        });
    </script>
@endSection
