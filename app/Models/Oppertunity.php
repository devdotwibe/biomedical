<?php

namespace App\Models;

use App\Models\OppertunityStatusLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContractProduct;

class Oppertunity extends Model
{
    public function customer()
    {
    	return $this->belongsTo('App\User','user_id');
    }

	public function oppstate()
    {
    	return $this->belongsTo('App\State','state');
    }

	public function oppdistrict()
    {
    	return $this->belongsTo('App\District','district');
    }

    public function product()
	{
		return $this->belongsTo('App\Product','product_id');
	}

	public function staff()
	{
		return $this->belongsTo('App\Staff','staff_id');
	}
	public function coordinator(){
		return $this->belongsTo('App\Staff','coordinator_id');
	}

	public function createdBy()
	{
		return $this->belongsTo('App\Staff','created_by_id');
	}

	public function oppertunityOppertunityProduct()
    {
    	return $this->hasMany('App\Oppertunity_product','oppertunity_id','id');
    }
	public function oppertunityOrder()
    {
    	return $this->belongsTo('App\Models\OppertunityOrder','id','oppertunity_id');
    }

	public function oppertunityquote()
    {
    	return $this->belongsTo('App\Quotehistory','quotehistory_id','id');
    }

	public function closequote()
    {
    	return $this->belongsTo('App\Quotehistory','quote_close_id','id');
    }

	public function oppertunityStatusLog()
    {
    	return $this->hasMany('App\Models\OppertunityStatusLog','oppertunity_id','id');
    }
    public function contractProducts()
   {
    return $this->hasMany(ContractProduct::class);
   }



      /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
		parent::boot();
		static::created(function ($opp) {
			try {
				$oplg=new OppertunityStatusLog;
				$oplg->oppertunity_id=$opp->id;
				$oplg->status=$opp->deal_stage;
				$oplg->status_type="deal_stage";
				$oplg->save();

				$oplg=new OppertunityStatusLog;
				$oplg->oppertunity_id=$opp->id;
				$oplg->status=$opp->order_forcast_category;
				$oplg->status_type="order_forcast_category";
				$oplg->save();
			} catch (\Throwable $th) {
				//throw $th;
			}
		});
		static::updated(function ($opp) {
			try {
				$lastlg=OppertunityStatusLog::where("oppertunity_id",$opp->id)->where("status_type","deal_stage")->where('status',$opp->deal_stage)->orderBy("id","desc")->first();
				if(empty($lastlg)){
					$oplg=new OppertunityStatusLog;
					$oplg->oppertunity_id=$opp->id;
					$oplg->status=$opp->deal_stage;
					$oplg->status_type="deal_stage";
					$oplg->save();
				}


				$lastlg=OppertunityStatusLog::where("oppertunity_id",$opp->id)->where("status_type","order_forcast_category")->where('status',$opp->order_forcast_category)->orderBy("id","desc")->first();
				if(empty($lastlg)){

					$oplg=new OppertunityStatusLog;
					$oplg->oppertunity_id=$opp->id;
					$oplg->status=$opp->order_forcast_category;
					$oplg->status_type="order_forcast_category";
					$oplg->save();
				}
			} catch (\Throwable $th) {
				//throw $th;
			}
		});
	}
}
