<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OppertunityApproveStatus extends Model
{
        protected $table = 'oppertunity_approve_status';

        public function staff()
        {
            return $this->belongsTo(\App\Staff::class,"staff_id","id");
        }
        public function closedStaff()
        {
            return $this->belongsTo(\App\Staff::class,"closed_by","id");
        }
        public function attachmens()
        {
            return $this->hasMany(\App\Models\OppertunityApproveStatusAttachement::class,"oppertunity_approve_status_id","id");
        }
}