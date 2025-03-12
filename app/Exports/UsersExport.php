<?php
namespace App\Exports;

use App\Models\Page;
use App\Product;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

  public function headings(): array {
    return [
       "Product Name","Product Short Name","Full Description","Short Description","Manufacture","Product Category","Item Code/ Part Number","Unit","Box Quantity","Unit Price","Tax Percentage","HSN Code","Warrenty","Payment","Validity","Company"
    ];
  }

  public function collection() {
  	
     return collect(Page::getUsers());
  }


}