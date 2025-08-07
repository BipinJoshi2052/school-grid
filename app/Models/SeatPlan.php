<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatPlan extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id', 'added_by','unassigned_students','unassigned_staffs'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function seatPlanDetails()
    {
        return $this->hasMany(SeatPlanDetail::class);
    }

    public function invigilatorPlanDetails()
    {
        return $this->hasMany(InvigilatorPlanDetail::class);
    }
}
