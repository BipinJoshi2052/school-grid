<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'institution_name',
        'hearAbout',
        'usingMIS'
    ];

    // Define the relationship with the InstitutionDetail model
    public function institutionDetails()
    {
        return $this->hasMany(InstitutionDetail::class);
    }
}