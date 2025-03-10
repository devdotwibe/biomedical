<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected  $fillable = [
        'asset_name', 'serial_no', 'system_id', 'company', 'product_no', 'product_descrption', 'assign_segment', 'modality', 'installed_at', 'state', 'district', 'account_name', 'asset_description', 'manufacturer', 'product_name', 'department', 'equipment_status', 'installed_on','reference'		
     ];

}
