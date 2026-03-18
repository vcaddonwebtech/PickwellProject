<?php

namespace App\Imports;

use App\Models\MachineSalesEntry;
use App\Models\Product;
use App\Models\Party;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;

class MachineSalesImportClass implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!empty($row['model_no']) && !empty($row['customer_no'])) {
            $party = Party::where('code', $row['customer_no'])->first();
            $product = Product::where('name', $row['model_no'])->first();
            if(!empty($party) || !empty($product)) {
                $start_date = null;
                $end_date = null;
                if(!empty($row['start_date'])) {
                    $dayval = $row['start_date'];    // you would read from your file here
                    $date = new DateTime('1899-12-31');
                    $date->modify("+$dayval day -1 day");
                    $start_date = $date->format('Y-m-d');
                    $end_date = date('Y-m-d', strtotime('+2 years', strtotime($start_date)));
                } 

                $billdate = null;
                if(!empty($row['billdate'])) {
                    $dayval = $row['billdate'];    // you would read from your file here
                    $date = new DateTime('1899-12-31');
                    $date->modify("+$dayval day -1 day");
                    $billdate = $date->format('Y-m-d');
                } 

                return new MachineSalesEntry([
                    'order_no' => $row['order_form'],
                    'date' => $billdate,
                    'party_id' => $party->id,
                    'product_id' => $product->id,
                    'serial_no' => $row['machine_no'],
                    'mc_no' => str_replace("-","",strstr($row['machine_no'], '-')),
                    'install_date' => $start_date,
                    'service_expiry_date' => $end_date,
                    'service_type_id' => 2,
                    'mic_fitting_engineer_id' => 1,
                    'delivery_engineer_id' => 1,
                    'firm_id' => 1,
                    'year_id' => 1,
                    'bill_no' => 1 
                ]);
            }
        }
    }
}
