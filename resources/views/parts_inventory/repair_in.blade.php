<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="row">
        <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-10 col-sm-12">
            <div class="card custom-card">
                <div class="d-flex justify-content-center">
                    <h1 class="text-center">OM SATYA</h1>
                </div>
                <div class="card-header justify-content-between">
                    <div class="card-title" style="margin-bottom: 20px;">{{ $title }}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="" style="border-collapse: collapse;">
                            <tbody>
                                <tr style="padding-inline: 5px !important">
                                    <td  style="width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;color:blue">Vouchar No. </td>
                                    <td  style="padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;color:blue">{{ $partsInventory->vou_no ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style="width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Party Name </td>
                                    <td  style="padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $partsInventory->in_party->name ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Repair In Date</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $partsInventory->repair_in_date ? date('d-m-Y', strtotime($partsInventory->repair_in_date)) : 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Repair In Time</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $partsInventory->repair_in_time ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Repair In Remarks</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $partsInventory->repair_in_remarks ?? 'N/A' }}</td>
                                </tr>
                                <tr style="padding-inline: 5px !important">
                                    <td  style=" width:50% !important; padding-left:5px; text-align:left; border: 1px solid black;">Repair Out Party Name</td>
                                    <td  style=" padding-left:5px; text-align:left; border: 1px solid black;border-collapse: collapse;">{{ $partsInventory->repair_out_party->name ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            <p>Signature</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>