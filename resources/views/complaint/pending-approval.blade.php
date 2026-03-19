{{-- resources/views/complaint/pending-approval.blade.php --}}
@extends('layouts.app')
@section('title', $title)
@section('content')
<style>
.pa-card{border-radius:10px;overflow:hidden}
.pa-topbar{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #e4e7ec;flex-wrap:wrap;gap:10px}
.pa-topbar-left{display:flex;align-items:center;gap:10px}
.pa-icon{width:36px;height:36px;background:#eff4ff;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#2563eb;font-size:15px;flex-shrink:0}
.pa-title{font-size:14px;font-weight:600;color:#1a2332;margin:0}
.pa-subtitle{font-size:11px;color:#6b7a90;margin:2px 0 0}
.pa-btn-approve{display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:7px;border:none;cursor:pointer;font-size:13px;font-weight:500;background:#e2e8f0;color:#94a3b8;transition:all .2s;pointer-events:none}
.pa-btn-approve.ready{background:#16a34a;color:#fff;box-shadow:0 3px 10px rgba(22,163,74,.3);pointer-events:auto}
.pa-btn-approve.ready:hover{background:#15803d;transform:translateY(-1px)}
.pa-pill{background:rgba(255,255,255,.25);border-radius:20px;padding:1px 7px;font-size:11px;font-weight:700;min-width:18px;display:inline-block;text-align:center}
.pa-btn-search:hover{background:#1d4ed8}
#pa-table{width:100%!important;font-size:13px;border-collapse:collapse}
#pa-table thead tr{background:#f8f9fb;border-bottom:2px solid #e4e7ec}
#pa-table thead th{padding:10px 13px;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7a90;border:none!important;white-space:nowrap}
#pa-table tbody td{padding:10px 13px;border-bottom:1px solid #f1f3f5;border-top:none;border-left:none;border-right:none;vertical-align:middle;transition:background .1s}
#pa-table tbody tr:hover td{background:#f5f8ff}
#pa-table tbody tr.pa-sel td{background:#f0f7ff!important;box-shadow:inset 0 0 0 0 transparent}
.pa-cb{appearance:none;-webkit-appearance:none;width:16px;height:16px;border:2px solid #cbd5e1;border-radius:4px;background:#fff;cursor:pointer;position:relative;display:block;transition:all .15s}
.pa-cb:hover{border-color:#2563eb}
.pa-cb:checked{background:#2563eb;border-color:#2563eb}
.pa-cb:checked::after{content:'';position:absolute;left:3px;top:0;width:6px;height:5px;border-left:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(-45deg)}
.pa-cb:indeterminate{background:#2563eb;border-color:#2563eb}
.pa-cb:indeterminate::after{content:'';position:absolute;left:2px;top:5px;width:8px;height:2px;background:#fff;border-radius:1px}
.pa-cbwrap{display:flex;align-items:center;justify-content:center}
.pa-avatar{width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #e4e7ec;display:block;transition:transform .15s,border-color .15s}
.pa-avatar:hover{transform:scale(1.08);border-color:#2563eb}
.pa-avatar-empty{width:42px;height:42px;border-radius:50%;background:#f1f3f5;display:inline-flex;align-items:center;justify-content:center;color:#c0c9d4;font-size:16px;border:2px dashed #e4e7ec}
.pa-name{color:#1a2332;font-weight:500;text-decoration:none;font-size:13px}
.pa-name:hover{color:#2563eb;text-decoration:underline}
.pa-badge{display:inline-block;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:700}
.pa-P{background:#f0fdf4;color:#16a34a}
.pa-A{background:#fef2f2;color:#dc2626}
.pa-L{background:#fffbeb;color:#d97706}
.pa-H{background:#f0f9ff;color:#0284c7}
.pa-shift{display:inline-flex;align-items:center;gap:5px;font-size:12.5px;color:#374151;font-weight:500}
.pa-dot{width:7px;height:7px;border-radius:50%;background:#2563eb;flex-shrink:0;opacity:.7}
.pa-time{font-family:monospace;font-size:12.5px;font-weight:500}
.pa-null{color:#c0c9d4;font-size:12px}
.pa-addr{max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#6b7a90;font-size:12px;display:block}
.pa-selbar{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(110px);background:#1a2332;color:#fff;border-radius:50px;padding:10px 20px;display:flex;align-items:center;gap:14px;box-shadow:0 8px 30px rgba(0,0,0,.22);z-index:9999;transition:transform .3s cubic-bezier(.34,1.56,.64,1);white-space:nowrap}
.pa-selbar.show{transform:translateX(-50%) translateY(0)}
.sb-lbl{font-size:13px;opacity:.8}
.sb-cnt{background:#2563eb;border-radius:20px;padding:2px 10px;font-size:12px;font-weight:700;font-family:monospace}
.sb-appr{background:#16a34a;color:#fff;border:none;border-radius:30px;padding:7px 18px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:5px;transition:background .15s}
.sb-appr:hover{background:#15803d}
.sb-clr{background:transparent;color:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.2);border-radius:30px;padding:5px 13px;font-size:12px;cursor:pointer;transition:all .15s}
.sb-clr:hover{color:#fff;border-color:rgba(255,255,255,.5)}
.dataTables_wrapper .dataTables_filter input,.dataTables_wrapper .dataTables_length select{border:1px solid #e4e7ec!important;border-radius:6px!important;font-size:13px!important;padding:4px 8px!important}
.dataTables_wrapper .dataTables_info,.dataTables_wrapper .dataTables_length label,.dataTables_wrapper .dataTables_filter label{font-size:12.5px!important;color:#6b7a90!important}
.dataTables_wrapper .dataTables_paginate .paginate_button{border-radius:6px!important;font-size:12.5px!important;border:none!important;padding:4px 10px!important}
.dataTables_wrapper .dataTables_paginate .paginate_button.current{background:#2563eb!important;color:#fff!important;border:none!important}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current){background:#eff4ff!important;color:#2563eb!important;border:none!important}
table.dataTable.no-footer{border-bottom:none!important}
</style>

<div class="container-fluid">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <nav>
                <ol class="breadcrumb mb-1 mb-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendap-today-report') }}">Attendance</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pending Approval</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card pa-card">

                {{-- Top bar --}}
                <div class="pa-topbar">
                    <div class="pa-topbar-left">
                        <div class="pa-icon"><i class="fa fa-clock-o"></i></div>
                        <div>
                            <div class="pa-title">Pending Approval</div>
                            <div class="pa-subtitle">Present &middot; Not Yet Approved</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <button class="pa-btn-approve" id="pa-approve-top" type="button">
                            <i class="fa fa-check-circle"></i>
                            Approve Selected
                            <span class="pa-pill" id="pa-count-top">0</span>
                        </button>
                </div>

                {{-- Table --}}
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table id="pa-table" class="table">
                            <thead>
                                <tr>
                                    <th style="width:42px;">
                                        <div class="pa-cbwrap">
                                            <input type="checkbox" class="pa-cb" id="pa-select-all">
                                        </div>
                                    </th>
                                    <th>#</th>
                                    <th>Selfie</th>
                                    <th>Engineer Name</th>
                                    <th>Department</th>
                                    <th>Shift</th>
                                    <th>Date</th>
                                    <th>A/P</th>
                                    <th>In Time</th>
                                    <th>In Address</th>
                                    <th>Out Time</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Sticky selection bar --}}
<div class="pa-selbar" id="pa-selbar">
    <span class="sb-lbl">Selected</span>
    <span class="sb-cnt" id="pa-selbar-count">0</span>
    <button type="button" class="sb-appr" id="pa-approve-bar">
        <i class="fa fa-check-circle"></i> Approve
    </button>
    <button type="button" class="sb-clr" id="pa-clear-btn">Clear</button>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {


    var dt = $('#pa-table').DataTable({
        processing : true,
        serverSide : true,
        searching  : true,
        pageLength : 50,
        order      : [],
        language   : {
            processing  : '<i class="fa fa-spinner fa-spin"></i> Loading...',
            emptyTable  : '<div style="padding:40px;text-align:center;color:#94a3b8;">No pending approvals found.</div>',
            zeroRecords : '<div style="padding:40px;text-align:center;color:#94a3b8;">No records match your filter.</div>'
        },
        ajax: {
            url  : "{{ route('pending-approval') }}",
            type : 'GET',
        },
        columns: [
            {
                data: null, orderable: false, searchable: false,
                className: 'pa-cbwrap',
                render: function (data, type, row) {
                    return '<input type="checkbox" class="pa-cb pa-row-cb" data-id="' + row.id + '">';
                }
            },
            {
                data: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (d) {
                    return '<span style="color:#94a3b8;font-size:12px;">' + d + '</span>';
                }
            },
            {
                data: 'in_selfie', name: 'in_selfie', orderable: false,
                render: function (data, type, row) {
                    var base = "{{ url('user-monthly-attendence-list') }}";
                    if (row.in_selfie && row.in_selfie !== '') {
                        return '<a href="' + base + '/' + row.id + '">'
                             + '<img class="pa-avatar" src="https://pickwell.addonwebtech.com/atdselfie/' + row.in_selfie + '" alt="selfie">'
                             + '</a>';
                    }
                    return '<span class="pa-avatar-empty"><i class="fa fa-user"></i></span>';
                }
            },
            {
                data: 'name', name: 'name',
                render: function (data, type, row) {
                    var base = "{{ url('user-monthly-attendence-list') }}";
                    return '<a class="pa-name" href="' + base + '/' + row.id + '">' + (data || 'N/A') + '</a>';
                }
            },
            {
                data: 'department.name', name: 'department.name', defaultContent: '',
                render: function (d) {
                    return d ? '<span style="font-size:12.5px;">' + d + '</span>' : '<span class="pa-null">-</span>';
                }
            },
            {
                data: 'shift', name: 'shift.shift_start', orderable: false,
                render: function (d) {
                    if (!d) return '<span class="pa-null">-</span>';
                    return '<span class="pa-shift"><span class="pa-dot"></span>' + d.title + '<span style="color:#9ca3af;font-size:11px;font-weight:400;"> ' + d.shift_start + '</span></span>';
                }
            },
            {
                data: 'in_date', name: 'in_date',
                render: function (d) { return '<span class="pa-time">' + (d || '-') + '</span>'; }
            },
            {
                data: 'attendance_status', name: 'attendance_status',
                render: function (d) {
                    var labels = { P:'Present', A:'Absent', L:'Leave', H:'Holiday' };
                    var ap = d || '?';
                    return '<span class="pa-badge pa-' + ap + '" title="' + (labels[ap]||ap) + '">' + ap + '</span>';
                }
            },
            {
                data: 'in_time', name: 'in_time',
                render: function (d) { return d ? '<span class="pa-time">' + d + '</span>' : '<span class="pa-null">-</span>'; }
            },
            {
                data: 'in_address', name: 'in_address', orderable: false,
                render: function (d) {
                    if (!d || d === 'null') return '<span class="pa-null">-</span>';
                    return '<span class="pa-addr" title="' + d + '">' + d + '</span>';
                }
            },
            {
                data: 'out_time', name: 'out_time',
                render: function (d) { return d ? '<span class="pa-time">' + d + '</span>' : '<span class="pa-null">-</span>'; }
            }
        ]
    });

    dt.on('draw', function () {
        $('#pa-select-all').prop('checked', false).prop('indeterminate', false);
        syncUI();
    });

    $(document).on('change', '#pa-select-all', function () {
        var checked = $(this).is(':checked');
        $('#pa-table tbody .pa-row-cb').prop('checked', checked);
        $('#pa-table tbody tr').toggleClass('pa-sel', checked);
        syncUI();
    });

    $('#pa-table').on('change', '.pa-row-cb', function () {
        $(this).closest('tr').toggleClass('pa-sel', $(this).is(':checked'));
        var total   = $('#pa-table tbody .pa-row-cb').length;
        var checked = $('#pa-table tbody .pa-row-cb:checked').length;
        $('#pa-select-all')
            .prop('indeterminate', checked > 0 && checked < total)
            .prop('checked', total > 0 && checked === total);
        syncUI();
    });

    function syncUI() {
        var n = $('#pa-table tbody .pa-row-cb:checked').length;
        $('#pa-count-top').text(n);
        if (n > 0) { $('#pa-approve-top').addClass('ready'); $('#pa-selbar').addClass('show'); }
        else        { $('#pa-approve-top').removeClass('ready'); $('#pa-selbar').removeClass('show'); }
        $('#pa-selbar-count').text(n);
    }

    $('#pa-clear-btn').on('click', function () {
        $('#pa-table tbody .pa-row-cb').prop('checked', false);
        $('#pa-table tbody tr').removeClass('pa-sel');
        $('#pa-select-all').prop('checked', false).prop('indeterminate', false);
        syncUI();
    });

    function doApprove() {
        var ids = [];
        $('#pa-table tbody .pa-row-cb:checked').each(function () { ids.push($(this).data('id')); });
        if (!ids.length) return;
        if (!confirm('Approve ' + ids.length + ' record(s)?')) return;

        $('#pa-approve-top').html('<i class="fa fa-spinner fa-spin"></i> Approving...');
        $('#pa-approve-bar').html('<i class="fa fa-spinner fa-spin"></i> Approving...').prop('disabled', true);

        $.ajax({
            url  : "{{ route('approve-attendance') }}",
            type : 'POST',
            data : { _token: "{{ csrf_token() }}", ids: ids },
            success: function (res) {
                if (res.success) {
                    if (typeof toastr !== 'undefined') toastr.success(res.message);
                    else alert(res.message);
                    dt.draw();
                    $('#pa-select-all').prop('checked', false).prop('indeterminate', false);
                } else {
                    if (typeof toastr !== 'undefined') toastr.error(res.message || 'Failed.');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Server error.';
                if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
            },
            complete: function () {
                $('#pa-approve-top').html('<i class="fa fa-check-circle"></i> Approve Selected <span class="pa-pill" id="pa-count-top">0</span>');
                $('#pa-approve-bar').html('<i class="fa fa-check-circle"></i> Approve').prop('disabled', false);
                syncUI();
            }
        });
    }

    $('#pa-approve-top').on('click', doApprove);
    $('#pa-approve-bar').on('click', doApprove);

});
</script>
@endsection