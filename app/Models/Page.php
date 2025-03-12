<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model {

   // Fetch all users
   public static function getUsers(){

   $records=DB::select("select `name`,`short_title`,`description`,`short_description`,`brand_name`,`category_name`,`item_code`,`unit`,`quantity`,`unit_price`,`tax_percentage`,`hsn_code`,`warrenty`,`payment`,`validity`,`company_name` from products order by id desc");

     return $records;
   }

}