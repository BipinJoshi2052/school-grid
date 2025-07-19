<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = "staffs";

    protected $fillable = [
        'school_id',
        'user_id',
        'name',
        'department_id',
        'position_id',
        'gender',
        'joined_date',
        'address',
        'added_by',
    ];

    public function school()
    {
        return $this->belongsTo(User::class, 'school_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
    
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', 'id')->select(['id', 'name', 'user_type_id']);
    }
}
