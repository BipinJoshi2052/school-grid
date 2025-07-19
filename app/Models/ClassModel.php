<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = ['user_id', 'title', 'batch_id','added_by'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class,'class_id','id');
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