<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'rooms','added_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', 'id')->select(['id', 'name', 'user_type_id']);
    }
}
