<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class BillProduct extends Model
{

    protected $table = 'bill_product';

    protected  $fillable = [
       
    ];



    public function billproduct()
	{
		return $this->belongsTo('App\ContractProduct','contract_product_id');
	}

}
