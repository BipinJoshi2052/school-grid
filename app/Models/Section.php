<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'class_id','added_by'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
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
