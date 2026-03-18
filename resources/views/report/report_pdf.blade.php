<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        .new-page {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center">Complain report {{ date('d-m-Y', strtotime($report_date)) }} -
        {{ 'Total: ' . count($total_todays_complaints) }}</h3>
    <table style="width: 100%; border: 2px solid black; border-collapse: collapse; text-align: center;">
        <thead>
            <tr>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="5%">#</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Date</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Comp No</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="30%">Party Name</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="15%">Mobile</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Machine</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="20%">Area</th>
            </tr>
        </thead>
        <?php foreach ($total_todays_complaints as $key => $total_todays_complaint) { ?>
        <tbody>
            <tr>
                <td style="border: 2px solid black; font-size: 14px;">{{ ++$key }}</td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ date('d-m-y', strtotime($total_todays_complaint['date'])) }}</td>
                <td style="border: 2px solid black; font-size: 14px;">{{ $total_todays_complaint['id'] }}</td>
                <td style="border: 2px solid black; font-size: 14px;">{{ $total_todays_complaint['party']['name'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $total_todays_complaint['party']['phone_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $total_todays_complaint['machine_sales_entry']['mc_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $total_todays_complaint['party']['area']['name'] }}
                </td>
            </tr>
        </tbody>
        <?php } ?>
    </table>
    <h3 class="new-page" style="text-align: center">Done Complain Report {{ date('d-m-Y', strtotime($report_date)) }} -
        {{ 'Total: ' . count($todays_total_dones) }}</h3>
    <table style="width: 100%; border: 2px solid black; border-collapse: collapse; text-align: center;">
        <thead>
            <tr>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="5%">#</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Date</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="5%">Comp No</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="15%">Eng Name</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="20%">Party Name</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="15%">Mobile</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Machine</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Time</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Area</th>
            </tr>
        </thead>
        <?php
            $doneComplainCount = 0;
            foreach ($todays_total_dones as $key => $todays_total_done) { 
        ?>
        <tbody>
            <tr>
                <td style="border: 2px solid black; font-size: 14px;">{{ ++$doneComplainCount }}</td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ date('d-m-y', strtotime($todays_total_done['date'])) }}</td>
                <td style="border: 2px solid black; font-size: 14px;">{{ $todays_total_done['id'] }}</td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ !isset($todays_total_done['engineer']) ? 'Not Assign' : $todays_total_done['engineer']['name'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">{{ $todays_total_done['party']['name'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $todays_total_done['party']['phone_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $todays_total_done['machine_sales_entry']['mc_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $todays_total_done['engineer_time_duration'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $todays_total_done['party']['area']['name'] }}
                </td>
            </tr>
        </tbody>
        <?php } ?>
    </table>
    <h3 class="new-page" style="text-align: center">Till pending complain report -
        {{ 'Total: ' . count($total_pending_complaints) }}</h3>
    <table style="width: 100%; border: 2px solid black; border-collapse: collapse; text-align: center;">
        <thead>
            <tr>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="5%">#</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Date</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="5%">Comp No</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="15%">Eng Name</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="30%">Party Name</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="15%">Mobile</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Machine</th>
                <th style="border: 2px solid black; background-color: #ffffb3;" width="10%">Area</th>
            </tr>
        </thead>
        <?php
        foreach ($total_pending_complaints as $pendingKey => $total_pending_complaint) { 
        ?>
        <tbody>
            <tr>
                <td style="border: 2px solid black; font-size: 14px;">{{ ++$pendingKey }}</td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ date('d-m-y', strtotime($total_pending_complaint['date'])) }}</td>
                <td style="border: 2px solid black; font-size: 14px;">{{ $total_pending_complaint['complaint_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ !isset($total_pending_complaint['engineer']) ? 'Not Assign' : $total_pending_complaint['engineer']['name'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">{{ $total_pending_complaint['party']['name'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $total_pending_complaint['party']['phone_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $total_pending_complaint['machine_sales_entry']['mc_no'] }}
                </td>
                <td style="border: 2px solid black; font-size: 14px;">
                    {{ $total_pending_complaint['party']['area']['name'] }}
                </td>
            </tr>
        </tbody>
        <?php } ?>
    </table>
</body>

</html>
