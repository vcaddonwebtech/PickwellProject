<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="row">
        <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-10 col-sm-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title" style="margin-bottom: 20px;">{{ $title }}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="" style="border-collapse: collapse; width: 100%;">
                            <tbody>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Party Name</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->party->name ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Party Email</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->party->email ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Party Mobile</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->party->phone_no ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Date</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->date ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Time</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->time ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Complaint Type</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->ComplaintType->name ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Machine Number</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->salesEntry->mc_no ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Product Name</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->product->name ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Service type Name</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->serviceType->name ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Complaint No</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->complaint_no ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer Name</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer In Time</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer_in_time ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer In Date</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer_in_date ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer In Address</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer_in_address ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer Out Time</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer_out_time ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer Out Date</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer_out_date ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Engineer Out Address</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->engineer_out_address ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Is Urgent</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->is_urgent ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Is assigned</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->is_assigned ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Remarks</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->remarks ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Status</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $complaint->status->name ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>