<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title'];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
