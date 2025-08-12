<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatPlanDetail extends Model
{
    use HasFactory;

    protected $fillable = ['seat_plan_id', 'building_id', 'room', 'bench', 'seat', 'student_id','student_name','student_class','student_section','student_roll_no','created_at','updated_at'];

    public function seatPlan()
    {
        return $this->belongsTo(SeatPlan::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
