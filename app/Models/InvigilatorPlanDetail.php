<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvigilatorPlanDetail extends Model
{
    // public $timestamps = true; // Ensure timestamps are enabled
    use HasFactory;

    protected $fillable = ['seat_plan_id', 'building_id', 'room', 'staff_id','created_at','updated_at'];

    public function seatPlan()
    {
        return $this->belongsTo(SeatPlan::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
