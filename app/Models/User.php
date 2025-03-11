<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    function userIb()
    {	
   	    return $this->hasMany('App\Models\Ib', 'user_id', 'id' );
    }

    public function userInvoice() {
        return $this->hasMany( 'App\Models\Invoice', 'user_id', 'id' );
       }
       public function userContact()
       {
          return $this->hasMany('App\Models\Contact_person','user_id','id');
       }

       public function userstate()
    {
    	return $this->belongsTo('App\Models\State','state_id');
    }

	public function userdistrict()
    {
    	return $this->belongsTo('App\Models\District','district_id');
    }

    public function usertaluk()
    {
    	return $this->belongsTo('App\Models\Taluk','taluk_id');
    }

    public function customer_category()
    {
    	return $this->belongsTo('App\Models\Customercategory','customer_category_id');
    }



}
