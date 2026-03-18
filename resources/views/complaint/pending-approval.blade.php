{{-- resources/views/attendance/pending-approval.blade.php --}}
@extends('layouts.app')
@section('title', $title)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ═══════════════════════════════════════════
       DESIGN SYSTEM – Pending Approval Page
    ═══════════════════════════════════════════ */
    :root {
        --pa-bg:          #f0f2f5;
        --pa-card:        #ffffff;
        --pa-border:      #e4e7ec;
        --pa-text:        #1a2332;
        --pa-muted:       #6b7a90;
        --pa-accent:      #2563eb;
        --pa-accent-soft: #eff4ff;
        --pa-green:       #16a34a;
        --pa-green-soft:  #f0fdf4;
        --pa-red:         #dc2626;
        --pa-red-soft:    #fef2f2;
        --pa-amber:       #d97706;
        --pa-amber-soft:  #fffbeb;
        --pa-row-hover:   #f5f8ff;
        --pa-row-checked: #eff4ff;
        --pa-shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --pa-shadow-md:   0 4px 16px rgba(37,99,235,.12);
        --pa-radius:      10px;
        --pa-font:        'DM Sans', sans-serif;
        --pa-mono:        'DM Mono', monospace;
    }

    body, .page-body, .main-content { font-family: var(--pa-font) !important; }

    /* ── Page shell ── */
    .pa-page { padding: 0 0 80px; }

    /* ── Breadcrumb ── */
    .pa-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: var(--pa-muted);
        margin-bottom: 22px;
        font-family: var(--pa-font);
    }
    .pa-breadcrumb a { color: var(--pa-muted); text-decoration: none; transition: color .15s; }
    .pa-breadcrumb a:hover { color: var(--pa-accent); }
    .pa-breadcrumb .sep { opacity: .4; }
    .pa-breadcrumb .current { color: var(--pa-text); font-weight: 500; }

    /* ── Card wrapper ── */
    .pa-card {
        background: var(--pa-card);
        border: 1px solid var(--pa-border);
        border-radius: var(--pa-radius);
        box-shadow: var(--pa-shadow-sm);
        overflow: hidden;
    }

    /* ── Card top bar ── */
    .pa-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid var(--pa-border);
        gap: 16px;
        flex-wrap: wrap;
    }
    .pa-topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .pa-title-icon {
        width: 38px; height: 38px;
        background: var(--pa-accent-soft);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--pa-accent);
        font-size: 16px;
        flex-shrink: 0;
    }
    .pa-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--pa-text);
        margin: 0;
        line-height: 1.2;
    }
    .pa-subtitle {
        font-size: 11.5px;
        color: var(--pa-muted);
        margin: 2px 0 0;
        font-family: var(--pa-mono);
    }
    .pa-topbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Buttons ── */
    .pa-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all .18s ease;
        border: none;
        font-family: var(--pa-font);
        white-space: nowrap;
    }
    .pa-btn-filter {
        background: var(--pa-bg);
        color: var(--pa-muted);
        border: 1px solid var(--pa-border);
    }
    .pa-btn-filter:hover { background: var(--pa-border); color: var(--pa-text); }
    .pa-btn-filter.active {
        background: var(--pa-accent-soft);
        color: var(--pa-accent);
        border-color: #bfdbfe;
    }

    .pa-btn-approve {
        background: var(--pa-green);
        color: #fff;
        box-shadow: 0 2px 8px rgba(22,163,74,.25);
        opacity: .45;
        pointer-events: none;
        transition: all .2s ease;
    }
    .pa-btn-approve.active {
        opacity: 1;
        pointer-events: auto;
        box-shadow: 0 4px 14px rgba(22,163,74,.35);
    }
    .pa-btn-approve.active:hover {
        background: #15803d;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(22,163,74,.4);
    }
    .pa-btn-approve.approving {
        background: #166534;
        opacity: .8;
        pointer-events: none;
    }

    /* Count pill on the approve button */
    .pa-count-pill {
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.25);
        border-radius: 20px;
        min-width: 20px; height: 20px;
        padding: 0 6px;
        font-size: 11px;
        font-weight: 600;
        font-family: var(--pa-mono);
        transition: all .2s;
    }

    /* ── Filter panel ── */
    .pa-filter-panel {
        padding: 18px 24px;
        background: #fafbfc;
        border-bottom: 1px solid var(--pa-border);
        display: none;
    }
    .pa-filter-panel.open { display: block; animation: slideDown .2s ease; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .pa-filter-panel label {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--pa-muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 5px;
        display: block;
    }
    .pa-filter-panel .form-control,
    .pa-filter-panel .form-select {
        border: 1px solid var(--pa-border);
        border-radius: 7px;
        font-size: 13px;
        font-family: var(--pa-font);
        color: var(--pa-text);
        background: #fff;
        height: 38px;
        transition: border-color .15s;
    }
    .pa-filter-panel .form-control:focus,
    .pa-filter-panel .form-select:focus {
        border-color: var(--pa-accent);
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        outline: none;
    }
    .pa-btn-search {
        background: var(--pa-accent);
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 500;
        font-family: var(--pa-font);
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .15s, transform .15s;
        height: 38px;
    }
    .pa-btn-search:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* ── Sticky selection bar ── */
    .pa-selection-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: var(--pa-text);
        color: #fff;
        border-radius: 50px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,.25);
        z-index: 1050;
        transition: transform .3s cubic-bezier(.34,1.56,.64,1);
        white-space: nowrap;
        font-family: var(--pa-font);
    }
    .pa-selection-bar.visible {
        transform: translateX(-50%) translateY(0);
    }
    .pa-selection-bar .sel-label {
        font-size: 13px;
        font-weight: 500;
        opacity: .85;
    }
    .pa-selection-bar .sel-count {
        background: var(--pa-accent);
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 12px;
        font-weight: 700;
        font-family: var(--pa-mono);
    }
    .pa-selection-bar .sel-approve-btn {
        background: var(--pa-green);
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        font-family: var(--pa-font);
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .15s, transform .15s;
    }
    .pa-selection-bar .sel-approve-btn:hover { background: #15803d; transform: scale(1.03); }
    .pa-selection-bar .sel-clear-btn {
        background: transparent;
        color: rgba(255,255,255,.6);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 30px;
        padding: 6px 14px;
        font-size: 12px;
        font-family: var(--pa-font);
        cursor: pointer;
        transition: all .15s;
    }
    .pa-selection-bar .sel-clear-btn:hover { color: #fff; border-color: rgba(255,255,255,.4); }

    /* ── Table ── */
    .pa-table-wrap { padding: 0; }
    #pending-approval-table {
        width: 100% !important;
        border-collapse: collapse;
        font-family: var(--pa-font);
        font-size: 13px;
    }
    #pending-approval-table thead tr {
        background: #f8f9fb;
        border-bottom: 2px solid var(--pa-border);
    }
    #pending-approval-table thead th {
        padding: 12px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--pa-muted);
        border: none !important;
        white-space: nowrap;
    }
    #pending-approval-table tbody td {
        padding: 11px 14px;
        border-bottom: 1px solid #f1f3f5;
        border-top: none;
        border-left: none;
        border-right: none;
        vertical-align: middle;
        color: var(--pa-text);
        transition: background .12s;
    }
    #pending-approval-table tbody tr {
        transition: background .12s;
        cursor: default;
    }
    #pending-approval-table tbody tr:hover td {
        background: var(--pa-row-hover);
    }
    #pending-approval-table tbody tr.row-selected td {
        background: var(--pa-row-checked) !important;
    }
    #pending-approval-table tbody tr.row-selected td:first-child {
        border-left: 3px solid var(--pa-accent);
    }

    /* ── Custom checkbox ── */
    .pa-checkbox-wrap {
        display: flex; align-items: center; justify-content: center;
    }
    .pa-cb {
        appearance: none;
        -webkit-appearance: none;
        width: 17px; height: 17px;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        background: #fff;
        cursor: pointer;
        transition: all .15s;
        position: relative;
        flex-shrink: 0;
    }
    .pa-cb:hover { border-color: var(--pa-accent); }
    .pa-cb:checked {
        background: var(--pa-accent);
        border-color: var(--pa-accent);
    }
    .pa-cb:checked::after {
        content: '';
        position: absolute;
        left: 3px; top: 1px;
        width: 7px; height: 5px;
        border-left: 2px solid #fff;
        border-bottom: 2px solid #fff;
        transform: rotate(-45deg);
    }
    .pa-cb:indeterminate {
        background: var(--pa-accent);
        border-color: var(--pa-accent);
    }
    .pa-cb:indeterminate::after {
        content: '';
        position: absolute;
        left: 2px; top: 5px;
        width: 9px; height: 2px;
        background: #fff;
        border-radius: 1px;
    }

    /* ── Selfie avatar ── */
    .pa-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--pa-border);
        transition: transform .15s, border-color .15s;
    }
    .pa-avatar:hover { transform: scale(1.1); border-color: var(--pa-accent); }
    .pa-avatar-empty {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: #f1f3f5;
        display: inline-flex; align-items: center; justify-content: center;
        color: #c0c9d4;
        font-size: 16px;
        border: 2px dashed var(--pa-border);
    }

    /* ── Name link ── */
    .pa-name-link {
        color: var(--pa-text);
        font-weight: 500;
        text-decoration: none;
        transition: color .15s;
    }
    .pa-name-link:hover { color: var(--pa-accent); text-decoration: underline; }

    /* ── Badges ── */
    .pa-badge {
        display: inline-flex; align-items: center;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        font-family: var(--pa-mono);
        letter-spacing: .02em;
    }
    .pa-badge-P  { background: var(--pa-green-soft); color: var(--pa-green); }
    .pa-badge-A  { background: var(--pa-red-soft);   color: var(--pa-red);   }
    .pa-badge-L  { background: var(--pa-amber-soft); color: var(--pa-amber); }
    .pa-badge-H  { background: #f0f9ff;              color: #0284c7;         }

    /* ── Time cell ── */
    .pa-time {
        font-family: var(--pa-mono);
        font-size: 12.5px;
        color: var(--pa-text);
        font-weight: 500;
    }
    .pa-time-empty { color: #c0c9d4; font-family: var(--pa-mono); font-size: 12px; }

    /* ── Address cell ── */
    .pa-address {
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--pa-muted);
        font-size: 12px;
        display: block;
    }

    /* ── Shift chip ── */
    .pa-shift-chip {
        display: inline-flex; align-items: center; gap: 4px;
        background: #f8f9fb;
        border: 1px solid var(--pa-border);
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 11.5px;
        font-family: var(--pa-mono);
        font-weight: 500;
        color: var(--pa-text);
    }
    .pa-shift-chip .shift-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--pa-accent);
        flex-shrink: 0;
    }

    /* ── DataTables overrides ── */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid var(--pa-border) !important;
        border-radius: 7px !important;
        font-size: 13px !important;
        font-family: var(--pa-font) !important;
        padding: 5px 10px !important;
        color: var(--pa-text) !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--pa-accent) !important;
        box-shadow: 0 0 0 3px rgba(37,99,235,.08) !important;
        outline: none !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        font-size: 12.5px !important;
        color: var(--pa-muted) !important;
        font-family: var(--pa-font) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        font-size: 12.5px !important;
        font-family: var(--pa-font) !important;
        color: var(--pa-muted) !important;
        border: none !important;
        padding: 5px 10px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--pa-accent) !important;
        color: #fff !important;
        border: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: var(--pa-accent-soft) !important;
        color: var(--pa-accent) !important;
        border: none !important;
    }
    div.dataTables_wrapper div.dataTables_filter { padding: 16px 24px 0; }
    div.dataTables_wrapper div.dataTables_length { padding: 16px 24px 0; }
    div.dataTables_wrapper div.dataTables_info   { padding: 12px 24px; }
    div.dataTables_wrapper div.dataTables_paginate { padding: 12px 24px; }
    table.dataTable.no-footer { border-bottom: none !important; }
    .dataTables_processing {
        background: rgba(255,255,255,.92) !important;
        border: 1px solid var(--pa-border) !important;
        border-radius: 8px !important;
        padding: 12px 24px !important;
        font-family: var(--pa-font) !important;
        font-size: 13px !important;
        color: var(--pa-muted) !important;
        box-shadow: var(--pa-shadow-md) !important;
    }

    /* ── Approve success animation on rows ── */
    @keyframes rowApproved {
        0%   { background: #dcfce7; }
        100% { background: transparent; }
    }
    .row-approving td { animation: rowApproved .8s ease forwards; }

    /* ── Empty state ── */
    .pa-empty {
        text-align: center;
        padding: 60px 24px;
        color: var(--pa-muted);
    }
    .pa-empty-icon { font-size: 40px; margin-bottom: 12px; opacity: .3; }
    .pa-empty p { font-size: 14px; margin: 0; }
</style>
@endpush

@section('content')
<div class="pa-page">

    {{-- ── Breadcrumb ─────────────────────────────────────────────── --}}
    <div class="pa-breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a>
        <span class="sep">/</span>
        <a href="{{ route('attendap-today-report') }}">Attendance</a>
        <span class="sep">/</span>
        <span class="current">Pending Approval</span>
    </div>

    {{-- ── Main Card ──────────────────────────────────────────────── --}}
    <div class="pa-card">

        {{-- Top bar --}}
        <div class="pa-topbar">
            <div class="pa-topbar-left">
                <div class="pa-title-icon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div>
                    <div class="pa-title">Pending Approval</div>
                    <div class="pa-subtitle">Present · Not Yet Approved</div>
                </div>
            </div>
            <div class="pa-topbar-right">
                {{-- Approve button (header – mirrors sticky bar) --}}
                <button class="pa-btn pa-btn-approve" id="approve-btn-top" type="button">
                    <i class="fa fa-check-circle"></i>
                    Approve Selected
                    <span class="pa-count-pill" id="approve-count-top">0</span>
                </button>
                {{-- Filter toggle --}}
                <button class="pa-btn pa-btn-filter" id="filter-toggle-btn" type="button">
                    <i class="fa fa-sliders"></i> Filter
                </button>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div class="pa-filter-panel" id="filter-panel">
            <div class="row g-3 align-items-end">
                <div class="col-md-2 col-sm-6">
                    <label>Date</label>
                    <input type="text" class="form-control" id="date_from"
                           name="date_from" placeholder="yyyy-mm-dd" autocomplete="off">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label>Attendance</label>
                    <select class="form-control" id="attn" name="attn">
                        <option value="">All</option>
                        <option value="P">P – Present</option>
                        <option value="A">A – Absent</option>
                        <option value="L">L – Leave</option>
                        <option value="H">H – Holiday</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label>Role</label>
                    <select class="form-control" id="role_id" name="role_id">
                        <option value="">All Roles</option>
                        @foreach (Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label>Department</label>
                    <select class="form-control" id="department" name="department">
                        <option value="">All Departments</option>
                        @foreach (App\Models\Department::all() as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <button type="button" id="search-button" class="pa-btn-search w-100">
                        <i class="fa fa-search"></i> Apply Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="pa-table-wrap">
            <div class="table-responsive">
                <table id="pending-approval-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:44px;">
                                <div class="pa-checkbox-wrap">
                                    <input type="checkbox" class="pa-cb" id="select-all-cb"
                                           title="Select all on this page">
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

    </div>{{-- /.pa-card --}}

</div>{{-- /.pa-page --}}

{{-- ── Sticky Selection Bar ──────────────────────────────────────── --}}
<div class="pa-selection-bar" id="selection-bar">
    <span class="sel-label">Selected</span>
    <span class="sel-count" id="sel-count-badge">0</span>
    <button type="button" class="sel-approve-btn" id="approve-btn-bar">
        <i class="fa fa-check-circle"></i> Approve
    </button>
    <button type="button" class="sel-clear-btn" id="clear-selection-btn">
        Clear
    </button>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    // ── Select2 ──────────────────────────────────────────────────────────────
    $('#role_id, #department').select2({ width: '100%' });

    // ── DataTable ─────────────────────────────────────────────────────────────
    var table = $('#pending-approval-table').DataTable({
        processing: true,
        serverSide: true,
        searching:  true,
        pageLength: 50,
        order: [],
        language: {
            processing:  '<i class="fa fa-spinner fa-spin"></i>&nbsp; Loading records…',
            emptyTable:  '<div class="pa-empty"><div class="pa-empty-icon"><i class="fa fa-check-square-o"></i></div><p>No pending approvals found.</p></div>',
            zeroRecords: '<div class="pa-empty"><div class="pa-empty-icon"><i class="fa fa-filter"></i></div><p>No records match your filter.</p></div>',
        },
        ajax: {
            url:  "{{ route('pending-approval') }}",
            type: 'GET',
            data: function (d) {
                d.date_from  = $('#date_from').val();
                d.attn       = $('#attn').val();
                d.role_id    = $('#role_id').val();
                d.department = $('#department').val();
            }
        },
        columns: [
            // ── Checkbox ─────────────────────────────────────────────────────
            {
                data: null, orderable: false, searchable: false,
                className: 'pa-checkbox-wrap',
                render: function (data, type, row) {
                    return '<input type="checkbox" class="pa-cb row-cb" data-id="' + row.id + '">';
                }
            },
            // ── Index ────────────────────────────────────────────────────────
            {
                data: 'DT_RowIndex', orderable: false, searchable: false,
                render: function (d) {
                    return '<span style="color:var(--pa-muted);font-family:var(--pa-mono);font-size:12px;">' + d + '</span>';
                }
            },
            // ── In Selfie ────────────────────────────────────────────────────
            {
                data: 'in_selfie', name: 'in_selfie', orderable: false,
                render: function (data, type, row) {
                    var base = "{{ url('user-monthly-attendence-list') }}";
                    if (row.in_selfie && row.in_selfie !== '') {
                        return '<a href="' + base + '/' + row.id + '">'
                             + '<img class="pa-avatar" '
                             + 'src="https://pickwell.addonwebtech.com/atdselfie/' + row.in_selfie + '" '
                             + 'title="View Profile">'
                             + '</a>';
                    }
                    return '<span class="pa-avatar-empty"><i class="fa fa-user"></i></span>';
                }
            },
            // ── Name ─────────────────────────────────────────────────────────
            {
                data: 'name', name: 'name',
                render: function (data, type, row) {
                    var base = "{{ url('user-monthly-attendence-list') }}";
                    return '<a class="pa-name-link" href="' + base + '/' + row.id + '">'
                         + (data || 'N/A') + '</a>';
                }
            },
            // ── Department ───────────────────────────────────────────────────
            {
                data: 'department.name', name: 'department.name', defaultContent: '',
                render: function (data) {
                    if (!data) return '<span style="color:var(--pa-muted)">—</span>';
                    return '<span style="font-size:12.5px;">' + data + '</span>';
                }
            },
            // ── Shift ────────────────────────────────────────────────────────
            {
                data: 'shift', name: 'shift.shift_start', orderable: false,
                render: function (data) {
                    if (!data) return '<span style="color:var(--pa-muted)">—</span>';
                    return '<span class="pa-shift-chip">'
                         + '<span class="shift-dot"></span>'
                         + data.title
                         + ' <span style="opacity:.55;font-size:10.5px;">(' + data.shift_start + ')</span>'
                         + '</span>';
                }
            },
            // ── Date ─────────────────────────────────────────────────────────
            {
                data: 'in_date', name: 'in_date',
                render: function (data) {
                    return '<span class="pa-time">' + (data || '—') + '</span>';
                }
            },
            // ── A/P Badge ────────────────────────────────────────────────────
            {
                data: 'attendance_status', name: 'attendance_status',
                render: function (data) {
                    var label = { P:'Present', A:'Absent', L:'Leave', H:'Holiday' };
                    var ap = data || '?';
                    return '<span class="pa-badge pa-badge-' + ap + '" title="' + (label[ap]||ap) + '">' + ap + '</span>';
                }
            },
            // ── In Time ──────────────────────────────────────────────────────
            {
                data: 'in_time', name: 'in_time',
                render: function (data) {
                    return data
                        ? '<span class="pa-time">' + data + '</span>'
                        : '<span class="pa-time-empty">—</span>';
                }
            },
            // ── In Address ───────────────────────────────────────────────────
            {
                data: 'in_address', name: 'in_address', orderable: false,
                render: function (data) {
                    if (!data || data === 'null') return '<span style="color:var(--pa-muted)">—</span>';
                    return '<span class="pa-address" title="' + data + '">' + data + '</span>';
                }
            },
            // ── Out Time ─────────────────────────────────────────────────────
            {
                data: 'out_time', name: 'out_time',
                render: function (data) {
                    return data
                        ? '<span class="pa-time">' + data + '</span>'
                        : '<span class="pa-time-empty">—</span>';
                }
            },
        ],
    });

    // ─────────────────────────────────────────────────────────────────────────
    //  CHECKBOX LOGIC
    // ─────────────────────────────────────────────────────────────────────────

    // Re-sync after every draw (pagination, filter, redraw)
    table.on('draw', function () {
        $('#select-all-cb').prop('checked', false).prop('indeterminate', false);
        refreshUI();
    });

    // Master checkbox
    $(document).on('change', '#select-all-cb', function () {
        var checked = $(this).is(':checked');
        $('#pending-approval-table tbody .row-cb').prop('checked', checked);
        // Toggle row highlight
        $('#pending-approval-table tbody tr').toggleClass('row-selected', checked);
        refreshUI();
    });

    // Row checkboxes
    $('#pending-approval-table').on('change', '.row-cb', function () {
        // Highlight/un-highlight the row
        $(this).closest('tr').toggleClass('row-selected', $(this).is(':checked'));
        syncMaster();
        refreshUI();
    });

    // Sync master checkbox state
    function syncMaster() {
        var $allCbs = $('#pending-approval-table tbody .row-cb');
        var total   = $allCbs.length;
        var checked = $allCbs.filter(':checked').length;
        $('#select-all-cb')
            .prop('indeterminate', checked > 0 && checked < total)
            .prop('checked', total > 0 && checked === total);
    }

    // Refresh approve buttons & sticky bar
    function refreshUI() {
        var count = $('#pending-approval-table tbody .row-cb:checked').length;

        // Top button
        var $topBtn = $('#approve-btn-top');
        $('#approve-count-top').text(count);
        if (count > 0) {
            $topBtn.addClass('active');
        } else {
            $topBtn.removeClass('active');
        }

        // Sticky bar
        $('#sel-count-badge').text(count);
        if (count > 0) {
            $('#selection-bar').addClass('visible');
        } else {
            $('#selection-bar').removeClass('visible');
        }
    }

    // Clear selection
    $('#clear-selection-btn').on('click', function () {
        $('#pending-approval-table tbody .row-cb').prop('checked', false);
        $('#pending-approval-table tbody tr').removeClass('row-selected');
        $('#select-all-cb').prop('checked', false).prop('indeterminate', false);
        refreshUI();
    });

    // ─────────────────────────────────────────────────────────────────────────
    //  APPROVE ACTION
    // ─────────────────────────────────────────────────────────────────────────

    function doApprove() {
        var ids = [];
        $('#pending-approval-table tbody .row-cb:checked').each(function () {
            ids.push($(this).data('id'));
        });
        if (!ids.length) return;

        if (!confirm('Approve ' + ids.length + ' attendance record(s)?')) return;

        // Loading states
        $('#approve-btn-top').addClass('approving').html(
            '<i class="fa fa-spinner fa-spin"></i> Approving…'
        );
        $('#approve-btn-bar').html('<i class="fa fa-spinner fa-spin"></i> Approving…')
                             .prop('disabled', true);

        // Flash rows
        $('#pending-approval-table tbody .row-cb:checked').each(function () {
            $(this).closest('tr').addClass('row-approving');
        });

        $.ajax({
            url:  "{{ route('approve-attendance') }}",
            type: 'POST',
            data: { _token: "{{ csrf_token() }}", ids: ids },
            success: function (res) {
                if (res.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message || ids.length + ' record(s) approved.');
                    } else {
                        alert(res.message || ids.length + ' record(s) approved.');
                    }
                    table.draw();
                    $('#select-all-cb').prop('checked', false).prop('indeterminate', false);
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(res.message || 'Could not approve records.');
                    }
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message : 'Server error. Please try again.';
                if (typeof toastr !== 'undefined') { toastr.error(msg); }
                else { alert(msg); }
            },
            complete: function () {
                $('#approve-btn-top')
                    .removeClass('approving')
                    .html('<i class="fa fa-check-circle"></i> Approve Selected <span class="pa-count-pill" id="approve-count-top">0</span>');
                $('#approve-btn-bar')
                    .html('<i class="fa fa-check-circle"></i> Approve')
                    .prop('disabled', false);
                refreshUI();
            }
        });
    }

    $('#approve-btn-top').on('click', doApprove);
    $('#approve-btn-bar').on('click', doApprove);

    // ─────────────────────────────────────────────────────────────────────────
    //  FILTER
    // ─────────────────────────────────────────────────────────────────────────

    $('#filter-toggle-btn').on('click', function () {
        var $panel = $('#filter-panel');
        var isOpen = $panel.hasClass('open');
        $panel.toggleClass('open', !isOpen);
        $(this).toggleClass('active', !isOpen);
    });

    $('#search-button').on('click', function () { table.draw(); });

    // Datepicker
    $('#date_from').datepicker({ dateFormat: 'yy-mm-dd' });

});
</script>
@endsection