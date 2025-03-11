<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\PmDetails;

class Staff extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';

    protected $guard = "staff";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','business_name','city','state','address1','address2','zip'
    ];
    protected $guarded = array();

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token','remember_token',
    ];

    public function designation()
    {
       return $this->belongsTo('App\Designation','designation_id');
    }


    // public function customerlocation()
    // {
    // 	return $this->belongsTo('App\CustomerLocation','staff_id');
    // }

    public function customerlocationstaff()
    {
        return $this->belongsTo('App\CustomerLocation', 'id', 'staff_id');
    }

    public function staffcategory()
    {
       return $this->belongsTo('App\StaffCategory','category_id');
    }

    public function pmDetails()
    {
        return $this->hasMany(PmDetails::class, 'engineer_id');
    }
    public function company()
    {
       return $this->belongsTo('App\Company','company_id');
    }

}
