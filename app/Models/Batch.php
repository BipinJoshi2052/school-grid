<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'faculty_id','added_by'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', 'id')->select(['id', 'name', 'user_type_id']);
    }
}
